@extends('front.layouts.app')
@section('title')
    Shop
@endsection
@section('content')
    <main>
        <section class="section-5 pt-3 pb-3 mb-3 bg-white">
            <div class="container">
                <div class="light-font">
                    <ol class="breadcrumb primary-color mb-0">
                        <li class="breadcrumb-item"><a class="white-text" href="{{ route('home') }}">Trang chủ</a></li>
                        <li class="breadcrumb-item active">Sản phẩm</li>
                        @if (isset($subcategory))
                            <li class="breadcrumb-item active">{{ $subcategory->name }}</li>
                        @endif
                    </ol>
                </div>
            </div>
        </section>

        <section class="section-6 pt-5">
            <div class="container">
                <div class="row">
                    <div class="col-md-3 sidebar">
                        <div class="sub-title">
                            <h2>Danh mục</h2>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <div class="accordion accordion-flush" id="accordionExample">
                                    @if ($categories->count() > 0)
                                        @foreach ($categories as $category)
                                            <div class="accordion-item">
                                                @if ($category->subcategories->isNotEmpty())
                                                    <h2 class="accordion-header" id="headingOne">
                                                        <button class="accordion-button collapsed text-lowercase"
                                                            type="button" data-bs-toggle="collapse"
                                                            data-bs-target="#collapse-{{ $category->id }}"
                                                            aria-expanded="false" aria-controls="collapseOne">
                                                            {{ $category->name }}
                                                        </button>
                                                    </h2>
                                                @else
                                                    <a class="nav-item nav-link {{ $category_selected_id == $category->id ? 'text-primary' : '' }}"
                                                        href="{{ route('shop.shop-now', ['categorySlug' => $category->slug]) }}"></a>
                                                @endif

                                                <div id="collapse-{{ $category->id }}"
                                                    class="accordion-collapse collapse {{ $category_selected_id == $category->id ? 'show' : '' }}"
                                                    aria-labelledby="headingOne" data-bs-parent="#accordionExample"
                                                    style="">
                                                    <div class="accordion-body">
                                                        <div class="navbar-nav">
                                                            @foreach ($category->subcategories as $subcategory)
                                                                <a href="{{ route('shop.shop-now', [$category->slug, $subcategory->slug]) }}"
                                                                    class="nav-item nav-link {{ $subcategory_selected_id == $subcategory->id ? 'text-primary' : '' }}">--{{ $subcategory->name }}</a>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="sub-title mt-5">
                            <h2>Thương hiệu</h2>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                @if ($brands->isNotEmpty())
                                    @foreach ($brands as $brand)
                                        <div class="form-check mb-2">
                                            <input {{ in_array($brand->id, $brandArray) ? 'checked' : '' }}
                                                class="form-check-input brand-label" type="checkbox" name="brand[]"
                                                value="{{ $brand->id }}" id="brand-{{ $brand->id }}">
                                            <label class="form-check-label" for="brand-{{ $brand->id }}">
                                                {{ $brand->name }}
                                            </label>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>

                        <div class="sub-title mt-5">
                            <h2>Giá</h2>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <input type="text" class="js-range-slider" name="my_range" value="" />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="row pb-3">
                            <div class="col-8">
                                @if (Request::get('search_text') != '')
                                    <h5>Kết quả tìm: {{ Request::get('search_text') }} ({{ $products->count() }} sản phẩm)
                                    </h5>
                                @endif
                            </div>
                            <div class="col-4 pb-1">
                                <div class="d-flex align-items-center justify-content-end mb-4">
                                    <div class="ml-2">
                                        <select name="sort" id="sort" class="form-select rounded-pill text-dark">
                                            <option value="latest" @if ($sort == 'latest') selected @endif>Mới
                                                nhất</option>
                                            <option value="price_desc" @if ($sort == 'price_desc') selected @endif>Giá
                                                giảm</option>
                                            <option value="price_asc" @if ($sort == 'price_asc') selected @endif>Giá
                                                tăng</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            @if ($products->count() > 0)
                                @foreach ($products as $product)
                                    <div class="col-md-4">
                                        <div class="card product-card">
                                            <div class="product-image position-relative">
                                                {{-- <a href="{{ route('shop.login') }}" class="product-img"><img class="card-img-top" src="{{ asset('fe-assets/images/product-1.jpg') }}" alt=""></a> --}}

                                                    <a href="{{ route('product.product-details', ['slug' => $product->slug]) }}"
                                                       class="product-img">
                                                        @if($product->product_images->isNotEmpty())
                                                        <img class="card-img-top"
                                                             src="{{ asset('uploads/products/large/' . $product->product_images[0]['image']) }}"
                                                             alt="" />
                                                        <img class="card-img-top"
                                                             src="{{ asset('uploads/products/large/' . $product->product_images[1]['image']) }}"
                                                             alt="" />
                                                        @endif
                                                    </a>
                                                {{-- whishlists --}}
                                                @if ($wishlist_user != null && $wishlist_user->count() > 0)
                                                    @php
                                                        // Kiểm tra nếu sản phẩm có trong danh sách yêu thích
                                                        $isInWishlist = $wishlist_user->contains(
                                                            'product_id',
                                                            $product->id,
                                                        );
                                                    @endphp

                                                    <a class="whishlist" href="javascript:void(0);"
                                                        onclick="updateWishlist({{ $product->id }})">
                                                        <i class="{{ $isInWishlist ? 'fas' : 'far' }} fa-heart"
                                                            id="iconWishlist_{{ $product->id }}"></i>
                                                    </a>
                                                @else
                                                    <a class="whishlist" href="javascript:void(0);"
                                                        onclick="updateWishlist({{ $product->id }})">
                                                        <i class="far fa-heart" id="iconWishlist_{{ $product->id }}"></i>
                                                    </a>
                                                @endif
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
                            @else
                                <div>
                                    <h2 class="text-center">Không có sản phẩm</h2>
                                </div>
                            @endif

                            <div class="col-md-12 pt-5">
                                <nav aria-label="Page navigation example">
                                    <ul class="pagination justify-content-center">
                                        {{ $products->appends($_GET)->links() }}
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection

