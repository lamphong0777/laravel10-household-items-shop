@extends('admin.layouts.app')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid my-2">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Quản lý vận chuyển</h1>
                    </div>
                </div>
            </div>
            <!-- /.container-fluid -->
        </section>
        <!-- Main content -->
        <section class="content">
            <!-- Default box -->
            <div class="container-fluid">
                <form action="{{ route('admin.shipping.store') }}" method="post" id="brandForm" name="brandForm">
                    @csrf
                    <div class="card">
                        <div class="card-body">
                            <div class="row flex-wrap align-items-center">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="province_id">Tỉnh thành <span class="text-danger">*</span></label>
                                        <select id="province_id" name="province_id"
                                            class="form-control @error('province_id') is-invalid @enderror">
                                            <option value="">--Chọn tỉnh thành--</option>
                                            @if ($provinces->isNotEmpty())
                                                @foreach ($provinces as $province)
                                                    <option value="{{ $province->id }}">{{ $province->name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                        @error('province_id')
                                            <p class="invalid-feedback">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="shipping_cost">Phí vận chuyển <span class="text-danger">*</span></label>
                                        <input type="text" name="shipping_cost" id="shipping_cost"
                                            class="form-control @error('shipping_cost') is-invalid @enderror"
                                            placeholder="Phí vận chuyển" value="{{ old('shipping_cost') }}">
                                        @error('shipping_cost')
                                            <p class="invalid-feedback">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <button class="btn btn-primary mt-md-3" type="submit">Thêm</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <!-- /.card -->
        </section>
        <!-- /.content -->
        <section class="content">
            <!-- Default box -->
            <div class="container-fluid">
                @include('admin.message')
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">
                            <button onclick="window.location.href='{{ route('admin.shipping.index') }}'"
                                class="btn btn-primary btn-sm"><i class="fas fa-sync-alt"></i>
                            </button>
                        </div>
                        <div class="card-tools">
                            <form action="{{ route('admin.shipping.index') }}" method="get">
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
                                    <th>Tỉnh</th>
                                    <th>Phí vận chuyển</th>
                                    <th width="100">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($shipping_charges->isNotEmpty())
                                    @foreach ($shipping_charges as $shipping_charge)
                                        <tr>
                                            <td>{{ $shipping_charge->id }}</td>
                                            <td>{{ $shipping_charge->province->name }}</td>
                                            <td>{{ number_format($shipping_charge->shipping_cost, 0, ',', '.') }}</td>
                                            <td>
                                                <a href="{{ route('admin.shipping.edit', $shipping_charge->id) }}"
                                                    class="btn btn-sm btn-info"><i class="fas fa-edit"></i></a>
                                                <a href="javascript:void(0);"
                                                    onclick="deleteShipping({{ $shipping_charge->id }})"
                                                    class="btn btn-sm
                                                    btn-danger"><i
                                                        class="fas fa-trash"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="5">Records not found!</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer clearfix">
                        <ul class="pagination pagination m-0 float-right">
                            {{ $shipping_charges->appends($_GET)->links() }}
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
        function deleteShipping(id) {
            let url = '{{ route('admin.shipping.destroy', 'ID') }}'
            let newUrl = url.replace("ID", id);

            Swal.fire({
                title: "Bạn chắc chắn muốn xóa?",
                text: "Bạn không thể khôi phục!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Xóa",
                cancelButtonText: "Hủy"
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: "Đã xóa!",
                        text: "Đã xóa thành công.",
                        icon: "success"
                    });
                    $.ajax({
                        url: newUrl,
                        type: 'delete',
                        data: {},
                        dataType: 'json',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            if (response['status']) {
                                let delayInMilliseconds = 2000; //2 second
                                setTimeout(function() {
                                    //Code to be executed after 2 second
                                    window.location.href =
                                        "{{ route('admin.shipping.index') }}";
                                }, delayInMilliseconds);
                            }
                        }
                    });
                }
            });
        }
    </script>
@endsection
