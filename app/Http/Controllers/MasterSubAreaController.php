<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MasterSubArea;
use App\Http\Controllers\Controller;
use App\Models\MasterArea;
use Illuminate\Support\Facades\Validator;

class MasterSubAreaController extends Controller
{
    public function index(Request $request)
    {
        $master_sub_areas = MasterSubArea::when($request->is_charter, function($q, $is_charter){
            return $q->where('is_charter', $is_charter);
        })->with('master_area')
            ->get();
        $master_areas     = MasterArea::when($request->is_charter, function($q, $is_charter){
            return $q->where('is_charter', $is_charter);
        })->get();

        $data = [
            'page_title'       => 'Sub Area',
            'base_url'         => env('APP_URL'),
            'app_name'         => env('APP_NAME'),
            'app_name_short'   => env('APP_NAME_ABBR'),
            'master_sub_areas' => $master_sub_areas,
            'master_areas'     => $master_areas,
        ];
        return view('admin.master_sub_area.main')->with($data);
    }

    public function store(Request $request)
    {
        $validator  = Validator::make(
            $request->all(),
            [
                'master_area_id' => 'required|exists:master_areas,id',
                'name'           => 'required|min:3|max:100',
                'is_active'      => 'required|in:1,0',
                'is_charter'      => 'required|in:1,0,2',
            ]
        );

        if ($validator->fails()) {
            return redirect('/admin/master_sub_area')
                ->withErrors($validator)
                ->withInput();
        }

        $validated = $validator->validated();

        $exec                 = new MasterSubArea();
        $exec->master_area_id = $request->master_area_id;
        $exec->name           = $request->name;
        $exec->is_active      = $request->is_active;
        $exec->is_charter      = $request->is_charter;
        $exec->save();
        return redirect()->route('admin.master_sub_area')->with('success', 'Create successfully.');
    }

    public function edit($id)
    {
        $page_title      = "Edit Sub Area";
        $base_url        = env('APP_URL');
        $app_name        = env('APP_NAME');
        $app_name_short  = env('APP_NAME_ABBR');
        $master_sub_area = MasterSubArea::find($id);
        $master_areas    = MasterArea::get();

        return view('admin.master_sub_area.edit', compact('master_sub_area', 'master_areas', 'page_title', 'base_url', 'app_name', 'app_name_short'));
    }

    public function update($id, Request $request)
    {
        $request->validate(
            [
                'master_area_id' => 'required|exists:master_areas,id',
                'name'           => 'required|min:3|max:100',
                'is_active'      => 'required|in:1,0',
                'is_charter'      => 'required|in:1,0,2',
            ]
        );

        $input = $request->all();

        MasterSubArea::find($id)->update($input);
        return redirect()->route('admin.master_sub_area')->with('success', 'Update successfully.');
    }

    public function delete($id)
    {
        MasterSubArea::find($id)->delete();
        return response()->json([
            'message' => 'Record deleted successfully!'
        ], 200);
    }
}
