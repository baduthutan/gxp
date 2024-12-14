<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\API\BaseController;
use Hash;

class ProfileController extends BaseController
{
    public function index()
    {
        return $this->sendResponse(Auth::user(), "success");
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'  => 'required|min:3|max:100',
            'email' => 'nullable|email:rfc,dns',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
        ]);

        if ($validator->fails()) {
            $errors        = $validator->errors();
            $error_message = "";
            foreach ($validator->failed() as $key => $val) {
                if ($errors->first($key)) {
                    $error_message = $errors->first($key);
                }
            }
            return $this->sendError($error_message, null);
        }

        $photo = $request->file('photo');

        if ($photo) {
            $fileName = Auth::user()->id . '.' . $photo->getClientOriginalExtension();
            $filePath = $photo->storeAs('images/users', $fileName, 'public');
            $filePath = 'storage/' . $filePath;
        }

        $user = Auth::user();
        $user->update([
            'name'  => $request->name,
            'email' => $request->email,
            'photo' => $filePath ?? $user->photo,
        ]);

        $data = [
            'id'    => $user->id,
            'name'  => $user->name,
            'email' => $user->email,
            'photo' => env('APP_URL') . 'storage/' . $user->photo,
        ];

        return $this->sendResponse($data, "success");
    }

    public function update_password(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'old_password'     => 'required|min:8|max:50',
            'new_password'     => 'required|min:8|max:50',
            'confirm_password' => 'required|min:8|max:50|same:new_password',
        ]);

        if ($validator->fails()) {
            $errors        = $validator->errors();
            $error_message = "";
            foreach ($validator->failed() as $key => $val) {
                if ($errors->first($key)) {
                    $error_message = $errors->first($key);
                }
            }
            return $this->sendError($error_message, null);
        }

        $user = Auth::user();

        if (!Hash::check($request->old_password, $user->password)) {
            return $this->sendError("Current password does not match", null);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return $this->sendResponse(null, "Update password success");
    }
}
