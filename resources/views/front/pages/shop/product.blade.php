@extends('front.layouts.app')
@section('title')
    {{ $product->title }}
@endsection

@section('head')
    <meta property="og:title" content="{{ $product->title }}" />
    <meta property="og:description" content="{{ Str::limit(strip_tags($product->description), 150) }}" />
    <meta property="og:url" content="{{ route('product.product-details', ['slug' => $product->slug]) }}" />
    <meta property="og:type" content="product" />
    <meta property="og:image" content="{{ url('uploads/products/large/' . $product->product_images[0]['image']) }}" />
    <meta property="og:image:width" content="1200" />
    <meta property="og:image:height" content="630" />
@endsection

@section('content')
    <main>
        <div id="fb-root"></div>
        <section class="section-5 pt-3 pb-3 mb-3 bg-white">
            <div class="container">
                <div class="light-font">
                    <ol class="breadcrumb primary-color mb-0">
                        <li class="breadcrumb-item"><a class="white-text" href="{{ route('home') }}">Trang chủ</a></li>
                        <li class="breadcrumb-item"><a class="white-text" href="{{ route('shop.shop-now') }}">Sản
                                phẩm</a></li>
                        @if ($product->count() > 0)
                            <li class="breadcrumb-item">{{ $product->title }}</li>
                        @endif
                    </ol>
                </div>
            </div>
        </section>

        <section class="section-7 pt-3 mb-3">
            @if (Session::has('success'))
                <div class="tn-box tn-box-color-1 tn-box-active alert-success">
                    <p class="text-center fs-5 fw-bold"><i
                            class="fa-regular fa-circle-check"></i>{{ Session::get('success') }}
                    </p>
                    <div class="tn-progress"></div>
                </div>
            @endif
            <div class="container">
                <div class="row ">
                    <div class="col-md-5">
                        <img id="featured"
                            src="{{ asset('uploads/products/large/' . $product->product_images[0]['image']) }}">

                        <div id="slide-wrapper">
                            <img id="slideLeft" class="arrow" src="{{ asset('fe-assets/images/arrow-left.png') }}">

