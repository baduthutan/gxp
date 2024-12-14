<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Eta;
use App\Http\Controllers\Controller;

class EtaController extends Controller {
    public function index()
    {
        $etas = Eta::get();

        $data = [
            'page_title' => 'DST',
            'base_url'       => env('APP_URL'),
            'app_name'       => env('APP_NAME'),
            'app_name_short' => env('APP_NAME_ABBR'),
            'dst' => $etas
        ];

        return view('admin.dst.main', $data);
    }

    public function edit($id)
    {
        $eta = Eta::find($id);
        $data = [
            'page_title' => 'Edit DST',
            'base_url'       => env('APP_URL'),
            'app_name'       => env('APP_NAME'),
            'app_name_short' => env('APP_NAME_ABBR'),
            'dst' => $eta
        ];

        return view('admin.dst.edit', $data);
    }

    public function update($id, Request $request)
    {
        $this->validate($request, [
            'dst_start' => 'required|time',
            'dst_end' => 'required|time',
        ]);

        $eta = Eta::find($id);
        $eta->trip_1 = $request->trip_1;
        $eta->trip_2 = $request->trip_2;
        $eta->save();

        return redirect()->back()->with('success', 'Update successfully.');
    }  
}