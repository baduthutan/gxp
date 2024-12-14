<?php

namespace App\Http\Controllers;

use App\Models\MasterArea;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class MasterAreaController extends Controller
{
    public function index(Request $request)
    {
        $master_areas = MasterArea::when($request->is_charter, function($q, $is_charter) {
            return $q->where('is_charter', $is_charter);
        })->get();

        $data = [
            'page_title'     => 'Main Area',
            'base_url'       => env('APP_URL'),
            'app_name'       => env('APP_NAME'),
            'app_name_short' => env('APP_NAME_ABBR'),
            'master_areas'   => $master_areas,
        ];
        return view('admin.master_area.main')->with($data);
    }

    public function store(Request $request)
    {
        $validator  = Validator::make(
            $request->all(),
            [
                'name'      => 'required|min:3|max:100',
                'area_type' => 'required|in:airport,city',
                'is_active' => 'required|in:1,0',
                'is_charter' => 'required|in:1,0,2',
            ]
        );

        if ($validator->fails()) {
            return redirect('/admin/master_area')
                ->withErrors($validator)
                ->withInput();
        }

        $validated = $validator->validated();

        $exec            = new MasterArea();
        $exec->name      = $request->name;
        $exec->area_type = $request->area_type;
        $exec->is_active = $request->is_active;
        $exec->is_charter = $request->is_charter;
        $exec->save();
        return redirect()->route('admin.master_area')->with('success', 'Create successfully.');
    }

    public function edit($id)
    {
        $page_title     = "Edit Main Area";
        $base_url       = env('APP_URL');
        $app_name       = env('APP_NAME');
        $app_name_short = env('APP_NAME_ABBR');
        $master_area    = MasterArea::find($id);

        return view('admin.master_area.edit', compact('master_area', 'page_title', 'base_url', 'app_name', 'app_name_short'));
    }

    public function update($id, Request $request)
    {
        $request->validate(
            [
                'name'      => 'required|min:3|max:100',
                'area_type' => 'required|in:airport,city',
                'is_active' => 'required|in:1,0',
                'is_charter' => 'required|in:1,0,2',
            ]
        );

        $input = $request->all();

        MasterArea::find($id)->update($input);
        return redirect()->route('admin.master_area')->with('success', 'Update successfully.');
    }

    public function delete($id)
    {
        MasterArea::find($id)->delete();
        return response()->json([
            'message' => 'Record deleted successfully!'
        ], 200);
    }
}
