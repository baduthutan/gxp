<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MasterSubArea;
use App\Models\MasterSpecialArea;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class MasterSpecialAreaController extends Controller
{
    public function index()
    {
        $master_special_areas = MasterSpecialArea::with('master_sub_area')->get();
        $master_sub_areas     = MasterSubArea::select([
            'master_sub_areas.id',
            'master_sub_areas.name',
            'master_areas.area_type',
            'master_areas.name as main_area',
        ])
            ->leftJoin('master_areas', 'master_areas.id', '=', 'master_sub_areas.master_area_id')
            ->where('master_sub_areas.is_active', '=', 1)
            ->get();

        $data = [
            'page_title'           => 'Special request drop off',
            'base_url'             => env('APP_URL'),
            'app_name'             => env('APP_NAME'),
            'app_name_short'       => env('APP_NAME_ABBR'),
            'master_special_areas' => $master_special_areas,
            'master_sub_areas'     => $master_sub_areas,
        ];
        return view('admin.master_special_area.main')->with($data);
    }

    public function store(Request $request)
    {
        $validator  = Validator::make(
            $request->all(),
            [
                'master_sub_area_id' => 'required|exists:master_sub_areas,id',
                'regional_name'      => 'required|min:3|max:100',
                'first_person_price' => 'required|numeric|between:0.01,9999.99',
                'extra_person_price' => 'required|numeric|between:0.01,9999.99',
                'is_active'          => 'required|in:1,0',
                'notes'              => 'nullable',
            ]
        );

        if ($validator->fails()) {
            return redirect('/admin/master_special_area')
                ->withErrors($validator)
                ->withInput();
        }

        $validated = $validator->validated();

        $exec                     = new MasterSpecialArea();
        $exec->master_sub_area_id = $request->master_sub_area_id;
        $exec->regional_name      = $request->regional_name;
        $exec->first_person_price = (float) $request->first_person_price;
        $exec->extra_person_price = (float) $request->extra_person_price;
        $exec->is_active          = (bool) $request->is_active;
        $exec->notes              = $request->notes;
        $exec->save();

        return redirect()->route('admin.master_special_area')->with('success', 'Create successfully.');
    }

    public function edit($id)
    {
        $page_title          = "Edit special request drop off";
        $base_url            = env('APP_URL');
        $app_name            = env('APP_NAME');
        $app_name_short      = env('APP_NAME_ABBR');
        $master_special_area = MasterSpecialArea::find($id);
        $master_sub_areas    = MasterSubArea::select([
            'master_sub_areas.id',
            'master_sub_areas.name',
            'master_areas.area_type',
            'master_areas.name as main_area',
        ])
            ->leftJoin('master_areas', 'master_areas.id', '=', 'master_sub_areas.master_area_id')
            ->where('master_sub_areas.is_active', '=', 1)
            ->get();

        return view('admin.master_special_area.edit', compact('master_special_area', 'master_sub_areas', 'page_title', 'base_url', 'app_name', 'app_name_short'));
    }

    public function update($id, Request $request)
    {
        $request->validate(
            [
                'master_sub_area_id' => 'required|exists:master_sub_areas,id',
                'regional_name'      => 'required|min:3|max:100',
                'first_person_price' => 'required|numeric|between:0.01,9999.99',
                'extra_person_price' => 'required|numeric|between:0.01,9999.99',
                'is_active'          => 'required|in:1,0',
                'notes'              => 'nullable',
            ]
        );

        $input = $request->all();

        MasterSpecialArea::find($id)->update($input);
        return redirect()->route('admin.master_special_area')->with('success', 'Update successfully.');
    }

    public function delete($id)
    {
        MasterSpecialArea::find($id)->delete();
        return response()->json([
            'message' => 'Record deleted successfully!'
        ], 200);
    }
}
