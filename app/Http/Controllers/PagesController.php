<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class PagesController extends Controller
{
    public function index()
    {
        $pages = Page::orderBy('type', 'desc')->orderBy('orderNumber', 'asc')->get();

        $data = [
            'page_title'     => 'Pages',
            'base_url'       => env('APP_URL'),
            'app_name'       => env('APP_NAME'),
            'app_name_short' => env('APP_NAME_ABBR'),
            'pages'          => $pages,
        ];
        return view('admin.pages.main')->with($data);
    }

    public function add()
    {
        $data = [
            'page_title'     => 'Add New Pages',
            'base_url'       => env('APP_URL'),
            'app_name'       => env('APP_NAME'),
            'app_name_short' => env('APP_NAME_ABBR'),
            'dropdown' => Page::where('is_dropdown', '1')->get()
        ];
        return view('admin.pages.add')->with($data);
    }

    public function store(Request $request)
    {
        $rules = [
            'slug'         => 'required|min:3|max:100|unique:pages,slug',
            'page_title'   => 'required|min:3|max:100',
            'page_content' => 'nullable',
            'parent_page_id' => 'nullable|numeric',
            'url' => 'nullable',
            'is_dropdown' => 'required|in:1,0',
            'type' => 'required|in:header,footer',
            'orderNumber' => 'nullable|numeric',
        ];

        if ($request->is_dropdown == '1'){
            unset($rules['slug']);
        }
        $validator  = Validator::make(
            $request->all(),
            $rules,
        );

        if ($validator->fails()) {
            return redirect('/admin/pages/add')
                ->withErrors($validator)
                ->withInput();
        }


        $exec = new Page();
        $exec->slug = $request->is_dropdown !== '1' ? Str::slug($request->slug, '-') : "";
        $exec->page_title = $request->page_title;
        $exec->page_content = $request->page_content;
        $exec->parent_page_id = $request->is_dropdown !== '1' ? $request->parent_page_id : null;
        $exec->url = $request->url;
        $exec->is_dropdown = $request->is_dropdown;
        $exec->type = $request->type;
        $exec->orderNumber = $request->orderNumber;
        $exec->save();
        return redirect()->route('admin.pages')->with('success', 'Create successfully.');
    }

    public function edit($id)
    {
        $pages = Page::find($id);
//        dd($pages);
        $data = [
            'page_title'     => 'Edit New Pages',
            'base_url'       => env('APP_URL'),
            'app_name'       => env('APP_NAME'),
            'app_name_short' => env('APP_NAME_ABBR'),
            'pages'          => $pages,
            'dropdown' => Page::where('is_dropdown', '1')->where('id', '!=', $pages->id)->get()
        ];
        return view('admin.pages.edit')->with($data);
    }

    public function update($id, Request $request)
    {
        $rules =  [
            'slug'         => 'required|min:3|max:100|unique:pages,slug,' . $id,
            'page_title'   => 'required|min:3|max:100',
            'page_content' => 'nullable',
            'parent_page_id' => 'nullable|numeric',
            'url' => 'nullable',
            'is_dropdown' => 'required|in:1,0',
            'type' => 'required|in:header,footer',
            'orderNumber' => 'nullable|numeric',
        ];

        if ($request->is_dropdown == '1'){
            unset($rules['slug']);
        }

        $request->validate(
           $rules
        );

        $input = $request->all();
        $input['slug'] = $request->is_dropdown !== '1' ? Str::slug($request->slug, '-') : "";
        $input['parent_page_id'] = $request->is_dropdown !== '1' ? $request->parent_page_id : null;

        Page::find($id)->update($input);
        return redirect()->route('admin.pages')->with('success', 'Update successfully.');
    }

    public function delete($id)
    {
        Page::find($id)->delete();
        return response()->json([
            'message' => 'Record deleted successfully!'
        ], 200);
    }
}
