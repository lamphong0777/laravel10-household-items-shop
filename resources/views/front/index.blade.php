@extends('front.layouts.app')
@section('title')
    Home
@endsection
@section('content')
    <main>
        <section class="section-1">
            <div id="carouselExampleIndicators" class="carousel slide carousel-fade" data-bs-ride="carousel"
                data-bs-interval="false">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <picture>
                            <img src="{{ asset('fe-assets/images/carousel-4.jpg') }}" alt="" />
                        </picture>

                    </div>
                    <div class="carousel-item">
                        <picture>
                            <img src="{{ asset('fe-assets/images/carousel-5.jpg') }}" alt="" />
                        </picture>

                    </div>
                    <div class="carousel-item">

                        <picture>

                            <img src="{{ asset('fe-assets/images/carousel-6.jpg') }}" alt="" />
                        </picture>

                    </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators"
                    data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators"
                    data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        </section>

        <section class="section-3 pt-5">
            <div class="container">
                <div class="section-title">
                    <h2>Danh mục sản phẩm</h2>
                </div>
                <div class="row pb-3">
                    @if ($categories->count() > 0)
                        @foreach ($categories as $category)
                            <div class="col-lg-3">
                                <div class="cat-card">
                                    <div class="left">
                                        <img src="{{ asset('uploads/category/' . $category->image) }}" alt=""
                                            class="img-fluid">
                                    </div>
                                    <div class="right">
                                        <div class="cat-data">
                                            <h2 class="text-lowercase fw-bold">{{ $category->name }}</h2>
                                            <p>{{ $category->product->count() }} Sản phẩm</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </section>

        <section class="section-4 pt-5">
            <div class="container">
                <div class="section-title">
                    <h2>Sản phẩm nổi bật</h2>
                </div>
                <div class="row pb-3">
                    @if ($products->count() > 0)
                        @foreach ($products as $product)
                            <div class="col-md-3">
                                <div class="card product-card">
                                    <div class="product-image position-relative">
                                        {{-- <a href="{{ route('shop.login') }}" class="product-img"><img class="card-img-top" src="{{ asset('fe-assets/images/product-1.jpg') }}" alt=""></a> --}}
                                        <a href="{{ route('product.product-details', ['slug' => $product->slug]) }}"
                                            class="product-img">
                                            <img class="card-img-top"
                                                src="{{ asset('uploads/products/large/' . $product->product_images[0]['image']) }}" />
                                            <img class="card-img-top"
                                                src="{{ asset('uploads/products/large/' . $product->product_images[1]['image']) }}" />
                                        </a>

                                        {{-- <a class="whishlist" href="222"><i class="far fa-heart"></i></a> --}}

                                        @if ($product->qty > 0)
                                            <div class="product-action">
                                                <a class="btn btn-dark" href="javascript:void(0);"
                                                    onclick="addToCart({{ $product->id }})">
                                                    <i class="fa fa-shopping-cart"></i> Thêm vào giỏ
                                                </a>
                                            </div>
                                        @else
                                            <div class="product-action">
                                                <p class="btn btn-danger">
                                                    <i class="fa fa-shopping-cart"></i> Tạm hết hàng
                                                </p>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="card-body text-center mt-3">
                                        <a class="h6 link product-title-cut"
                                            href="{{ route('product.product-details', ['slug' => $product->slug]) }}">{{ $product->title }}</a>
                                        <div class="price mt-2">
                                            <span
                                                class="h5"><strong>{{ number_format($product->price, 0, ',', '.') }}đ</strong></span>
                                            <span
                                                class="h6 text-underline"><del>{{ number_format($product->compare_price, 0, ',', '.') }}đ</del></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </section>

        <section class="section-4 pt-5">
            <div class="container">
                <div class="section-title">
                    <h2>Sản phẩm mới</h2>
                </div>
                <div class="row pb-3">
                    @if ($latest_products->count() > 0)
                        @foreach ($latest_products as $product)
                            <div class="col-md-3">
                                <div class="card product-card">
                                    <div class="product-image position-relative">
                                        {{-- <a href="{{ route('shop.login') }}" class="product-img"><img class="card-img-top" src="{{ asset('fe-assets/images/product-1.jpg') }}" alt=""></a> --}}
                                        <a href="{{ route('product.product-details', ['slug' => $product->slug]) }}"
                                            class="product-img">
                                            <img class="card-img-top"
                                                src="{{ asset('uploads/products/large/' . $product->product_images[0]['image']) }}" />
                                            <img class="card-img-top"
                                                src="{{ asset('uploads/products/large/' . $product->product_images[1]['image']) }}" />
                                        </a>

                                        {{-- <a class="whishlist" href="222"><i class="far fa-heart"></i></a> --}}

                                        @if ($product->qty > 0)
                                            <div class="product-action">
                                                <a class="btn btn-dark" href="javascript:void(0);"
                                                    onclick="addToCart({{ $product->id }})">
                                                    <i class="fa fa-shopping-cart"></i> Thêm vào giỏ
                                                </a>
                                            </div>
                                        @else
                                            <div class="product-action">
                                                <p class="btn btn-danger">
                                                    <i class="fa fa-shopping-cart"></i> Tạm hết hàng
                                                </p>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="card-body text-center mt-3">
                                        <a class="h6 link product-title-cut"
                                            href="{{ route('product.product-details', ['slug' => $product->slug]) }}">{{ $product->title }}</a>
                                        <div class="price mt-2">
                                            <span
                                                class="h5"><strong>{{ number_format($product->price, 0, ',', '.') }}đ</strong></span>
                                            <span
                                                class="h6 text-underline"><del>{{ number_format($product->compare_price, 0, ',', '.') }}đ</del></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif

                </div>
            </div>
        </section>
        <section class="section-2">
            <div class="container">
                <div class="row">
                    <div class="col-lg-3">
                        <div class="box shadow-lg">
                            <div class="fa icon fa-check text-primary m-0 mr-3"></div>
                            <h2 class="font-weight-semi-bold m-0">Sản phẩm chất lượng</h2>
                        </div>
                    </div>
                    <div class="col-lg-3 ">
                        <div class="box shadow-lg">
                            <div class="fa icon fa-shipping-fast text-primary m-0 mr-3"></div>
                            <h2 class="font-weight-semi-bold m-0">Miễn phí vận chuyển</h2>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="box shadow-lg">
                            <div class="fa icon fa-exchange-alt text-primary m-0 mr-3"></div>
                            <h2 class="font-weight-semi-bold m-0">14 ngày đổi trả</h2>
                        </div>
                    </div>
                    <div class="col-lg-3 ">
                        <div class="box shadow-lg">
                            <div class="fa icon fa-phone-volume text-primary m-0 mr-3"></div>
                            <h2 class="font-weight-semi-bold m-0">Hỗ trợ 24/7</h2>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="section-blogs pt-5">
            <div class="container">
                <div class="section-title">
                    <h2>Bài viết mới nhất</h2>
                </div>
                <div class="row">
                    @if ($blogs->count() > 0)
                        @foreach ($blogs->take(4) as $blog)
                            <div class="col-lg-3 col-md-6 mb-4">
                                <div class="card blog-card">
                                    <a href="{{ route('blog.details', $blog->slug) }}">
                                        <img src="{{ asset('uploads/blogs/' . $blog->image) }}"
                                            alt="{{ $blog->title }}" class="img-fluid">
                                    </a>
                                    <div class="card-body">
                                        <h5 class="card-title">
                                            <a href="{{ route('blog.details', $blog->slug) }}"
                                                class="text-decoration-none text-dark">
                                                {{ $blog->title }}
                                            </a>
                                        </h5>
                                        <p class="card-text">
                                            {{ Str::limit(strip_tags($blog->content), 100, '...') }}
                                        </p>
                                        <a href="{{ route('blog.details', $blog->slug) }}"
                                            class="btn btn-primary btn-sm">Đọc thêm</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <p class="text-center">Không có bài viết nào.</p>
                    @endif
                </div>
            </div>
        </section>
    </main>
@endsection

@section('js')
    <script>
        function addToCart(id) {
            $.ajax({
                url: '{{ route('shopping.cart.add') }}',
                type: 'post',
                data: {
                    id: id,
                    qty: 1
                },
                dataType: 'json',
                success: function(response) {
                    if (response['status']) {
                        Swal.fire({
                            title: "Thành công!",
                            text: "Đã thêm sản phẩm vào giỏ hàng!",
                            icon: "success"
                        });
                        getCartCount();
                    } else {
                        Swal.fire({
                            title: "Lỗi!",
                            text: "Bạn cần đăng nhập!",
                            icon: "error"
                        });
                    }
                },
            })
        }

        function getCartCount() {
            $.ajax({
                url: '{{ route('shop.cart.count') }}',
                type: 'get',
                data: {},
                dataType: 'json',
                success: function(response) {
                    if (response['status']) {
                        $('#cart_count').html(response['cartCount']);
                    } else {

                    }
                },
            })
        }
    </script>
@endsection
