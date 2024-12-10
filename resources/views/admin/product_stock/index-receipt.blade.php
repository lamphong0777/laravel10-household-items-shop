@extends('admin.layouts.app')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid my-2">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Quản lý phiếu nhập</h1>
                    </div>
                    <div class="col-sm-6 text-right">
                        <a href="{{ route('admin.product-stocks.receipt.create') }}" class="btn btn-primary">Nhập sản phẩm</a>
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
                            <button onclick="window.location.href='{{ route('admin.product-stocks.receipt') }}'"
                                    class="btn btn-primary btn-sm"><i class="fas fa-sync-alt"></i>
                            </button>
                        </div>
                        <div class="card-tools">
                            <form action="{{ route('admin.product-stocks.receipt') }}" method="get">
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
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover text-nowrap">
                            <thead>
                            <tr>
                                <th width="60">ID</th>
                                <th>Nhân viên lập phiếu</th>
                                <th>Ngày nhập</th>
                                <th>Ghi chú</th>
                                <th width="100">Hành động</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if ($goodsReceipts->count() > 0)
                                @foreach ($goodsReceipts as $goodsReceipt)
                                    <tr>
                                        <td>{{ $goodsReceipt->id }}</td>
                                        <td>{{ $goodsReceipt->staff->name }}</td>
                                        <td>{{ date("d-m-Y", strtotime($goodsReceipt->import_date)) }}</td>
                                        <td>{{ $goodsReceipt->notes }}</td>
                                        <td class="text-center"><a href="{{ route('admin.product-stocks.receipt.details', $goodsReceipt->id) }}">
                                                <button class="btn-sm btn-info"><i class="fas fa-eye"></i></button>
                                            </a></td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="5">Không có bản ghi nào!</td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer clearfix">
                        <ul class="pagination pagination m-0 float-right">
                            {{ $goodsReceipts->appends($_GET)->links() }}
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
    <script src="{{ asset('admin-assets/js/custom.js') }}"></script>
@endsection
