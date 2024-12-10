@extends('admin.layouts.app')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid my-2">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Quản lý đơn hàng</h1>
                    </div>
                    <div class="col-sm-6 text-right">
                    </div>
                </div>
            </div>
            <!-- /.container-fluid -->
        </section>
        <!-- Main content -->
        <section class="content">
            <!-- Default box -->
            <div class="container-fluid">
                @include('admin.message')
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">
                            <button onclick="window.location.href='{{ route('admin.orders.index') }}'"
                                    class="btn btn-primary btn-sm"><i class="fas fa-sync-alt"></i>
                            </button>
                        </div>
                        <div class="card-tools">
                            <div class="d-flex justify-content-between align-items-center">
                                <!-- Lọc theo ngày -->
                                <div class="mr-3">
                                    <form action="{{ route('admin.orders.index') }}" method="get">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <input type="date" name="start_date" class="form-control mr-1" value="{{ Request::get('start_date') }}">
                                            <input type="date" name="end_date" class="form-control mr-1" value="{{ Request::get('end_date') }}">
                                            <button type="submit" class="btn btn-default">
                                                <i class="fas fa-filter"></i>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                                <div class="mr-3">
                                    <select name="sort_order" id="sort_order" class="form-control">
                                        <option value="all" @if($sort_order === 'all') selected @endif>Tất cả</option>
                                        <option value="pending" @if($sort_order === 'pending') selected @endif>Chờ duyệt</option>
                                        <option value="preparing" @if($sort_order === 'preparing') selected @endif>Đang chuẩn bị hàng</option>
                                        <option value="shipped" @if($sort_order === 'shipped') selected @endif>Đang vận chuyển</option>
                                        <option value="delivered" @if($sort_order === 'delivered') selected @endif>Đã giao hàng</option>
                                        <option value="cancelled" @if($sort_order === 'cancelled') selected @endif>Đã hủy</option>
                                        <option value="failed" @if($sort_order === 'failed') selected @endif>Thanh toán bị hủy</option>
                                    </select>
                                </div>
                                <div class="">
                                    <form action="{{ route('admin.orders.index') }}" method="get">
                                        <div class="input-group input-group" style="width: 250px;">

                                            <input type="text" name="searchText" class="form-control float-right"
                                                   placeholder="Tìm kiếm..." value="{{ Request::get('searchText') }}">

                                            <div class="input-group-append">
                                                <button type="submit" class="btn btn-default">
                                                    <i class="fas fa-search"></i>
                                                </button>
                                            </div>

                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover text-nowrap">
                            <thead>
                            <tr>
                                <th>Mã đơn hàng #</th>
                                <th>Khách hàng</th>
                                <th>Email</th>
                                <th>Điện thoại</th>
                                <th>Trạng thái</th>
                                <th>Tổng tiền</th>
                                <th>Ngày đặt hàng</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if ($orders->isNotEmpty())
                                @foreach ($orders as $order)
                                    <tr>
                                        <td><a
                                                href="{{ route('admin.orders.show', ['id' => $order->id]) }}">{{ $order->id }}</a>
                                        </td>
                                        <td>{{ $order->name }}</td>
                                        <td>{{ $order->email }}</td>
                                        <td>{{ $order->phone }}</td>
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
                                        <td>{{ number_format($order->subtotal, 0, ',', '.') }}</td>
                                        <td>{{ date_format($order->created_at, 'd-m-Y') }}</td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td>Không có đơn hàng nào.</td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer clearfix">
                        <ul class="pagination pagination m-0 float-right">
                            {{ $orders->appends($_GET)->links() }}
                        </ul>
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
        $("#sort_order").change(function () {
           let url = '{{ url()->current() }}?';
           // Url + sort_order=
            const sort_order = $("#sort_order").val();
            url += 'sort_order=' + sort_order;
            window.location.href = url;
        });
    </script>
@endsection
