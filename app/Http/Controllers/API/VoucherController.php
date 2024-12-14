<?php

namespace App\Http\Controllers\API;

use App\Models\Voucher;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\API\BaseController;

class VoucherController extends BaseController
{
    public function index(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'voucher_code' => 'required|exists:vouchers,code',
                // 'password'     => 'required',
            ],
            [
                'exists' => ':attribute not found',
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

        $vouchers = Voucher::where('code', $request->voucher_code)
//            ->where('date_expired','>', Carbon::now()->format('Y-m-d'))
            ->whereRaw('CURDATE() between date_start and date_expired')
            ->first();

        if (!$vouchers) {
            return $this->sendError("voucher code not founds or expired", null);
        }

        // $password = $vouchers->agent->password;
        // if ($request->password != $password) {
        //     return $this->sendError("Password agent wrong", null);
        // }

        return $this->sendResponse($vouchers, 'success');
    }
}
