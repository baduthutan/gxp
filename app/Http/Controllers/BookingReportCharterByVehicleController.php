<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\BookingReportByVehicleRequest;
use App\Models\Booking;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class BookingReportCharterByVehicleController extends Controller
{
    public function index(BookingReportByVehicleRequest $request)
    {
        $vehicle = Vehicle::get();

        $data = [
            'page_title' => 'BOOKING REPORT CHARTER FILTERED BY VEHICLE',
            'base_url' => env('APP_URL'),
            'app_name' => env('APP_NAME'),
            'app_name_short' => env('APP_NAME_ABBR'),
            'vehicle' => $vehicle,
            'bookings' => $this->_getBookings($request),
        ];

        return view('admin.bookingreportcharterbyvehicle.index', $data);
    }

    private function _getBookings($request)
    {
        $query = Booking::selectRaw("*");
        if (count($request->all()) >= 1) {
            $query = Booking::where("schedule_type", 'charter')
                ->where('payment_status', 'paid')
                ->where('booking_status', 'active')
                ->when($request, function ($q, $request) {
                    if ($request->vehicle_number) {
                        $q->where("vehicle_number", $request->vehicle_number);
                    }

                    if ($request->datetime_departure) {
                        $q->whereRaw("date_format(datetime_departure, '%Y-%m-%d') = '{$request->datetime_departure}'");
                    }
                })
                ->withCount('booking_customers')
                ->orderBy('created_at', 'desc')
                ->get();

        } else {
            $query->where('id', 0)->get();
        }


        return $query;
    }
}
