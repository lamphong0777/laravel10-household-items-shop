<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\StaticPage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StaticPageController extends Controller
{
    public function index(Request $request)
    {
        $pages = StaticPage::paginate(10);
        return view('admin.page.index', compact('pages'));
    }

    public function create()
    {
        return view('admin.page.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required|unique:static_pages,slug',
            'content' => 'required',
        ]);

        if ($validator->passes()) {
            // save static page information
            StaticPage::create([
                'name' => $request->input('name'),
                'slug' => $request->input('slug'),
                'content' => $request->input('content'),
            ]);
            $request->session()->flash('success', 'Static page created successfully');
            return response()->json([
                'status' => true,
                'message'=>'Static page created successfully'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function edit($id)
    {
        $page = StaticPage::find($id);
        return view('admin.page.edit', compact('page'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required|unique:static_pages,slug,'.$id,
            'content' => 'required',
        ]);

        if ($validator->passes()) {
            // save static page information
            StaticPage::find($id)->update([
                'name' => $request->input('name'),
                'slug' => $request->input('slug'),
                'content' => $request->input('content'),
            ]);
            $request->session()->flash('success', 'Static page updated successfully');
            return response()->json([
                'status' => true,
                'message'=>'Static page updated successfully'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function destroy($id)
    {
        $page = StaticPage::find($id);
        if($page) {
            $page->delete();
            return response()->json([
                'status' => true,
                'message'=>'Static page deleted successfully'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message'=>'Static page not found'
            ]);
        }
    }
}
