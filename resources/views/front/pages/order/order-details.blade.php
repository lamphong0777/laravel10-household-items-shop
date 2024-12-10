@extends('front.layouts.app')
@section('content')
    <main>
        <section class="section-5 pt-3 pb-3 mb-3 bg-white">
            <div class="container">
                <div class="light-font">
                    <ol class="breadcrumb primary-color mb-0">
                        <li class="breadcrumb-item"><span class="text-primary">Tài khoản</span></li>
                        <li class="breadcrumb-item">Hóa đơn</li>
                        <li class="breadcrumb-item">Chi tiết hóa đơn</li>
                    </ol>
                </div>
            </div>
        </section>

        <section class=" section-11 ">
            <div class="container">
                <div class="row">
                    <div class="col-md-9 mx-auto">
                        <div class="card">
                            <div class="card-header bg-dark text-white d-flex justify-content-between">
                                <h2 class="h5 mb-0 pt-2 pb-2">Hóa đơn</h2>
                                @if($order->order_status == 'pending')
                                    <a href="javascript:void(0);" onclick="cancelOrder({{ $order->id }})">
                                        <button class="btn btn-warning">
                                            Hủy đơn
                                        </button>
                                    </a>
                                @endif
                            </div>

                            <div class="card-body pb-0">
                                <!-- Info -->
                                <div class="card card-sm">
                                    <div class="card-body bg-light mb-3">
                                        <div class="row">
                                            <div class="col-6 col-lg-3">
                                                <!-- Heading -->
                                                <h6 class="heading-xxxs text-muted">Mã hóa đơn:</h6>
                                                <!-- Text -->
                                                <p class="mb-lg-0 fs-sm fw-bold">
                                                    {{ $order->id }}
                                                </p>
                                            </div>
                                            <div class="col-6 col-lg-3">
                                                <!-- Heading -->
                                                <h6 class="heading-xxxs text-muted">Ngày giao hàng:</h6>
                                                <!-- Text -->
                                                <p class="mb-lg-0 fs-sm fw-bold">
                                                    <time datetime="2019-10-01">
                                                        N/A
                                                    </time>
                                                </p>
                                            </div>
                                            <div class="col-6 col-lg-3">
                                                <!-- Heading -->
                                                <h6 class="heading-xxxs text-muted">Trạng thái:</h6>
                                                <!-- Text -->
                                                <p class="mb-0 fs-sm fw-bold">
                                                    @if ($order->order_status == 'pending')
                                                        <span class="badge bg-warning">Chờ duyệt</span>
                                                    @elseif ($order->order_status == 'preparing')
                                                        <span class="badge bg-primary">Đang chuẩn bị</span>
                                                    @elseif ($order->order_status == 'shipped')
                                                        <span class="badge bg-primary">Đang vận chuyển</span>
                                                    @elseif ($order->order_status == 'delivered')
                                                        <span class="badge bg-success">Đã giao hàng</span>
                                                    @elseif ($order->order_status == 'failed')
                                                        <span class="badge bg-danger">Thanh toán bị hủy</span>
                                                    @else
                                                        <span class="badge bg-danger">Đã hủy</span>
                                                    @endif
                                                </p>
                                            </div>
                                            <div class="col-6 col-lg-3">
                                                <!-- Heading -->
                                                <h6 class="heading-xxxs text-muted">Tổng hóa đơn:</h6>
                                                <!-- Text -->
                                                <p class="mb-0 fs-sm fw-bold">
                                                    {{ number_format($order->grand_total, 0, ',', '.') }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card card-sm">
                                    <div class="card-body bg-light mb-3">
                                        <div class="row">
                                            <div class="col-6 col-lg-3">
                                                <!-- Heading -->
                                                <h6 class="heading-xxxs text-muted">Thanh toán:</h6>
                                                <!-- Text -->
                                                <p class="mb-lg-0 fs-sm fw-bold">
                                                    {{ $order->payment_method }}
                                                </p>
                                            </div>
                                            <div class="col-6 col-lg-3">
                                                <!-- Heading -->
                                                <h6 class="heading-xxxs text-muted">Ngày Thanh toán:</h6>
                                                <!-- Text -->
                                                <p class="mb-lg-0 fs-sm fw-bold">
                                                    @if ($order->paid_date != '')
                                                        <time datetime="2019-10-01">
                                                            {{ $order->paid_date }}
                                                        </time>
                                                    @else
                                                        <time datetime="2019-10-01">
                                                            N/A
                                                        </time>
                                                    @endif
                                                </p>
                                            </div>
                                            <div class="col-6 col-lg-3">
                                                <!-- Heading -->
                                                <h6 class="heading-xxxs text-muted">Tên khách hàng:</h6>
                                                <!-- Text -->
                                                <p class="mb-0 fs-sm fw-bold">
                                                    {{ $order->name }}
                                                </p>
                                            </div>
                                            <div class="col-6 col-lg-3">
                                                <!-- Heading -->
                                                <h6 class="heading-xxxs text-muted">Email / Sdt:</h6>
                                                <!-- Text -->
                                                <p class="mb-0 fs-sm fw-bold">
                                                    {{ $order->email }}
                                                </p>
                                                <p class="mb-0 fs-sm fw-bold">
                                                    {{ $order->phone }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card card-sm">
                                    <div class="card-body bg-light mb-3">
                                        <div class="row">
                                            <div class="col-6 col-lg-9">
                                                <!-- Heading -->
                                                <h6 class="heading-xxxs text-muted">Địa chỉ:</h6>
                                                <!-- Text -->
                                                <p class="mb-lg-0 fs-sm fw-bold">
                                                    {{ $order->address }}
                                                    , {{ $ward->name }}
                                                    , {{ $district->name }}
                                                    , Tỉnh/Tp: {{ $province->name }}.
                                                </p>
                                            </div>

                                            <div class="col-6 col-lg-3">
                                                <!-- Heading -->
                                                <h6 class="heading-xxxs text-muted">Ghi chú:</h6>
                                                <!-- Text -->
                                                <p class="mb-0 fs-sm fw-bold">
                                                    {{ $order->notes }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer p-3 bg-dark text-white">

                                <!-- Heading -->
                                <h6 class="mb-7 h5 mt-4">Sản phẩm ({{ $order_items->count() }})</h6>

                                <!-- Divider -->
                                <hr class="my-3">

                                <!-- List group -->
                                <ul>
                                    @if ($order_items->count() > 0)
                                        @foreach ($order_items as $order_item)
                                            <li class="list-group-item">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <div class="fs-sm fw-bold">
                                                        <span class="text-body">{{ $order_item->title }}
                                                            ({{ $order_item->qty }} x
                                                            {{ number_format($order_item->price, 0, ',', '.') }})
                                                        </span> <br>
                                                    </div>
                                                    <div class="text-muted">
                                                        {{ number_format($order_item->total, 0, ',', '.') }}
                                                    </div>
                                                </div>
                                            </li>
                                        @endforeach
                                    @endif
                                </ul>
                            </div>
                        </div>

                        <div class="card card-lg mb-5 mt-3">
                            <div class="card-body bg-dark text-white">
                                <!-- Heading -->
                                <h6 class="mt-0 mb-3 h5">Thành tiền</h6>

                                <!-- List group -->
                                <ul>
                                    <li class="list-group-item d-flex">
                                        <span>Hóa đơn</span>
                                        <span class="ms-auto">{{ number_format($order->subtotal, 0, ',', '.') }}</span>
                                    </li>
                                    <li class="list-group-item d-flex">
                                        <span>Phí vận chuyển</span>
                                        <span class="ms-auto">{{ number_format($order->shipping, 0, ',', '.') }}</span>
                                    </li>
                                    <li class="list-group-item d-flex">
                                        <span>Giảm giá</span>
                                        <span class="ms-auto">{{ number_format($order->discount, 0, ',', '.') }}</span>
                                    </li>
                                    <li class="list-group-item d-flex fs-lg fw-bold">
                                        <span>Tổng</span>
                                        <span class="ms-auto">{{ number_format($order->grand_total, 0, ',', '.') }}</span>
                                    </li>
                                </ul>
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
        function cancelOrder(id) {
            if(confirm('Bạn chắc chắn hủy đơn này. Mã đơn: '+id)){
                let url = '{{ route('user.cancel-order', 'ID') }}'
                let newUrl = url.replace('ID', id);

                $.ajax({
                    url: newUrl,
                    type: 'post',
                    data: {},
                    dataType: 'json',
                    success: (response) => {
                        if(response.status) {
                            window.location.href = '{{ route('user.my-order') }}';
                        } else {
                            Swal.fire({
                                icon: "error",
                                title: "Oops...",
                                text: "Hủy đơn thất bại, Hãy liên hệ với quản trị viên để được hỗ trợ!",
                            });
                        }
                    },
                    error: () => {
                        console.log('Some thing went wrong.');
                    }
                });
            }
        }
    </script>
@endsection
