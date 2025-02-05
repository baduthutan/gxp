<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Booking;
use App\Models\BookingCustomer;
use App\Models\Charter;
use App\Models\Dst;
use App\Models\Eta;
use App\Models\MasterArea;
use App\Models\MasterSpecialArea;
use App\Services\ScheduleQueryService;
use App\Services\StripeTransaction;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\MasterSubArea;
use App\Models\Page;
use Illuminate\Support\Carbon;
use App\Models\ScheduleShuttle;
use App\Models\Voucher;

class WelcomeController extends Controller
{
    public function index()
    {
        $banners = Banner::where('is_active', true)->get();
        $pages = Page::get();
        $master_area = MasterArea::query()
            ->with(['master_sub_area' => function ($q) {
                $q->where('is_active', '1')->whereIn('is_charter',[0,2]);
            }])->where('is_active', '1')->whereIn('is_charter',[0,2])->get();

        $data = [
            'title' => env('APP_NAME'),
            'master_area' => $master_area,
            'app_name' => env('APP_NAME'),
            'banners' => $banners,
            'pages' => $pages,
        ];

        return view('home', $data);
    }

    public function page($slug)
    {
        $banners = Banner::where('is_active', true)->get();
        $pages = Page::get();

        if (empty($slug)){
            abort('404');
        }

        $isis = Page::where('slug', $slug)->first();

        $data = [
            'title' => env('APP_NAME'),
            'app_name' => env('APP_NAME'),
            'banners' => $banners,
            'pages' => $pages,
            'isis' => $isis,
        ];

        if (!$isis) {
            return view('page_not_found', $data);
        }

        return view('page', $data);
    }

    public function services()
    {
        $banners = Banner::where('is_active', true)->get();
        $pages = Page::get();



        $data = [
            'title' => env('APP_NAME'),
            'app_name' => env('APP_NAME'),
            'banners' => $banners,
            'pages' => $pages,
        ];

        return view('user.services', $data);
    }

