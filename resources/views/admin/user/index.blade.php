@extends('admin.layouts.app')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid my-2">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Tài khoản khách hàng</h1>
                    </div>
                    <div class="col-sm-6 text-right">
                        <a href="{{ route('admin.user.create') }}" class="btn btn-primary">Thêm</a>
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
                            <button onclick="window.location.href='{{ route('admin.user.index') }}'"
                                    class="btn btn-primary btn-sm"><i class="fas fa-sync-alt"></i>
                            </button>
                        </div>
                        <div class="card-tools">
                            <form action="{{ route('admin.user.index') }}" method="get">
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
                                    <th>Tên khách hàng</th>
                                    <th>Email</th>
                                    <th>Điện thoại</th>
                                    <th width="100">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($users->isNotEmpty())
                                    @foreach ($users as $user)
                                        <tr>
                                            <td>{{ $user->id }}</td>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>{{ $user->phone != '' ? $user->phone : '####' }}</td>
                                            <td>
                                                <a href="{{ route('admin.user.edit', $user->id) }}" class="btn btn-sm btn-info"><i class="fas fa-edit"></i>
                                                </a>
                                                <a href="javascript:void(0);" onclick="deleteUser({{ $user->id }})"
                                                   class="btn btn-sm
                                                    btn-danger"><i
                                                        class="fas fa-trash"></i>
                                                </a>

                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td>Trống!</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer clearfix">
                        <ul class="pagination pagination m-0 float-right">
                            {{ $users->appends($_GET)->links() }}
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
        function deleteUser(id) {
            let url = '{{ route('admin.user.destroy', 'ID') }}'
            let newUrl = url.replace("ID", id);
            Swal.fire({
                title: "Bạn chắc chắn muốn xóa?",
                text: "Bạn không thể khôi phục bản ghi này!",
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
                        text: "Đã xóa tài khoản.",
                        icon: "success"
                    });
                    $.ajax({
                        url: newUrl,
                        type: 'delete',
                        dataType: 'json',
                        data: '',
                        success: (response) => {
                            if (response["status"]) {
                                let delayInMilliseconds = 2000; //2 second
                                setTimeout(function() {
                                    //Code to be executed after 2 second
                                    window.location.href = "{{ route('admin.user.index') }}";
                                }, delayInMilliseconds);
                            }
                        },
                        error: () => {
                            console.log('some thing went wrong!');
                        }
                    })
                }
            });
        }
    </script>
@endsection
