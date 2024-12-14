<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index()
    {
        $users = User::get();
        $data = [
            'page_title'     => 'User',
            'base_url'       => env('APP_URL'),
            'app_name'       => env('APP_NAME'),
            'app_name_short' => env('APP_NAME_ABBR'),
            'users'         => $users,
        ];
        return view('admin.user.main')->with($data);
    }

    public function store(Request $request)
    {
        $validator  = Validator::make(
            $request->all(),
            [
                'phone' => 'required|unique:users,phone|min:3|max:20',
                'name'  => 'required|min:3|max:100',
                'email' => 'required|email:rfc,dns',
                'password' => 'required|min:4|max:100',
            ]
        );

        if ($validator->fails()) {
            return redirect('/admin/user')
                ->withErrors($validator)
                ->withInput();
        }

        $validated = $validator->validated();

        $banner           = new User();
        $banner->phone    = $request->phone;
        $banner->password = bcrypt($request->password);
        $banner->name     = $request->name;
        $banner->email    = $request->email;
        $banner->save();
        return redirect()->route('admin.user')->with('success', 'Create successfully.');
    }

    public function edit($id)
    {
        $page_title     = "Edit User";
        $base_url       = env('APP_URL');
        $app_name       = env('APP_NAME');
        $app_name_short = env('APP_NAME_ABBR');
        $user           = User::find($id);

        return view('admin.user.edit', compact('user', 'page_title', 'base_url', 'app_name', 'app_name_short'));
    }

    public function update($id, Request $request)
    {
        $request->validate(
            [
                'phone'    => 'required|min:3|max:20|unique:users,phone,' . $id,
                'name'     => 'required|min:3|max:100',
                'email'    => 'required|email:rfc,dns',
            ]
        );

        $input = $request->all();

        User::find($id)->update($input);
        return redirect()->route('admin.user')->with('success', 'Update successfully.');
    }

    public function reset_password($id, Request $request)
    {
        $request->validate(
            [
                'new_password' => 'required|min:4|max:50|confirmed',
            ]
        );
        $exec           = User::find($id);
        $exec->password = bcrypt($request->new_password);
        $exec->save();

        return response()->json([
            'code'    => 200,
            'message' => 'Reset Password successfully.',
        ]);
    }

    public function delete($id)
    {
        $user = User::find($id);
        $user->delete();
        return response()->json([
            'message' => 'Record deleted successfully!'
        ], 200);
    }
}
