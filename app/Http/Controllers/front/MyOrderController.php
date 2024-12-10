<?php

namespace App\Http\Controllers\front;

use App\Http\Controllers\Controller;
use App\Models\District;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Province;
use App\Models\Ward;
use Auth;
use Illuminate\Http\Request;

class MyOrderController extends Controller
{
    public function MyOrder(Request $request)
    {
        // get user order
        $uid = Auth::user()->id;
        $orders = Order::where('user_id', $uid)->latest()->paginate(5);

        return view('front.pages.order.my-orders', ['orders' => $orders]);
    }

    public function MyOrderDetails($id)
    {
        // get order details
        $order = Order::find($id);
        // get order items
        $order_items = OrderItem::where('order_id', $id)->get();
        // get Customer address
        // get district province ward
        $province = Province::find($order->province_id);
        $district = District::find($order->district_id);
        $ward = Ward::find($order->ward_id);
        return view('front.pages.order.order-details', compact('order', 'order_items', 'province', 'district', 'ward'));
    }

}