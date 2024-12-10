<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    public function index()
    {
        $blogs = Blog::latest()->paginate(10);
        return view('admin.blog.index', compact('blogs'));
    }

    public function create()
    {
        return view('admin.blog.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required',
            'slug' => 'required|string|max:255|unique:blogs',
            'image' => 'nullable|image|max:2048',
        ], [
            'title.required' => 'Tiêu đề không được để trống.',
            'title.string' => 'Tiêu đề phải là một chuỗi.',
            'title.max' => 'Tiêu đề không được vượt quá 255 ký tự.',
            'content.required' => 'Nội dung không được để trống.',
            'slug.required' => 'Slug không được để trống.',
            'slug.string' => 'Slug phải là một chuỗi.',
            'slug.max' => 'Slug không được vượt quá 255 ký tự.',
            'slug.unique' => 'Slug này đã tồn tại.',
            'image.image' => 'Ảnh phải là một tệp hình ảnh hợp lệ.',
            'image.max' => 'Ảnh không được vượt quá 2048 KB.',
        ]);

        if($validator->fails()){
            return response()->json([
               'status' => false,
               'errors' => $validator->errors()
            ]);
        }

        $user = Auth::user();
        $staffId = $user->staff->id;
        $data = $request->all();
        $data['staff_id'] = $staffId;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = $image->getClientOriginalName();
            $newName = time().'.'.$filename;
            $image->move(public_path().'/uploads/blogs/', $newName);
            $data['image'] = $newName;
        }

        Blog::create($data);
        $request->session()->flash('success', 'Bài viết được tạo thành công.');
        return response()->json([
            'status' => true,
            'message' => 'Blogs created successfully.'
        ]);
    }

    public function edit($id)
    {
        $blog = Blog::find($id);
        return view('admin.blog.edit', compact('blog'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required',
            'slug' => 'required|string|max:255|unique:blogs,slug,'.$id,
            'image' => 'nullable|image|max:2048',
        ], [
            'title.required' => 'Tiêu đề không được để trống.',
            'title.string' => 'Tiêu đề phải là một chuỗi.',
            'title.max' => 'Tiêu đề không được vượt quá 255 ký tự.',
            'content.required' => 'Nội dung không được để trống.',
            'slug.required' => 'Slug không được để trống.',
            'slug.string' => 'Slug phải là một chuỗi.',
            'slug.max' => 'Slug không được vượt quá 255 ký tự.',
            'slug.unique' => 'Slug này đã tồn tại.',
            'image.image' => 'Ảnh phải là một tệp hình ảnh hợp lệ.',
            'image.max' => 'Ảnh không được vượt quá 2048 KB.',
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }

        $user = Auth::user();
        $staffId = $user->staff->id;
        $data = $request->all();
        $data['staff_id'] = $staffId;
        $blog = Blog::find($id);

        if ($request->hasFile('image')) {
            if ($blog->image) {
                $path = public_path('/uploads/blogs/' . $blog->image);
                // delete image in temp folder
                if (File::exists($path)) {
                    File::delete($path);
                }
            }
            $image = $request->file('image');
            $filename = $image->getClientOriginalName();
            $newName = time().'.'.$filename;
            $image->move(public_path().'/uploads/blogs/', $newName);
            $data['image'] = $newName;
        }

        $blog->update($data);

        $request->session()->flash('success', 'Bài viết được sửa thành công.');
        return response()->json([
            'status' => true,
            'message' => 'Blogs updated successfully.'
        ]);
    }

    public function destroy($id)
    {
        $blog = Blog::find($id);
        if ($blog->image) {
            $path = public_path('/uploads/blogs/' . $blog->image);
            // delete image in temp folder
            if (File::exists($path)) {
                File::delete($path);
            }
        }
        $blog->delete();
        session()->flash('success', 'Bài viết đã được xóa');
        return response()->json([
            'status' => true,
            'message' => 'Blogs deleted successfully.'
        ]);
    }
}
