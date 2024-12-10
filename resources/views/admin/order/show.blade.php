@extends('admin.layouts.app')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid my-2">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Hóa đơn: #{{ $order->id }} - Ngày đặt: {{ date_format($order->created_at, 'd/m/Y') }}</h1>
                    </div>
                    <div class="col-sm-6 text-right">
                        <a href="{{ route('admin.orders.index') }}" class="btn btn-primary">Trở về</a>
                    </div>
                </div>
            </div>
            <!-- /.container-fluid -->
        </section>
        <!-- Main content -->
        <section class="content">
            <!-- Default box -->
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header pt-3">
                                <div class="row invoice-info">
                                    <div class="col-sm-6 invoice-col">
                                        <h1 class="h5 mb-3">Thông tin khách hàng</h1>
                                        <address>
                                            <strong>{{ $order->name }}</strong><br>
                                            {{ $order->address }}<br>
                                            {{ $ward->name }}, {{ $district->name }}, Tỉnh/Tp: {{ $province->name }}<br>
                                            <strong>Điện thoại:</strong> {{ $order->phone }}<br>
                                            <strong>Email:</strong> {{ $order->email }}
                                        </address>
                                    </div>

                                    <div class="col-sm-4 invoice-col">
                                        <h1 class="h5 mb-3">Hóa đơn</h1>
                                        <b>Mã hóa đơn: </b>#{{ $order->id }}<br>
                                        <b>Tổng:</b> {{ number_format($order->grand_total, 0, ',', '.') }}<br>
                                        <b>Thanh toán:</b> {{ $order->payment_method }}<br>
                                        <b>Ngày thanh toán:</b>
                                        {{ $order->paid_date ? \Carbon\Carbon::parse($order->paid_date)->format('d/m/Y') : 'N/A' }}<br>
                                        <b>Trạng thái:</b>
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
                                        <br>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body table-responsive p-3">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Sản phẩm</th>
                                            <th width="100">Giá bán</th>
                                            <th width="100">Số lượng</th>
                                            <th width="100">Thành tiền</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($order_items as $order_item)
                                            <tr>
                                                <td>{{ $order_item->title }}</td>
                                                <td>{{ number_format($order_item->price, 0, ',', '.') }}</td>
                                                <td>{{ $order_item->qty }}</td>
                                                <td>{{ number_format($order_item->total, 0, ',', '.') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <form action="" method="post" id="updateOrderForm">
                                    @csrf
                                    <h2 class="h4 mb-3">Trạng thái đơn hàng</h2>
                                    <div class="mb-3">
                                        <select name="status" id="status" class="form-control">
                                            <option value="pending" @if ($order->order_status == 'pending') selected @endif>
                                                Chờ duyệt
                                            </option>
                                            <option value="preparing" @if ($order->order_status == 'preparing') selected @endif>
                                                Đang chuẩn bị
                                            </option>
                                            <option value="shipped" @if ($order->order_status == 'shipped') selected @endif>
                                                Đang giao hàng
                                            </option>
                                            <option value="delivered" @if ($order->order_status == 'delivered') selected @endif>
                                                Đã giao hàng
                                            </option>
                                            <option value="cancelled" @if ($order->order_status == 'cancelled') selected @endif>
                                                Hủy đơn
                                            </option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="shipped_date">Ngày vận chuyển</label>
                                        <input type="text" class="form-control" name="shipped_date" id="shipped_date"
                                            value="{{ $order->shipped_date }}">
                                        <p></p>
                                    </div>
                                    <div class="mb-3">
                                        <label for="delivered_date">Ngày giao hàng</label>
                                        <input type="text" class="form-control" name="delivered_date" id="delivered_date"
                                            value="{{ $order->delivered_date }}">
                                        <p></p>
                                    </div>
                                    @if ($order->payment_method == 'cod')
                                        <div class="mb-3">
                                            <label for="paid_date">Ngày thanh toán</label>
                                            <input type="text" class="form-control" name="paid_date" id="paid_date"
                                                value="{{ $order->paid_date }}">
                                            <p></p>
                                        </div>
                                    @endif
                                    <div class="mb-3">
                                        @if ($order->order_status == 'cancelled' || $order->order_status == 'failed')
                                            <button class="btn btn-primary w-100" disabled>Cập nhật</button>
                                        @else
                                            <button class="btn btn-primary w-100" type="submit">Cập nhật</button>
                                        @endif
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <h2 class="h4 mb-3">Gửi thông tin hóa đơn</h2>
                                <form action="" id="sendOrderEmailForm" name="sendOrderEmailForm">
                                    <div class="mb-3">
                                        <select name="user_type" id="user_type" class="form-control">
                                            <option value="customer">Khách hàng</option>
                                            <option value="admin">Quản trị viên</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <button class="btn btn-success w-100" type="submit">Gửi</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.card -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection
@section('js')
    <script>
        $(document).ready(function() {
            $('#shipped_date').datetimepicker({
                // options here
                format: 'Y-m-d H:i:s',
            });
            $('#paid_date').datetimepicker({
                // options here
                format: 'Y-m-d H:i:s',
            });
            $('#delivered_date').datetimepicker({
                // options here
                format: 'Y-m-d H:i:s',
            });
        });

        // handle post form update order status
        $('#updateOrderForm').submit(function(e) {
            e.preventDefault();
            let element = $(this);
            let shipped_date = $('#shipped_date');
            let paid_date = $('#paid_date');
            let delivered_date = $('#delivered_date');
            $.ajax({
                url: '{{ route('admin.orders.update', ['id' => $order->id]) }}',
                type: 'put',
                dataType: 'json',
                data: element.serializeArray(),
                success: function(response) {
                    if (response["status"]) {
                        window.location.href = '{{ route('admin.orders.index') }}';
                    } else {
                        const errors = response["errors"];
                        if (errors["shipped_date"]) {
                            shipped_date.addClass('is-invalid').siblings('p').addClass(
                                'invalid-feedback').html(errors["shipped_date"]);
                        } else {
                            shipped_date.removeClass('is-invalid').siblings('p').removeClass(
                                'invalid-feedback').html('');
                        }

                        if (errors["paid_date"]) {
                            paid_date.addClass('is-invalid').siblings('p').addClass(
                                'invalid-feedback').html(errors["paid_date"]);
                        } else {
                            paid_date.removeClass('is-invalid').siblings('p').removeClass(
                                'invalid-feedback').html('');
                        }

                        if (errors["delivered_date"]) {
                            delivered_date.addClass('is-invalid').siblings('p').addClass(
                                'invalid-feedback').html(errors["delivered_date"]);
                        } else {
                            delivered_date.removeClass('is-invalid').siblings('p').removeClass(
                                'invalid-feedback').html('');
                        }
                    }
                },
                error: function() {
                    console.log('Some thing went wrong!');
                }
            })
        });


        $("#sendOrderEmailForm").submit(function(e) {
            e.preventDefault();
            let data = $(this).serializeArray();
            $("button[type=submit]").prop('disabled', true);
            $.ajax({
                url: '{{ route('admin.orders.send', $order->id) }}',
                type: 'post',
                dataType: 'json',
                data: data,
                success: (response) => {
                    $("button[type=submit]").prop('disabled', false);
                    if (response["status"]) {
                        Swal.fire({
                            title: "Thành công!",
                            text: "Hóa đơn đã được gửi!",
                            icon: "success"
                        });
                    } else {
                        Swal.fire({
                            icon: "error",
                            title: "Thất bại!",
                            text: "Hóa được gửi thất bại!",
                        });
                    }
                },
                error: () => {
                    console.log('Some thing went wrong!');
                }
            })
        });
    </script>
@endsection
