<?php

namespace App\Http\Controllers\Admin;

use App\Models\Banner;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BannerController extends Controller
{
    public function index()
    {
        $banners = Banner::get();
        $data = [
            'page_title'     => 'Banner',
            'base_url'       => env('APP_URL'),
            'app_name'       => env('APP_NAME'),
            'app_name_short' => env('APP_NAME_ABBR'),
            'banners'        => $banners,
        ];
        return view('admin.banner.main')->with($data);
    }

    public function store(Request $request)
    {
        $validator  = Validator::make(
            $request->all(),
            [
                'picture'   => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
                'url'       => 'required',
                'is_active' => 'required',
            ]
        );

        if ($validator->fails()) {
            return redirect('/admin/banner')
                ->withErrors($validator)
                ->withInput();
        }

        $validated = $validator->validated();

        $picture = $request->file('picture');

        if ($picture) {
            $fileName = time() . '.' . $request->picture->extension();
            $request->picture->move(public_path('img/slider/'), $fileName);
            $filePath = 'img/slider/' . $fileName;
        }

        $banner            = new Banner();
        $banner->picture   = $filePath;
        $banner->url       = $request->url;
        $banner->is_active = $request->is_active;
        $banner->save();
        return redirect()->route('admin.banner')->with('success', 'Create successfully.');
    }

    public function edit($id)
    {
        $page_title     = "Edit Banner";
        $base_url       = env('APP_URL');
        $app_name       = env('APP_NAME');
        $app_name_short = env('APP_NAME_ABBR');
        $banner         = Banner::find($id);

        return view('admin.banner.edit', compact('banner', 'page_title', 'base_url', 'app_name', 'app_name_short'));
    }

    public function update($id, Request $request)
    {
        $request->validate(
            [
                'picture'   => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
                'url'       => 'required',
                'is_active' => 'required',
            ]
        );

        $input = $request->all();

        if ($request->file('picture')) {
            $fileName = time() . '.' . $request->picture->extension();
            $request->picture->move(public_path('img/slider/'), $fileName);
            $input['picture'] = 'img/slider/' . $fileName;
        }

        Banner::find($id)->update($input);
        return redirect()->route('admin.banner')->with('success', 'Update successfully.');
    }

    public function delete($id)
    {
        $banner = Banner::find($id);
        unlink($banner->picture);
        $banner->delete();
        return response()->json([
            'message' => 'Record deleted successfully!'
        ], 200);
    }
}
