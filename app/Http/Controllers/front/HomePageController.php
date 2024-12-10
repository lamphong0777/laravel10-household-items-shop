<?php

namespace App\Http\Controllers\front;

use App\Http\Controllers\Controller;
use App\Mail\ContactEmail;
use App\Models\Blog;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductRating;
use App\Models\StaticPage;
use App\Models\SubCategory;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class HomePageController extends Controller {
    public function index() {
        $categories = Category::orderBy( 'id', 'desc' )->get();
        $products = Product::where( 'is_featured', 'Yes' )->orderBy( 'created_at', 'desc' )->take( 8 )->get();
        $latest_products = Product::orderBy( 'created_at', 'desc' )->take( 8 )->get();
        $blogs = Blog::orderBy( 'created_at', 'desc' )->take( 8 )->get();

        return view( 'front.index', compact( 'products', 'categories', 'latest_products', 'blogs' ) );
    }

    public function shop( Request $request, $categorySlug = null, $subcategorySlug = null ) {
        $category_selected_id = '';
        $subcategory_selected_id = '';
        $brandArray = [];
        $priceMin = intval( $request->get( 'price_min' ) );
        $priceMax = ( intval( $request->get( 'price_max' ) ) == 0 ) ? 1000000 : $request->get( 'price_max' );
        $sort = '';

        $categories = Category::orderBy( 'id', 'desc' )->get();
        $brands = Brand::orderBy( 'name', 'asc' )->get();

        $products = Product::where( 'status', 1 );

        // search for product
        $searchText = $request->get('search_text');

        if (filled($searchText)) {
            $products = Product::where( 'title', 'like', '%' . $searchText . '%' );
        }

        // apply filter
        if ( !empty( $categorySlug ) ) {
            $category = Category::where( 'slug', $categorySlug )->first();
            $category_selected_id = $category->id;
            $products = Product::where( 'category_id', $category->id );
        }

        if ( !empty( $subcategorySlug ) ) {
            $subcategory = SubCategory::where( 'slug', $subcategorySlug )->first();
            $subcategory_selected_id = $subcategory->id;
            $products = Product::where( 'sub_category_id', $subcategory->id );
        }

        if ( !empty( $request->get( 'brand' ) ) ) {
            $brandArray = explode( ',', $request->get( 'brand' ) );
            $products = $products->whereIn( 'brand_id', $brandArray );
        }

        if ( $request->get( 'price_min' ) != '' && $request->get( 'price_max' ) != '' ) {
            $products = $products->whereBetween( 'price', [ intval( $request->get( 'price_min' ) ), intval( $request->get( 'price_max' ) ) ] );
        }

        // sort filter
        if ( $request->get( 'sort' ) != '' ) {
            $sort = $request->get( 'sort' );
            if ( $request->get( 'sort' ) == 'latest' ) {
                $products = $products->orderBy( 'created_at', 'desc' );
            } else if ( $request->get( 'sort' ) == 'price_desc' ) {
                $products = $products->orderBy( 'price', 'desc' );
            } else if ( $request->get( 'sort' ) == 'price_asc' ) {
                $products = $products->orderBy( 'price', 'asc' );
            }
        }

        // get user wishlist
        $wishlist_user = null;
        if ( Auth::check() ) {
            $wishlist_user = Wishlist::where( 'user_id', Auth::user()->id )->get();
        }

        $products = $products->orderBy( 'created_at', 'desc' )->paginate( 9 );
        return view( 'front.pages.shop.shop', compact( 'products', 'brands', 'categories', 'category_selected_id', 'subcategory_selected_id', 'brandArray', 'priceMin', 'priceMax', 'sort', 'wishlist_user' ) );
    }

    public function product( $slug ) {
        $product = Product::where( 'slug', $slug )
        ->withCount( 'product_ratings' )
        ->withSum( 'product_ratings', 'rating' )
        ->firstOrFail();
        if ( $product == null ) {
            abort( 404 );
        }
        $related_products = Product::where( 'category_id', $product->category_id )
        ->where( 'id', '<>', $product->id )
        ->take( 4 )
        ->get();

        $avgRating = '0.00';
        if ( $product->product_ratings_count > 0 ) {
            $avgRating = number_format( $product->product_ratings_sum_rating/$product->product_ratings_count, 2 );
        }

        return view( 'front.pages.shop.product', compact( 'product', 'related_products', 'avgRating' ) );
    }

    public function showStaticPage( $slug ) {
        $staticPage = StaticPage::where( 'slug', $slug )->firstOrFail();
        return view( 'front.pages.shop.static-page', compact( 'staticPage' ) );
    }

    public function sendContact( Request $request ) {
        $validator = Validator::make( $request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'subject' => 'required',
            'message' => 'required',
        ], [
            'name.required' => 'Tên không được để trống.',
            'email.required' => 'Email không được để trống.',
            'email.email' => 'Email không hợp lệ.',
            'subject.required' => 'Tiêu đề không được để trống.',
            'message.required' => 'Nội dung tin nhắn không được để trống.',
        ] );

        if ( $validator->passes() ) {
            $mailData = [
                'subject' => $request->input( 'subject' ),
                'name' => $request->input( 'name' ),
                'email' => $request->input( 'email' ),
                'message' => $request->input( 'message' ),
            ];
            Mail::to( env( 'ADMIN_EMAIL' ) )->send( new ContactEmail( $mailData ) );
            return response()->json( [
                'status' => true,
                'message' => 'Đã gửi liên hệ thành công!'
            ] );
        } else {
            return response()->json( [
                'status' => false,
                'errors' => $validator->errors()
            ] );
        }
    }

    public function ProductRating( $id, Request $request ) {
        $validator = Validator::make( $request->all(), [
            'review' => 'required|min:10',
            'rating' => 'required'
        ], [
            'review.required' => 'Vui lòng nhập nội dung đánh giá.',
            'review.min' => 'Nội dung đánh giá phải có ít nhất 10 ký tự.',
            'rating.required' => 'Vui lòng chọn mức đánh giá.'
        ] );
        if ( $validator->passes() ) {
            // Kiểm tra nếu user đã đánh giá sản phẩm
            $existingRating = ProductRating::where( 'product_id', $id )
            ->where( 'user_id', Auth::user()->id )
            ->first();

            if ( $existingRating ) {
                // Nếu user đã đánh giá
                return response()->json( [
                    'status' => false,
                    'message' => 'Bạn đã đánh giá sản phẩm này'
                ] );
            }
            // Lưu đánh giá nếu chưa tồn tại
            ProductRating::create( [
                'product_id' => $id,
                'user_id' => Auth::user()->id,
                'comment' => $request->input( 'review' ),
                'rating' => $request->input( 'rating' )
            ] );

            $request->session()->flash( 'success', 'Đã đánh giá sản phẩm' );

            return response()->json( [
                'status' => true,
                'message' => 'success'
            ] );
        } else {
            return response()->json( [
                'status' => false,
                'message' => '',
                'errors' => $validator->errors()
            ] );
        }
    }

    public function blogDetails($slug)
    {
        $blog = Blog::where('slug', $slug)->firstOrFail(); // Lấy blog theo slug, nếu không có thì báo lỗi 404
        $relatedBlogs = Blog::where('slug', '!=', $slug)->latest()->take(3)->get(); // Gợi ý các bài viết liên quan

        return view('front.pages.shop.blog-details', compact('blog', 'relatedBlogs'));
    }
}
