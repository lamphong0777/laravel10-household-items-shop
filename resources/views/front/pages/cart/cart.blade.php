@extends('front.layouts.app')
@section('title')
    Shopping cart
@endsection
@section('content')
    <main>
        <section class="section-5 pt-3 pb-3 mb-3 bg-white">
            <div class="container">
                <div class="light-font">
                    <ol class="breadcrumb primary-color mb-0">
                        <li class="breadcrumb-item"><a class="white-text" href="{{ route('home') }}">Trang chủ</a></li>
                        <li class="breadcrumb-item"><a class="white-text" href="{{ route('shop.shop-now') }}">Sản phẩm</a>
                        </li>
                        <li class="breadcrumb-item">Giỏ hàng</li>
                    </ol>
                </div>
            </div>
        </section>

        <section class=" section-9 pt-4">
            <div class="container">
                @if (Session::has('success'))
                    <div class="alert">
                        <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
                        <strong>Đặt hàng thành công! </strong> {{ Session::get('success') }}
                    </div>
                @elseif (Session::has('order_cancelled'))
                    <div class="tn-box tn-box-color-1 tn-box-active alert-success">
                        <p class="text-center fs-5 fw-bold"><i class="fa-regular fa-circle-check"></i> Đơn hàng đã được hủy!
                        </p>
                        <div class="tn-progress"></div>
                    </div>
                @elseif (Session::has('error'))
                    <div class="alert alert-warning">
                        <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
                        <strong>Error! </strong> {{ Session::get('error') }}
                    </div>
                @endif
                <div class="row">
                    @if ($cartContent->count() > 0)
                        <div class="col-md-8">
                            <div class="table-responsive">

                                <table class="table bg-light" id="cart">
                                    <thead>
                                        <tr>
                                            <th class="text-start">Sản phẩm</th>
                                            <th>Giá</th>
                                            <th>Số lượng</th>
                                            <th>Tổng</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($cartContent as $cartItem)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center justify-content-start text-start">
                                                    <img src="{{ asset('uploads/products/large/' . $cartItem->image) }}" alt="pictures" width="" height="">
                                                    <h2 class="{{ $cartItem->product->qty < $cartItem->qty ? 'text-danger' : '' }}">
                                                        {{ $cartItem->title }}
                                                        ({{ $cartItem->product->qty }} còn lại)
                                                    </h2>
                                                </div>
                                            </td>
                                            <td><span class="fs-7">{{ number_format($cartItem->price, 0, ',', '.') }}</span></td>
                                            <td class="">
                                                <div class="input-group quantity mx-auto" style="width: 140px;">
                                                    <div class="input-group-btn">
                                                        <button class="btn btn-sm btn-dark btn-minus p-3 pt-1 pb-1 rounded-pill sub"
                                                            {{ $cartItem->product->qty < $cartItem->qty ? 'disabled' : '' }}>
                                                            <i class="fa fa-minus"></i>
                                                        </button>
                                                    </div>
                                                    <input type="text" onchange="changeQty({{ $cartItem->id }})"
                                                           name="cart_qty" id="{{ $cartItem->id }}"
                                                           class="form-control form-control-sm border-0 text-center"
                                                           value="{{ $cartItem->qty }}"
                                                        {{ $cartItem->product->qty < $cartItem->qty ? 'disabled' : '' }}>
                                                    <div class="input-group-btn">
                                                        <button class="btn btn-sm btn-dark btn-plus p-3 pt-1 pb-1 rounded-pill add"
                                                            {{ $cartItem->product->qty < $cartItem->qty ? 'disabled' : '' }}>
                                                            <i class="fa fa-plus"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="fw-bold">{{ number_format($cartItem->price * $cartItem->qty, 0, ',', '.') }}</td>
                                            <td>
                                                <a href="{{ route('shopping.cart.remove', ['rowId' => $cartItem->id]) }}" class="fs-7 mt-3 text-danger">
                                                    <i class="fas fa-trash-alt"></i> xóa
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>

                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card cart-summery bg-light">
                                <div class="sub-title">
                                    <h2 class="">Thành tiền</h2>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex justify-content-between pb-2">
                                        <div class="fs-6">Tạm tính</div>
                                        <div class="fs-6">{{ number_format($total, 0, ',', '.') }}</div>
                                    </div>
                                    {{--                                    <div class="d-flex justify-content-between pb-2"> --}}
                                    {{--                                        <div class="fs-6">Phí vận chuyển</div> --}}
                                    {{--                                        <div> --}}
                                    {{--                                            <p class="fs-6 text-info">0 vnd</p> --}}
                                    {{--                                        </div> --}}
                                    {{--                                    </div> --}}
                                    <div class="d-flex justify-content-between summery-end">
                                        <div>Tổng</div>
                                        <div><strong></strong>{{ number_format($total, 0, ',', '.') }}</div>
                                    </div>
{{--                                    <div class="pt-5">--}}
{{--                                        <a href="{{ route('shop.checkout') }}" class="btn-dark btn btn-block w-100">Thanh--}}
{{--                                            toán</a>--}}
{{--                                    </div>--}}
                                    <div class="pt-5">
                                        @php
                                            $disableCheckout = $cartContent->filter(fn($cartItem) => $cartItem->product->qty < $cartItem->qty)->isNotEmpty();
                                        @endphp
                                        <a href="{{ route('shop.checkout') }}"
                                           class="btn btn-dark btn-block w-100 {{ $disableCheckout ? 'disabled' : '' }}"
                                           style="{{ $disableCheckout ? 'background-color: #ff0000; cursor: not-allowed;' : '' }}">
                                            Thanh toán
                                        </a>
                                        @if ($disableCheckout)
                                            <p class="text-danger text-center mt-2">Không thể thanh toán vì có sản phẩm vượt quá số lượng tồn kho.</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>
            @else
                <h2 class="text-center mb-3"><i class="fas fa-shopping-cart"></i> Giỏ hàng trống</h2>
                <a href="{{ route('home') }}" class="h3 text-center">---Trang chủ---</a>
                @endif
            </div>
        </section>

    </main>
@endsection

@section('js')
    <script>
        $('.add').click(function() {
            let qtyElement = $(this).parent().prev(); // Qty input
            let qtyValue = parseInt(qtyElement.val());
            let id = qtyElement[0].id;
            if (qtyValue < 10) {
                qtyElement.val(qtyValue + 1);
                let qty_new = $(`#${id}`).val();
                // console.log(qty_current);
                updateCart(id, qty_new);
            }
        })

        $('.sub').click(function() {
            let qtyElement = $(this).parent().next();
            let qtyValue = parseInt(qtyElement.val());
            let id = qtyElement[0].id;
            if (qtyValue > 1) {
                qtyElement.val(qtyValue - 1);
                let qty_new = $(`#${id}`).val();
                // console.log(qty_current);
                updateCart(id, qty_new);
            }
        })

        function updateCart(rowId, qty) {
            $.ajax({
                url: '{{ route('shopping.cart.update') }}',
                type: 'post',
                data: {
                    rowId: rowId,
                    qty: qty
                },
                dataType: 'json',
                success: function(response) {
                    if (response['status']) {
                        $(".add").prop('disabled', true);
                        $(".sub").prop('disabled', true);
                        window.location.href = '{{ route('shopping.cart') }}'
                    } else {
                        alert('Số lượng đã đạt tối đa');
                        window.location.href = '{{ route('shopping.cart') }}';
                    }
                },
            })
        }

        changeQty = (id) => {
            let element = $(`#${id}`);
            updateCart(id, element.val());
        }
    </script>
@endsection
