<?php

namespace App\Http\Controllers\API;

use App\Models\Page;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\API\BaseController;

class PagesController extends BaseController
{
    public function index(Request $request)
    {
        $result = [];

        if ($request->id) {
            $arr_page = Page::where('id', $request->id)->get();
        } else {
            $arr_page = Page::get();
        }

        if ($arr_page->count() == 0) {
            return $this->sendError('Data empty', $result);
        }

        $data = [];
        foreach ($arr_page as $key) {
            $nested['id']           = $key->id;
            $nested['slug']         = $key->slug;
            $nested['page_title']   = $key->page_title;
            $nested['page_content'] = $key->page_content;
            array_push($data, $nested);
        }

        return $this->sendResponse($data, 'success');
    }
}
