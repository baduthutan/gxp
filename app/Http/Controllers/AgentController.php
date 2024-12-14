<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AgentController extends Controller
{
    public function index()
    {
        $agents   = Agent::get();

        $data = [
            'page_title'     => 'Agent',
            'base_url'       => env('APP_URL'),
            'app_name'       => env('APP_NAME'),
            'app_name_short' => env('APP_NAME_ABBR'),
            // 'agents'         => $agents,
        ];
        return view('admin.agent.main')->with($data);
    }

    public function store(Request $request)
    {
        $validator  = Validator::make(
            $request->all(),
            [
                'name'     => 'required|min:3|max:100',
                'password' => 'required|min:3|max:100',
            ]
        );

        if ($validator->fails()) {
            return redirect('/admin/agent')
                ->withErrors($validator)
                ->withInput();
        }

        $validated = $validator->validated();

        $exec           = new Agent();
        $exec->name     = $request->name;
        $exec->password = $request->password;
        $exec->save();
        return redirect()->route('admin.agent')->with('success', 'Create successfully.');
    }

    public function edit($id)
    {
        $page_title     = "Edit Agent";
        $base_url       = env('APP_URL');
        $app_name       = env('APP_NAME');
        $app_name_short = env('APP_NAME_ABBR');
        $agent          = Agent::find($id);

        return view('admin.agent.edit', compact('agent', 'page_title', 'base_url', 'app_name', 'app_name_short'));
    }

    public function update($id, Request $request)
    {
        $request->validate(
            [
                'name'     => 'required|min:3|max:100',
                'password' => 'required|min:3|max:100',
            ]
        );

        $input = $request->all();

        Agent::find($id)->update($input);
        return redirect()->route('admin.agent')->with('success', 'Update successfully.');
    }

    public function delete($id)
    {
        Agent::find($id)->delete();
        Voucher::where('agent_id', $id)->delete();
        return response()->json([
            'message' => 'Record deleted successfully!'
        ], 200);
    }
}
