@extends('admin.layouts.app')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid my-2">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Sửa tài khoản khách hàng</h1>
                    </div>
                    <div class="col-sm-6 text-right">
                        <a href="{{ route('admin.user.index') }}" class="btn btn-primary">Trở về</a>
                    </div>
                </div>
            </div>
            <!-- /.container-fluid -->
        </section>
        <!-- Main content -->
        <section class="content">
            <!-- Default box -->
            <div class="container-fluid">
                <form action="" id="updateUserForm">
                    @csrf
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="name">Tên khách hàng <span class="text-danger">*</span></label>
                                        <input type="text" name="name" id="name" class="form-control"
                                            placeholder="Tên..." value="{{ $user->name }}">
                                        <p></p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="email">Email <span class="text-danger">*</span></label>
                                        <input type="text" name="email" id="email" class="form-control"
                                            placeholder="Email..." value="{{ $user->email }}">
                                        <p></p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="phone">Điện thoại</label>
                                        <input type="text" name="phone" id="phone" class="form-control"
                                            placeholder="Phone" value="{{ $user->phone }}">
                                        <p></p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="password">Mật khẩu <span class="text-danger">(Nếu thay đổi)</span></label>
                                        <input type="password" name="password" id="password" class="form-control"
                                            placeholder="Mật khẩu...">
                                        <p></p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="confirm_password">Xác nhận mật khẩu <span class="text-danger">(Nếu thay đổi)</span></label>
                                        <input type="password" name="confirm_password" id="confirm_password"
                                            class="form-control" placeholder="Xác nhận mật khẩu...">
                                        <p></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="pb-5 pt-3">
                        <button class="btn btn-primary" type="submit">Update</button>
                        <a href="{{ route('admin.user.index') }}" class="btn btn-outline-dark ml-3">Cancel</a>
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
        $('#updateUserForm').submit(function(e) {
            e.preventDefault();
            $("button[type=submit]").prop('disabled', true);
            $.ajax({
                url: '{{ route('admin.user.update', $user->id) }}',
                type: 'put',
                dataType: 'json',
                data: $(this).serializeArray(),
                success: (response) => {
                    $("button[type=submit]").prop('disabled', false);
                    if (response["status"]) {
                        window.location.href = '{{ route('admin.user.index') }}';
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
