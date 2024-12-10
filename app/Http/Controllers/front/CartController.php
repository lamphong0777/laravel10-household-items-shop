<?php

namespace App\Http\Controllers\front;

use App\Http\Controllers\Controller;
use App\Models\CustomerAddress;
use App\Models\District;
use App\Models\Product;
use App\Models\Cart;
use App\Models\Province;
use App\Models\ShippingCharge;
use App\Models\Ward;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller {

    public function index( Request $request ): \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        if ( Auth::check() ) {
            $cartContent = Cart::where( 'user_id', Auth::id() )->get();
        } else {
            $cartContent = Cart::where( 'id', 0 )->get();
        }

        $total = $this->total( Auth::id() );
        //        dd( Cart::total() );
        return view( 'front.pages.cart.cart', compact( 'cartContent', 'total' ) );
    }

    public function addToCart( Request $request ): \Illuminate\Http\JsonResponse {
        if ( Auth::check() ) {
            $product = Product::with( 'product_images' )->find( $request->id );
            if ( empty( $product ) ) {
                return response()->json( [
                    'status' => false,
                    'message' => 'Product not found',
                    'notFound' => true
                ] );
            }
            $productExist = Cart::where( 'user_id', Auth::id() )->where( 'product_id', $product->id )->get();
            if ( $productExist->count() > 0 ) {
                $current_qty = $productExist->first()->qty;
                if ( $product->qty > $current_qty + $request->qty ) {
                    // update cart qty
                    Cart::find( $productExist->first()->id )->update( [
                        'qty' => $current_qty + $request->qty
                    ] );
                    $status = true;
                    $message = 'Product update qty in cart';
                } else {
                    $status = false;
                    $message = 'Số lượng vượt quá giới hạn';
                }
            } else {
                // add new product to cart
                // check if request qty > product qty
                if ( $request->qty > $product->qty ) {
                    $status = false;
                    $message = 'Số lượng vượt quá giới hạn';
                } else {
                    $cart = new Cart();
                    $cart->id = time() . Auth::id() . $product->id;
                    $cart->user_id = Auth::id();
                    $cart->product_id = $product->id;
                    $cart->title = $product->title;
                    $cart->qty = $request->qty;
                    $cart->price = $product->price;
                    $cart->image = ( !empty( $product->product_images ) ) ? $product->product_images->first()->image : '';
                    $cart->save();
                    //            Cart::add( $product->id, $product->title, $request->qty, $product->price, [ 'productImage' => ( !empty( $product->product_images ) ) ? $product->product_images->first() : '' ] );
                    $status = true;
                    $message = 'Product added in cart';
                }
            }
        } else {
            $status = false;
            $message = 'Bạn cần đăng nhập';
        }

        return response()->json( [
            'status' => $status,
            'message' => $message
        ] );
    }

    public function updateCart( Request $request ): \Illuminate\Http\JsonResponse {
        $cart = Cart::find( $request->rowId );
        // if qty in product > qty cart
        if ( !empty( $cart ) ) {
            $product = Product::find( $cart->product_id );
            if ( $request->qty < $product->qty ) {
                $cart->update( [
                    'qty' => $request->qty,
                ] );
                return response()->json( [
                    'status' => true,
                    'message' => 'Update cart successfully!'
                ] );
            } else {
                return response()->json( [
                    'status' => false,
                    'message' => 'Update cart failed!'
                ] );
            }
        } else {
            return response()->json( [
                'status' => false,
                'message' => 'Cart not found!'
            ] );
        }
    }

    public function removeFromCart( $rowId ) {
        Cart::find( $rowId )->delete();

        $cartContent = Cart::where( 'user_id', Auth::id() )->get();
        $total = $this->total( Auth::id() );
        return view( 'front.pages.cart.cart', compact( 'cartContent', 'total' ) );
    }

    public function cartCount(): \Illuminate\Http\JsonResponse {
        $cart = Cart::where( 'user_id', Auth::id() )->get();
        return response()->json( [
            'status' => true,
            'cartCount' => count( $cart ),
        ] );
    }

    public function destroyCart( $user_id ): void {
        $cart = Cart::where( 'user_id', $user_id )->delete();
    }

    public function checkout( Request $request ) {
        // if cart is empty, redirect to cart page
        if ( !Cart::count() ) {
            return redirect()->route( 'shopping.cart' );
        }
        //        if user is not logged in then redirect to register-login page
        if ( !Auth::check() ) {
            if ( !session()->has( 'url.intended' ) ) {
                session( [ 'url.intended' => url()->current() ] );
            }
        }

        session()->forget( 'url.intended' );

        // get cart content
        $cartContent = Cart::where( 'user_id', Auth::id() )->get();
        $total = $this->total( Auth::id() );
        // get cart total
        $provinces = Province::all();
        $customerAddress = CustomerAddress::where( 'user_id', Auth::id() )->first();
        // get Customer address

        //shipping
        $shipping_cost = 0;
        $grand_total = $total + $shipping_cost;
        // grand total

        if ( !empty( $customerAddress ) ) {
            $districts = District::where( 'province_id', $customerAddress->province_id )->get();
            $wards = Ward::where( 'district_id', $customerAddress->district_id )->get();
            // get shipping code
            $shipping_charge = ShippingCharge::where( 'province_id', $customerAddress->province_id )->first();
            $shipping_cost = $shipping_charge->shipping_cost;
            // handle grand total
            $grand_total = $total + $shipping_cost;
            return view( 'front.pages.order.checkout', compact( 'cartContent', 'total', 'provinces', 'customerAddress', 'districts', 'wards', 'shipping_cost', 'grand_total' ) );
        }
        return view( 'front.pages.order.checkout', compact( 'cartContent', 'total', 'provinces', 'customerAddress', 'shipping_cost', 'grand_total' ) );
    }

    public function total( $user_id ): float|int {
        $cart = Cart::where( 'user_id', $user_id )->get();
        $total = 0;
        foreach ( $cart as $item ) {
            $total += $item->qty * $item->price;
        }
        return $total;
    }
}