    public function schedule()
    {
        $pages = Page::get();
        $dst = Dst::first();

        // City -> [ Newark + JFK Airport ] Schedule
        $cities_data = ScheduleShuttle::select([
            'schedule_shuttles.id',
            'schedule_shuttles.from_type',
            'from_areas.name as from_master_area',
            'from_sub_areas.name as from_master_sub_area',
            'to_areas.name as to_master_area',
            'to_sub_areas.name as to_master_sub_area',
            'schedule_shuttles.time_departure'
        ])
        ->leftJoin('master_areas as from_areas', 'schedule_shuttles.from_master_area_id', '=', 'from_areas.id')
        ->leftJoin('master_sub_areas as from_sub_areas', 'schedule_shuttles.from_master_sub_area_id', '=', 'from_sub_areas.id')
        ->leftJoin('master_areas as to_areas', 'schedule_shuttles.to_master_area_id', '=', 'to_areas.id')
        ->leftJoin('master_sub_areas as to_sub_areas', 'schedule_shuttles.to_master_sub_area_id', '=', 'to_sub_areas.id')
        ->where('schedule_shuttles.is_active', true)
        ->whereIn('schedule_shuttles.from_master_area_id', [1, 2]) // [ Philadelphia (ID: 1) + New Jersey (ID: 2) ]
        ->whereIn('schedule_shuttles.to_master_area_id', [3, 4]) // [ Newark Airport (ID: 3) + JFK Airport (ID: 4) ]
        ->groupBy([
            'schedule_shuttles.id',
            'schedule_shuttles.from_type',
            'from_areas.name',
            'from_sub_areas.name',
            'to_areas.name',
            'to_sub_areas.name',
            'schedule_shuttles.time_departure'
        ])
        ->get();

        // Airport -> [ Philadelphia + New Jersey ] Schedule
        $airport_data = ScheduleShuttle::select([
            'schedule_shuttles.id',
            'schedule_shuttles.from_type',
            'from_areas.name as from_master_area',
            'from_sub_areas.name as from_master_sub_area',
            'to_areas.name as to_master_area',
            'to_sub_areas.name as to_master_sub_area',
            'schedule_shuttles.time_departure'
        ])
        ->leftJoin('master_areas as from_areas', 'schedule_shuttles.from_master_area_id', '=', 'from_areas.id')
        ->leftJoin('master_sub_areas as from_sub_areas', 'schedule_shuttles.from_master_sub_area_id', '=', 'from_sub_areas.id')
        ->leftJoin('master_areas as to_areas', 'schedule_shuttles.to_master_area_id', '=', 'to_areas.id')
        ->leftJoin('master_sub_areas as to_sub_areas', 'schedule_shuttles.to_master_sub_area_id', '=', 'to_sub_areas.id')
        ->where('schedule_shuttles.is_active', true)
        ->whereIn('schedule_shuttles.from_master_area_id', [3, 4]) // [ Newark Airport (ID: 3) + JFK Airport (ID: 4) ]
        ->whereIn('schedule_shuttles.to_master_area_id', [1, 2]) // [ Philadelphia (ID: 1) + New Jersey (ID: 2) ]
        ->groupBy([
            'schedule_shuttles.id',
            'schedule_shuttles.from_type',
            'from_areas.name',
            'from_sub_areas.name',
            'to_areas.name',
            'to_sub_areas.name',
            'schedule_shuttles.time_departure'
        ])
        ->orderBy('schedule_shuttles.time_departure', 'asc')
        ->orderBy('schedule_shuttles.id')
        ->orderBy('schedule_shuttles.from_type')
        ->orderBy('from_master_area')
        ->orderBy('from_master_sub_area')
        ->orderBy('to_master_area')
        ->orderBy('to_master_sub_area')
        ->get();

        $dst = Dst::first();
        $nextDst = Dst::skip(1)->first();

        $currentYear = date('Y', strtotime($dst->dst_start));

        $dst_start = Carbon::parse($dst->dst_start);
        $dst_end = Carbon::parse($dst->dst_end);

        $nextDst_start = Carbon::parse($nextDst->dst_start);
        $nextDst_end = Carbon::parse($nextDst->dst_end);

        // ---------- Format untuk Philadelphia ----------
        $cities_data = $cities_data->groupBy('from_master_area')->map(function ($group) use ($dst, $nextDst) {
            $sub_areas = $group->pluck('from_master_sub_area')->unique()->values();

            $grouped_times = $group->groupBy('from_master_sub_area')->map(function ($timesGroup) {
                return $timesGroup->sortBy('time_departure');
            });

            $dst_start = Carbon::parse($dst->dst_start);
            $dst_end = Carbon::parse($dst->dst_end);
        
            $first_trip_times = [];
            $second_trip_times = [];
            $third_trip_times = [];
            $fourth_trip_times = [];
            $fifth_trip_times = [];
            $sixth_trip_times = []; 
        
            $grouped_times->each(function ($timesGroup, $sub_area) use (&$first_trip_times, &$second_trip_times, &$third_trip_times, &$fourth_trip_times, &$fifth_trip_times, &$sixth_trip_times, $dst, $nextDst) {
                $times_by_destination = $timesGroup->groupBy('to_master_area');
                // dd($times_by_destination);
                $sorted_times = $times_by_destination->flatMap(function ($destinationGroup) {
                    return $destinationGroup->pluck('time_departure')->map(function ($time) {
                        return \Carbon\Carbon::parse($time)->format('H:i');
                    });
                })->values()->toArray();
                // Existing logic for 1st to 4th trips
                if (isset($sorted_times[0])) {
                    $first_trip_times[] = $sorted_times[0];
                }
                if (isset($sorted_times[1])) {
                    $second_trip_times[] = $sorted_times[1];
                }

                $third_trip_time = \Carbon\Carbon::parse($sorted_times[0]);
                if ($third_trip_time->hour < 12) {
                    $third_trip_time->addMinutes($dst->morning_schedule_time);
                } else {
                    $third_trip_time->addMinutes($dst->afternoon_schedule_time);
                }
                $third_trip_times[] = $third_trip_time->format('H:i');

                $fourth_trip_time = \Carbon\Carbon::parse($sorted_times[1]);
                if ($fourth_trip_time->hour < 12) {
                    $fourth_trip_time->addMinutes($dst->morning_schedule_time);
                } else {
                    $fourth_trip_time->addMinutes($dst->afternoon_schedule_time);
                }
                $fourth_trip_times[] = $fourth_trip_time->format('H:i');
        
                $fifth_trip_time = \Carbon\Carbon::parse($sorted_times[0]);
                if ($fifth_trip_time->hour < 12) {
                    $fifth_trip_time->addMinutes($nextDst->morning_schedule_time);
                } else {
                    $fifth_trip_time->addMinutes($nextDst->afternoon_schedule_time);
                }
                $fifth_trip_times[] = $fifth_trip_time->format('H:i');
    
                $sixth_trip_time = \Carbon\Carbon::parse($sorted_times[1]);
                if ($sixth_trip_time->hour < 12) {
                    $sixth_trip_time->addMinutes($nextDst->morning_schedule_time);
                } else {
                    $sixth_trip_time->addMinutes($nextDst->afternoon_schedule_time);
                }
                $sixth_trip_times[] = $sixth_trip_time->format('H:i');

            });

            if (Carbon::now()->lessThan($dst_start)) {
                return [
                    'from_master_area' => $group->first()->from_master_area,
                    'from_master_sub_area' => $sub_areas,
                    '1st_trip' => array_values($first_trip_times),
                    '2nd_trip' => array_values($second_trip_times),
                    '3rd_trip' => array_values($third_trip_times),
                    '4th_trip' => array_values($fourth_trip_times),
                    '5th_trip' => array_values($first_trip_times),
                    '6th_trip' => array_values($second_trip_times),
                    'to_master_area' => $group->first()->to_master_area,
                ];
            } elseif (Carbon::now()->greaterThan($dst_end)) {
                return [
                    'from_master_area' => $group->first()->from_master_area,
                    'from_master_sub_area' => $sub_areas,
                    '1st_trip' => array_values($first_trip_times),
                    '2nd_trip' => array_values($second_trip_times),
                    '3rd_trip' => array_values($fifth_trip_times),
                    '4th_trip' => array_values($sixth_trip_times),
                    '5th_trip' => array_values($first_trip_times),
                    '6th_trip' => array_values($second_trip_times),
                    'to_master_area' => $group->first()->to_master_area,
                ];
            } else {
                return [
                    'from_master_area' => $group->first()->from_master_area,
                    'from_master_sub_area' => $sub_areas,
                    '1st_trip' => array_values($third_trip_times),
                    '2nd_trip' => array_values($fourth_trip_times),
                    '3rd_trip' => array_values($first_trip_times),
                    '4th_trip' => array_values($second_trip_times),
                    '5th_trip' => array_values($fifth_trip_times),
                    '6th_trip' => array_values($sixth_trip_times),
                    'to_master_area' => $group->first()->to_master_area,
                ];
            }
        
        })->values()->all();
        

        // ---------- Format untuk JFK Airport ----------
        $airport_data = $airport_data->groupBy('from_master_area')->map(function ($group) use ($dst, $nextDst) {
            $sub_areas = $group->pluck('from_master_area')->unique()->values();
            $grouped_times = $group->groupBy('to_master_sub_area')->map(function ($timesGroup) {
                return $timesGroup->sortBy('time_departure');
            });
            // dd($grouped_times);
            $dst_start = Carbon::parse($dst->dst_start);
            $dst_end = Carbon::parse($dst->dst_end);
        
            $first_trip_times = [];
            $second_trip_times = [];
            $third_trip_times = [];
            $fourth_trip_times = [];
            $fifth_trip_times = []; 
            $sixth_trip_times = [];  
        
            $grouped_times->each(function ($timesGroup, $sub_area) use (&$first_trip_times, &$second_trip_times, $dst, $nextDst) {
                $times_by_destination = $timesGroup->groupBy('to_master_area');
        
                $sorted_times = $times_by_destination->flatMap(function ($destinationGroup) {
                    return $destinationGroup->pluck('time_departure')->map(function ($time) {
                        return \Carbon\Carbon::parse($time)->format('H:i');
                    });
                })->values()->toArray();
        
                if (isset($sorted_times[0])) {
                    $first_trip_times[] = $sorted_times[0];
                }
                if (isset($sorted_times[1])) {
                    $second_trip_times[] = $sorted_times[1];
                }
            });
        
            // Adjust the 3rd and 4th trip times based on this year's DST
            $third_trip_time = \Carbon\Carbon::parse(array_slice($first_trip_times, 0, 1)[0]);
            if ($third_trip_time->hour < 12) {
                $third_trip_time->addMinutes($dst->morning_schedule_time);
            } else {
                $third_trip_time->addMinutes($dst->afternoon_schedule_time);
            }
            $third_trip_times[] = $third_trip_time->format('H:i');
        
            $fourth_trip_time = \Carbon\Carbon::parse(array_slice($second_trip_times, 0, 1)[0]);
            if ($fourth_trip_time->hour < 12) {
                $fourth_trip_time->addMinutes($dst->morning_schedule_time);
            } else {
                $fourth_trip_time->addMinutes($dst->afternoon_schedule_time);
            }
            $fourth_trip_times[] = $fourth_trip_time->format('H:i');
        
            // Adjust the 5th and 6th trip times based on next year's DST
            $fifth_trip_time = \Carbon\Carbon::parse(array_slice($first_trip_times, 0, 1)[0]);
            if ($fifth_trip_time->hour < 12) {
                $fifth_trip_time->addMinutes($nextDst->morning_schedule_time);
            } else {
                $fifth_trip_time->addMinutes($nextDst->afternoon_schedule_time);
            }
            $fifth_trip_times[] = $fifth_trip_time->format('H:i');
        
            $sixth_trip_time = \Carbon\Carbon::parse(array_slice($second_trip_times, 0, 1)[0]);
            if ($sixth_trip_time->hour < 12) {
                $sixth_trip_time->addMinutes($nextDst->morning_schedule_time);
            } else {
                $sixth_trip_time->addMinutes($nextDst->afternoon_schedule_time);
            }
            $sixth_trip_times[] = $sixth_trip_time->format('H:i');
        
            if (Carbon::now()->lessThan($dst_start)) {
                return [
                    'from_master_area' => null,
                    'from_master_sub_area' => $sub_areas,
                    '1st_trip' => array_slice($first_trip_times, 0, 1),
                    '2nd_trip' => array_slice($second_trip_times, 0, 1),
                    '3rd_trip' => array_slice($third_trip_times, 0, 1),
                    '4th_trip' => array_slice($fourth_trip_times, 0, 1),
                    '5th_trip' => array_slice($first_trip_times, 0, 1),
                    '6th_trip' => array_slice($second_trip_times, 0, 1),
                    'to_master_area' => $group->first()->to_master_area,
                ];
            } elseif (Carbon::now()->greaterThan($dst_end)) {
                return [
                    'from_master_area' => null,
                    'from_master_sub_area' => $sub_areas,
                    '1st_trip' => array_slice($first_trip_times, 0, 1),
                    '2nd_trip' => array_slice($second_trip_times, 0, 1),
                    '3rd_trip' => array_slice($fifth_trip_times, 0, 1),
                    '4th_trip' => array_slice($sixth_trip_times, 0, 1),
                    '5th_trip' => array_slice($first_trip_times, 0, 1),
                    '6th_trip' => array_slice($second_trip_times, 0, 1),
                    'to_master_area' => $group->first()->to_master_area,
                ];
            } else {
                return [
                    'from_master_area' => null,
                    'from_master_sub_area' => $sub_areas,
                    '1st_trip' => array_slice($third_trip_times, 0, 1),
                    '2nd_trip' => array_slice($fourth_trip_times, 0, 1),
                    '3rd_trip' => array_slice($first_trip_times, 0, 1),
                    '4th_trip' => array_slice($second_trip_times, 0, 1),
                    '5th_trip' => array_slice($fifth_trip_times, 0, 1),
                    '6th_trip' => array_slice($sixth_trip_times, 0, 1),
                    'to_master_area' => $group->first()->to_master_area,
                ];
            }
        })->values()->all();

        $eta = Eta::get();
        $first_eta_morning = [];
        $second_eta_morning = [];
        $third_eta_morning = [];

        $first_eta_afternoon = [];
        $second_eta_afternoon = [];
        $third_eta_afternoon = [];

        foreach ($eta as $data) {
            $trip1 = $data->trip_1 ?? null;
            $trip2 = $data->trip_2 ?? null;

            if ($trip1 !== null) {
                $first_eta_morning[] = $trip1;
                $second_eta_morning[] = $trip1;
                $third_eta_morning[] = $trip1;
            }

            if ($trip2 !== null) {
                $first_eta_afternoon[] = $trip2;
                $second_eta_afternoon[] = $trip2;
                $third_eta_afternoon[] = $trip2;
            }
        }

        function addMinutesToArray(array &$array, $minutes) {
            foreach ($array as &$time) {
                $time = Carbon::parse($time)->addMinutes($minutes)->format('H:i');
            }
        }

        if (Carbon::now()->lessThan($dst_start)) {
            $first_from = Carbon::parse("$currentYear-01-01")->format('d-m-Y');
            $first_to = $dst_start->copy()->subDay()->format('d-m-Y');
            $second_from = $dst_start->format('d-m-Y');
            $second_to = $dst_end->format('d-m-Y');
            $third_from = $dst_end->copy()->addDay()->format('d-m-Y');
            $third_to = $nextDst_start->copy()->subDay()->format('d-m-Y');

            addMinutesToArray($second_eta_afternoon, $dst->afternoon_schedule_time);
            addMinutesToArray($second_eta_morning, $dst->morning_schedule_time);
        } elseif (Carbon::now()->greaterThan($dst_end)) {
            $first_from = $dst_end->copy()->addDay()->format('d-m-Y');
            $first_to = $nextDst_start->copy()->subDay()->format('d-m-Y');
            $second_from = $nextDst_start->format('d-m-Y');
            $second_to = $nextDst_end->format('d-m-Y');
            $third_from = $nextDst_end->copy()->addDay()->format('d-m-Y');
            $third_to = Carbon::parse("$currentYear-12-31")->addYear()->format('d-m-Y');

            addMinutesToArray($second_eta_afternoon, $nextDst->afternoon_schedule_time);
            addMinutesToArray($second_eta_morning, $nextDst->morning_schedule_time);
        } else {
            $first_from = $dst_start->format('d-m-Y');
            $first_to = $dst_end->format('d-m-Y');
            $second_from = $dst_end->copy()->addDay()->format('d-m-Y');
            $second_to = $nextDst_start->copy()->subDay()->format('d-m-Y');
            $third_from = $nextDst_start->format('d-m-Y');
            $third_to = $nextDst_end->format('d-m-Y');

            addMinutesToArray($first_eta_afternoon, $dst->afternoon_schedule_time);
            addMinutesToArray($first_eta_morning, $dst->morning_schedule_time);
            addMinutesToArray($third_eta_afternoon, $nextDst->afternoon_schedule_time);
            addMinutesToArray($third_eta_morning, $nextDst->morning_schedule_time);
        }

        

        // ---------- Format untuk view data ----------
        $data = [
            'title' => 'schedule',
            'app_name' => env('APP_NAME'),
            'pages' => $pages,
            'schedule' => [
                0 => $cities_data,
                1 => $airport_data
            ],
            'first_from' => $first_from,
            'first_to' => $first_to,
            'second_from' => $second_from,
            'second_to' => $second_to,
            'third_from' => $third_from,
            'third_to' => $third_to,
            'first_eta_morning' => $first_eta_morning,
            'second_eta_morning' => $second_eta_morning,
            'third_eta_morning' => $third_eta_morning,
            'first_eta_afternoon' => $first_eta_afternoon,
            'second_eta_afternoon' => $second_eta_afternoon,
            'third_eta_afternoon' => $third_eta_afternoon,
        ];

        // header('Content-Type: application/json');
        // echo json_encode($data); exit();

        return view('user.schedule', $data);
    }

