<?php

namespace App\Http\Controllers;

use App\Exports\BookingShuttleByAreaReport;
use App\Http\Controllers\Controller;
use App\Http\Requests\BookingReportByAreaRequest;
use App\Models\Booking;
use App\Models\MasterArea;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class BookingReportByAreaController extends Controller
{
    public function index(BookingReportByAreaRequest $request)
    {
        $master_area = MasterArea::query()
            ->with(['master_sub_area' => function ($q) {
                $q->where('is_active', '1')->whereIn('is_charter', [0, 2]);
            }])->where('is_active', '1')->whereIn('is_charter', [0, 2])->get();


        $data = [
            'page_title' => 'BOOKING REPORT SHUTTLE FILTERED BY AREA',
            'base_url' => env('APP_URL'),
            'app_name' => env('APP_NAME'),
            'app_name_short' => env('APP_NAME_ABBR'),
            'master_area' => $master_area,
            'sub_area' => [],
            'bookings' => $this->_getBookings($request),
            'from_master_sub_area_id' => 0,
            'to_master_sub_area_id' => 0,
        ];

        if ($request->from_master_sub_area_id) {
            $exp_ = explode('|', $request->from_master_sub_area_id);
            $from_master_sub_area_id = $exp_[1];
            $data['from_master_sub_area_id'] = $exp_[1];
        }

        if ($request->to_master_sub_area_id) {
            $exp_ = explode('|', $request->to_master_sub_area_id);
            $area_type = $exp_[2];
            $data['to_master_sub_area_id'] = $exp_[1];
            $data['sub_area'] = MasterArea::query()
                ->with(['master_sub_area' => function ($q) {
                    $q->where('is_active', '1')->whereIn('is_charter', [0, 2]);
                }])->where('is_active', '1')->whereIn('is_charter', [0, 2])->where('area_type', $area_type)->get();
        }

        return view('admin.bookingreportbyarea.index', $data);
    }

    private function _getBookings($request)
    {
        $query = Booking::selectRaw("*");
        if (count($request->all()) >= 1) {
            $query = Booking::where("schedule_type", 'shuttle')
                ->where('payment_status', 'paid')
                ->where('booking_status', 'active')
                ->when($request, function ($q, $request) {
                    // if ($request->from_master_sub_area_id) {
                    //     $exp_ = explode('|', $request->from_master_sub_area_id);
                    //     $from_master_area_id = $exp_[0];
                    //     $from_master_sub_area_id = $exp_[1];
                    //     $q->where('from_master_area_id', $from_master_area_id);
                    //     $q->where('from_master_sub_area_id', $from_master_sub_area_id);
                    // }

                    // if ($request->to_master_sub_area_id) {
                    //     $exp_ = explode('|', $request->to_master_sub_area_id);
                    //     $to_master_area_id = $exp_[0];
                    //     $to_master_sub_area_id = $exp_[1];
                    //     $q->where('to_master_area_id', $to_master_area_id);
                    //     $q->where('to_master_sub_area_id', $to_master_sub_area_id);
                    // }

                    if ($request->area_type) {
                        $q->where('area_type', $request->area_type);
                    }

                    if ($request->datetime_departure) {
                        $q->whereRaw("date_format(datetime_departure, '%Y-%m-%d') = '{$request->datetime_departure}'");
                    }

                    if ($request->trip_number) {
                        $q->where("trip_number", $request->trip_number);
                    }
                })
                ->orderBy('created_at', 'desc');
                if (!$request->trip_number) {
//                    $query->groupBy('trip_number');
                    $query->orderBy('trip_number','asc');
                }

                $query = $query->get();
//                ->get();
        } else {
            $query->where('id', 0)->get();
        }


        return $query;
    }

    public function export_xlx(BookingReportByAreaRequest $request)
    {
        $now = Carbon::now()->format('d_m_Y_H_i_s');
        $title = "Report_booking_shuttle_by_area{$now}.xlsx";

        return Excel::download(new BookingShuttleByAreaReport($request), $title);
    }
}
