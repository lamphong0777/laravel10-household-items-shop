@extends('front.layouts.app')
@section('content')
    <main>
        <section class="section-5 pt-3 pb-3 mb-3 bg-white">
            <div class="container">
                <div class="light-font">
                    <ol class="breadcrumb primary-color mb-0">
                        <li class="breadcrumb-item text-primary">Tài khoản</li>
                        <li class="breadcrumb-item">Đổi mật khẩu</li>
                    </ol>
                </div>
            </div>
        </section>

        <section class=" section-11 ">
            <div class="container">
                @if(Session::has('error'))
                    <div class="alert alert-warning">
                        <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
                        <strong>Lỗi! </strong> {{ Session::get('error') }}
                    </div>
                @elseif(Session::has('success'))
                    <div class="alert">
                        <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
                        <strong>Lỗi! </strong> {{ Session::get('success') }}
                    </div>
                @endif
                <div class="row">
                    <div class="col-md-9 mx-auto">
                        <div class="card">
                            <div class="card-header">
                                <h2 class="h5 mb-0 pt-2 pb-2">Đổi mật khẩu</h2>
                            </div>
                            <div class="card-body p-4">
                                <div class="row">
                                    <form action="" id="updateNewPasswordForm">
                                        @csrf
                                        <div class="mb-3">
                                            <label for="name">Mật khẩu hiện tại <span
                                                    class="text-danger">*</span></label>
                                            <input type="password" name="old_password" id="old_password"
                                                   placeholder="Nhập mật khẩu hiện tại..." class="form-control">
                                            <p></p>
                                        </div>
                                        <div class="mb-3">
                                            <label for="name">Mật khẩu mới <span class="text-danger">*</span></label>
                                            <input type="password" name="new_password" id="new_password"
                                                   placeholder="Nhập mật khẩu mới..." class="form-control">
                                            <p></p>
                                        </div>
                                        <div class="mb-3">
                                            <label for="name">Xác nhận mật khẩu mới <span
                                                    class="text-danger">*</span></label>
                                            <input type="password" name="confirm_password" id="confirm_password"
                                                   placeholder="Nhập lại mật khẩu mới..." class="form-control">
                                            <p></p>
                                        </div>
                                        <div class="d-flex">
                                            <button class="btn btn-dark">Xác nhận</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection

@section('js')
    <script>
        $("#updateNewPasswordForm").submit(function (e) {
            e.preventDefault();
            const element = $(this).serializeArray();

            $.ajax({
                url: '{{ route('user.update-password') }}',
                type: 'post',
                dataType: 'json',
                data: element,
                success: (response) => {
                    if(response.status) {
                        window.location.href = '{{ route('user.change-password') }}'
                    } else {
                        const errors = response.errors;
                        $("input[type='password']").removeClass('is-invalid')
                            .siblings('p').removeClass('invalid-feedback')
                            .html('');
                        $.each(errors, function(key, value) {
                            $(`#${key}`).addClass('is-invalid').siblings('p').addClass(
                                'invalid-feedback').html(value);
                        });
                    }
                },
                error: () => {
                    console.log('Some thing went wrong!');
                }
            })
        });
    </script>
@endsection
