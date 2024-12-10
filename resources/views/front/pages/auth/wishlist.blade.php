@extends('front.layouts.app')
@section('content')
    <main>
        <section class="section-5 pt-3 pb-3 mb-3 bg-white">
            <div class="container">
                <div class="light-font">
                    <ol class="breadcrumb primary-color mb-0">
                        <li class="breadcrumb-item"><a class="text-primary">Tài khoản</a></li>
                        <li class="breadcrumb-item">Yêu thích</li>
                    </ol>
                </div>
            </div>
        </section>

        <section class=" section-11 ">
            <div class="container">
                <div class="row">
                    <div class="col-md-9 mx-auto">
                        <div class="card">
                            <div class="card-body p-2">
                                {{-- show product in wishlist --}}
                                @if ($wishlists->isNotEmpty())
                                    @foreach ($wishlists as $wishlist)
                                        @php
                                            $product_image = getOneProductImage($wishlist->product_id);
                                        @endphp
                                        <div
                                            class="d-sm-flex justify-content-between mt-lg-4 mb-4 pb-3 pb-sm-2 border-bottom">
                                            <div class="d-block d-sm-flex align-items-start text-center text-sm-start"><a
                                                    class="d-block flex-shrink-0 mx-auto me-sm-4" href="#"
                                                    style="width: 10rem;"><img
                                                        src="{{ asset('uploads/products/large/' . $product_image->image) }}"
                                                        alt="Product"></a>
                                                <div class="pt-2">
                                                    <h3 class="product-title fs-base mb-2"><a
                                                            href="{{ route('product.product-details', ['slug' => $wishlist->product->slug]) }} ">{{ $wishlist->product->title }}</a>
                                                    </h3>
                                                    <div class="fs-lg text-accent pt-2">
                                                        {{ number_format($wishlist->product->price, 0, ',', '.') }}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="pt-2 ps-sm-3 mx-auto mx-sm-0 text-center">
                                                <a class="btn btn-outline-danger btn-sm" href="javascript:void(0)"
                                                    onclick="updateWishlist({{ $wishlist->product_id }})">
                                                    <i class="fas fa-trash-alt"></i>
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection

@section('js')
    <script>
        function updateWishlist(product_id) {
            const url = "{{ route('user.wishlist') }}";
            $.ajax({
                url: '{{ route('user.wishlist-update') }}',
                type: 'post',
                dataType: 'json',
                data: {
                    product_id: product_id
                },
                success: (response) => {
                    if (response["status"]) {
                        window.location.href = url;
                    }
                }
            })
        }
    </script>
@endsection
