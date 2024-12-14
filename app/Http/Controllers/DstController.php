<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dst;
use App\Http\Controllers\Controller;

class DstController extends Controller {
    public function index()
    {
        $dsts = Dst::get();

        $data = [
            'page_title' => 'DST',
            'base_url'       => env('APP_URL'),
            'app_name'       => env('APP_NAME'),
            'app_name_short' => env('APP_NAME_ABBR'),
            'dsts' => $dsts
        ];

        return view('admin.dst.main', $data);
    }

    public function edit($id)
    {
        $dst = Dst::find($id);
        $data = [
            'page_title' => 'Edit DST',
            'base_url'       => env('APP_URL'),
            'app_name'       => env('APP_NAME'),
            'app_name_short' => env('APP_NAME_ABBR'),
            'dst' => $dst
        ];

        return view('admin.dst.edit', $data);
    }

    // public function update( Request $request)
    // {
    //     dd($request->method(), $request->all());

    //     $input = $request->all();

    //     Dst::first()->update($input);
    //     return redirect()->back()->with('success', 'Update successfully.');
    // }

    public function update($id, Request $request)
    {
        $this->validate($request, [
            'dst_start' => 'required|date',
            'dst_end' => 'required|date',
            'morning_schedule_time' => 'required|integer',
            'afternoon_schedule_time' => 'required|integer',
        ]);

        $dst = Dst::find($id);
        $dst->dst_start = \Carbon\Carbon::createFromFormat('Y-m-d\TH:i', $request->dst_start)->format('Y-m-d H:i:s');
        $dst->dst_end = \Carbon\Carbon::createFromFormat('Y-m-d\TH:i', $request->dst_end)->format('Y-m-d H:i:s');
        $dst->morning_schedule_time = $request->morning_schedule_time;
        $dst->afternoon_schedule_time = $request->afternoon_schedule_time;
        $dst->save();

        return redirect()->back()->with('success', 'Update successfully.');
    }  
}