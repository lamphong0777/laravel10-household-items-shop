@extends('front.layouts.app')
@section('content')
    <main>
        <section class="section-5 pt-3 pb-3 mb-3 bg-white">
            <div class="container">
                <div class="light-font">
                    <ol class="breadcrumb primary-color mb-0">
                        <li class="breadcrumb-item"><span class="text-primary">Tài khoản</span></li>
                        <li class="breadcrumb-item">Hóa đơn</li>
                    </ol>
                </div>
            </div>
        </section>

        <section class=" section-11 ">
            <div class="container">
                <div class="row">
                    <div class="col-md-9 mx-auto">
                        @if (Session::has('success'))
                            <div class="alert">
                                <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
                                <strong>Đã hủy đơn thành công! </strong> {{ Session::get('success') }}
                            </div>
                        @endif
                        <div class="card">
                            <div class="card-header bg-dark text-primary">
                                <h2 class="h5 mb-0 pt-2 pb-2">Hóa đơn của tôi</h2>
                            </div>
                            <div class="card-body p-4">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Mã hóa đơn #</th>
                                                <th>Ngày đặt</th>
                                                <th>Trạng thái</th>
                                                <th>Tổng tiền</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if ($orders->isNotEmpty())
                                                @foreach ($orders as $order)
                                                    <tr>
                                                        <td>
                                                            <a class="text-primary"
                                                                href="{{ route('user.order-details', ['id' => $order->id]) }}">
                                                                {{ $order->id }}
                                                            </a>
                                                        </td>
                                                        <td>{{ date('d-m-Y H:i:s', strtotime($order->created_at)) }}</td>
                                                        <td>
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
                                                        </td>
                                                        <td><strong>{{ number_format($order->grand_total, 0, ',', '.') }}</strong>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td>Bạn chưa có đơn hàng nào</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                    <div>
                                        <ul class="align-items-center justify-content-center d-flex">
                                            {{ $orders->appends($_GET)->links() }}
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
