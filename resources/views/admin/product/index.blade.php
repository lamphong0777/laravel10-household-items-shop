@extends('admin.layouts.app')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid my-2">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Quản lý sản phẩm</h1>
                    </div>
                    <div class="col-sm-6 text-right">
                        <a href="{{ route('admin.products.create') }}" class="btn btn-primary">Thêm</a>
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
                            <button onclick="window.location.href='{{ route('admin.products.index') }}'"
                                    class="btn btn-primary btn-sm"><i class="fas fa-sync-alt"></i>
                            </button>
                        </div>
                        <div class="card-tools">
                            <form action="{{ route('admin.products.index') }}" method="get">
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
                                <th width="80">Ảnh</th>
                                <th>Tên sản phẩm</th>
                                <th>Giá bán</th>
                                <th>Số lượng</th>
                                <th>SKU</th>
                                <th width="100">Trạng thái</th>
                                <th width="100">Hành động</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if (!empty($products))
                                @foreach ($products as $product)
                                    <tr>
                                        <td>{{ $product->id }}</td>
                                        <td>
                                            @if (!empty($product->product_images->first()))
                                                <img
                                                    src="{{ asset('/uploads/products/large/' . $product->product_images->first()->image) }}"
                                                    class="img-thumbnail" width="50">
                                            @else
                                                <img src="{{ asset('admin-assets/img/default-150x150.png') }}"
                                                     class="img-thumbnail" width="50">
                                            @endif
                                        </td>
                                        <td class="text-primary">{{ $product->title }}</td>
                                        <td>{{ number_format($product->price, 0, ',', '.') }}</td>
                                        <td>{{ $product->qty }} còn lại</td>
                                        <td>{{ $product->sku }}</td>
                                        <td>
                                            @if ($product->status)
                                                <button class="btn btn-sm btn-success"><i class="fas fa-check-circle"></i>
                                                </button>
                                            @else
                                                <button class="btn btn-sm btn-danger"><i class="fas fa-ban"></i></button>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.products.edit', ['id' => $product->id]) }}"
                                               class="btn btn-sm btn-info">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="javascript:void(0);" onclick="deleteProduct({{ $product->id }})"
                                               class="btn btn-sm
                                                    btn-danger"><i
                                                    class="fas fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td>Product not found!</td>
                                </tr>
                            @endif

                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer clearfix">
                        {{ $products->appends($_GET)->links() }}
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
        function deleteProduct(id) {
            let url = '{{ route('admin.products.destroy', 'ID') }}'
            let newUrl = url.replace("ID", id);
            if (confirm("Bạn chắc chắn! Bạn muốn xóa sản phẩm này.")) {
                $.ajax({
                    url: newUrl,
                    type: 'delete',
                    data: {},
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        if (response['status']) {
                            window.location.href = "{{ route('admin.products.index') }}";
                        }
                    }
                });
            }
        }
    </script>
@endsection