    public function search(Request $request)
    {
        // dd($request->all());
        // Validate the request
        $validated = $request->validate([
            'booking_type' => 'required|string',
            'to_master_sub_area_id' => 'required|integer',
            'area_type' => 'required|string',
            'is_roundtrip' => 'required|integer',
        ]);
    
        // $charterTypes = $request->booking_type !== 'shuttle' ? [1, 2] : [0, 2];

        // $master_area = MasterArea::query()
        //     ->with(['master_sub_area' => function ($q) use ($charterTypes) {
        //         $q->where('is_active', '1')->whereIn('is_charter', $charterTypes);
        //     }])
        //     ->where('is_active', '1')
        //     ->whereIn('is_charter', $charterTypes)
        //     ->get();
    
        // $arrival_area = MasterArea::query()
        //     ->where('area_type', '<>', $request->area_type)
        //     ->with(['master_sub_area' => function ($q) use ($charterTypes) {
        //         $q->where('is_active', '1')->whereIn('is_charter', $charterTypes);
        //     }])
        //     ->where('is_active', '1')
        //     ->whereIn('is_charter', $charterTypes)
        //     ->get();
    
        // Generate schedule data
        // $from_request = clone $request;
        // $from_request->merge([
        //     'from_master_sub_area_id' => $request->to_master_sub_area_id,
        //     'to_master_sub_area_id' => $request->from_master_sub_area_id,
        //     'date_departure' => $request->date_departure,
        // ]);
        $schedule = ScheduleQueryService::generate_data($request);
    
        // If roundtrip, generate return schedule
        $return_schedule = null;
        if ($request->is_roundtrip == 1) {
            $return_request = clone $request;
            $return_request->merge([
                'from_master_sub_area_id' => $request->from_master_sub_area_id_2,
                'to_master_sub_area_id' => $request->to_master_sub_area_id_2,
                'date_departure' => $request->date_departure_2, // Assuming the return date is passed in this field
            ]);
            $return_schedule = ScheduleQueryService::generate_data($return_request);
        }
    
        // Adjust departure times based on DST
        $dst = Dst::first();
        $nextDst = Dst::skip(1)->first();
    
        foreach ($schedule as $item) {
            $item->time_departure = $this->adjustTimeForDst($item->time_departure, $request->date_departure, $dst, $nextDst);
        }
    
        if ($return_schedule) {
            foreach ($return_schedule as $item) {
                $item->time_departure = $this->adjustTimeForDst($item->time_departure, $request->date_departure_2, $dst, $nextDst);
            }
        }
    
        // Prepare data for the view
        $data = [
            'title' => config('app.name'),
            'app_name' => config('app.name'),
            'schedule' => $schedule,
            'return_schedule' => $return_schedule,
            'pages' => Page::all(),
            'is_roundtrip' => $request->is_roundtrip,
            'passenger_adult' => $request->passenger_adult,
            'passenger_baby' => $request->passenger_baby,
            'booking_type' => $request->booking_type,
            'date_departure' => $request->date_departure,
            'date_departure_2' => $request->date_departure_2
        ];
        // dd($data);
        // Return the view with data
        return view('search', $data);
    }
    
