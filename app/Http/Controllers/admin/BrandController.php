<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BrandController extends Controller
{
    private $brand;

    public function __construct(Brand $brand)
    {
        $this->brand = $brand;
    }

    public function index(Request $request)
    {
        $brands = $this->brand->latest();
        if (!empty($request->searchText)) {
            $brands = $brands->where('name', 'like', '%' . $request->searchText . '%');
        }
        $brands = $brands->paginate(10);
        return view('admin.brand.index', compact('brands'));
    }

    public function create()
    {
        return view('admin.brand.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:brands|max:255',
            'slug' => 'required|unique:brands|max:255',
            'status' => 'required',
        ]);
        if ($validator->passes()) {
            $this->brand->create([
                'name' => $request->get('name'),
                'slug' => $request->get('slug'),
                'status' => $request->get('status'),
            ]);

            $request->session()->flash('success', 'Đã thêm thương hiệu thành công.');

            return response()->json([
                'status' => true,
                'message' => 'Brand Created Successfully.'
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
        $brand = $this->brand->find($id);
        return view('admin.brand.edit', compact('brand'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'slug' => 'required|max:255|unique:brands,slug,'.$id,
            'status' => 'required',
        ]);
        if ($validator->passes()) {
            $this->brand->find($id)->update($request->all());

            $request->session()->flash('success', 'Đã cập nhật thương hiệu thành công.');
            return response()->json([
                'status' => true,
                'message' => 'Brand Updated Successfully.'
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
        $brand = $this->brand->find($id);
        if(empty($brand)){
           return response()->json([
               'status' => false,
               'errors' => 'Brand Does Not Exist'
           ]);
        }
        $brand->delete();

        $request->session()->flash('success', 'Đã xóa thương hiệu.');

        return response()->json([
            'status' => true,
            'message' => 'Brand Deleted Successfully.'
        ]);
    }
}