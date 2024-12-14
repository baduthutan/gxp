<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class AdminLoginController extends Controller
{
    public function index()
    {
        $data = [
            'page_title' => 'Admin Login',
            'base_url'   => env('APP_URL'),
        ];
        return view('admin.login.main', $data);
    }

    public function check(Request $request)
    {
        $request->validate(
            [
                'username' => 'required|min:3|max:20|exists:admins,username',
                'password' => 'required|min:4|max:50',
            ]
        );

        if (Auth::guard('admin')->attempt(['username' => $request->input('username'), 'password' => $request->input('password')])) {
            return redirect()->route('admin.dashboard')->with('success', 'Login Success');
        }

        return back()->with('error', 'Invalid Username or Password');
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        return redirect('/admin/login');
    }
}