    /**
     * Adjust time for DST
     */
    private function adjustTimeForDst($timeDeparture, $dateDeparture, $dst, $nextDst)
    {
        $timeDeparture = \Carbon\Carbon::parse($timeDeparture);
        if (\Carbon\Carbon::parse($dateDeparture)->between($dst->dst_start, $dst->dst_end)) {
            if ($timeDeparture->hour < 12) {
                $timeDeparture->addMinutes($dst->morning_schedule_time);
            } else {
                $timeDeparture->addMinutes($dst->afternoon_schedule_time);
            }
        } elseif (\Carbon\Carbon::parse($dateDeparture)->between($nextDst->dst_start, $nextDst->dst_end)) {
            if ($timeDeparture->hour < 12) {
                $timeDeparture->addMinutes($nextDst->morning_schedule_time);
            } else {
                $timeDeparture->addMinutes($nextDst->afternoon_schedule_time);
            }
        }
        return $timeDeparture->format('H:i');
    }
    

    public function debugger($data) {
        header('Content-Type: application/json');
        echo json_encode($data, JSON_PRETTY_PRINT); exit();
    }

    public function booking(Request $request)
    {
        $request->validate([
            'departure_schedule' => 'required|json',
        ]);
        // dd($request->all());
        $departure_schedule = json_decode($request->departure_schedule);
        

        // Access nested data
        $from_master_area = $departure_schedule->from_master_area->name;
        $from_master_sub_area = $departure_schedule->from_master_sub_area->name ?? '';
        $from_type = $departure_schedule->from_master_area->area_type;
        $to_master_area = $departure_schedule->to_master_area->name;
        $to_master_sub_area = $departure_schedule->to_master_sub_area->name ?? '';
        $base_price = $departure_schedule->price;
        $luggage_price = $departure_schedule->luggage_price;

        $pages = Page::get();

        $booking_type = $request->booking_type;
        $date_departure = $request->date_departure;
        $is_roundtrip = $request->is_roundtrip;
        $passenger_adult = $request->passenger_adult ?? 1;
        $passenger_baby = $request->passenger_baby ?? 0;

        if($is_roundtrip == 1){
            $request->validate([
                'return_schedule' => 'required|json',
            ]);
            $return_schedule = json_decode($request->return_schedule) ?? '';
            $return_from_master_area = $return_schedule->from_master_area->name;
            $return_from_master_sub_area = $return_schedule->from_master_sub_area->name ?? '';
            $return_from_type = $return_schedule->from_master_area->area_type;
            $return_to_master_area = $return_schedule->to_master_area->name;
            $return_to_master_sub_area = $return_schedule->to_master_sub_area->name ?? '';
            $base_price = $departure_schedule->price + $return_schedule->price;
            $return_luggage_price = $return_schedule->luggage_price;
            $date_return = $request->date_departure_2;
        }

        session([
            'from_type' => $from_type,
            'from_master_area' => $from_master_area,
            'from_master_sub_area' => $from_master_sub_area,
            'to_master_area' => $to_master_area,
            'to_master_sub_area' => $to_master_sub_area,
            'booking_type' => $booking_type,
            'date_departure' => $date_departure,
            'passenger_adult' => $passenger_adult,
            'passenger_baby' => $passenger_baby,
            'is_roundtrip' => $is_roundtrip
        ]);

        if ($booking_type == "shuttle") {  
            $datetime_format = 'Y M d h:i A';
        } else {
            $datetime_format = 'Y M d';
        }

        $dst = Dst::first();
        $timeDeparture = \Carbon\Carbon::parse($departure_schedule->time_departure);
        if (\Carbon\Carbon::parse($request->date_departure)->between($dst->dst_start, $dst->dst_end)) {
            if ($timeDeparture->hour < 12) {
                $timeDeparture->addMinutes($dst->morning_schedule_time);
            } else {
                $timeDeparture->addMinutes($dst->afternoon_schedule_time);
            }
        }
        $departure_schedule->time_departure = $timeDeparture->format('H:i');
        $date_time_departure = Carbon::parse($date_departure . " " . $departure_schedule->time_departure)->format($datetime_format);
        if ($from_type == "airport") {      
            $special_areas = MasterSpecialArea::where('is_active', true)
                ->where('master_sub_area_id', $departure_schedule->to_master_sub_area->id)
                ->orderBy('regional_name', 'asc')
                ->get();
        } elseif ($from_type == "city") {  
            $special_areas = MasterSpecialArea::where('is_active', true)
                ->where('master_sub_area_id', $departure_schedule->from_master_sub_area->id)
                ->orderBy('regional_name', 'asc')
                ->get();
        } else {
            $special_areas = collect([]);
        }
        
        $destination_type = MasterArea::where('id', $departure_schedule->to_master_area->id)->first();
        $passenger_total = $passenger_adult + $passenger_baby;
        $base_price_total = $base_price * $passenger_total;
        

        $pajak = env('PAJAK');
        if(empty($pajak)){
            $pajak=0;
        }


        
        $data = [
            'title'               => env('APP_NAME'),
            'app_name'            => env('APP_NAME'),
            'booking_type'        => $booking_type,
            'pages'               => $pages,
            'special_areas'       => $special_areas,
            'from_type'           => $from_type,
            'from_main_name'      => $from_master_area,
            'from_sub_name'       => $from_master_sub_area,
            'to_main_name'        => $to_master_area,
            'to_sub_name'         => $to_master_sub_area,
            'date_time_departure' => $date_time_departure,
            'passenger_adult'     => $passenger_adult,
            'passenger_baby'      => $passenger_baby,
            'passenger_total'     => $passenger_total,
            'base_price_total'    => $base_price_total,
            'luggage_price'       => $luggage_price,
            'pajak'               => $pajak,
            'is_roundtrip'        => $is_roundtrip,
            // '_token' => $request->all()['_token'],
        ];
        if($is_roundtrip == 1){
            // Add return-specific data to $data array
            $data = array_merge($data, [
                'return_from_main_name' => $return_from_master_area,
                'return_from_sub_name'  => $return_from_master_sub_area,
                'return_from_type'      => $return_from_type,
                'return_to_main_name'   => $return_to_master_area,
                'return_to_sub_name'    => $return_to_master_sub_area,
                'return_luggage_price'  => $return_luggage_price,
                'date_return'           => $date_return,
            ]);
        }

        return view('booking', $data);
    }


