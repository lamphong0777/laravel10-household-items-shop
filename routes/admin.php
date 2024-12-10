<?php

// admin controller
use App\Http\Controllers\admin\BlogController;
use App\Http\Controllers\admin\DiscountController;
use App\Http\Controllers\admin\HomeController;
use App\Http\Controllers\admin\LoginController;
use App\Http\Controllers\admin\CategoryController;
use App\Http\Controllers\admin\OrderController;
use App\Http\Controllers\admin\PermissionController;
use App\Http\Controllers\admin\ProducerController;
use App\Http\Controllers\admin\ProductImageController;
use App\Http\Controllers\admin\ProductStockController;
use App\Http\Controllers\admin\ShippingController;
use App\Http\Controllers\admin\StaticPageController;
use App\Http\Controllers\admin\TempImagesController;
use App\Http\Controllers\admin\SubCategoryController;
use App\Http\Controllers\admin\BrandController;
use App\Http\Controllers\admin\ProductController;
use App\Http\Controllers\admin\ProductSubcategoryController;
use App\Http\Controllers\admin\UserController;
use App\Http\Controllers\admin\StaffController;

use App\Http\Controllers\ChatController;
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

// admin route
Route::group(['prefix' => 'admin'], function () {
    Route::group(['middleware' => 'admin.guest'], function () {
        Route::get('/login', [LoginController::class, 'index'])->name('admin.login');
        Route::post('/authenticate', [LoginController::class, 'authenticate'])->name('admin.authenticate');

    });
    Route::group(['middleware' => 'admin.auth'], function () {
        Route::get('/dashboard', [HomeController::class, 'index'])->name('admin.dashboard');
        Route::get('/logout', [LoginController::class, 'logout'])->name('admin.logout');
        Route::get('/customer-chat', [ChatController::class, 'customerChatIndex'])->name('admin.customer-chat.index');
        Route::get('/admin-chat/{id}', [ChatController::class, 'adminIndex'])->name('admin.chat.index');
        Route::post('/admin-chat/send/{id}', [ChatController::class, 'sendMessageAdmin'])->name('admin.chat.send');
        //Category
        Route::group(['prefix' => 'categories', 'middleware' => 'permission:quan-ly-san-pham'], function () {
            Route::get('/', [CategoryController::class, 'index'])->name('admin.categories.index');
            Route::get('/create', [CategoryController::class, 'create'])->name('admin.categories.create');
            Route::post('/store', [CategoryController::class, 'store'])->name('admin.categories.store');
            Route::get('/edit/{id}', [CategoryController::class, 'edit'])->name('admin.categories.edit');
            Route::put('/update/{id}', [CategoryController::class, 'update'])->name('admin.categories.update');
            Route::delete('/delete/{id}', [CategoryController::class, 'destroy'])->name('admin.categories.destroy');
        });
        //Sub category
        Route::group(['prefix' => 'subcategories', 'middleware' => 'permission:quan-ly-san-pham'], function () {
            Route::get('/', [SubCategoryController::class, 'index'])->name('admin.subcategories.index');
            Route::get('/create', [SubCategoryController::class, 'create'])->name('admin.subcategories.create');
            Route::post('/store', [SubCategoryController::class, 'store'])->name('admin.subcategories.store');
            Route::get('/edit/{id}', [SubCategoryController::class, 'edit'])->name('admin.subcategories.edit');
            Route::put('/update/{id}', [SubCategoryController::class, 'update'])->name('admin.subcategories.update');
            Route::delete('/delete/{id}', [SubCategoryController::class, 'destroy'])->name('admin.subcategories.destroy');
        });

        //Brand
        Route::group(['prefix' => 'brands', 'middleware' => 'permission:quan-ly-san-pham'], function () {
            Route::get('/', [BrandController::class, 'index'])->name('admin.brands.index');
            Route::get('/create', [BrandController::class, 'create'])->name('admin.brands.create');
            Route::post('/store', [BrandController::class, 'store'])->name('admin.brands.store');
            Route::get('/edit/{id}', [BrandController::class, 'edit'])->name('admin.brands.edit');
            Route::put('/update/{id}', [BrandController::class, 'update'])->name('admin.brands.update');
            Route::delete('/delete/{id}', [BrandController::class, 'destroy'])->name('admin.brands.destroy');
        });

        //Product
        Route::group(['prefix' => 'products', 'middleware' => 'permission:quan-ly-san-pham'], function () {
            Route::get('/', [ProductController::class, 'index'])->name('admin.products.index');
            Route::get('/create', [ProductController::class, 'create'])->name('admin.products.create');
            Route::post('/store', [ProductController::class, 'store'])->name('admin.products.store');
            Route::get('/edit/{id}', [ProductController::class, 'edit'])->name('admin.products.edit');
            Route::put('/update/{id}', [ProductController::class, 'update'])->name('admin.products.update');
            Route::delete('/delete/{id}', [ProductController::class, 'destroy'])->name('admin.products.destroy');
            Route::get('/rating', [ProductController::class, 'rating'])->name('admin.products.rating');
            Route::post('/approve-rating/{id}', [ProductController::class, 'approveRating'])->name('admin.products.approve-rating');
        });

        // Product stock
        Route::group(['prefix' => 'product-stocks', 'middleware' => 'permission:quan-ly-kho'], function () {
            Route::get('/receipt', [ProductStockController::class, 'indexReceipt'])->name('admin.product-stocks.receipt');
            Route::get('/receipt/create', [ProductStockController::class, 'createReceipt'])->name('admin.product-stocks.receipt.create');
            Route::post('/receipt/store', [ProductStockController::class, 'storeReceipt'])->name('admin.product-stocks.receipt.store');
            Route::get('/receipt/details/{id}', [ProductStockController::class, 'showReceipt'])->name('admin.product-stocks.receipt.details');
        });

        // Order
        Route::group(['prefix' => 'orders', 'middleware' => 'permission:quan-ly-hoa-don'], function () {
            Route::get('/', [OrderController::class, 'index'])->name('admin.orders.index');
            Route::get('/details/{id}', [OrderController::class, 'show'])->name('admin.orders.show');
            //            Route::get('/create', [OrderController::class, 'create'])->name('admin.orders.create');
//            Route::post('/store', [OrderController::class, 'store'])->name('admin.orders.store');
//            Route::get('/edit/{id}', [OrderController::class, 'edit'])->name('admin.orders.edit');
            Route::put('/update/{id}', [OrderController::class, 'update'])->name('admin.orders.update');
            //            Route::delete('/delete/{id}', [OrderController::class, 'destroy'])->name('admin.orders.destroy');
            Route::post('/send-order-email/{id}', [OrderController::class, 'sendOrderEmail'])->name('admin.orders.send');
        });

        // Shipping
        Route::group(['prefix' => 'shipping-charges', 'middleware' => 'permission:quan-ly-van-chuyen-khuyen-mai'], function () {
            Route::get('/', [ShippingController::class, 'index'])->name('admin.shipping.index');
            //            Route::get('/create', [OrderController::class, 'create'])->name('admin.orders.create');
            Route::post('/store', [ShippingController::class, 'store'])->name('admin.shipping.store');
            Route::get('/edit/{id}', [ShippingController::class, 'edit'])->name('admin.shipping.edit');
            Route::post('/update/{id}', [ShippingController::class, 'update'])->name('admin.shipping.update');
            Route::delete('/delete/{id}', [ShippingController::class, 'destroy'])->name('admin.shipping.destroy');
        });

        // Discount coupon
        Route::group(['prefix' => 'discount-coupons', 'middleware' => 'permission:quan-ly-van-chuyen-khuyen-mai'], function () {
            Route::get('/', [DiscountController::class, 'index'])->name('admin.discount.index');
            Route::get('/create', [DiscountController::class, 'create'])->name('admin.discount.create');
            Route::post('/store', [DiscountController::class, 'store'])->name('admin.discount.store');
            Route::get('/edit/{id}', [DiscountController::class, 'edit'])->name('admin.discount.edit');
            Route::post('/update/{id}', [DiscountController::class, 'update'])->name('admin.discount.update');
            Route::delete('/delete/{id}', [DiscountController::class, 'destroy'])->name('admin.discount.destroy');
        });

        // User -> Customer
        Route::group(['prefix' => 'users', 'middleware' => 'permission:quan-ly-tai-khoan'], function () {
            Route::get('/', [UserController::class, 'index'])->name('admin.user.index');
            Route::get('/create', [UserController::class, 'create'])->name('admin.user.create');
            Route::post('/store', [UserController::class, 'store'])->name('admin.user.store');
            Route::get('/edit/{id}', [UserController::class, 'edit'])->name('admin.user.edit');
            Route::put('/update/{id}', [UserController::class, 'update'])->name('admin.user.update');
            Route::delete('/delete/{id}', [UserController::class, 'destroy'])->name('admin.user.destroy');
        });

        // Staff
        Route::group(['prefix' => 'staffs', 'middleware' => 'permission:quan-ly-tai-khoan'], function () {
            Route::get('/', [StaffController::class, 'index'])->name('admin.staff.index');
            Route::get('/create', [StaffController::class, 'create'])->name('admin.staff.create');
            Route::post('/store', [StaffController::class, 'store'])->name('admin.staff.store');
            Route::get('/edit/{id}', [StaffController::class, 'edit'])->name('admin.staff.edit');
            Route::put('/update/{id}', [StaffController::class, 'update'])->name('admin.staff.update');
            Route::delete('/delete/{id}', [StaffController::class, 'destroy'])->name('admin.staff.destroy');
        });

        // static pages
        Route::group(['prefix' => 'pages', 'middleware' => 'permission:quan-ly-bai-viet'], function () {
            Route::get('/', [StaticPageController::class, 'index'])->name('admin.page.index');
            Route::get('/create', [StaticPageController::class, 'create'])->name('admin.page.create');
            Route::post('/store', [StaticPageController::class, 'store'])->name('admin.page.store');
            Route::get('/edit/{id}', [StaticPageController::class, 'edit'])->name('admin.page.edit');
            Route::put('/update/{id}', [StaticPageController::class, 'update'])->name('admin.page.update');
            Route::delete('/delete/{id}', [StaticPageController::class, 'destroy'])->name('admin.page.destroy');
        });
        // Blogs
        Route::group(['prefix' => 'blogs', 'middleware' => 'permission:quan-ly-bai-viet'], function () {
            Route::get('/', [BlogController::class, 'index'])->name('admin.blog.index');
            Route::get('/create', [BlogController::class, 'create'])->name('admin.blog.create');
            Route::post('/store', [BlogController::class, 'store'])->name('admin.blog.store');
            Route::get('/edit/{id}', [BlogController::class, 'edit'])->name('admin.blog.edit');
            Route::put('/update/{id}', [BlogController::class, 'update'])->name('admin.blog.update');
            Route::delete('/delete/{id}', [BlogController::class, 'destroy'])->name('admin.blog.destroy');
        });

        // Permissions
        Route::group(['prefix' => 'permissions', 'middleware' => 'permission:quan-ly-quyen'], function () {
            Route::get('/', [PermissionController::class, 'index'])->name('admin.permission.index');
            Route::get('/create', [PermissionController::class, 'create'])->name('admin.permission.create');
            Route::post('/store', [PermissionController::class, 'store'])->name('admin.permission.store');
            Route::get('/edit/{id}', [PermissionController::class, 'edit'])->name('admin.permission.edit');
            Route::put('/update/{id}', [PermissionController::class, 'update'])->name('admin.permission.update');
            Route::delete('/delete/{id}', [PermissionController::class, 'destroy'])->name('admin.permission.destroy');
        });

        // Producers
        Route::group(['prefix' => 'producers', 'middleware' => 'permission:quan-ly-kho'], function () {
            Route::get('/', [ProducerController::class, 'index'])->name('admin.producer.index');
            Route::get('/create', [ProducerController::class, 'create'])->name('admin.producer.create');
            Route::post('/store', [ProducerController::class, 'store'])->name('admin.producer.store');
            Route::get('/edit/{id}', [ProducerController::class, 'edit'])->name('admin.producer.edit');
            Route::put('/update/{id}', [ProducerController::class, 'update'])->name('admin.producer.update');
            Route::delete('/delete/{id}', [ProducerController::class, 'destroy'])->name('admin.producer.destroy');
        });

        // get subcategory from category request
        Route::get('/get-subcategory', [ProductSubcategoryController::class, 'index'])->name('admin.products.subcategory.index');
        // update product image
        Route::post('/product-images/update', [ProductImageController::class, 'update'])->name('admin.products.images.update');
        // delete product image
        Route::delete('/product-images/delete', [ProductImageController::class, 'delete'])->name('admin.products.images.delete');
        // get slug
        Route::get('/get-slug', function (Request $request) {
            $slug = '';
            if (!empty($request->title)) {
                $slug = Str::slug($request->title);
            }
            return response()->json([
                'status' => true,
                'slug' => $slug
            ]);
        })->name('admin.categories.getSlug');
        // temp-images.create
        Route::post('/upload-temp-image', [TempImagesController::class, 'create'])->name('temp-images.create');

        // Change password
        Route::get('/change-password', [StaffController::class, 'changePassword'])->name('admin.change-password');
        Route::post('/update-password', [StaffController::class, 'updatePassword'])->name('admin.update-password');
    });
});
