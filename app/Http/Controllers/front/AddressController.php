<?php

namespace App\Http\Controllers\front;

use App\Http\Controllers\Controller;
use App\Models\District;
use App\Models\ShippingCharge;
use App\Models\Ward;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    public function getDistrict(Request $request): \Illuminate\Http\JsonResponse
    {
        if (!empty($request->province_id)) {
            $districts = District::where('province_id', $request->province_id)->orderBy('name', 'asc')->get();
            return response()->json([
                'status' => true,
                'districts' => $districts
            ]);
        } else {
            return response()->json([
                'status' => false,
                'districts' => []
            ]);
        }
    }

    public function getWard(Request $request): \Illuminate\Http\JsonResponse
    {
        if (!empty($request->district_id)) {
            $wards = Ward::where('district_id', $request->district_id)->orderBy('name', 'asc')->get();
            return response()->json([
                'status' => true,
                'wards' => $wards
            ]);
        } else {
            return response()->json([
                'status' => false,
                'wards' => []
            ]);
        }
    }

    public function getShippingCost(Request $request): \Illuminate\Http\JsonResponse
    {
        $uid = Auth::id(); // get user id
        // input province id output shipping cost
        // if session has coupon code -> out $grand_total - $discount_value
        if (!empty($request->province_id)) {
            $shipping_cost = ShippingCharge::where('province_id', $request->province_id)->first();
            $total = app(CartController::class)->total($uid); // get cart total
            $grand_total = $total + $shipping_cost->shipping_cost;

            return response()->json([
                'status' => true,
                'shipping_cost' => $shipping_cost->shipping_cost,
                'grand_total' => $grand_total
            ]);
        }
        $total = app(CartController::class)->total($uid);
        return response()->json([
            'status' => false,
            'shipping_cost' => [],
            'grand_total' => $total
        ]);
    }
}