    public function booking_checks(Request $request)
    {
        $code1 = urldecode($request->code1);
        $code2 = urldecode($request->code2);
        
        $pages = Page::get();

        $booking1 = Booking::where('booking_number', $code1)->first();
        $booking2 = Booking::where('booking_number', $code2)->first();

        if (!$booking1 && !$booking2) {
            $data = [
                'title' => 'Booking Not Found',
                'app_name' => env('APP_NAME'),
                'pages' => $pages,
            ];
            return view('booking_not_found', $data);
        }

        if ($booking1 && !$booking2) {
            $vouchers = Voucher::where('id', $booking1->voucher_id)->first();
            $data = [
                'title' => 'Booking Check',
                'app_name' => env('APP_NAME'),
                'pages' => $pages,
                'bookings' => $booking1,
                'vouchers' => $vouchers,
                'hashed_code' => encrypt($booking1->booking_number),
            ];

            return view('check', $data);
        }

        if (!$booking1 && $booking2) {
            $vouchers = Voucher::where('id', $booking2->voucher_id)->first();
            $data = [
                'title' => 'Booking Check',
                'app_name' => env('APP_NAME'),
                'pages' => $pages,
                'bookings' => $booking2,
                'vouchers' => $vouchers,
                'hashed_code' => encrypt($booking2->booking_number),
            ];

            return view('check', $data);
        }

        if ($booking1 && $booking2) {
            $vouchers1 = Voucher::where('id', $booking1->voucher_id)->first();
            $vouchers2 = Voucher::where('id', $booking2->voucher_id)->first();
            $data = [
                'title' => 'Bookings Check',
                'app_name' => env('APP_NAME'),
                'pages' => $pages,
                'booking1' => $booking1,
                'booking2' => $booking2,
                'vouchers1' => $vouchers1,
                'vouchers2' => $vouchers2,
                'hashed_code1' => encrypt($booking1->booking_number),
                'hashed_code2' => encrypt($booking2->booking_number),
            ];

            return view('checks', $data);
        }
    }

