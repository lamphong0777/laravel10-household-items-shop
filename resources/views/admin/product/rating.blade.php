@extends('admin.layouts.app')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid my-2">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Quản lý đánh giá</h1>
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
                            <button onclick="window.location.href='{{ route('admin.products.rating') }}'"
                                class="btn btn-primary btn-sm"><i class="fas fa-sync-alt"></i>
                            </button>
                        </div>
                        <div class="card-tools">
                            <form action="{{ route('admin.products.rating') }}" method="get">
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
                                    <th width="60">Tên sản phẩm</th>
                                    <th>Khách hàng</th>
                                    <th>Bình luận</th>
                                    <th width="100">Duyệt</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($ratings->count() > 0)
                                    @foreach ($ratings as $rating)
                                        <tr>
                                            <td>{{ $rating->id }}</td>
                                            <td>{{ $rating->product->title }}</td>
                                            <td>{{ $rating->user->name }}</td>
                                            <td>{{ $rating->comment }}</td>
                                            @if ($rating->status == 1)
                                                <td>
                                                    <a href="javascript:void(0);"
                                                        onclick="approveRating({{ $rating->id }})"
                                                        class="btn-sm btn-success">
                                                        <i class="fas fa-check-circle"></i>
                                                    </a>
                                                </td>
                                            @else
                                                <td>
                                                    <a href="javascript:void(0);"
                                                        onclick="approveRating({{ $rating->id }})"
                                                        class="btn-sm btn-danger">
                                                        <i class="fas fa-ban"></i></i>
                                                    </a>
                                                </td>
                                            @endif
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
                            {{ $ratings->appends($_GET)->links() }}
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
        function approveRating(id) {
            let url = '{{ route('admin.products.approve-rating', 'ID') }}'
            let newUrl = url.replace("ID", id);
            if (confirm("Bạn chắc chắn chỉnh sửa đánh giá này!.")) {
                $.ajax({
                    url: newUrl,
                    type: 'post',
                    data: {},
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response['status']) {
                            window.location.href = "{{ route('admin.products.rating') }}";
                        }
                    }
                });
            }
        }
    </script>
@endsection
