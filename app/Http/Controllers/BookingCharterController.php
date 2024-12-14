<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Charter;
use App\Models\Voucher;
use Illuminate\Http\Request;
use App\Models\ScheduleShuttle;

class BookingCharterController extends Controller
{
    public function index()
    {
        $bookings = Booking::where([
            'schedule_type' => 'charter'
        ])->orderBy('created_at', 'desc')->get();

        $data = [];
        foreach ($bookings as $key) {
            $id = $key->id;
            $booking_number = $key->booking_number;
            $schedule_id = $key->schedule_id;
            $from_master_area_id = $key->from_master_area_id;
            $from_master_area_name = $key->from_master_area_name;
            $from_master_sub_area_id = $key->from_master_sub_area_id;
            $from_master_sub_area_name = $key->from_master_sub_area_name;
            $to_master_area_id = $key->to_master_area_id;
            $to_master_area_name = $key->to_master_area_name;
            $to_master_sub_area_id = $key->to_master_sub_area_id;
            $to_master_sub_area_name = $key->to_master_sub_area_name;
            $vehicle_name = $key->vehicle_name;
            $vehicle_number = $key->vehicle_number;
            $datetime_departure = $key->datetime_departure;
            $schedule_type = $key->schedule_type;
            $customer_phone = $key->customer_phone;
            $customer_name = $key->customer_name;
            $passanger_phone = $key->passanger_phone;
            $passanger_name = $key->passanger_name;
            $qty_adult = $key->qty_adult;
            $qty_baby = $key->qty_baby;
            $special_request = $key->special_request;
            $regional_name = $key->regional_name;
            $special_area_detail = $key->special_area_detail;
            $flight_number = $key->flight_number;
            $flight_info = $key->flight_info;
            $notes = $key->notes;
            $luggage_qty = $key->luggage_qty;
            $luggage_price = $key->luggage_price;
            $extra_price = $key->extra_price;
            $voucher_id = $key->voucher_id;
            $promo_price = $key->promo_price;
            $base_price = $key->base_price;
            $total_price = $key->total_price;
            $booking_status = $key->booking_status;
            $payment_status = $key->payment_status;
            $payment_method = $key->payment_method;
            $payment_token = $key->payment_token;
            $total_payment = $key->total_payment;

            $from_area = $from_master_area_name;
            if ($from_master_sub_area_name) {
                $from_area .= " - " . $from_master_sub_area_name;
            }

            $to_area = $to_master_area_name;
            if ($to_master_sub_area_name) {
                $to_area .= " - " . $to_master_sub_area_name;
            }

            if ($schedule_type == "shuttle") {
                $from_type = ScheduleShuttle::where('id', $schedule_id)->first();
            } else {
                $from_type = Charter::where('id', $schedule_id)->first();
            }

            $voucher_name = "";
            if ($voucher_id) {
                $voucher_name = Voucher::where('id', $voucher_id)->first()->name;
            }

            $nested = [
                'id' => $id,
                'booking_number' => $booking_number,
                'schedule_id' => $schedule_id,
                'from_type' => !empty($from_type) ? $from_type->from_type : "",
                'from_area' => $from_area,
                'to_area' => $to_area,
                'from_master_area_id' => $from_master_area_id,
                'from_master_area_name' => $from_master_area_name,
                'from_master_sub_area_id' => $from_master_sub_area_id,
                'from_master_sub_area_name' => $from_master_sub_area_name,
                'to_master_area_id' => $to_master_area_id,
                'to_master_area_name' => $to_master_area_name,
                'to_master_sub_area_id' => $to_master_sub_area_id,
                'to_master_sub_area_name' => $to_master_sub_area_name,
                'vehicle_name' => $vehicle_name,
                'vehicle_number' => $vehicle_number,
                'datetime_departure' => $datetime_departure,
                'schedule_type' => $schedule_type,
                'customer_phone' => $customer_phone,
                'customer_name' => $customer_name,
                'passanger_phone' => $passanger_phone,
                'passanger_name' => $passanger_name,
                'qty_adult' => $qty_adult,
                'qty_baby' => $qty_baby,
                'special_request' => ($special_request) ? "Yes" : "No",
                'regional_name' => $regional_name,
                'special_area_detail' => $special_area_detail,
                'flight_number' => $flight_number,
                'flight_info' => $flight_info,
                'notes' => $notes,
                'luggage_qty' => $luggage_qty,
                'luggage_price' => $luggage_price,
                'extra_price' => $extra_price,
                'voucher_id' => $voucher_id,
                'voucher_name' => $voucher_name,
                'promo_price' => $promo_price,
                'base_price' => $base_price,
                'total_price' => $total_price,
                'booking_status' => $booking_status,
                'payment_status' => $payment_status,
                'payment_method' => $payment_method,
                'payment_token' => $payment_token,
                'total_payment' => $total_payment,
            ];

            array_push($data, $nested);
        }

        $data = [
            'page_title' => 'Booking Charter',
            'base_url' => env('APP_URL'),
            'app_name' => env('APP_NAME'),
            'app_name_short' => env('APP_NAME_ABBR'),
            'bookings' => $data,
        ];
        return view('admin.booking_charter.main')->with($data);
    }

    public function delete($id)
    {
        Booking::find($id)->delete();
        return response()->json([
            'message' => 'Record deleted successfully!'
        ], 200);
    }
}
