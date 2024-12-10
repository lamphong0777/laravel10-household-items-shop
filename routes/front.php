<?php

//front controller
use App\Http\Controllers\front\AddressController;
use App\Http\Controllers\front\CheckoutController;
use App\Http\Controllers\front\FrontDiscountController;
use App\Http\Controllers\front\HomePageController;
use App\Http\Controllers\front\AuthController;
use App\Http\Controllers\front\CartController;
use App\Http\Controllers\front\MyOrderController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// front route
Route::group(['prefix' => '/'], function () {
    Route::get('/', [HomePageController::class, 'index'])->name('home');
    // products
    Route::get('/shop/{categorySlug?}/{subcategorySlug?}', [HomePageController::class, 'shop'])->name('shop.shop-now');
    // product details
    Route::get('/product-details/{slug}', [HomePageController::class, 'product'])->name('product.product-details');
    // Cart page
    Route::get('/shopping-cart', [CartController::class, 'index'])->name('shopping.cart');
    // Add item to cart
    Route::post('/shopping-cart/add', [CartController::class, 'addToCart'])->name('shopping.cart.add');
    // Update cart
    Route::post('/shopping-cart/update', [CartController::class, 'updateCart'])->name('shopping.cart.update');
    // Remove from cart
    Route::get('/shopping-cart/remove/{rowId}', [CartController::class, 'removeFromCart'])->name('shopping.cart.remove');
    // Register
    Route::post('/register', [AuthController::class, 'register'])->name('user.register');
    // Update wish list
    Route::post('/update-wish-list', [AuthController::class, 'updateProductWishlist'])->name('user.wishlist-update');
    // static page
    Route::get('page/{slug}', [HomePageController::class, 'showStaticPage'])->name('shop.static-page');
    // Contact send
    Route::post('send-contact', [HomePageController::class, 'sendContact'])->name('shop.send-contact');
    // Product rating
    Route::post('/rating/{id}', [HomePageController::class, 'ProductRating'])->name('user.rating');
    // Reset password
    Route::get('/forgot-password', [AuthController::class, 'forgotPassword'])->name('user.forgot-password');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('user.reset-password');
    Route::get('/reset-password/{token}', [AuthController::class, 'resetPasswordToken'])->name('user.email-reset-password');
    Route::post('/update-password/{token}', [AuthController::class, 'updatePasswordToken'])->name('user.update-password-token');
    // Blog details
    Route::get('/blog/{slug}', [HomePageController::class, 'blogDetails'])->name('blog.details');

    Route::group(['prefix' => 'account'], function () {
        // if user logged in
        Route::group(['middleware' => 'auth'], function () {
            Route::get('/logout', [AuthController::class, 'logout'])->name('user.logout');
            // Profile page
            Route::get('/profile/{id}', [AuthController::class, 'profile'])->name('user.profile');
            // My order and order details
            Route::get('/my-order', [MyOrderController::class, 'MyOrder'])->name('user.my-order');
            Route::get('/order-details/{id}', [MyOrderController::class, 'MyOrderDetails'])->name('user.order-details');
            // Cancel order
            Route::post('cancel-order/{id}', [CheckoutController::class, 'cancelOrder'])->name('user.cancel-order');
            // Check out page
            Route::get('/checkout', [CartController::class, 'checkout'])->name('shop.checkout');
            // Get cart count
            Route::get('/cart-count', [CartController::class, 'cartCount'])->name('shop.cart.count');
            // get districts
            Route::get('/district', [AddressController::class, 'getDistrict'])->name('shop.district');
            // get wards
            Route::get('/ward', [AddressController::class, 'getWard'])->name('shop.ward');
            // get shipping cost
            Route::get('/shipping', [AddressController::class, 'getShippingCost'])->name('shop.shipping');
            // process checkout
            Route::post('/process-checkout', [CheckoutController::class, 'processCheckout'])->name('shop.checkout.process');
            // Momo payment return url
            Route::get('/momo-payment', [CheckoutController::class, 'handleMomoPayment'])->name('momo-payment');
            // Momo return ipn URL
            Route::post('/momo-payment-post', [CheckoutController::class, 'handleMomoPayment'])->name('momo-payment-post');
            // apply discount to order
            Route::post('/apply-discount', [FrontDiscountController::class, 'applyDiscount'])->name('shop.apply-discount');
            // wishlist
            Route::get('/wish-list', [AuthController::class, 'wishlist'])->name('user.wishlist');
            // Change password customer
            Route::get('/change-password', [AuthController::class, 'changePassword'])->name('user.change-password');
            Route::post('/update-password', [AuthController::class, 'updatePassword'])->name('user.update-password');
            // update profile and address
            Route::post('/update-profile', [AuthController::class, 'updateCustomerProfile'])->name('user.update-profile');
            Route::post('/update-address', [AuthController::class, 'updateCustomerAddress'])->name('user.update-address');
            // vn pay
            Route::get('/vnpay_return', [CheckoutController::class,'returnPayment'])->name('vnpay_return');
        });

        Route::group(['middleware' => 'guest'], function () {
            // Login and register
            Route::get('/account', [AuthController::class, 'index'])->name('shop.account');
            // Login post
            Route::post('/login', [AuthController::class, 'login'])->name('user.login');
        });
    });

});