    // stripe logic starts here
    public function booking_check(Request $request)
    {
        $encode = $request->code;
        $decode = urldecode($encode);
        $pages = Page::get();

        $bookings = Booking::where('booking_number', $decode)->first();

        if (!$bookings) {
            $data = [
                'title' => 'Booking Check',
                'app_name' => env('APP_NAME'),
                'pages' => $pages,
                'bookings' => $bookings,
            ];
            return view('booking_not_found', $data);
        }

        $vouchers = Voucher::where('id', $bookings->voucher_id)->first();

        $data = [
            'title' => 'Booking Check',
            'app_name' => env('APP_NAME'),
            'pages' => $pages,
            'bookings' => $bookings,
            'vouchers' => $vouchers,
            'hashed_code' => encrypt($bookings->booking_number),
        ];

        return view('check', $data);
    }

    public function booking_ticket(Request $request)
    {
        $encode = $request->code;
        $decode = urldecode($encode);
        $pages = Page::get();

        $bookings = Booking::where('booking_number', $decode)->first();
        $booking_customers = BookingCustomer::where('booking_id', $bookings->booking_number)->get();
        $charter = null;
        if ($bookings->schedule_type) {
            $charter = Charter::where('id', $bookings->schedule_id)->first();
        }

        $data = [
            'title' => 'E - Ticket',
            'app_name' => env('APP_NAME'),
            'pages' => $pages,
            'bookings' => $bookings,
            'charter' => $charter,
            'booking_customers' => $booking_customers,
            'hashed_code' => encrypt($bookings->booking_number),
        ];

        $pdf = PDF::loadview('ticket', $data);
        // return $pdf->download('e-ticket.pdf');
        return $pdf->setPaper('a4', 'potrait')->stream();
    }

