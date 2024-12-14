<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Agent;
use Illuminate\Support\Facades\Validator;

class VoucherController extends Controller
{
    public function index()
    {
        $vouchers = Voucher::get();
        // $agents   = Agent::get();

        $data = [
            'page_title'     => 'Voucher',
            'base_url'       => env('APP_URL'),
            'app_name'       => env('APP_NAME'),
            'app_name_short' => env('APP_NAME_ABBR'),
            'vouchers'       => $vouchers,
            // 'agents'         => $agents,
        ];
        return view('admin.voucher.main')->with($data);
    }

    public function store(Request $request)
    {
        $validator  = Validator::make(
            $request->all(),
            [
                // 'agent_id'       => 'required|exists:agents,id',
                'name'           => 'required|min:3|max:100',
                'code'           => 'required|min:3|max:100',
                'date_start'     => 'required|date',
                'date_expired'   => 'required|date|after_or_equal:date_start',
                'discount_type'  => 'required|in:percentage,value',
                'discount_value' => 'required',
                'is_active'      => 'required|in:1,0',
            ]
        );

        if ($validator->fails()) {
            return redirect('/admin/voucher')
                ->withErrors($validator)
                ->withInput();
        }

        $validated = $validator->validated();

        $exec                 = new Voucher();
        // $exec->agent_id       = $request->agent_id;
        $exec->name           = $request->name;
        $exec->code           = $request->code;
        $exec->date_start     = $request->date_start;
        $exec->date_expired   = $request->date_expired;
        $exec->discount_type  = $request->discount_type;
        $exec->discount_value = $request->discount_value;
        $exec->is_active      = $request->is_active;
        $exec->save();
        return redirect()->route('admin.voucher')->with('success', 'Create successfully.');
    }

    public function edit($id)
    {
        $page_title     = "Edit Voucher";
        $base_url       = env('APP_URL');
        $app_name       = env('APP_NAME');
        $app_name_short = env('APP_NAME_ABBR');
        $voucher        = Voucher::find($id);
        // $agents         = Agent::get();

        return view('admin.voucher.edit', compact('voucher', 'page_title', 'base_url', 'app_name', 'app_name_short'));
    }

    public function update($id, Request $request)
    {
        $request->validate(
            [
                // 'agent_id'       => 'required|exists:agents,id',
                'name'           => 'required|min:3|max:100',
                'code'           => 'required|min:3|max:100',
                'date_start'     => 'required|date',
                'date_expired'   => 'required|date|after_or_equal:date_start',
                'discount_type'  => 'required|in:percentage,value',
                'discount_value' => 'required',
                'is_active'      => 'required|in:1,0',
            ]
        );

        $input = $request->all();

        Voucher::find($id)->update($input);
        return redirect()->route('admin.voucher')->with('success', 'Update successfully.');
    }

    public function delete($id)
    {
        Voucher::find($id)->delete();
        return response()->json([
            'message' => 'Record deleted successfully!'
        ], 200);
    }
}