{{--                            <div id="slider">--}}
{{--                                @for ($i = 0; $i < $product->product_images->count(); $i++)--}}
{{--                                    <img class="thumbnail @if ($i == 0) active @endif"--}}
{{--                                        src="{{ asset('uploads/products/large/' . $product->product_images[$i]['image']) }}"--}}
{{--                                        alt="">--}}
{{--                                @endfor--}}
{{--                            </div>--}}
                            <div id="slider">
                                @foreach ($product->product_images as $index => $image)
                                    <img class="thumbnail {{ $index === 0 ? 'active' : '' }}"
                                         src="{{ asset('uploads/products/large/' . $image['image']) }}"
                                         alt="Image {{ $index + 1 }}">
                                @endforeach
                            </div>

                            <img id="slideRight" class="arrow" src="{{ asset('fe-assets/images/arrow-right.png') }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="bg-light right">
                            <h1>{{ $product->title }}</h1>
                            <div class="d-flex mb-3 align-items-center">
                                <div class="text-primary mr-2">
                                    <div class="star-rating fs-5" title="70%">
                                        <div class="back-stars">
                                            <i class="fa fa-star" aria-hidden="true"></i>
                                            <i class="fa fa-star" aria-hidden="true"></i>
                                            <i class="fa fa-star" aria-hidden="true"></i>
                                            <i class="fa fa-star" aria-hidden="true"></i>
                                            <i class="fa fa-star" aria-hidden="true"></i>

                                            <div class="front-stars" style="width: {{ ($avgRating * 100) / 5 }}%">
                                                <i class="fa fa-star" aria-hidden="true"></i>
                                                <i class="fa fa-star" aria-hidden="true"></i>
                                                <i class="fa fa-star" aria-hidden="true"></i>
                                                <i class="fa fa-star" aria-hidden="true"></i>
                                                <i class="fa fa-star" aria-hidden="true"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <small class="pt-1">({{ $product->product_ratings_count }} Đánh giá)</small>
                            </div>
                            <h2 class="price text-secondary">
                                <del>{{ number_format($product->compare_price, 0, ',', '.') }}đ</del>
                            </h2>
                            <h2 class="price ">{{ number_format($product->price, 0, ',', '.') }}đ</h2>

                            @if ($product->qty > 0)
                                <p>Có thể bán: {{ $product->qty }}</p>
                                <div class="row">
                                    <div class="input-group quantity p-0" style="width: 176px;">
                                        <div class="input-group-btn">
                                            <button class="btn btn-sm btn-dark btn-minus p-3 pt-1 pb-1 rounded sub">
                                                <i class="fa fa-minus"></i>
                                            </button>
                                        </div>
                                        <input type="text" id="cart_product_qty"
                                            class="form-control form-control-sm  border-0 text-center" value="1">
                                        <div class="input-group-btn">
                                            <button class="btn btn-sm btn-dark btn-plus p-3 pt-1 pb-1 rounded add">
                                                <i class="fa fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <a href="javascript:void(0);" onclick="addToCart({{ $product->id }})"
                                    class="btn btn-dark mt-3 rounded"><i class="fas fa-shopping-cart"></i> &nbsp;THÊM
                                    VÀO GIỎ</a>
                            @else
                                <p class="text-danger">Tạm hết hàng</p>
                            @endif
                            <div>
                                <a href="https://www.facebook.com/sharer/sharer.php?u={{ route('product.product-details', ['slug' => $product->slug]) }}"
                                    target="blank" class="btn btn-primary mt-3 rounded"><i class="fab fa-facebook"></i>
                                    &nbsp;Chia sẻ</a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12 mt-5">
                        <div class="bg-light">
                            <ul class="nav nav-tabs" id="myTab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="description-tab" data-bs-toggle="tab"
                                        data-bs-target="#description" type="button" role="tab"
                                        aria-controls="description" aria-selected="true">Mô tả sản phẩm
                                    </button>
                                </li>
                                {{--                                <li class="nav-item" role="presentation"> --}}
                                {{--                                    <button class="nav-link" id="shipping-tab" data-bs-toggle="tab" --}}
                                {{--                                            data-bs-target="#shipping" type="button" role="tab" aria-controls="shipping" --}}
                                {{--                                            aria-selected="false">Shipping & Returns --}}
                                {{--                                    </button> --}}
                                {{--                                </li> --}}
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="reviews-tab" data-bs-toggle="tab"
                                        data-bs-target="#reviews" type="button" role="tab" aria-controls="reviews"
                                        aria-selected="false">
                                        Đánh giá
                                    </button>
                                </li>
                            </ul>
                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade show active" id="description" role="tabpanel"
                                    aria-labelledby="description-tab">
                                    <p>{!! $product->description !!}</p>
                                </div>
                                {{-- <div class="tab-pane fade" id="shipping" role="tabpanel"
                                    aria-labelledby="shipping-tab">
                                    <p>Shipping</p>
                                </div> --}}
                                {{-- reviews --}}
                                <div class="tab-pane fade" id="reviews" role="tabpanel" aria-labelledby="reviews-tab">
                                    <div class="col-md-8">
                                        <div class="row">
                                            @if (Auth::check())
                                                <form action="" id="commentPostForm">
                                                    @csrf
                                                    <div class="form-group mb-3">
                                                        <label for="rating">Đánh giá sao</label>
                                                        <br>
                                                        <div class="rating" style="width: 10rem" id="rating">
                                                            <input id="rating-5" type="radio" name="rating"
                                                                value="5" /><label for="rating-5"><i
                                                                    class="fas fa-3x fa-star"></i></label>
                                                            <input id="rating-4" type="radio" name="rating"
                                                                value="4" /><label for="rating-4"><i
                                                                    class="fas fa-3x fa-star"></i></label>
                                                            <input id="rating-3" type="radio" name="rating"
                                                                value="3" /><label for="rating-3"><i
                                                                    class="fas fa-3x fa-star"></i></label>
                                                            <input id="rating-2" type="radio" name="rating"
                                                                value="2" /><label for="rating-2"><i
                                                                    class="fas fa-3x fa-star"></i></label>
                                                            <input id="rating-1" type="radio" name="rating"
                                                                value="1" /><label for="rating-1"><i
                                                                    class="fas fa-3x fa-star"></i></label>
                                                        </div>
                                                        <p></p>
                                                    </div>
                                                    <div class="form-group mb-3">
                                                        <label for="">Bình luận sản phẩm</label>
                                                        <textarea name="review" id="review" class="form-control" cols="30" rows="3"
                                                            placeholder="Bình luận của bạn..."></textarea>
                                                        <p></p>
                                                    </div>
                                                    <div>
                                                        <button class="btn btn-dark" type="submit">Đánh giá</button>
                                                    </div>
                                                </form>
                                            @else
                                                <p>Vui lòng <a href="{{ route('shop.account') }}">đăng nhập</a> để đánh
                                                    giá sản phẩm.</p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-12 mt-5">
                                        <div class="overall-rating mb-3">
                                            <div class="d-flex">
                                                <h1 class="h3 pe-3">{{ $avgRating }}</h1>
                                                <div class="star-rating mt-2" title="70%">
                                                    <div class="back-stars">
                                                        <i class="fa fa-star" aria-hidden="true"></i>
                                                        <i class="fa fa-star" aria-hidden="true"></i>
                                                        <i class="fa fa-star" aria-hidden="true"></i>
                                                        <i class="fa fa-star" aria-hidden="true"></i>
                                                        <i class="fa fa-star" aria-hidden="true"></i>

                                                        <div class="front-stars"
                                                            style="width: {{ ($avgRating * 100) / 5 }}%">
                                                            <i class="fa fa-star" aria-hidden="true"></i>
                                                            <i class="fa fa-star" aria-hidden="true"></i>
                                                            <i class="fa fa-star" aria-hidden="true"></i>
                                                            <i class="fa fa-star" aria-hidden="true"></i>
                                                            <i class="fa fa-star" aria-hidden="true"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="pt-2 ps-2">({{ $product->product_ratings_count }} Đánh giá)
                                                </div>
                                            </div>
                                        </div>
                                        @if ($product->product_ratings->isNotEmpty())
                                            @foreach ($product->product_ratings as $rating)
                                                <div class="rating-group mb-4">
                                                    <span class="author"><strong>{{ $rating->user->name }}</strong></span>
                                                    <div class="star-rating mt-2">
                                                        <div class="back-stars">
                                                            <i class="fa fa-star" aria-hidden="true"></i>
                                                            <i class="fa fa-star" aria-hidden="true"></i>
                                                            <i class="fa fa-star" aria-hidden="true"></i>
                                                            <i class="fa fa-star" aria-hidden="true"></i>
                                                            <i class="fa fa-star" aria-hidden="true"></i>

                                                            <div class="front-stars"
                                                                style="width: {{ ($rating->rating * 100) / 5 }}%">
                                                                <i class="fa fa-star" aria-hidden="true"></i>
                                                                <i class="fa fa-star" aria-hidden="true"></i>
                                                                <i class="fa fa-star" aria-hidden="true"></i>
                                                                <i class="fa fa-star" aria-hidden="true"></i>
                                                                <i class="fa fa-star" aria-hidden="true"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="my-3">
                                                        <p>
                                                            {{ $rating->comment }}
                                                        </p>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif

                                    </div>
                                </div>
                                {{-- end reviews --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="pt-5 section-8">
            <div class="container">
                <div class="section-title">
                    <h2>Sản phẩm liên quan</h2>
                </div>
                <div class="col-md-12">
                    <div id="related-products" class="carousel">
                        @if ($related_products->count() > 0)
                            @foreach ($related_products as $related_product)
                                <div class="card product-card">
                                    <div class="product-image position-relative">
                                        <a href="{{ route('product.product-details', ['slug' => $related_product->slug]) }}"
                                            class="product-img">
                                            <img class="card-img-top"
                                                src="{{ asset('uploads/products/large/' . $related_product->product_images[0]['image']) }}"
                                                alt="" />
                                            <img class="card-img-top"
                                                src="{{ asset('uploads/products/large/' . $related_product->product_images[1]['image']) }}"
                                                alt="" />
                                        </a>
                                        <a class="whishlist" href="#"><i class="far fa-heart"></i></a>

                                        @if ($related_product->qty > 0)
                                            <div class="product-action">
                                                <a class="btn btn-dark" href="javascript:void(0);"
                                                   onclick="addToCart({{ $related_product->id }})">
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
                                            href="{{ route('product.product-details', ['slug' => $related_product->slug]) }}">{{ $related_product->title }}</a>
                                        <div class="price mt-2">
                                            <span
                                                class="h5"><strong>{{ number_format($related_product->price, 0, ',', '.') }}đ</strong></span>
                                            <span
                                                class="h6 text-underline"><del>{{ number_format($related_product->compare_price, 0, ',', '.') }}đ</del></span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection

@section('js')
    <script type="text/javascript">
        // $('#cart_product_qty').change(function () {
        //     test = $(this).val();
        //     console.log('test = '+test);
        // });

        $('.add').click(function() {
            let qtyElement = $(this).parent().prev(); // Qty input
            let qtyValue = parseInt(qtyElement.val());
            if (qtyValue < {{ $product->qty }}) {
                qtyElement.val(qtyValue + 1);

            }
        })

        $('.sub').click(function() {
            let qtyElement = $(this).parent().next();
            let qtyValue = parseInt(qtyElement.val());
            if (qtyValue > 1) {
                qtyElement.val(qtyValue - 1);

            }
        })

        function addToCart(id) {
            let qty = $('#cart_product_qty').val();
            $.ajax({
                url: '{{ route('shopping.cart.add') }}',
                type: 'post',
                data: {
                    id: id,
                    qty: qty
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
                            text: response["message"],
                            icon: "error"
                        });
                    }
                },
            })
        }

        // get cart count
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


        // image slider
        let thumbnails = document.getElementsByClassName('thumbnail')

        let activeImages = document.getElementsByClassName('active')

        for (var y = 0; y < thumbnails.length; y++) {

            thumbnails[y].addEventListener('mouseover', function() {
                // console.log(activeImages)

                if (activeImages.length > 0) {
                    activeImages[0].classList.remove('active')
                }


                this.classList.add('active')
                document.getElementById('featured').src = this.src
            })
        }


        let buttonRight = document.getElementById('slideRight');
        let buttonLeft = document.getElementById('slideLeft');

        buttonLeft.addEventListener('click', function() {
            document.getElementById('slider').scrollLeft -= 180
        })

        buttonRight.addEventListener('click', function() {
            document.getElementById('slider').scrollLeft += 180
        })


        $("#commentPostForm").submit(function(e) {
            e.preventDefault();
            $.ajax({
                url: '{{ route('user.rating', $product->id) }}',
                type: 'post',
                data: $(this).serializeArray(),
                dataType: 'json',
                success: function(response) {
                    if (response['status']) {
                        window.location.href =
                            '{{ route('product.product-details', ['slug' => $product->slug]) }}'
                    } else {
                        const errors = response["errors"];
                        $("input[type='text'], textarea").removeClass('is-invalid')
                            .siblings('p').removeClass('invalid-feedback')
                            .html('');
                        $.each(errors, function(key, value) {
                            $(`#${key}`).addClass('is-invalid').siblings('p').addClass(
                                'invalid-feedback').html(value);
                        });
                        if (response.message != '') {
                            Swal.fire(response.message);
                        }
                    }
                },
            })
        });
    </script>
@endsection