    public function booking_payment(Request $request)
    {
        $pages = Page::get();

        try {
            $decryptNumberBooking = decrypt($request->hcode);
        } catch (DecryptException $e) {
            $data = [
                'title' => 'Booking process',
                'app_name' => env('APP_NAME'),
                'pages' => $pages,
                'bookings' => null,
            ];
            return view('booking_not_found', $data);
        }


        $bookings = Booking::where('booking_number', (string)$decryptNumberBooking)->first();

        if (!$bookings) {
            $data = [
                'title' => 'Booking Payment',
                'app_name' => env('APP_NAME'),
                'pages' => $pages,
                'bookings' => $bookings,
            ];

            return view('booking_not_found', $data);
        }


        if ($bookings->payment_status == 'paid' || $bookings->payment_status !== 'waiting') {
            $data = [
                'title' => 'Booking Payment',
                'app_name' => env('APP_NAME'),
                'pages' => $pages,
                'bookings' => $bookings,
            ];

            return view('booking_not_found', $data);
        }

        $vouchers = Voucher::where('id', $bookings->voucher_id)->first();

        $stripe = new StripeTransaction();

        $response = $stripe->create_intent($bookings);

        if ($response->status !== 'requires_payment_method') {
            try {
                $decryptNumberBooking = decrypt($request->hcode);
            } catch (DecryptException $e) {
                abort(401);
            }

            return redirect()->route("booking_check", ["code" => $decryptNumberBooking])->with("message", "<div class='alert alert-warning' role='alert'><span class='font-weight-bold'><i class='fas fa-exclamation-triangle'></i> Something wrong happened, please contact the admin !</span> </div>");
        }

        $data = [
            'title' => 'Booking Payment',
            'app_name' => env('APP_NAME'),
            'pages' => $pages,
            'bookings' => $bookings,
            'vouchers' => $vouchers,
            'hcode' => encrypt($decryptNumberBooking),
            'client_secret' => $response->client_secret,
            'intent_id' => $response->id
        ];

        return view('payment', $data);
    }

