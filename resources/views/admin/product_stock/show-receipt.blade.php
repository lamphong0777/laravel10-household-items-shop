@extends('admin.layouts.app')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid my-2">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Phiếu nhập: #{{ $receipt->id }}</h1>
                    </div>
                    <div class="col-sm-6 text-right">
                        <a href="{{ route('admin.product-stocks.receipt') }}" class="btn btn-primary">Trở về</a>
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
                    <div class="card col-md-12 mx-auto">
                        <div class="card-header pt-3">
                            <div class="row">
                                <div class="col-sm-12">
                                    <h1 class="h5 mb-3">Thông tin phiếu nhập (<b>Ngày
                                            nhập:</b> {{ \Carbon\Carbon::parse($receipt->import_date)->format('d-m-Y') }})
                                    </h1>
                                    <b>Mã phiếu: </b>#{{ $receipt->id }}<br>
                                    <b>Người lập phiếu: </b>{{ $receipt->staff->name }}<br>
                                    <b>Nhà cung cấp:</b> {{ $receipt->producer->name }}<br>
                                    <b>Giá nhập:</b> {{ number_format($receipt->total_price, 0, ',', '.') }}<br>
                                    <b>Ghi chú:</b> {{ $receipt->notes }}<br>
                                </div>
                            </div>
                        </div>
                        <div class="card-body table-responsive p-3">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>Sản phẩm</th>
                                    <th width="100">Giá nhập</th>
                                    <th width="100">Số lượng nhập</th>
                                    <th width="100">Số lượng còn lại</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($receipt->receiptDetails as $receiptItem)
                                    <tr>
                                        <td>{{ $receiptItem->product->title }}</td>
                                        <td>{{ number_format($receiptItem->import_price, 0, ',', '.') }}</td>
                                        <td>{{ $receiptItem->import_qty }}</td>
                                        <td>{{ $receiptItem->remaining_qty }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
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

    </script>
@endsection
