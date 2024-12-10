<?php

namespace App\Http\Controllers\front;

use App\Http\Controllers\Controller;
use App\Models\DiscountCoupon;
use App\Models\Order;
use App\Models\ShippingCharge;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class FrontDiscountController extends Controller
{
    public function applyDiscount(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'discount_code' => 'nullable',
            'province_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'code_message' => '',
                'errors' => $validator->errors()
            ]);
        }
        // $grand total
        $uId = Auth::id();
        $total = app(CartController::class)->total($uId); // get total
        // get shipping code from province id
        $provinceId = $request->input('province_id');
        $shipping = ShippingCharge::where('province_id', $provinceId)->first();
        $grandTotal = $total + $shipping->shipping_cost;
        $discountValueShow = 0;

        // get discount coupon
        $code = $request->discount_code;

        if($code != '') {
            $discount = DiscountCoupon::where('code', $code)->first();
            if (!$discount) {
                return response()->json([
                    'status' => false,
                    'code_message' => 'Mã giảm giá không hợp lệ.',
                    'grandTotal' => $grandTotal,
                    'shippingCost' => $shipping->shipping_cost,
                    'discountValueShow' => $discountValueShow,
                ]);
            }

            // check if coupon start date is valid or not
            $now = Carbon::now();
            $startDate = Carbon::createFromFormat('Y-m-d H:i:s', $discount->starts_at);
            $endDate = Carbon::createFromFormat('Y-m-d H:i:s', $discount->expires_at);

            if ($now->lt($startDate)) {
                return response()->json([
                    'status' => false,
                    'code_message' => 'Mã giảm giá chưa bắt đầu.',
                    'grandTotal' => $grandTotal,
                    'shippingCost' => $shipping->shipping_cost,
                    'discountValueShow' => $discountValueShow,
                ]);
            }

            if ($now->gt($endDate)) {
                return response()->json([
                    'status' => false,
                    'code_message' => 'Mã giảm giá đã hết hạn.',
                    'grandTotal' => $grandTotal,
                    'shippingCost' => $shipping->shipping_cost,
                    'discountValueShow' => $discountValueShow,
                ]);
            }


            if ($total < $discount->min_discount_value) {
                return response()->json([
                    'status' => false,
                    'code_message' => 'Giá tối thiểu để áp dụng là: ' . number_format($discount->min_discount_value, 0, ',', '.'),
                    'grandTotal' => $grandTotal,
                    'shippingCost' => $shipping->shipping_cost,
                    'discountValueShow' => $discountValueShow,
                ]);
            }

            // get order
            $couponUsed = Order::where('coupon_code', $code)->count();
            if ($discount->max_uses <= $couponUsed) {
                return response()->json([
                    'status' => false,
                    'code_message' => 'Mã giảm giá đã hết hạn.',
                    'grandTotal' => $grandTotal,
                    'shippingCost' => $shipping->shipping_cost,
                    'discountValueShow' => $discountValueShow,
                ]);
            }

            // get order
            $couponUsedUser = Order::where(['user_id' => $uId, 'coupon_code' => $code])->count();
            if ($discount->max_uses_user <= $couponUsedUser) {
                return response()->json([
                    'status' => false,
                    'code_message' => 'Bạn đã sử dụng mã giảm giá này rồi.',
                    'grandTotal' => $grandTotal,
                    'shippingCost' => $shipping->shipping_cost,
                    'discountValueShow' => $discountValueShow,
                ]);
            }


            if ($discount->type == 'percent') {
                $discountValueShow = $grandTotal * ($discount->discount_value / 100);
                $grandTotal -= $discountValueShow;
            } else {
                $discountValueShow = $discount->discount_value;
                $grandTotal -= $discountValueShow;
            }


            return response()->json([
                'status' => true,
                'code_message' => 'Discount code is correct',
                'grandTotal' => $grandTotal,
                'shippingCost' => $shipping->shipping_cost,
                'discountValueShow' => $discountValueShow,
            ]);
        } else {
            return response()->json([
                'status' => true,
                'code_message' => '',
                'grandTotal' => $grandTotal,
                'shippingCost' => $shipping->shipping_cost,
                'discountValueShow' => $discountValueShow,
            ]);
        }
    }
}
