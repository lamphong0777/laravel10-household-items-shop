<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\DiscountCoupon;
use Illuminate\Http\Request;

class DiscountController extends Controller
{
    public function index(Request $request)
    {
        $discounts = DiscountCoupon::paginate(10);
        return view('admin.discount.index', compact('discounts'));
    }

    public function create()
    {
        return view('admin.discount.create');
    }

    public function store(Request $request)
    {
        // make a request
        $request->validate([
            'code' => 'required|string|unique:discount_coupons,code',
            'discount_value' => 'required',
            'name' => 'nullable|min:3|max:50',
            'type' => 'required|in:percent,fixed',
            'starts_at' => 'required|after:today',
            'expires_at' => 'required|after:starts_at',
            'min_discount_value' => 'nullable|min:0',
        ], [
            'code.required' => 'Mã giảm giá không được để trống.',
            'code.string' => 'Mã giảm giá phải là chuỗi ký tự.',
            'code.unique' => 'Mã giảm giá đã tồn tại.',
            'discount_value.required' => 'Giá trị giảm giá không được để trống.',
            'name.min' => 'Tên mã giảm giá phải có ít nhất 3 ký tự.',
            'name.max' => 'Tên mã giảm giá không được dài hơn 50 ký tự.',
            'type.required' => 'Loại giảm giá không được để trống.',
            'type.in' => 'Loại giảm giá không hợp lệ. Chỉ chấp nhận "percent" hoặc "fixed".',
            'starts_at.required' => 'Ngày bắt đầu không được để trống.',
            'starts_at.after' => 'Ngày bắt đầu phải là ngày trong tương lai.',
            'expires_at.required' => 'Ngày kết thúc không được để trống.',
            'expires_at.after' => 'Ngày kết thúc phải sau ngày bắt đầu.',
            'min_discount_value.min' => 'Giá trị giảm giá tối thiểu phải lớn hơn hoặc bằng 0.',
        ]);

        // Save discount coupon to database
        DiscountCoupon::create($request->all());

        $request->session()->flash('success', 'Discount coupon created successfully');
        return redirect(route('admin.discount.index'));
    }

    public function edit($id)
    {
        $discount = DiscountCoupon::find($id);
        return view('admin.discount.edit', compact('discount'));
    }

    public function update(Request $request, $id)
    {
        $discount = DiscountCoupon::find($id);

        $request->validate([
            'code' => 'required|string|unique:discount_coupons,code,' . $id,
            'discount_value' => 'required',
            'name' => 'nullable|min:3|max:50',
            'type' => 'required|in:percent,fixed',
            'starts_at' => 'required|after_or_equal:' . $discount->starts_at,
            'expires_at' => 'required|after:starts_at',
            'min_discount_value' => 'nullable|min:0',
        ], [
            'code.required' => 'Mã giảm giá không được để trống.',
            'code.string' => 'Mã giảm giá phải là chuỗi ký tự.',
            'code.unique' => 'Mã giảm giá đã tồn tại.',
            'discount_value.required' => 'Giá trị giảm giá không được để trống.',
            'name.min' => 'Tên mã giảm giá phải có ít nhất 3 ký tự.',
            'name.max' => 'Tên mã giảm giá không được dài hơn 50 ký tự.',
            'type.required' => 'Loại giảm giá không được để trống.',
            'type.in' => 'Loại giảm giá không hợp lệ. Chỉ chấp nhận "percent" hoặc "fixed".',
            'starts_at.required' => 'Ngày bắt đầu không được để trống.',
            'starts_at.after' => 'Ngày bắt đầu phải là ngày trong tương lai.',
            'expires_at.required' => 'Ngày kết thúc không được để trống.',
            'expires_at.after' => 'Ngày kết thúc phải sau ngày bắt đầu.',
            'min_discount_value.min' => 'Giá trị giảm giá tối thiểu phải lớn hơn hoặc bằng 0.',
        ]);

        $updateSuccess = DiscountCoupon::find($id)->update($request->all());
        if ($updateSuccess) {
            $request->session()->flash('success', 'Cập nhật mã giảm giá thành công.');
            return redirect(route('admin.discount.index'));
        }
        $request->session()->flash('error', 'Cập nhật mã giảm giá thất bại.');
        return redirect(route('admin.discount.index'));
    }

    public function destroy($id)
    {
        $discount = DiscountCoupon::find($id);
        if (empty($discount)) {
            return response()->json([
                'status' => false,
                'message' => 'Discount not found'
            ]);
        } else {
            $discount->delete();
            session()->flash('success', 'Đã xóa mã giảm giá.');
            return response()->json([
                'status' => true,
                'message' => 'Discount deleted successfully'
            ]);
        }
    }
}
