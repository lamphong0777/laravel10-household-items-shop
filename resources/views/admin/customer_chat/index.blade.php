@extends('admin.layouts.app')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid my-2">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Quản lý tin nhắn</h1>
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
                        <div class="card-tools">
                            <div class="input-group input-group" style="width: 250px;">
                                <input type="text" name="table_search" class="form-control float-right"
                                       placeholder="Tìm kiếm...">

                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-default">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover text-nowrap">
                            <thead>
                            <tr>
                                <th width="60">ID</th>
                                <th>Tên khách hàng</th>
                                <th>Email</th>
                                <th width="110">Hành động</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if ($userHaveChat->isNotEmpty())
                                @foreach ($userHaveChat as $user)
                                    <tr>
                                        <td>{{ $user->id }}</td>
                                        @php
                                        $newChat = \App\Models\Message::where('user_id', $user->id)
                                        ->latest()->first();
                                        @endphp
                                        <td class="notification">{{ $user->name }}
                                            @if($newChat->created_at >= $fiveDaysAgo && $newChat->created_at <= $dayNow)
                                                <span class="badge">Mới</span>
                                            @endif
                                        </td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            <a class="text-reset"
                                               href="{{ route('admin.chat.index', $user->id) }}">
                                                <button class="btn btn-sm btn-success">
                                                    <i class="fas fa-comments"></i>
                                                </button>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td>Không có tin nhắn nào.</td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer clearfix">
                        <ul class="pagination pagination m-0 float-right">
                            {{--                            {{ $pages->appends($_GET)->links() }}--}}
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
        function deletePage(id) {
            let url = '{{ route('admin.page.destroy', 'ID') }}'
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
                        dataType: 'json',
                        data: '',
                        success: (response) => {
                            if (response["status"]) {
                                let delayInMilliseconds = 2000; //2 second
                                setTimeout(function () {
                                    //Code to be executed after 2 second
                                    window.location.href = "{{ route('admin.page.index') }}";
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