@section('js')
    <script type="text/javascript">
        $('.brand-label').change(function() {
            apply_filters();
        });

        $('#sort').change(function() {
            apply_filters();
        });

        rangeSlider = $('.js-range-slider').ionRangeSlider({
            type: "double",
            min: 0,
            max: 1000000,
            from: {{ $priceMin }},
            step: 1000,
            to: {{ $priceMax }},
            skin: "round",
            max_postfix: "+",
            prefix: "",
            onFinish: function() {
                apply_filters();
            }
        });

        let slider = $(".js-range-slider").data("ionRangeSlider");
        // console.log(slider);

        function apply_filters() {
            let brands = [];
            let keyword = $('#search_text').val();

            $(".brand-label").each(function() {
                if ($(this).is(':checked')) {
                    brands.push($(this).val());
                }
            });

            {{-- console.log(brands); --}}
            let url = '{{ url()->current() }}?';

            // price range filter
            url += '&price_min=' + slider.result.from + '&price_max=' + slider.result.to;
            // brand filter
            if (brands.length > 0) {
                url += '&brand=' + brands.toString();
            }

            // search text
            if (keyword.length > 0) {
                url += '&search_text=' + keyword;
            }

            // sorting
            url += '&sort=' + $("#sort").val();

            window.location.href = url;
        }

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

        function updateWishlist(product_id) {
            let iconWistlist = $('#iconWishlist_' + product_id);

            $.ajax({
                url: '{{ route('user.wishlist-update') }}',
                type: 'post',
                dataType: 'json',
                data: {
                    product_id: product_id
                },
                success: (response) => {
                    if (response["status"]) {
                        if (response["icon"] == 'add') {
                            iconWistlist.removeClass('far').addClass('fas');
                        } else {
                            iconWistlist.removeClass('fas').addClass('far');
                        }
                        Swal.fire({
                            title: "Thành công!",
                            text: response["message"],
                            icon: "success"
                        });
                    } else {
                        Swal.fire({
                            title: "Lỗi!",
                            text: response["message"],
                            icon: "error"
                        });
                    }
                }
            })
        }
    </script>
@endsection
