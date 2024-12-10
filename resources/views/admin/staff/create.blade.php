@extends('admin.layouts.app')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid my-2">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Thêm nhân viên</h1>
                    </div>
                    <div class="col-sm-6 text-right">
                        <a href="{{ route('admin.staff.index') }}" class="btn btn-primary">Trở về</a>
                    </div>
                </div>
            </div>
            <!-- /.container-fluid -->
        </section>
        <!-- Main content -->
        <section class="content">
            <!-- Default box -->
            <div class="container-fluid">
                <form action="" id="createStaffForm">
                    @csrf
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="name">Tên nhân viên <span class="text-danger">*</span></label>
                                        <input type="text" name="name" id="name" class="form-control"
                                            placeholder="Tên...">
                                        <p></p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="email">Email <span class="text-danger">*</span></label>
                                        <input type="text" name="email" id="email" class="form-control"
                                            placeholder="Email...">
                                        <p></p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="phone">Điện thoại <span class="text-danger">*</span></label>
                                        <input type="text" name="phone" id="phone" class="form-control"
                                            placeholder="Điện thoại...">
                                        <p></p>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="address">Địa chỉ <span class="text-danger">*</span></label>
                                        <input type="text" name="address" id="address" class="form-control"
                                               placeholder="Địa chỉ...">
                                        <p></p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="password">Chức vụ <span class="text-danger">*</span></label>
                                        <select name="position" id="position" class="form-control">
                                            <option value="staff">Nhân viên</option>
                                            <option value="manager">Quản lý</option>
                                        </select>
                                        <p></p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="password">Mật khẩu <span class="text-danger">*</span></label>
                                        <input type="password" name="password" id="password" class="form-control"
                                            placeholder="Mật khẩu...">
                                        <p></p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="confirm_password">Xác nhận mật khẩu <span
                                                class="text-danger">*</span></label>
                                        <input type="password" name="confirm_password" id="confirm_password"
                                            class="form-control" placeholder="Mật khẩu...">
                                        <p></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <h4>Phân quyền <span class="text-danger">*</span></h4>
                            <div class="row ">
                                @if($permissions->isNotEmpty())
                                    @foreach($permissions as $permission)
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <input type="checkbox" name="permission[]" value="{{ $permission->id }}"> {{ $permission->name }}
                                            </div>
                                        </div>
                                    @endforeach
                                @endif

                            </div>
                        </div>
                    </div>
                    <div class="pb-5 pt-3">
                        <button class="btn btn-primary" type="submit">Thêm</button>
                        <a href="{{ route('admin.staff.index') }}" class="btn btn-outline-dark ml-3">Hủy</a>
                    </div>
                </form>
            </div>
            <!-- /.card -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection
@section('js')
    <script>
        $('#createStaffForm').submit(function(e) {
            e.preventDefault();
            $("button[type=submit]").prop('disabled', true);
            $.ajax({
                url: '{{ route('admin.staff.store') }}',
                type: 'post',
                dataType: 'json',
                data: $(this).serializeArray(),
                success: (response) => {
                    $("button[type=submit]").prop('disabled', false);
                    if (response["status"]) {
                        window.location.href = '{{ route('admin.staff.index') }}';
                    } else {
                        const errors = response["errors"];

                        $("input[type='text'], input[type='password']").removeClass('is-invalid')
                            .siblings('p').removeClass('invalid-feedback')
                            .html('');

                        $.each(errors, function(key, value) {
                            $(`#${key}`).addClass('is-invalid').siblings('p').addClass(
                                'invalid-feedback').html(value);
                        })
                    }
                },
                error: () => {
                    console.log('Some thing went wrong!');
                }
            })
        })
    </script>
@endsection
