<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\ShuttleScheduleResource;
use App\Models\MasterArea;
use App\Services\ScheduleQueryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BookingFilterController extends BaseController
{
    public function get_arrival_filter(Request $request)
    {

        if($request->booking_type !== 'shuttle'){

            $data = MasterArea::when($request->area_type, function ($q, $area_type) use ($request) {
                $area_type = $request->area_type == 'airport' ? 'city' : 'airport';
                $q->where('area_type', $area_type);
            })->with(['master_sub_area' => function ($q) use ($request) {
                $q->where('is_active', '1')->whereIn('is_charter',[1,2]);
            }])->where('is_active', '1')->whereIn('is_charter',[1,2])->get();

        }else{

            $data = MasterArea::when($request->area_type, function ($q, $area_type) use ($request) {
                $area_type = $request->area_type == 'airport' ? 'city' : 'airport';
                $q->where('area_type', $area_type);
            })->with(['master_sub_area' => function ($q) {
                $q->where('is_active', '1')->whereIn('is_charter',[0,2]);
            }])->where('is_active', '1')->whereIn('is_charter',[0,2])->get();

        }

        $arr = [];

        foreach ($data as $idx => $item) {
            $arr[$idx]['text'] = $item->name;
            foreach ($item->master_sub_area as $subItem) {
                $arr[$idx]['children'][] = [
                    'id' => $subItem->id,
                    'name' => $subItem->name,
                    'master_area_id' => $subItem->master_area_id,
                    'area_type' => $item->area_type,
                ];
            }
        }

        return $this->sendResponse($arr, 'Data founded');
    }

    public function get_departure_charter_filter(Request $request)
    {
//        dd($request->booking_type);
        if ($request->booking_type == 'charter')
        {
            $data = MasterArea::query()
                ->with(['master_sub_area' => function ($q) {
                    $q->where('is_active', '1')->whereIn('is_charter',[1,2]);
                }])->where('is_active', '1')->whereIn('is_charter',[1,2])->get();
        }else{
            $data = MasterArea::query()
                ->with(['master_sub_area' => function ($q) {
                    $q->where('is_active', '1')->whereIn('is_charter',[0,2]);
                }])->where('is_active', '1')->whereIn('is_charter',[0,2])->get();
        }

        $arr = [];

        foreach ($data as $idx => $item) {
            $arr[$idx]['text'] = $item->name;
            foreach ($item->master_sub_area as $subItem) {
                $arr[$idx]['children'][] = [
                    'id' => $subItem->id,
                    'name' => $subItem->name,
                    'master_area_id' => $subItem->master_area_id,
                    'area_type' => $item->area_type,
                ];
            }
        }

        return $this->sendResponse($arr, 'Data founded');
    }


    public function get_booking_schedule(Request $request){
        $validator = Validator::make(
            $request->all(),
            [
                'booking_type' => 'required|in:shuttle,charter',
                'from_master_sub_area_id' => 'required',
                'to_master_sub_area_id' => 'required',
                'date_departure' => 'required|date|after_or_equal:today|date_format:Y-m-d',
                'qty_adult' => 'required|numeric',
                'qty_baby' => 'required|numeric'
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

        $schedule = ScheduleQueryService::generate_data($request, false);

        if ($schedule->isEmpty()) {
            return $this->sendError(null,'Schedule not found',200);
        }

        return $this->sendResponse(ShuttleScheduleResource::collection($schedule),'Schedule founded', 200);
    }
}
