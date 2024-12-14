<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    public function index()
    {
        $admins = Admin::get();
        $data = [
            'page_title'     => 'Admin',
            'base_url'       => env('APP_URL'),
            'app_name'       => env('APP_NAME'),
            'app_name_short' => env('APP_NAME_ABBR'),
            'admins'         => $admins,
        ];
        return view('admin.admin.main')->with($data);
    }

    public function store(Request $request)
    {
        $validator  = Validator::make(
            $request->all(),
            [
                'photo'    => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
                'username' => 'required|unique:admins,username|min:3|max:20',
                'password' => 'required|min:4|max:100',
                'name'     => 'required|min:3|max:100',
                'role'     => 'required',
            ]
        );

        if ($validator->fails()) {
            return redirect('/admin/admin')
                ->withErrors($validator)
                ->withInput();
        }

        $validated = $validator->validated();

        $photo = $request->file('photo');

        $filePath = 'img/admin_pp/default.jpg';
        if ($photo) {
            $fileName = time() . '.' . $request->photo->extension();
            $request->photo->move(public_path('img/admin_pp/'), $fileName);
            $filePath = 'img/admin_pp/' . $fileName;
        }

        $banner           = new Admin();
        $banner->photo  = $filePath;
        $banner->username = $request->username;
        $banner->password = bcrypt($request->password);
        $banner->name     = $request->name;
        $banner->role     = $request->role;
        $banner->save();
        return redirect()->route('admin.admin')->with('success', 'Create successfully.');
    }

    public function edit($id)
    {
        $page_title     = "Edit Admin";
        $base_url       = env('APP_URL');
        $app_name       = env('APP_NAME');
        $app_name_short = env('APP_NAME_ABBR');
        $admin          = Admin::find($id);

        return view('admin.admin.edit', compact('admin', 'page_title', 'base_url', 'app_name', 'app_name_short'));
    }

    public function update($id, Request $request)
    {
        $request->validate(
            [
                'photo'    => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
                'username' => 'required|unique:admins,username|min:3|max:20',
                'name'     => 'required|min:3|max:100',
                'role'     => 'required',
            ]
        );

        $input = $request->all();

        if ($request->file('photo')) {
            unlink($input['photo']);
            $fileName = time() . '.' . $request->photo->extension();
            $request->photo->move(public_path('img/slider/'), $fileName);
            $input['photo'] = 'img/admin/' . $fileName;
        }

        Admin::find($id)->update($input);
        return redirect()->route('admin.admin')->with('success', 'Update successfully.');
    }

    public function reset_password($id, Request $request)
    {
        $request->validate(
            [
                'new_password' => 'required|min:4|max:50|confirmed',
            ]
        );
        $exec           = Admin::find($id);
        $exec->password = bcrypt($request->new_password);
        $exec->save();

        return response()->json([
            'code'    => 200,
            'message' => 'Reset Password successfully.',
        ]);
    }

    public function delete($id)
    {
        $admin = Admin::find($id);
        if ($admin->photo != 'img/admin_pp/default.jpg') {
            unlink($admin->photo);
        }
        $admin->delete();
        return response()->json([
            'message' => 'Record deleted successfully!'
        ], 200);
    }
}
