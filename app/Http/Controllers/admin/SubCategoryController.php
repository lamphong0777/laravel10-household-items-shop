<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SubCategoryController extends Controller
{
    public function index(Request $request)
    {

        $subCategories = SubCategory::latest();
        if (!empty($request->searchText)) {
            $subCategories = $subCategories->where('name', 'like', '%' . $request->searchText . '%');
        }
        $subCategories = $subCategories->paginate(10);
        return view('admin.sub_category.index', compact('subCategories'));
    }

    public function create()
    {
        $categories = Category::orderBy('name', 'asc')->get();
        return view('admin.sub_category.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:sub_categories|max:255',
            'category_id' => 'required',
            'status' => 'required',
            'slug' => 'required|unique:sub_categories|max:255',
        ]);
        if ($validator->passes()) {
            $subCategory = new SubCategory();
            $subCategory->create([
                'name' => $request->get('name'),
                'category_id' => $request->get('category_id'),
                'status' => $request->get('status'),
                'slug' => $request->get('slug'),
            ]);

            $request->session()->flash('success', 'Đã thêm danh mục con thành công.');

            return response()->json([
                'status' => true,
                'message' => 'SubCategory added successfully.'
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
        $subCategory = SubCategory::find($id);
        $categories = Category::orderBy('name', 'asc')->get();
        return view('admin.sub_category.edit', compact('subCategory', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'category_id' => 'required',
            'status' => 'required',
            'slug' => 'required|max:255|unique:sub_categories,slug,'.$id,
        ]);
        if ($validator->passes()) {
            SubCategory::find($id)->update([
                'name' => $request->get('name'),
                'category_id' => $request->get('category_id'),
                'status' => $request->get('status'),
                'slug' => $request->get('slug'),
            ]);

            $request->session()->flash('success', 'Đã cập nhật danh mục con thành công.');
            return response()->json([
                'status' => true,
                'message' => 'SubCategory was updated successfully.'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function destroy($id, Request $request)
    {
        $subCategory = SubCategory::find($id);
        if (empty($subCategory)) {
            return response()->json([
                'status' => false,
                'notFound' => true,
                'message' => 'SubCategory not found.'
            ]);
        }
        $subCategory->delete();

        $request->session()->flash('success', 'Đã xóa thành công.');

        return response()->json([
            'status' => true,
            'message' => 'SubCategory deleted successfully.'
        ]);
    }
}
