@extends('admin.layouts.app')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid my-2">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Quản lý quyền</h1>
                    </div>
                </div>
            </div>
            <!-- /.container-fluid -->
        </section>
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <form action="" method="post" id="createPermissionForm"
                      name="createPermissionForm">
                    @csrf
                    <div class="card">
                        <div class="card-body">
                            <div class="row flex-wrap align-items-center">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="permission_name">Tên quyền <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="permission_name" id="permission_name"
                                               class="form-control"
                                               placeholder="Tên quyền...">
                                        <p></p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="slug">Slug <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="slug" id="slug"
                                               class="form-control"
                                               placeholder="Slug..." readonly>
                                        <p></p>
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
            <!-- Default box -->
            <div class="container-fluid">
                @include('admin.message')
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">
                            <button onclick="window.location.href='{{ route('admin.permission.index') }}'"
                                    class="btn btn-primary btn-sm"><i class="fas fa-sync-alt"></i>
                            </button>
                        </div>
                        <div class="card-tools">
                            <form action="{{ route('admin.permission.index') }}" method="get">
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
                                <th>Tên</th>
                                <th>Slug</th>
                                <th width="100">Hành động</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if($permissions->isNotEmpty())
                                @foreach($permissions as $permission)
                                    <tr>
                                        <td>{{ $permission->id }}</td>
                                        <td>{{ $permission->name }}</td>
                                        <td>{{ $permission->slug }}</td>
                                        <td>
                                            <a href="{{ route('admin.permission.edit', $permission->id) }}" class="btn btn-sm btn-info"><i class="fas fa-edit"></i>
                                            </a>
                                            <a href="javascript:void(0);" onclick="deletePermission({{ $permission->id }})"
                                               class="btn btn-sm
                                                    btn-danger"><i
                                                    class="fas fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td>Không có bản ghi nào.</td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer clearfix">
                        <ul class="pagination pagination m-0 float-right">
                            {{ $permissions->appends($_GET)->links() }}
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
        function deletePermission(id) {
            let url = '{{ route('admin.permission.destroy', 'ID') }}'
            let newUrl = url.replace("ID", id);
            Swal.fire({
                title: "Bạn chắc chắn muốn xóa?",
                text: "Bạn không thể khôi phục dữ liệu!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Xóa!",
                cancelButtonText: "Hủy",
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: "Đã xóa thành công!",
                        text: "Đã xóa bản ghi.",
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
                                    window.location.href =
                                        "{{ route('admin.permission.index') }}";
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

        $("#createPermissionForm").submit(function (event) {
            event.preventDefault();
            let element = $(this).serializeArray();

            $.ajax({
                url: '{{ route('admin.permission.store') }}',
                type: 'post',
                dataType: 'json',
                data: element,
                success: (response) => {
                    if (response.status) {
                        window.location.href = '{{ route('admin.permission.index') }}';
                    } else {
                        const errors = response.errors;
                        $("input[type='text']").removeClass('is-invalid')
                            .siblings('p').removeClass('invalid-feedback')
                            .html('');

                        $.each(errors, function (key, value) {
                            $(`#${key}`).addClass('is-invalid').siblings('p').addClass(
                                'invalid-feedback').html(value);
                        })
                    }
                },
                error: () => {
                    console.log('Some thing went wrong!');
                }
            })
        });

        $("#permission_name").change(function () {
            $("button[type=submit]").prop('disabled', true);
            const element = $(this);
            $.ajax({
                url: '{{ route('admin.categories.getSlug') }}',
                type: 'get',
                data: {
                    title: element.val()
                },
                dataType: 'json',
                success: function (response) {
                    $("button[type=submit]").prop('disabled', false);
                    if (response['status']) {
                        $("#slug").val(response['slug']);
                    }
                }
            });
        });
    </script>
@endsection
