<?php

namespace App\Http\Controllers\API;

use App\Models\Booking;
use App\Models\Dst;
use App\Models\Voucher;
use App\Models\MasterArea;
use Illuminate\Http\Request;
use App\Models\MasterSubArea;
use App\Models\MasterSpecialArea;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\API\BaseController;
use App\Models\BookingCustomer;
use App\Models\BookingSequence;
use App\Models\Charter;
use App\Models\ScheduleShuttle;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class BookingController extends BaseController
{
    public function index(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'schedule_type'           => 'required|in:shuttle,charter',
                'is_roundtrip'            => 'required|boolean',
                'from_type'               => 'required|in:airport,city',
                'schedule_id'             => 'required|integer',
                'date_departure'          => 'required|date|after_or_equal:today|date_format:Y-m-d',
                'from_master_area_id'     => 'required',
                'from_master_sub_area_id' => 'nullable',
                'to_master_area_id'       => 'required',
                'to_master_sub_area_id'   => 'nullable',
                'qty_adult'               => 'required|integer|min:1',
                'qty_baby'                => 'required|integer|min:0',
                'special_request'         => 'nullable|boolean',
                'special_area_id'         => 'required_if:special_request,1',
                'special_area_detail'     => 'nullable',
                'luggage_qty'             => 'nullable|required_if:schedule_type,shuttle|integer|min:0',
                'overweight_luggage_qty'  => 'nullable|required_if:schedule_type,shuttle|integer|min:0',
                'flight_number'           => 'nullable',
                'flight_info'             => 'nullable',
                'notes'                   => 'nullable',
                'voucher_code'            => 'nullable',
                'customer_phone'          => 'required|min:3|max:50',
                'customer_name'           => 'required|min:3|max:255',
                'customer_email'          => 'nullable|min:3|max:100|email:rfc,dns',
                'customer_password'       => 'required|min:4|max:50',
                'passenger'               => 'required|array',

                'return_schedule_id'             => 'nullable|required_if:is_roundtrip,1|integer',
                'return_from_master_area_id'     => 'nullable|required_if:is_roundtrip,1',
                'return_from_master_sub_area_id' => 'nullable',
                'return_from_type'               => 'nullable|required_if:is_roundtrip,1|in:airport,city',
                'return_to_master_area_id'       => 'nullable|required_if:is_roundtrip,1',
                'return_to_master_sub_area_id'   => 'nullable',
                'return_date'                    => 'nullable|required_if:is_roundtrip,1|date|after_or_equal:today|date_format:Y-m-d',
                'return_special_request'         => 'nullable|boolean',
                'return_special_area_id'         => 'nullable|required_if:return_special_request,1',
                'return_special_area_detail'     => 'nullable',
                'return_luggage_qty'             => 'nullable|required_if:is_roundtrip,1,schedule_type,shuttle|integer|min:0',
                'return_overweight_luggage_qty'  => 'nullable|required_if:is_roundtrip,1,schedule_type,shuttle|integer|min:0',
                'return_flight_number'           => 'nullable',
                'return_flight_info'             => 'nullable',
                'return_notes'                   => 'nullable',
            ],
            [
                'exists' => ':attribute not found',
                'in'     => ':attribute only accept value :values'
            ]
        );
        if ($validator->fails()) {
            $errors = $validator->errors();
            $error_message = "";
            foreach ($validator->failed() as $key => $val) {
                if ($errors->first($key)) {
                    $error_message = $errors->first($key);
                }
            }
            return $this->sendError($error_message, null);
        }

        if ($request->schedule_type == "shuttle") {
            if ($request->qty_adult == 0) {
                return $this->sendError("qty adult minimum 1", null);
            }

            if ($request->from_type == "airport") {
                if (!$request->to_master_sub_area_id) {
                    return $this->sendError("to_master_sub_area_id is required", null);
                }

                if ($request->special_request == 1) {
                    $special_area_id = $request->special_area_id;
                    $special_areas = MasterSpecialArea::where([
                        'id' => $special_area_id,
                        'master_sub_area_id' => $request->to_master_sub_area_id,
                    ])->first();
                    if (!$special_areas) {
                        return $this->sendError("Special Area Not Found", null);
                    }
                }
            } else {
                if (!$request->from_master_sub_area_id) {
                    return $this->sendError("from_master_sub_area_id is required", null);
                }

                if ($request->special_request == 1) {
                    $special_area_id = $request->special_area_id;
                    $special_areas = MasterSpecialArea::where([
                        'id' => $special_area_id,
                        'master_sub_area_id' => $request->from_master_sub_area_id,
                    ])->first();
                    if (!$special_areas) {
                        return $this->sendError("Special Area Not Found", null);
                    }
                }
            }
        }

        $user_id                  = null;
        $customer_phone           = $request->customer_phone;
        $customer_password        = $request->customer_password;
        $customer_name            = $request->customer_name;
        $customer_email           = $request->customer_email;
        $total_person             = $request->qty_adult + $request->qty_baby;
        $base_price               = 0;
        $return_base_price        = 0;
        $total_base_price         = 0;
        $return_total_base_price  = 0;
        $luggage_price            = 0;
        $return_luggage_price     = 0;
        $overweight_luggage_price = 0;
        $return_overweight_luggage_price = 0;
        $extra_price              = 0;
        $return_extra_price       = 0;
        $promo_price              = 0;
        $return_promo_price       = 0;
        $sub_total_price          = 0;
        $return_sub_total_price   = 0;
        $fee_price                = 0;
        $return_fee_price         = 0;
        $total_price              = 0;
        $return_total_price       = 0;

        $voucher_id               = null;
        $discount_type            = null;
        $discount_value           = 0;

        // check user registered or not
        $check_users = User::where([
            'phone' => $customer_phone,
        ])->first();
        if ($check_users) {
            // user telah terdaftar
            if (!password_verify($customer_password, $check_users->password)) {
                return $this->sendError("Wrong password, please try again", null);
            }

            $user_id = $check_users->id;
            User::where(['id' => $check_users->id])->update([
                'name' => $customer_name,
                'email' => $customer_email,
            ]);
        } else {
            // register user baru
            $register_user = new User();
            $register_user->name = $customer_name;
            $register_user->phone = $customer_phone;
            $register_user->password = bcrypt($customer_password);
            $register_user->email = $customer_email;
            $register_user->photo = 'img/user_pp/default.jpg';
            $register_user->save();
            $user_id = $register_user->id;
        }

        // voucher & promo price
        if ($request->voucher_code) {

            $vouchers = Voucher::select([
                'id',
                'discount_type',
                'discount_value',
            ])->where([
                'is_active' => true,
                'code' => $request->voucher_code,
            ])
                ->whereRaw('CURDATE() between date_start and date_expired')
                ->first();

            if (!$vouchers) {
                return $this->sendError('Voucher not found or expired', null);
            }

            $voucher_id = $vouchers->id;
            $discount_type = $vouchers->discount_type;
            $discount_value = (float)$vouchers->discount_value;
        }

        if ($request->schedule_type == "shuttle") {
            // jika jenis pemberangkatan adalah shuttle
            $schedules = ScheduleShuttle::select([
                'from_master_area.name as from_master_area_name',
                'to_master_area.name as to_master_area_name',
                'schedule_shuttles.*',
            ])
                ->where([
                    'schedule_shuttles.is_active' => true,
                    'schedule_shuttles.id' => $request->schedule_id,
                ])
                ->leftJoin('master_areas as from_master_area', 'from_master_area.id', '=', 'schedule_shuttles.from_master_area_id')
                ->leftJoin('master_areas as to_master_area', 'to_master_area.id', '=', 'schedule_shuttles.to_master_area_id')
                ->first();

            if (!$schedules) {
                return $this->sendError('Schedule not found, please try again', null);
            } else {
                $dst = Dst::first();
                $nextDst = Dst::skip(1)->first();
                $timeDeparture = \Carbon\Carbon::parse($schedules->time_departure);
                if (\Carbon\Carbon::parse($request->date_departure)->between($dst->dst_start, $dst->dst_end)) {
                    if ($timeDeparture->hour < 12) {
                        $timeDeparture->addMinutes($dst->morning_schedule_time);
                    } else {
                        $timeDeparture->addMinutes($dst->afternoon_schedule_time);
                    }
                }else if (\Carbon\Carbon::parse($request->date_departure)->between($nextDst->dst_start, $nextDst->dst_end)) {
                    if ($timeDeparture->hour < 12) {
                        $timeDeparture->addMinutes($nextDst->morning_schedule_time);
                    } else {
                        $timeDeparture->addMinutes($nextDst->afternoon_schedule_time);
                    }
                }
                $schedules->time_departure = $timeDeparture->format('H:i');
            }

            $can_book = $this->count_seat_avail($schedules->total_seat, $total_person);

            if ($can_book == false) {
                return $this->sendError('No seat left', null);
            }
            if($request->is_roundtrip == 1){
                $return_schedules = ScheduleShuttle::select([
                    'from_master_area.name as from_master_area_name',
                    'to_master_area.name as to_master_area_name',
                    'schedule_shuttles.*',
                ])
                    ->where([
                        'schedule_shuttles.is_active' => true,
                        'schedule_shuttles.id' => $request->return_schedule_id,
                    ])
                    ->leftJoin('master_areas as from_master_area', 'from_master_area.id', '=', 'schedule_shuttles.from_master_area_id')
                    ->leftJoin('master_areas as to_master_area', 'to_master_area.id', '=', 'schedule_shuttles.to_master_area_id')
                    ->first();
                if (!$return_schedules) {
                    return $this->sendError('Return Schedule not found, please try again', null);
                } else {
                    $dst = Dst::first();
                    $nextDst = Dst::skip(1)->first();
                    $timeDeparture = \Carbon\Carbon::parse($return_schedules->time_departure);
                    if (\Carbon\Carbon::parse($request->date_departure)->between($dst->dst_start, $dst->dst_end)) {
                        if ($timeDeparture->hour < 12) {
                            $timeDeparture->addMinutes($dst->morning_schedule_time);
                        } else {
                            $timeDeparture->addMinutes($dst->afternoon_schedule_time);
                        }
                    }else if (\Carbon\Carbon::parse($request->date_departure)->between($nextDst->dst_start, $nextDst->dst_end)) {
                        if ($timeDeparture->hour < 12) {
                            $timeDeparture->addMinutes($nextDst->morning_schedule_time);
                        } else {
                            $timeDeparture->addMinutes($nextDst->afternoon_schedule_time);
                        }
                    }
                    $return_schedules->time_departure = $timeDeparture->format('H:i');
                }

                $return_can_book = $this->count_seat_avail($return_schedules->total_seat, $total_person);
                if ($return_can_book == false) {
                    return $this->sendError('No seat left (return)', null);
                }
            }

            $luggage_base_price            = 20;
            $overweight_luggage_base_price = 10;

            if (($request->luggage_qty) > (2 * $total_person)) {
                $luggage_price = ($request->luggage_qty - (2 * $total_person)) * $luggage_base_price;
            }
            if (($request->return_luggage_qty) > (2 * $total_person)) {
                $return_luggage_price = ($request->return_luggage_qty - (2 * $total_person)) * $luggage_base_price;
            }
            $overweight_luggage_price = $request->overweight_luggage_qty * $overweight_luggage_base_price;
            $return_overweight_luggage_price = $request->return_overweight_luggage_qty * $overweight_luggage_base_price;

            if ($request->special_request) {
                $extra_prices = MasterSpecialArea::select([
                    'first_person_price',
                    'extra_person_price'
                ])->where([
                    'is_active' => true,
                    'id' => $request->special_area_id,
                ])->get();

                foreach ($extra_prices as $key) {
                    $first_person_price = (float)$key->first_person_price;
                    $extra_person_price = (float)$key->extra_person_price;

                    if ($total_person == 1) {
                        $extra_price = $first_person_price;
                    } else {
                        $extra_price = (($total_person - 1) * $extra_person_price) + $first_person_price;
                    }
                }
            }

            if ($request->return_special_request) {
                $return_extra_prices = MasterSpecialArea::select([
                    'first_person_price',
                    'extra_person_price'
                ])->where([
                    'is_active' => true,
                    'id' => $request->return_special_area_id,
                ])->get();

                foreach ($return_extra_prices as $key) {
                    $return_first_person_price = (float)$key->first_person_price;
                    $return_extra_person_price = (float)$key->extra_person_price;

                    if ($total_person == 1) {
                        $return_extra_price = $return_first_person_price;
                    } else {
                        $return_extra_price = (($total_person - 1) * $return_extra_person_price) + $return_first_person_price;
                    }
                }
            }

            $base_price       = $schedules->price;
            $total_base_price = $total_person * $base_price;
            $sub_total_price  = $total_base_price + $luggage_price + $overweight_luggage_price + $extra_price;

            $return_base_price       = $return_schedules->price;
            $return_total_base_price = $total_person * $return_base_price;
            $return_sub_total_price  = $return_total_base_price + $return_luggage_price + $return_overweight_luggage_price + $return_extra_price;

            if ($voucher_id) {
                if ($discount_type == "percentage") {
                    $promo_price = ($sub_total_price * $discount_value) / 100;
                    $return_promo_price = ($sub_total_price * $discount_value) / 100;
                } else {
                    $promo_price = $discount_value;
                    $return_promo_price = $discount_value;
                }
                $sub_total_price = $sub_total_price - $promo_price;
                $return_sub_total_price = $return_sub_total_price - $return_promo_price;
            }

            $qty_adult = $request->qty_adult;
            $qty_baby = $request->qty_baby;
        } else {
            // jika jenis pemberangkatan adalah charter
            $total_person = 1;

            $schedules = Charter::select([
                'from_master_area.name as from_master_area_name',
                'to_master_area.name as to_master_area_name',
                'charters.*',
            ])
                ->where([
                    // 'charters.is_available' => true,
                    'charters.id' => $request->schedule_id,
//                    'DATE(charters.datetime_departure)' => $request->date_departure,
                ])
                ->leftJoin('master_areas as from_master_area', 'from_master_area.id', '=', 'charters.from_master_area_id')
                ->leftJoin('master_areas as to_master_area', 'to_master_area.id', '=', 'charters.to_master_area_id')
                ->first();
            if (!$schedules) {
                return $this->sendError('Charter data not found, please try again', null);
            }

            if($request->is_roundtrip == 1){
                $return_schedules = Charter::select([
                    'from_master_area.name as from_master_area_name',
                    'to_master_area.name as to_master_area_name',
                    'charters.*',
                ])
                    ->where([
                        // 'charters.is_available' => true,
                        'charters.id' => $request->return_schedule_id,
    //                    'DATE(charters.datetime_departure)' => $request->date_departure,
                    ])
                    ->leftJoin('master_areas as from_master_area', 'from_master_area.id', '=', 'charters.from_master_area_id')
                    ->leftJoin('master_areas as to_master_area', 'to_master_area.id', '=', 'charters.to_master_area_id')
                    ->first();
                if (!$return_schedules) {
                    return $this->sendError('Return Charter data not found, please try again', null);
                }
            }

            $base_price               = $schedules->price;
            $total_base_price         = $schedules->price;
            if($request->is_roundtrip == 1){
                $return_base_price          = $return_schedules->price;
                $return_total_base_price          = $return_schedules->price;
            }
            $luggage_price            = 0;
            $overweight_luggage_price = 0;
            $extra_price              = 0;
            $promo_price              = 0;
            $sub_total_price          = $base_price;
            $return_sub_total_price   = $base_price;
            $fee_price                = 0;
            $total_price              = 0;

            if ($voucher_id) {
                if ($discount_type == "percentage") {
                    $promo_price = ($sub_total_price * $discount_value) / 100;
                    $return_promo_price = ($sub_total_price * $discount_value) / 100;
                } else {
                    $promo_price = $discount_value;
                    $return_promo_price = $discount_value;
                }
                $sub_total_price = $sub_total_price - $promo_price;
                $return_sub_total_price = $return_sub_total_price - $return_promo_price;
            }

            $qty_adult = 0;
            $qty_baby = 0;
        }

        $from_master_sub_area_name = null;
        $to_master_sub_area_name = null;
        $regional_name = null;

        if ($schedules->from_master_area_id != $request->from_master_area_id) {
            return $this->sendError('from_master_area_id schedule did not match', null);
        }

        if ($schedules->to_master_area_id != $request->to_master_area_id) {
            return $this->sendError('to_master_area_id schedule did not match', null);
        }

        if ($request->from_type == "airport") {
            if ($request->from_master_sub_area_id) {
                $from_sub = MasterSubArea::where('id', $request->from_master_sub_area_id)->where('master_area_id', $schedules->from_master_area_id)->first();
                if (!$from_sub) {
                    return $this->sendError('from sub area not found', null);
                }
                $from_master_sub_area_name = $from_sub->name;
            }

            if ($request->to_master_sub_area_id != $schedules->to_master_sub_area_id) {
                return $this->sendError('to sub area schedule did not match', null);
            }

            $to_sub = MasterSubArea::where('id', $request->to_master_sub_area_id)->where('master_area_id', $schedules->to_master_area_id)->first();
            if (!$to_sub) {
                return $this->sendError('to sub area not found', null);
            }
            $to_master_sub_area_name = $to_sub->name;
        } else {
            if ($request->to_master_sub_area_id) {
                $to_sub = MasterSubArea::where('id', $request->to_master_sub_area_id)->where('master_area_id', $schedules->to_master_area_id)->first();
                if (!$to_sub) {
                    return $this->sendError('to sub area not found', null);
                }
                $to_master_sub_area_name = $to_sub->name;
            }

            if ($request->from_master_sub_area_id != $schedules->from_master_sub_area_id) {
                return $this->sendError('from sub area schedule did not match', null);
            }

            $from_sub = MasterSubArea::where('id', $request->from_master_sub_area_id)->where('master_area_id', $schedules->from_master_area_id)->first();
            if (!$from_sub) {
                return $this->sendError('from sub area not found', null);
            }
            $from_master_sub_area_name = $from_sub->name;
        }

        if ($request->special_request && $request->special_area_id) {
            $regional_name = MasterSpecialArea::where('id', $request->special_area_id)->first()->regional_name;
        }

        if($request->is_roundtrip){
            $return_from_master_sub_area_name = null;
            $return_to_master_sub_area_name = null;
            $return_regional_name = null;
    
            if ($return_schedules->from_master_area_id != $request->return_from_master_area_id) {
                return $this->sendError('return from master area id schedule did not match', null);
            }
    
            if ($return_schedules->to_master_area_id != $request->return_to_master_area_id) {
                return $this->sendError('return to master area id schedule did not match', null);
            }
    
            if ($request->return_from_type == "airport") {
                if ($request->return_from_master_sub_area_id) {
                    $return_from_sub = MasterSubArea::where('id', $request->return_from_master_sub_area_id)->where('master_area_id', $return_schedules->from_master_area_id)->first();
                    if (!$return_from_sub) {
                        return $this->sendError('return from sub area not found', null);
                    }
                    $return_from_master_sub_area_name = $return_from_sub->name;
                }
    
                if ($request->return_to_master_sub_area_id != $return_schedules->to_master_sub_area_id) {
                    return $this->sendError('return to sub area schedule did not match', null);
                }
    
                $return_to_sub = MasterSubArea::where('id', $request->return_to_master_sub_area_id)->where('master_area_id', $return_schedules->to_master_area_id)->first();
                if (!$return_to_sub) {
                    return $this->sendError('return to sub area not found', null);
                }
                $return_to_master_sub_area_name = $return_to_sub->name;
            } else {
                if ($request->return_to_master_sub_area_id) {
                    $return_to_sub = MasterSubArea::where('id', $request->return_to_master_sub_area_id)->where('master_area_id', $return_schedules->to_master_area_id)->first();
                    if (!$return_to_sub) {
                        return $this->sendError('return to sub area not found', null);
                    }
                    $return_to_master_sub_area_name = $return_to_sub->name;
                }
    
                if ($request->return_from_master_sub_area_id != $return_schedules->from_master_sub_area_id) {
                    return $this->sendError('return from sub area schedule did not match', null);
                }
    
                $return_from_sub = MasterSubArea::where('id', $request->return_from_master_sub_area_id)->where('master_area_id', $return_schedules->from_master_area_id)->first();
                if (!$return_from_sub) {
                    return $this->sendError('return from sub area not found', null);
                }
                $return_from_master_sub_area_name = $return_from_sub->name;
            }
    
            if ($request->return_special_request && $request->return_special_area_id) {
                $return_regional_name = MasterSpecialArea::where('id', $request->return_special_area_id)->first()->regional_name;
            }
        }

        $fee_price   = (($sub_total_price * env('PAJAK')) / 100);
        $total_price = $sub_total_price + $fee_price;

        $return_fee_price   = (($return_sub_total_price * env('PAJAK')) / 100);
        $return_total_price = $return_sub_total_price + $return_fee_price;

        $booking_number = $this->generate_booking_number();
        $booking_number_encode = urlencode($booking_number);

        $booking                            = new Booking();
        $booking->area_type                 = $request->from_type;

        $booking->booking_number            = $booking_number;
        $booking->schedule_id               = $request->schedule_id;
        $booking->from_master_area_id       = $schedules->from_master_area_id;
        $booking->from_master_area_name     = $schedules->from_master_area_name;
        $booking->from_master_sub_area_id   = $request->from_master_sub_area_id;
        $booking->from_master_sub_area_name = $from_master_sub_area_name;
        $booking->to_master_area_id         = $schedules->to_master_area_id;
        $booking->to_master_area_name       = $schedules->to_master_area_name;
        $booking->to_master_sub_area_id     = $request->to_master_sub_area_id;
        $booking->to_master_sub_area_name   = $to_master_sub_area_name;
        $booking->vehicle_name              = $schedules->vehicle_name;
        $booking->vehicle_number            = $schedules->vehicle_number;
        $booking->datetime_departure        = $request->date_departure . " " . $schedules->time_departure;
        $booking->schedule_type             = $request->schedule_type;
        $booking->user_id                   = $user_id;
        $booking->customer_phone            = $customer_phone;
        $booking->customer_name             = $customer_name;
        $booking->customer_email            = $customer_email;
        $booking->qty_adult                 = $qty_adult;
        $booking->qty_baby                  = $qty_baby;
        $booking->base_price                = $base_price;
        $booking->total_base_price          = $total_base_price;
        $booking->flight_number             = ($request->flight_number) ?? null;
        $booking->flight_info               = ($request->flight_info) ?? null;
        $booking->notes                     = ($request->notes) ?? null;
        $booking->luggage_qty               = ($request->luggage_qty) ?? 0;
        $booking->luggage_price             = $luggage_price;
        $booking->overweight_luggage_qty    = ($request->overweight_luggage_qty) ?? 0;
        $booking->overweight_luggage_price  = $overweight_luggage_price;
        $booking->special_request           = ($request->special_request) ?? false;
        $booking->special_area_id           = ($request->special_area_id) ?? null;
        $booking->special_area_detail       = ($request->special_area_detail) ?? null;
        $booking->regional_name             = $regional_name;
        $booking->extra_price               = $extra_price;
        $booking->voucher_id                = $voucher_id;
        $booking->promo_price               = $promo_price;
        $booking->sub_total_price           = $sub_total_price;
        $booking->fee_price                 = $fee_price;
        $booking->total_price               = $total_price;
        $booking->booking_status            = 'pending';
        $booking->payment_status            = 'waiting';
        $booking->payment_method            = null;
        $booking->payment_token             = null;
        $booking->total_payment             = $total_price;
        $booking->trip_number               = !empty($schedules->trip_number) ? $schedules->trip_number : null;
        $booking->save();
        $booking_id = $booking->id;
        $booking_number = $booking->booking_number;

        if($request->is_roundtrip == 1){
            $return_booking                            = new Booking();
            $return_booking->area_type                 = $request->return_from_type;

            $return_booking->booking_number            = $booking_number;
            $return_booking->schedule_id               = $request->return_schedule_id;
            $return_booking->from_master_area_id       = $return_schedules->from_master_area_id;
            $return_booking->from_master_area_name     = $return_schedules->from_master_area_name;
            $return_booking->from_master_sub_area_id   = $request->return_from_master_sub_area_id;
            $return_booking->from_master_sub_area_name = $return_from_master_sub_area_name;
            $return_booking->to_master_area_id         = $return_schedules->to_master_area_id;
            $return_booking->to_master_area_name       = $return_schedules->to_master_area_name;
            $return_booking->to_master_sub_area_id     = $request->return_to_master_sub_area_id;
            $return_booking->to_master_sub_area_name   = $return_to_master_sub_area_name;
            $return_booking->vehicle_name              = $return_schedules->vehicle_name;
            $return_booking->vehicle_number            = $return_schedules->vehicle_number;
            $return_booking->datetime_departure        = $request->return_date . " " . $return_schedules->time_departure;
            $return_booking->schedule_type             = $request->schedule_type;
            $return_booking->user_id                   = $user_id;
            $return_booking->customer_phone            = $customer_phone;
            $return_booking->customer_name             = $customer_name;
            $return_booking->customer_email            = $customer_email;
            $return_booking->qty_adult                 = $qty_adult;
            $return_booking->qty_baby                  = $qty_baby;
            $return_booking->base_price                = $base_price;
            $return_booking->total_base_price          = $total_base_price;
            $return_booking->flight_number             = ($request->return_flight_number) ?? null;
            $return_booking->flight_info               = ($request->return_flight_info) ?? null;
            $return_booking->notes                     = ($request->return_notes) ?? null;
            $return_booking->luggage_qty               = ($request->return_luggage_qty) ?? 0;
            $return_booking->luggage_price             = $return_luggage_price;
            $return_booking->overweight_luggage_qty    = ($request->return_overweight_luggage_qty) ?? 0;
            $return_booking->overweight_luggage_price  = $return_overweight_luggage_price;
            $return_booking->special_request           = ($request->return_special_request) ?? false;
            $return_booking->special_area_id           = ($request->return_special_area_id) ?? null;
            $return_booking->special_area_detail       = ($request->return_special_area_detail) ?? null;
            $return_booking->regional_name             = $return_regional_name;
            $return_booking->extra_price               = $return_extra_price;
            $return_booking->voucher_id                = $voucher_id;
            $return_booking->promo_price               = $return_promo_price;
            $return_booking->sub_total_price           = $return_sub_total_price;
            $return_booking->fee_price                 = $return_fee_price;
            $return_booking->total_price               = $return_total_price;
            $return_booking->booking_status            = 'pending';
            $return_booking->payment_status            = 'waiting';
            $return_booking->payment_method            = null;
            $return_booking->payment_token             = null;
            $return_booking->total_payment             = $return_total_price;
            $return_booking->trip_number               = !empty($return_schedules->trip_number) ? $return_schedules->trip_number : null;
            $return_booking->save();
            $booking_id = $return_booking->id;
            $booking_number = $return_booking->booking_number;
        }
        $data_customer = [];
        $arr_passenger = $request->passenger;
        for ($i = 0; $i < count($arr_passenger); $i++) {
            $customer_name = $arr_passenger[$i]['name'];
            $customer_phone = $arr_passenger[$i]['phone'];
            array_push($data_customer, [
                'booking_id' => $booking_number,
                'customer_name' => $customer_name,
                'customer_phone' => $customer_phone,
            ]);
        }

        BookingCustomer::insert($data_customer);

        $res = Booking::where('id', $booking_id)->first();
        $datetime_departure = Carbon::createFromFormat('Y-m-d H:i:s', $res->datetime_departure)->format('Y-m-d H:i:s');

        if ($request->schedule_type == "charter") {
            // $datetime_departure = Carbon::createFromFormat('Y-m-d H:i:s', $res->datetime_departure)->format('Y-m-d');
            // $charters = Charter::find($request->schedule_id);
            // $charters->is_available = false;
            // $charters->save();
        } else {
            $shuttle             = ScheduleShuttle::find($request->schedule_id);
//            $shuttle->total_seat = $shuttle->total_seat - $qty_adult - $qty_baby;
            $shuttle->save();
        }

        $result = [
            'id'                        => (int)$res->id,
            'booking_number'            => $res->booking_number,
            'schedule_id'               => (int)$res->schedule_id,
            'from_master_area_id'       => (int)$res->from_master_area_id,
            'from_master_area_name'     => $res->from_master_area_name,
            'from_master_sub_area_id'   => (int)$res->from_master_sub_area_id,
            'from_master_sub_area_name' => $res->from_master_sub_area_name,
            'to_master_area_id'         => (int)$res->to_master_area_id,
            'to_master_area_name'       => $res->to_master_area_name,
            'to_master_sub_area_id'     => (int)$res->to_master_sub_area_id,
            'to_master_sub_area_name'   => $res->to_master_sub_area_name,
            'vehicle_name'              => $res->vehicle_name,
            'vehicle_number'            => $res->vehicle_number,
            'datetime_departure'        => $datetime_departure,
            'schedule_type'             => $res->schedule_type,
            'user_id'                   => (int)$res->user_id,
            'customer_phone'            => $res->customer_phone,
            'customer_name'             => $res->customer_name,
            'customer_email'            => $res->customer_email,
            'passenger'                 => $arr_passenger,
            'qty_adult'                 => (int)$res->qty_adult,
            'qty_baby'                  => (int)$res->qty_baby,
            'base_price'                => (float)$res->base_price,
            'total_base_price'          => (float)$res->total_base_price,
            'flight_number'             => $res->flight_number,
            'flight_info'               => $res->flight_info,
            'notes'                     => $res->notes,
            'trip_number'               => $res->trip_number,
            'luggage_qty'               => (int)$res->luggage_qty,
            'luggage_price'             => (float)$res->luggage_price,
            'overweight_luggage_qty'    => (int)$res->overweight_luggage_qty,
            'overweight_luggage_price'  => (float)$res->overweight_luggage_price,
            'special_request'           => ($res->special_request) ?? null,
            'special_area_id'           => ((int)$res->special_area_id) ?? null,
            'special_area_detail'       => ((int)$res->special_area_detail) ?? null,
            'regional_name'             => $res->regional_name,
            'extra_price'               => (float)$res->extra_price,
            'voucher_id'                => $res->voucher_id,
            'promo_price'               => (float)$res->promo_price,
            'sub_total_price'           => (float)$res->sub_total_price,
            'fee_price'                 => (float)$res->fee_price,
            'total_price'               => (float)$res->total_price,
            'booking_status'            => $res->booking_status,
            'payment_status'            => $res->payment_status,
            'payment_method'            => $res->payment_method,
            'payment_token'             => $res->payment_token,
            'total_payment'             => (float)$res->total_payment,
            'created_at'                => Carbon::createFromFormat('Y-m-d H:i:s', $res->created_at)->format('Y-m-d H:i:s'),
            'booking_number_encode'     => $booking_number_encode,
        ];

        return $this->sendResponse($result, 'success');
    }

    public function show(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'from_date' => 'required|date',
                'to_date' => 'required|date',
                'phone' => 'required',
            ],
            [
                'in' => ':attribute only accept value :values'
            ]
        );

        if ($validator->fails()) {
            $errors = $validator->errors();
            $error_message = "";
            foreach ($validator->failed() as $key => $val) {
                if ($errors->first($key)) {
                    $error_message = $errors->first($key);
                }
            }
            return $this->sendError($error_message, null);
        }

        $phone = $request->phone;
        $from_date = $request->from_date;
        $to_date = $request->to_date;

        $bookings = Booking::whereDate('datetime_departure', '>=', $from_date)->whereDate('datetime_departure', '<=', $to_date)->where(function ($query) use ($phone) {
            $query->where('customer_phone', '=', $phone);
        })->get();

        if ($bookings->count() == 0) {
            return $this->sendError("Data not found", null);
        }

        return $this->sendResponse($bookings, 'success');
    }

    public function booking_update(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'id' => 'required|numeric',
                'payment_token' => 'required',
                'payment_method' => 'required',
                'payment_status' => 'required',
            ]
        );

        if ($validator->fails()) {
            $errors = $validator->errors();
            $error_message = "";
            foreach ($validator->failed() as $key => $val) {
                if ($errors->first($key)) {
                    $error_message = $errors->first($key);
                }
            }
            return $this->sendError($error_message, null);
        }

        $booking = Booking::where('id', $request->id)->first();

        if (!$booking) {
            return $this->sendError(null, "Booking not founded");
        }

        if ($booking->payment_status !== 'waiting' || $request->payment_status == 'failed') {
            return $this->sendError(null, "Payment has been proccesed already !");
        }

        $data = [
            'payment_token' => $request->payment_token,
            'payment_method' => $request->payment_method,
            'payment_status' => 'paid',
            'booking_status' => 'active'
        ];

        if ($request->payment_status !== 'succeeded') {
            $data['payment_status'] = 'failed';
        }

        Booking::where('id', $request->id)->update($data);

        return $this->sendResponse(null, $request->payment_status);
    }

    public function check_booking_number(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'booking_number' => 'required|exists:bookings,booking_number',
            ],
            [
                'exists' => ':attribute ' . $request->booking_number . ' not found'
            ]
        );

        if ($validator->fails()) {
            $errors = $validator->errors();
            $error_message = "";
            foreach ($validator->failed() as $key => $val) {
                if ($errors->first($key)) {
                    $error_message = $errors->first($key);
                }
            }
            return $this->sendError($error_message, null);
        }

        $booking_number = $request->booking_number;

        $bookings = Booking::where('booking_number', $booking_number)->get();
        return $this->sendResponse($bookings, 'success');
    }

    public function get_list_from_departure(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'from_type' => 'required|in:airport,city',
                'keyword' => 'nullable',
            ],
            [
                'in' => ':attribute only accept value :values'
            ]
        );

        if ($validator->fails()) {
            $errors = $validator->errors();
            $error_message = "";
            foreach ($validator->failed() as $key => $val) {
                if ($errors->first($key)) {
                    $error_message = $errors->first($key);
                }
            }
            return $this->sendError($error_message, null);
        }

        $from_type = $request->from_type;
        $keyword = ($request->keyword) ?? null;

        $master_areas = MasterArea::with('master_sub_area')->where([
            'is_active' => true,
            'area_type' => $from_type,
        ])->orderBy('name', 'asc');

        if ($keyword) {
            $master_areas->whereHas('master_sub_area', function (Builder $query) use ($keyword) {
                $query->where('name', 'like', '%' . $keyword . '%');
                $query->where('is_active', true);
            });
        }

        if ($master_areas->count() == 0) {
            return $this->sendError('Data Empty', null);
        }

        $data = [];
        foreach ($master_areas->get() as $key_area) {
            $id_area = $key_area->id;
            $name_area = $key_area->name;

            $nested = [
                'id' => $id_area,
                'name' => $name_area,
                'sub_area' => [],
            ];

            $master_sub_areas = MasterSubArea::where([
                'is_active' => true,
                'master_area_id' => $id_area,
            ])->orderBy('name', 'asc');

            if ($keyword) {
                $master_sub_areas->where('name', 'like', '%' . $keyword . '%');
            }

            if ($master_sub_areas->count() == 0) {
                return $this->sendError('Data Empty', null);
            }

            foreach ($master_sub_areas->get() as $key_sub) {
                $id_sub = $key_sub->id;
                $name_sub = $key_sub->name;

                $nested_sub = [
                    'id' => $id_sub,
                    'name' => $name_sub,
                    'parent_id' => $id_area,
                ];
                array_push($nested['sub_area'], $nested_sub);
            }

            array_push($data, $nested);
        }

        return $this->sendResponse($data, 'success');
    }

    public function get_list_to_destination(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'from_type' => 'required|in:airport,city',
                'keyword' => 'nullable',
            ],
            [
                'in' => ':attribute only accept value :values'
            ]
        );

        if ($validator->fails()) {
            $errors = $validator->errors();
            $error_message = "";
            foreach ($validator->failed() as $key => $val) {
                if ($errors->first($key)) {
                    $error_message = $errors->first($key);
                }
            }
            return $this->sendError($error_message, null);
        }

        $from_type = $request->from_type;
        $keyword = ($request->keyword) ?? null;

        $master_areas = MasterArea::with('master_sub_area')->where([
            'is_active' => true,
            'area_type' => $from_type,
        ])->orderBy('name', 'asc');

        if ($keyword) {
            $master_areas->whereHas('master_sub_area', function (Builder $query) use ($keyword) {
                $query->where('name', 'like', '%' . $keyword . '%');
                $query->where('is_active', true);
            });
        }

        if ($master_areas->count() == 0) {
            return $this->sendError('Data Empty', null);
        }

        $data = [];
        foreach ($master_areas->get() as $key_area) {
            $id_area = $key_area->id;
            $name_area = $key_area->name;

            $nested = [
                'id' => $id_area,
                'name' => $name_area,
                'sub_area' => [],
            ];

            $master_sub_areas = MasterSubArea::where([
                'is_active' => true,
                'master_area_id' => $id_area,
            ])->orderBy('name', 'asc');

            if ($keyword) {
                $master_sub_areas->where('name', 'like', '%' . $keyword . '%');
            }

            if ($master_sub_areas->count() == 0) {
                return $this->sendError('Data Empty', null);
            }

            foreach ($master_sub_areas->get() as $key_sub) {
                $id_sub = $key_sub->id;
                $name_sub = $key_sub->name;

                $nested_sub = [
                    'id' => $id_sub,
                    'name' => $name_sub,
                    'parent_id' => $id_area,
                ];
                array_push($nested['sub_area'], $nested_sub);
            }

            array_push($data, $nested);
        }

        return $this->sendResponse($data, 'success');
    }

    public function get_schedule_shuttles(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'from_type'               => 'required|in:airport,city',
                'date_booking'            => 'required|date|after_or_equal:today|date_format:Y-m-d',
                'qty_adult'               => 'required|integer|min_digits:0',
                'qty_baby'                => 'required|integer|min_digits:0',
                'from_master_area_id'     => 'required',
                'from_master_sub_area_id' => 'nullable',
                'to_master_area_id'       => 'required',
                'to_master_sub_area_id'   => 'nullable',
            ]
        );

        if ($validator->fails()) {
            $errors = $validator->errors();
            $error_message = "";
            foreach ($validator->failed() as $key => $val) {
                if ($errors->first($key)) {
                    $error_message = $errors->first($key);
                }
            }
            return $this->sendError($error_message, null);
        }

        $schedules = ScheduleShuttle::select([
            'schedule_shuttles.id',
            'schedule_shuttles.from_type',
            'from_master_area.name as from_master_area_name',
            'from_master_sub_area.name as from_master_sub_area_name',
            'to_master_area.name as to_master_area_name',
            'to_master_sub_area.name as to_master_sub_area_name',
            'schedule_shuttles.vehicle_name',
            'schedule_shuttles.vehicle_number',
            'schedule_shuttles.time_departure',
            'schedule_shuttles.photo',
            'schedule_shuttles.price',
            'schedule_shuttles.driver_contact',
            'schedule_shuttles.notes',
            'schedule_shuttles.total_seat',
            'schedule_shuttles.luggage_price',
            DB::raw("
                ifnull(
                (select (sum(bookings.qty_adult) + sum(bookings.qty_baby)) from bookings where bookings.schedule_id = schedule_shuttles.id and DATE(bookings.datetime_departure) = '" . $request->date_booking . "' group by bookings.schedule_id)
                , 0) as seat_booked
            ")
        ])
            ->leftJoin('master_areas as from_master_area', 'from_master_area.id', '=', 'schedule_shuttles.from_master_area_id')
            ->leftJoin('master_sub_areas as from_master_sub_area', 'from_master_sub_area.id', '=', 'schedule_shuttles.from_master_sub_area_id')
            ->leftJoin('master_areas as to_master_area', 'to_master_area.id', '=', 'schedule_shuttles.to_master_area_id')
            ->leftJoin('master_sub_areas as to_master_sub_area', 'to_master_sub_area.id', '=', 'schedule_shuttles.to_master_sub_area_id')
            ->where('schedule_shuttles.is_active', true);

        if ($request->from_type == "airport") {
            $schedules->where([
                'schedule_shuttles.from_master_area_id' => $request->from_master_area_id,
                'schedule_shuttles.to_master_area_id' => $request->to_master_area_id,
                'schedule_shuttles.to_master_sub_area_id' => $request->to_master_sub_area_id,
            ]);
        } else {
            $schedules->where([
                'schedule_shuttles.from_master_area_id' => $request->from_master_area_id,
                'schedule_shuttles.from_master_sub_area_id' => $request->from_master_sub_area_id,
                'schedule_shuttles.to_master_area_id' => $request->to_master_area_id,
            ]);
        }

        if ($request->date_booking == Carbon::now()->format('Y-m-d')) {
            $schedules->where('schedule_shuttles.time_departure', '>=', Carbon::now()->format('H:i:s'));
        }

        $schedules = $schedules->get();

        if ($schedules->count() == 0) {
            return $this->sendError('Data Empty', null, 200);
        }

        $data = [];
        foreach ($schedules as $key) {

            $is_available = true;
            if ($key->total_seat - $key->seat_booked <= 0) {
                $is_available = false;
            } elseif (($key->seat_booked + $request->qty_adult + $request->qty_baby) > $key->total_seat) {
                $is_available = false;
            }

            $nested['id'] = $key->id;
            $nested['from_type'] = $key->from_type;
            $nested['from_master_area_name'] = $key->from_master_area_name;
            $nested['from_master_sub_area_name'] = $key->from_master_sub_area_name;
            $nested['to_master_area_name'] = $key->to_master_area_name;
            $nested['to_master_sub_area_name'] = $key->to_master_sub_area_name;
            $nested['vehicle_name'] = $key->vehicle_name;
            $nested['vehicle_number'] = $key->vehicle_number;
            $nested['time_departure'] = $key->time_departure;
            $nested['photo'] = $key->photo;
            $nested['price'] = (float)$key->price;
            $nested['driver_contact'] = $key->driver_contact;
            $nested['notes'] = $key->notes;
            $nested['total_seat'] = $key->total_seat;
            $nested['luggage_price'] = (float)$key->luggage_price;
            $nested['total_seat_used'] = (int)$key->seat_booked;
            $nested['is_available'] = $is_available;
            array_push($data, $nested);
        }

        return $this->sendResponse($data, 'success');
    }

    public function get_avail_charter(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'from_type' => 'required|in:airport,city',
                'from_master_area_id' => 'required',
                'from_master_sub_area_id' => 'nullable',
                'to_master_area_id' => 'required',
                'to_master_sub_area_id' => 'nullable',
                'date_reserve' => 'required|date|after_or_equal:today|date_format:Y-m-d',
            ]
        );

        if ($validator->fails()) {
            $errors = $validator->errors();
            $error_message = "";
            foreach ($validator->failed() as $key => $val) {
                if ($errors->first($key)) {
                    $error_message = $errors->first($key);
                }
            }
            return $this->sendError($error_message, null);
        }

        $schedules = Charter::where('is_available', '=', 1)->whereRaw(DB::raw("
        charters.id NOT IN (
            SELECT
                bookings.schedule_id
            FROM
                bookings
            WHERE
                bookings.schedule_type = 'charter'
            AND DATE ( bookings.datetime_departure ) = '$request->date_reserve'
            AND bookings.payment_status != 'failed'
        )
        "));

        if ($request->from_type == "airport") {
            $schedules->where([
                'from_master_area_id' => $request->from_master_area_id,
                'to_master_area_id' => $request->to_master_area_id,
                'to_master_sub_area_id' => $request->to_master_sub_area_id,
            ]);
        } else {
            $schedules->where([
                'from_master_area_id' => $request->from_master_area_id,
                'from_master_sub_area_id' => $request->from_master_sub_area_id,
                'to_master_area_id' => $request->to_master_area_id,
            ]);
        }
        $schedules = $schedules->get();
        // return response()->json($schedules->toSql());

        if ($schedules->count() == 0) {
            return $this->sendError('Data Empty', null);
        }

        $data = [];
        foreach ($schedules as $key) {
            $nested['id'] = $key->id;
            $nested['from_type'] = $key->from_type;
            $nested['from_master_area_id'] = $key->from_master_area_id;
            $nested['from_master_area_name'] = ($key->from_master_area_id) ? MasterArea::where('id', $key->from_master_area_id)->first()->name : null;
            $nested['from_master_sub_area_id'] = $key->from_master_sub_area_id;
            $nested['from_master_sub_area_name'] = ($key->from_master_sub_area_id) ? MasterSubArea::where('id', $key->from_master_sub_area_id)->first()->name : null;
            $nested['to_master_area_id'] = $key->to_master_area_id;
            $nested['to_master_area_name'] = ($key->to_master_area_id) ? MasterArea::where('id', $key->to_master_area_id)->first()->name : null;
            $nested['to_master_sub_area_id'] = $key->to_master_sub_area_id;
            $nested['to_master_sub_area_name'] = ($key->to_master_sub_area_id) ? MasterSubArea::where('id', $key->to_master_sub_area_id)->first()->name : null;
            $nested['vehicle_name'] = $key->vehicle_name;
            $nested['vehicle_number'] = $key->vehicle_number;
            $nested['is_available'] = $key->is_available;
            $nested['photo'] = env('APP_URL') . $key->photo;
            $nested['price'] = $key->price;
            $nested['driver_contact'] = $key->driver_contact;
            $nested['notes'] = $key->notes;

            array_push($data, $nested);
        }

        return $this->sendResponse($data, 'success');
    }

    private function generate_booking_number()
    {
        $seq = BookingSequence::where([
            'date_sequence' => Carbon::now()->format('Y-m-d'),
        ])->first();

        if ($seq) {
            $current_sequence = $seq->current_sequence + 1;

            //update sequences
            $up = BookingSequence::find($seq->id);
            $up->current_sequence = $current_sequence;
            $up->save();
        } else {
            $current_sequence = 1;

            // store new sequences
            $booking_sequences = new BookingSequence();
            $booking_sequences->date_sequence = Carbon::now()->format('Y-m-d');
            $booking_sequences->current_sequence = $current_sequence;
            $booking_sequences->save();
        }

        $now = Carbon::now();
        $prefix = "GEO";
        $year = $now->format('y');
        $month = $now->format('m');
        $date = $now->format('d');
        $unique = 0;

        switch ($current_sequence) {
            case ($current_sequence < 10):
                $unique = "000" . $current_sequence;
                break;
            case ($current_sequence < 100):
                $unique = "00" . $current_sequence;
                break;
            case ($current_sequence < 1000):
                $unique = "0" . $current_sequence;
                break;
            default:
                $unique = $current_sequence;
                break;
        }

        return $prefix . $year . $month . $date . $unique;
    }

    private function count_seat_avail($total_seat, $total_person)
    {
        // $sum_seat_used = Booking::where([
        //     'schedule_id' => $schedule_id,
        //     'schedule_type' => 'shuttle',
        // ])
        //     ->whereIn('booking_status', ['pending', 'active'])
        //     ->whereIn('payment_status', ['waiting', 'paid'])
        //     ->get();

        // $sum_qty_adult = $sum_seat_used->sum('qty_adult');
        // $sum_qty_baby = $sum_seat_used->sum('qty_baby');


        // $remaining_seat = $total_seat;

        $result = ($total_seat >= $total_person) ? true : false;

        return $result;
    }

    public function get_special_area(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'master_sub_area_id' => 'required',
            ],
        );

        if ($validator->fails()) {
            $errors = $validator->errors();
            $error_message = "";
            foreach ($validator->failed() as $key => $val) {
                if ($errors->first($key)) {
                    $error_message = $errors->first($key);
                }
            }
            return $this->sendError($error_message, null);
        }

        $master_sub_areas = MasterSubArea::where('id', $request->master_sub_area_id)->where('is_active', true)->first();
        if (!$master_sub_areas) {
            return $this->sendError('Master Sub Area Not Found', null);
        }

        $master_special_areas = MasterSpecialArea::where('master_sub_area_id', $request->master_sub_area_id)->where('is_active', true)->get();
        if ($master_special_areas->count() == 0) {
            return $this->sendError('Master Special Area Not Found', null);
        }

        return $this->sendResponse($master_special_areas, 'success');
    }

    public function get_list_sub_area(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'master_area_id' => 'required|exists:master_sub_areas,master_area_id',
            ],
        );

        if ($validator->fails()) {
            $errors = $validator->errors();
            $error_message = "";
            foreach ($validator->failed() as $key => $val) {
                if ($errors->first($key)) {
                    $error_message = $errors->first($key);
                }
            }
            return $this->sendError($error_message, null);
        }

        $master_area_id = $request->master_area_id;

        $master_sub_areas = MasterSubArea::where([
            'master_area_id' => $master_area_id,
            'is_active' => true,
        ])->orderBy('name', 'asc')->get();

        if ($master_sub_areas->count() == 0) {
            return $this->sendError('Data Empty', null);
        }

        $data = [];
        foreach ($master_sub_areas as $key_area) {
            $id_area = $key_area->id;
            $name_area = $key_area->name;

            $nested = [
                'id' => $id_area,
                'name' => $name_area,
            ];
            array_push($data, $nested);
        }

        return $this->sendResponse($data, 'success');
    }

    public function reschedule(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'id' => 'required|exists:bookings,id',
                'datetime_departure' => 'required|date|date_format:"Y-m-d H:i:s"',
            ],
        );

        if ($validator->fails()) {
            $errors = $validator->errors();
            $error_message = "";
            foreach ($validator->failed() as $key => $val) {
                if ($errors->first($key)) {
                    $error_message = $errors->first($key);
                }
            }
            return $this->sendError($error_message, null);
        }

        $id = $request->id;
        $datetime_departure = $request->datetime_departure;

        $exec = Booking::find($id);
        $exec->datetime_departure = $datetime_departure;
        $exec->save();

        return $this->sendResponse($exec, 'success');
    }

    public function change_payment_status(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'id' => 'required|exists:bookings,id',
                'payment_status' => 'required',
            ],
        );

        if ($validator->fails()) {
            $errors = $validator->errors();
            $error_message = "";
            foreach ($validator->failed() as $key => $val) {
                if ($errors->first($key)) {
                    $error_message = $errors->first($key);
                }
            }
            return $this->sendError($error_message, null);
        }

        $id = $request->id;
        $payment_status = $request->payment_status;


        $exec = Booking::find($id);
        $exec->payment_status = $payment_status;
        if ($request->payment_status == 'paid'){
            $exec->booking_status = 'active';
        }

        if ($request->payment_status == 'waiting'){
            $exec->booking_status = 'pending';
        }

        $exec->save();

        return $this->sendResponse($exec, 'success');
    }
}