    public function booking_process(Request $request)
    {
        try {
            $decryptNumberBooking = decrypt($request->hcode);
        } catch (DecryptException $e) {
            abort(401);
        }

        $bookings = Booking::where('booking_number', (string)$decryptNumberBooking)->first();

        $stripe = new StripeTransaction();
        $response = $stripe->retrive_payment_intent($request->intent_id);

        if ($response->status !== 'succeeded') {
            return redirect()->route("booking_check", ["code" => $decryptNumberBooking])->with("message", "<div class='alert alert-warning' role='alert'><span class='font-weight-bold'><i class='fas fa-exclamation-triangle'></i> Payment has been processed !</span> </div>");
        }

        if (!$bookings) {
            abort(404);
        }

        if ($bookings->payment_status == 'paid' || $bookings->payment_status !== 'waiting') {
            abort(404);
        }

        Booking::where('id', $bookings->id)->update([
            'payment_status' => 'paid',
            'booking_status' => 'active',
            'payment_method' => $response->payment_method,
            'payment_token' => $response->id,
        ]);

        return redirect()->route("booking_check", ["code" => $decryptNumberBooking])->with("message", "<div class='alert alert-success' role='alert'><span class='font-weight-bold'><i class='fas fa-check-circle'></i> Payment Succeeded !</span> </div>");
    }

    public function payment_venmo(Request $request)
    {
        try {
            $decryptNumberBooking = decrypt($request->hcode);
        } catch (DecryptException $e) {
            abort(401);
        }

        $decode = $decryptNumberBooking;

        $bookings = Booking::where('booking_number', $decryptNumberBooking)->update([
            'payment_status' => 'paid',
            'booking_status' => 'active',
            'payment_method' => 'venmo',
        ]);
        $text = urlencode("Please confirm if Venmo payment has been accepted.\nYour Booking number : *$decryptNumberBooking*");

        return redirect()->route("booking_check", ["code" => $decryptNumberBooking])->with("message", "<div class='alert alert-success' role='alert'><span class='font-weight-bold'><i class='fas fa-check-circle'></i> Please confirm if Venmo payment has been accepted ! <br> inform us via <a href='https://wa.me/+12152718381?text=$text' target='_blank'> Whatsapp Message<i class='fab fa-whatsapp'></i></a></span> </div>");
    }

    public function payment_bank(Request $request)
    {
        try {
            $decryptNumberBooking = decrypt($request->hcode);
        } catch (DecryptException $e) {
            abort(401);
        }

        $decode = $decryptNumberBooking;

        $bookings = Booking::where('booking_number', $decryptNumberBooking)->update([
            'payment_status' => 'paid',
            'booking_status' => 'active',
            'payment_method' => 'bank',
        ]);

        $text = urlencode("Please confirm if bank transfer payment has been accepted.\nYour Booking number : *$decryptNumberBooking*");

        return redirect()->route("booking_check", ["code" => $decryptNumberBooking])->with("message", "<div class='alert alert-success' role='alert'><span class='font-weight-bold'><i class='fas fa-check-circle'></i> Please inform if Bank Transfer payment has been made ! <br>inform us via <a href='https://wa.me/+12152718381?text=$text' target='_blank'> Whatsapp Message <i class='fab fa-whatsapp'></i></a></span> </div>");
    }
}
