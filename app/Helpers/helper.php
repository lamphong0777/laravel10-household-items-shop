<?php

use App\Mail\OrderEmail;
use App\Models\Category;
use App\Models\Order;
use App\Models\ProductImage;
use Illuminate\Support\Facades\Mail;

function getCategories()
{
    return Category::orderBy('name', 'asc')
        ->with('sub_category')
        ->orderBy('id', 'desc')
        ->where('status', '=', 1)
        ->get();
}

function getAllProductImage($id)
{
    $images = ProductImage::where('product_id', $id)->get();
    return $images;
}

function getOneProductImage($id)
{
    return ProductImage::where('product_id', $id)->first();
}

function orderEmail($orderId, $userType = "customer")
{
    $order = Order::where('id', $orderId)
        ->with('items')
        ->with('province')
        ->with('district')
        ->with('ward')
        ->first();

    if ($userType == "customer") {
        $email = $order->email;
        $mailData = [
            'subject' => 'Cảm ơn bạn đã đặt hàng',
            'order' => $order,
        ];
    } else {
        $email = env('ADMIN_EMAIL');
        $mailData = [
            'subject' => 'Bạn có đơn hàng mới',
            'order' => $order,
        ];
    }



    Mail::to($email)->send(new OrderEmail($mailData));
    // dd($order);
}

?>