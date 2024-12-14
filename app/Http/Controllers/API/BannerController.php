<?php

namespace App\Http\Controllers\API;

use App\Models\Banner;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\API\BaseController;

class BannerController extends BaseController
{
    public function index()
    {
        $result = [];

        $arr_banner = Banner::where('is_active', true)->get();

        if ($arr_banner->count() == 0) {
            return $this->sendError('Data empty', $result);
        }

        $data = [];
        foreach ($arr_banner as $key) {
            $nested['id']      = $key->id;
            $nested['picture'] = env('APP_URL') . $key->picture;
            $nested['url']     = $key->url;

            array_push($data, $nested);
        }

        return $this->sendResponse($data, 'success');
    }

    public function show($id)
    {
        $result = [];

        $arr_banner = Banner::where('is_active', true)->where('id', $id)->get();

        if ($arr_banner->count() == 0) {
            return $this->sendError('Data empty', $result);
        }

        $data = [];
        foreach ($arr_banner as $key) {
            $nested['id']        = $key->id;
            $nested['picture']   = env('APP_URL') . $key->picture;
            $nested['url']       = $key->url;
            $nested['is_active'] = $key->is_active;

            array_push($data, $nested);
        }

        return $this->sendResponse($data, 'success');
    }
}
