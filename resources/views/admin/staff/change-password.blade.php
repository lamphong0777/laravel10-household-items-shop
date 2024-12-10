@extends('admin.layouts.app')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid my-2">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Đổi mật khẩu</h1>
                    </div>
                </div>
            </div>
            <!-- /.container-fluid -->
        </section>
        <!-- Main content -->
        <section class="content">
            @include('admin.message')
            <div class="container-fluid">
                <form action="" method="post" id="updatePasswordForm"
                      name="updatePasswordForm">
                    @csrf
                    <div class="card">
                        <div class="card-body">
                            <div class="row flex-wrap align-items-center">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="old_password">Mật khẩu cũ <span
                                                class="text-danger">*</span></label>
                                        <input type="password" name="old_password" id="old_password"
                                               class="form-control"
                                               placeholder="Mật khẩu cũ...">
                                        <p></p>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="new_password">Mật khẩu mới <span
                                                class="text-danger">*</span></label>
                                        <input type="password" name="new_password" id="new_password"
                                               class="form-control"
                                               placeholder="Mật khẩu mới...">
                                        <p></p>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="confirm_password">Xác nhận mật khẩu <span
                                                class="text-danger">*</span></label>
                                        <input type="password" name="confirm_password" id="confirm_password"
                                               class="form-control"
                                               placeholder="Xác nhận mật khẩu...">
                                        <p></p>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <button class="btn btn-primary mt-md-3" type="submit">Đổi mật khẩu</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <!-- /.card -->

            <!-- /.card -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection
@section('js')
    <script>
        $("#updatePasswordForm").submit(function (event) {
            event.preventDefault();
            let element = $(this).serializeArray();

            $.ajax({
                url: '{{ route('admin.update-password') }}',
                type: 'post',
                dataType: 'json',
                data: element,
                success: (response) => {
                    if (response.status) {
                        window.location.href = '{{ route('admin.change-password') }}';
                    } else {
                        const errors = response.errors;
                        $("input[type='password']").removeClass('is-invalid')
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
    </script>
@endsection
