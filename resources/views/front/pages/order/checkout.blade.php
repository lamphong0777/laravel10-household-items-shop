@extends('front.layouts.app')
@section('content')
    <main>
        <section class="section-5 pt-3 pb-3 mb-3 bg-white">
            <div class="container">
                <div class="light-font">
                    <ol class="breadcrumb primary-color mb-0">
                        <li class="breadcrumb-item"><a class="white-text" href="{{ route('home') }}">Trang chủ</a></li>
                        <li class="breadcrumb-item"><a class="white-text" href="{{ route('shop.shop-now') }}">Sản
                                phẩm</a></li>
                        <li class="breadcrumb-item">Thanh toán</li>
                    </ol>
                </div>
            </div>
        </section>

        <section class="section-9 pt-4">
            <div class="container">
                <!-- check out form starts -->
                {{-- <form action="" method="" name="checkoutForm" id="checkoutForm"> --}}
                <form action="{{ route('shop.checkout.process') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-4">
                            <div class="sub-title">
                                <h2>Thông tin mua hàng</h2>
                            </div>
                            <div class="card shadow-lg border-0">
                                <div class="card-body checkout-form">
                                    <div class="row">

                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <input type="text" name="name" id="name"
                                                    class="form-control @error('name') is-invalid @enderror"
                                                    placeholder="Họ tên khách hàng (*)"
                                                    value="{{ old('name', $customerAddress ? $customerAddress->name : '') }}">
                                                @error('name')
                                                    <p class="error invalid-feedback">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <input type="text" name="email" id="email"
                                                    class="form-control @error('email') is-invalid @enderror"
                                                    placeholder="Email"
                                                    value="{{ old('email', $customerAddress ? $customerAddress->email : '') }}">
                                                @error('email')
                                                    <p class="error invalid-feedback">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <select name="province" id="province"
                                                    class="form-control @error('province') is-invalid @enderror">
                                                    <option value="">- Tỉnh thành -</option>
                                                    @foreach ($provinces as $province)
                                                        <option value="{{ $province->id }}"
                                                            @if ($customerAddress) @if ($province->id == $customerAddress->province_id) selected @endif
                                                            @endif>{{ $province->name }}</option>
                                                    @endforeach
                                                </select>
                                                <p class="fs-7"></p>
                                                @error('province')
                                                    <p class="error invalid-feedback">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <select name="district" id="district"
                                                    class="form-control @error('district') is-invalid @enderror">
                                                    <option value="">- Quận/huyện -</option>
                                                    @if (isset($districts))
                                                        @foreach ($districts as $district)
                                                            <option value="{{ $district->id }}"
                                                                @if ($customerAddress->district_id == $district->id) selected @endif>
                                                                {{ $district->name }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                                @error('district')
                                                    <p class="error invalid-feedback">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <select name="ward" id="ward"
                                                    class="form-control @error('ward') is-invalid @enderror">
                                                    <option value="">- Xã/phường -</option>
                                                    @if (isset($wards))
                                                        @foreach ($wards as $ward)
                                                            <option value="{{ $ward->id }}"
                                                                @if ($customerAddress->ward_id == $ward->id) selected @endif>
                                                                {{ $ward->name }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                                @error('ward')
                                                    <p class="error invalid-feedback">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <textarea name="address" id="address" cols="30" rows="3" placeholder="địa chỉ (tùy chọn)"
                                                    class="form-control @error('address') is-invalid @enderror">{{ $customerAddress ? $customerAddress->address : '' }}</textarea>
                                                @error('address')
                                                    <p class="error invalid-feedback">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <input type="text" name="phone" id="phone"
                                                    class="form-control @error('phone') is-invalid @enderror"
                                                    placeholder="Điện thoại (*)"
                                                    value="{{ old('phone', $customerAddress ? $customerAddress->phone : '') }}">
                                                @error('phone')
                                                    <p class="error invalid-feedback">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <textarea name="order_notes" id="order_notes" cols="30" rows="2" placeholder="Ghi chú (tùy chọn)"
                                                    class="form-control @error('order_notes') is-invalid @enderror"></textarea>
                                                @error('order_notes')
                                                    <p class="error invalid-feedback">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="sub-title">
                                <h2>Thanh toán</h2>
                            </div>
                            <div class="card payment-form">
                                <div class="card-body p-0">
                                    <div class="row mb-2 d-flex justify-content-between align-items-center">
                                        <input type="radio" class="col-1" name="payment_method" checked value="cod">
                                        <label for="payment_method" class="col-8">Thanh toán khi nhận hàng</label>
                                        <span class="col-3"><i
                                                class="fa-solid fa-truck-fast fs-3 ps-2 text-primary"></i></span>
                                    </div>
                                    <hr>
                                    <div class="row d-flex justify-content-between align-items-center">
                                        <input type="radio" class="col-1" name="payment_method" value="vn_pay">
                                        <label for="payment_method" class="col-8">Thanh toán qua VN_pay</label>
                                        <span class="col-3"><img src="{{ asset('fe-assets/images/vn_pay.png') }}"
                                                alt=""></span>
                                    </div>
                                    <hr>
                                    <div class="row d-flex justify-content-between align-items-center">
                                        <input type="radio" class="col-1" name="payment_method" value="momo">
                                        <label for="payment_method" class="col-8">Thanh toán qua MOMO</label>
                                        <span class="col-3"><img src="{{ asset('fe-assets/images/momo.png') }}"
                                                alt=""></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="sub-title">
                                <h2>Đơn hàng ( {{ $cartContent->count() }} sản phẩm )</h2>
                            </div>
                            <div class="card cart-summery">
                                <div class="card-body p-0">
                                    @if (!empty($cartContent))
                                        @foreach ($cartContent as $cartItem)
                                            <div class="d-flex justify-content-between pb-2 pr-0">
                                                <div class="row align-items-center border-bottom">
                                                    <div class="col-2">
                                                        <img src="{{ asset('uploads/products/large/' . $cartItem->image) }}"
                                                            alt="">
                                                    </div>
                                                    <div class="col-10">
                                                        <div class="row">
                                                            <p class="m-0 p-0 fs-6 checkout-cart-title">
                                                                {{ $cartItem->title }}</p>
                                                        </div>
                                                        <div class="row justify-content-between">
                                                            <div class="m-0 p-0 fs-7 col">{{ $cartItem->qty }} x
                                                                {{ number_format($cartItem->price, 0, ',', '.') }}</div>
                                                            <div class="h6 col text-end fw-bolder">
                                                                {{ number_format($cartItem->price * $cartItem->qty, 0, ',', '.') }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        @endforeach
                                    @endif
                                    {{-- subtotal --}}
                                    <div class="d-flex justify-content-between summery-end">
                                        <div class="h6"><strong>Tạm tính</strong></div>
                                        <div class="h6"><strong>{{ number_format($total, 0, ',', '.') }}</strong>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between mt-2">
                                        <div class="h6"><strong>Phí vận chuyển</strong></div>
                                        <div class="h6 text-primary"><strong
                                                id="shipping_cost">{{ number_format($shipping_cost, 0, ',', '.') }}</strong>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between mt-2">
                                        <div class="h6"><strong>Giảm giá</strong></div>
                                        <div class="h6 text-success"><strong id="discount_value_show">0</strong>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between mt-2 summery-end">
                                        <div class="h5"><strong>Tổng cộng</strong></div>
                                        <div class="h5"><strong
                                                id="grand_total_show">{{ number_format($grand_total, 0, ',', '.') }}</strong>
                                        </div>
                                    </div>
                                </div>

                                <div class="input-group apply-coupon mt-4">
                                    <input type="text" name="discount_code" placeholder="Mã giảm giá"
                                        class="form-control" id="discount_code">
                                    <button class="btn btn-secondary" type="button" id="apply_discount">Áp dụng
                                    </button>
                                    <p class="fs-7"></p>
                                </div>

                                <div class="pt-4">
                                    <button type="submit" id="payUrl" name="payUrl"
                                        class="btn-dark btn btn-block w-100">Đặt
                                        hàng
                                    </button>
                                </div>
                                <div class="pt-4">
                                    <a href="{{ route('shopping.cart') }}" class="btn-secondary btn btn-block w-100">Quay
                                        về giỏ hàng</a>
                                </div>
                            </div>
                            <!-- CREDIT CARD FORM ENDS HERE -->
                        </div>
                    </div>
                </form>
                <!-- check out form ends -->
            </div>
        </section>
    </main>
@endsection

@section('js')
    <script>
        $("#province").change(function() {
            let province_id = $(this).val();
            $("#discount_value_show").html(0);
            $("#discount_code").val("");
            $.ajax({
                url: '{{ route('shop.district') }}',
                type: 'get',
                dataType: 'json',
                data: {
                    province_id: province_id
                },
                success: (response) => {
                    $("#district").find("option").not(":first").remove();
                    $("#ward").find("option").not(":first").remove();
                    $.each(response["districts"], (key, item) => {
                        $("#district").append(
                            `<option value="${item.id}">${item.name}</option>`)
                    });
                },
                error: () => {
                    console.log('Something went wrong!');
                }
            });

            $.ajax({
                url: '{{ route('shop.shipping') }}',
                type: 'get',
                dataType: 'json',
                data: {
                    province_id: province_id
                },
                success: (response) => {
                    if (response["status"]) {
                        let $shipping = $('#shipping_cost');
                        let $grand_total_show = $('#grand_total_show');

                        $shipping.html(new Intl.NumberFormat('de-DE', {
                            currency: 'EUR'
                        }).format(response['shipping_cost']));
                        $grand_total_show.html(new Intl.NumberFormat('de-DE', {
                            currency: 'EUR'
                        }).format(response["grand_total"]));

                    }
                },
                error: () => {
                    console.log('Something went wrong!');
                }
            });

        });


        $("#district").change(function() {
            let district_id = $(this).val();

            $.ajax({
                url: '{{ route('shop.ward') }}',
                type: 'get',
                dataType: 'json',
                data: {
                    district_id: district_id
                },
                success: (response) => {
                    $("#ward").find("option").not(":first").remove();
                    $.each(response["wards"], (key, item) => {
                        $("#ward").append(`<option value="${item.id}">${item.name}</option>`)
                    });
                },
                error: () => {
                    console.log('Something went wrong!');
                }
            });
        });

        // apply discount
        $("#apply_discount").click(function() {
            // let discount_code =  '';
            let code = $('#discount_code').val();
            // if(code !== ''){
            //     discount_code = $("#discount_code").val();
            // }
            $.ajax({
                url: '{{ route('shop.apply-discount') }}',
                type: 'post',
                data: {
                    discount_code: code,
                    province_id: $("#province").val()
                },
                dataType: 'json',
                success: (response) => {
                    let discountCode = $("#discount_code");
                    let provinceId = $("#province");

                    // show new grand total and discount value
                    const discountValueShow = response["discountValueShow"];
                    const grandTotal = response["grandTotal"];

                    discountCode.removeClass("is-invalid")
                        .siblings("p").removeClass("invalid-feedback")
                        .html('');
                    provinceId.removeClass("is-invalid")
                        .siblings("p").removeClass("invalid-feedback")
                        .html('');

                    if (response["status"]) {
                        $("#discount_value_show").html("-" + new Intl.NumberFormat('de-DE', {
                            currency: 'EUR'
                        }).format(discountValueShow));
                        $("#grand_total_show").html(new Intl.NumberFormat('de-DE', {
                            currency: 'EUR'
                        }).format(grandTotal));
                    } else {
                        let code_message = response["code_message"];
                        if (code_message) {
                            discountCode.addClass("is-invalid")
                                .siblings("p").addClass("invalid-feedback")
                                .html(response["code_message"]);
                            discountCode.val('');
                        } else {
                            let errors = response["errors"];
                            if (errors["discount_code"]) {
                                discountCode.addClass("is-invalid")
                                    .siblings("p").addClass("invalid-feedback")
                                    .html(errors["discount_code"]);
                            }
                            if (errors["province_id"]) {
                                provinceId.addClass("is-invalid")
                                    .siblings("p").addClass("invalid-feedback")
                                    .html(errors["province_id"]);
                            }
                        }
                    }
                },
                error: () => {
                    console.log('Some thing went wrong!')
                }
            });
        });
    </script>
@endsection
