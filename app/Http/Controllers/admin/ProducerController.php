<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Producer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProducerController extends Controller
{
    public function index(Request $request) {
        $producers = Producer::latest();
        if(filled($request->get('searchText'))) {
            $searchText = $request->get('searchText');
            $producers = Producer::where('name', 'like', '%'.$searchText.'%');
        }
        $producers = $producers->paginate(10);
        return view('admin.producer.index', compact('producers'));
    }

    public function create() {
        return view('admin.producer.create');
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|unique:producers',
            'phone' => 'required|unique:producers',
            'address' => 'required',
        ], [
            'name.required' => 'Tên là bắt buộc.',
            'email.required' => 'Email là bắt buộc.',
            'email.unique' => 'Email đã tồn tại.',
            'phone.required' => 'Số điện thoại là bắt buộc.',
            'phone.unique' => 'Số điện thoại đã tồn tại.',
            'address.required' => 'Địa chỉ là bắt buộc.',
        ]);

        if($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
        Producer::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'phone' => $request->get('phone'),
            'address' => $request->get('address'),
        ]);

        $request->session()->flash('success', 'Thêm nhà cung cấp thành công.');
        return response()->json([
            'status' => true,
            'message' => 'Producer created successfully.'
        ]);
    }

    public function edit($id) {
        $producer = Producer::find($id);
        return view('admin.producer.edit', compact('producer'));
    }

    public function update(Request $request, $id) {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|unique:producers,email,'.$id,
            'phone' => 'required|unique:producers,phone,'.$id,
            'address' => 'required',
        ], [
            'name.required' => 'Tên là bắt buộc.',
            'email.required' => 'Email là bắt buộc.',
            'email.unique' => 'Email đã tồn tại.',
            'phone.required' => 'Số điện thoại là bắt buộc.',
            'phone.unique' => 'Số điện thoại đã tồn tại.',
            'address.required' => 'Địa chỉ là bắt buộc.',
        ]);

        if($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }

        Producer::find($id)->update([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'phone' => $request->get('phone'),
            'address' => $request->get('address'),
        ]);
        $request->session()->flash('success', 'Nhà cung cấp đã được cập nhật.');
        return response()->json([
            'status' => true,
            'message' => 'Update successfully.'
        ]);
    }

    public function destroy(Request $request, $id) {
        $producer = Producer::find($id);
        if(is_null($producer)) {
            $request->session()->flash('error', 'Không tìm thấy nhà cung cấp.');
            return response()->json([
                'status' => false,
                'message' => 'Producer not found.'
            ]);
        }
        $producer->delete();
        return response()->json([
            'status' => true,
            'message' => 'Delete successfully.'
        ]);
    }
}
