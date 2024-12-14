<?php

namespace App\Services;

use App\Models\Charter;
use App\Models\MasterSubArea;
use App\Models\ScheduleShuttle;
use Carbon\Carbon;
use DB;

class ScheduleQueryService
{
    public static function generate_data($request, $paginate = true)
    {
        $from_sub_area = MasterSubArea::where('id', $request->from_master_sub_area_id)->with('master_area')->first();
        $to_sub_area = MasterSubArea::where('id', $request->to_master_sub_area_id)->with('master_area')->first();
        $query = self::generate_query($request);

        if ($from_sub_area->master_area->area_type == "airport") {
            $query->where([
                'from_master_area_id' => $from_sub_area->master_area_id,
                'to_master_area_id' => $to_sub_area ? $to_sub_area->master_area_id : 0,
                'to_master_sub_area_id' => $request->to_master_sub_area_id,
            ]);
        } else {
            $query->where([
                'from_master_area_id' => $from_sub_area->master_area_id,
                'from_master_sub_area_id' => $request->from_master_sub_area_id,
                'to_master_area_id' => $to_sub_area ? $to_sub_area->master_area_id : 0,
            ]);
        }

        $check_diff = Carbon::now()->startOfDay()->diffInDays($request->date_departure);

        if ($check_diff < 2) {
            if ($request->booking_type == 'shuttle') {
                $query->whereRaw("time_departure = null");
            } else {
                $query->whereRaw("id is null");
            }
        } elseif ($request->date_departure == Carbon::now()->format('Y-m-d') && $request->booking_type == 'shuttle') {
            $now = Carbon::now()->format('H:i:s');
            $query->whereRaw("time_departure >= '{$now}'");
        }

        if ($paginate) {
            $data = $query->paginate(7)->withQueryString();
        } else {
            $data = $query->get();
        }

        if ($request->booking_type !== 'charter') {
            $data->map(function ($item) use ($request) {
                $item->is_available = true;

                $total_passanger = $request->passanger_adult + $request->passanger_baby;
                $total_booked = $item->seat_booked;

                if (($total_passanger  + $total_booked ) > $item->total_seat){
                    $item->is_available = false;
                }

                return $item;
            });
        } else {
            $data->map(function ($item) use ($request) {
                $item->is_available = false;
                if (empty($item->bookings_count)) {
                    $item->is_available = true;
                }
                return $item;
            });
        }
        return $data;
    }

    private static function generate_query($request)
    {
        $query = ScheduleShuttle::selectRaw("*,
                ifnull(
                (select (sum(bookings.qty_adult) + sum(bookings.qty_baby)) from bookings where
                DATE(datetime_departure) = '{$request->date_departure}' and
                schedule_shuttles.vehicle_number = vehicle_number and area_type='{$request->area_type}' and
                schedule_shuttles.trip_number = bookings.trip_number and schedule_type = 'shuttle' ),
                0) as seat_booked
            ")
            ->with([
                'from_master_area',
                'from_master_sub_area',
                'to_master_area',
                'to_master_sub_area',
            ])
            ->where('is_active', 1)->orderBy('time_departure', 'asc');

        if ($request->booking_type == 'charter') {
            $query = Charter::with([
                'from_master_area',
                'from_master_sub_area',
                'to_master_area',
                'to_master_sub_area',
            ])->withCount(['bookings' => function ($q) use ($request) {
//                    dd(Carbon::parse($request->date_departure)->startOfDay()->format
//                    ('Y-m-d H:i:s'));
                $date_departure = Carbon::parse($request->date_departure)->startOfDay()
                    ->format('Y-m-d');
                return $q->whereRaw("date_format(datetime_departure, '%Y-%m-%d') = '$date_departure' and (payment_status = 'paid' OR payment_status = 'waiting')");
            }]);

        }
        return $query;
    }
}
