<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\API\BaseController;
use Illuminate\Validation\ValidationException;

class AuthController extends BaseController
{

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required|min:3|max:100',
            'phone'    => 'required|min:8|max:20|unique:users',
            'password' => 'required|min:8|max:50',
            'email'    => 'required|email:rfc,dns',
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

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $token =  $user->createToken($user->phone)->plainTextToken;

        $result = [
            'name'  => $user->name,
            'phone' => $user->phone,
            'photo' => $user->photo,
            'email' => $user->email,
            'token' => $token,
        ];

        return $this->sendResponse($result, 'User register successfully.');
    }

    public function login(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'phone'    => 'required|min:8|max:20|exists:users,phone',
                'password' => 'required|min:8|max:50',
            ],
            [
                'exists' => ':attribute not found'
            ]
        );

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

        $user = User::where('phone', $request->phone)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return $this->sendError('Unauthorized', null);
        }

        $token = $user->createToken($user->phone)->plainTextToken;

        $result = [
            'id'    => $user->id,
            'name'  => $user->name,
            'phone' => $user->phone,
            'photo' => $user->photo,
            'email' => $user->email,
            'token' => $token,
        ];

        return $this->sendResponse($result, 'success');
    }

    public function logout(Request $request)
    {
        if (!$request->user()->currentAccessToken()->delete()) {
            return $this->sendError('Unauthorized', null);
        }
        return $this->sendResponse(null, 'logout success');
    }
}
