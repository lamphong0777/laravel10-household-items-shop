@extends('front.layouts.app')
@section('title')
    Account
@endsection
@section('content')
    <div class="auth-container" id="auth-container">
        <div class="form-container sign-up">
            <form action="" method="post" id="register-form">
                @csrf
                <h1>Đăng ký</h1>
                <div class="social-icons">
                    <a href="#" class="icon"><i class="fa-brands fa-google-plus-g"></i></a>
                    <a href="#" class="icon"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="#" class="icon"><i class="fa-brands fa-github"></i></a>
                    <a href="#" class="icon"><i class="fa-brands fa-linkedin-in"></i></a>
                </div>
                <span>Hoặc đăng ký qua Email</span>
                {{--                <input type="text" placeholder="Name"> --}}
                <div class="div-input">
                    <input type="text" id="name" name="name" placeholder="Họ tên" class="form-control">
                    <p class="error"></p>
                </div>
                <div class="div-input">
                    <input type="email" id="email" name="email" placeholder="Email" class="form-control">
                    <p class="error"></p>
                </div>
                <div class="div-input">
                    <input type="text" id="phone" name="phone" placeholder="Số điện thoại" class="form-control">
                    <p class="error"></p>
                </div>
                <div class="div-input">
                    <input type="password" name="password" id="password" placeholder="Mật khẩu" class="form-control">
                    <p class="error"></p>
                </div>
                <div class="div-input">
                    <input type="password" name="passwordConfirm" id="passwordConfirm" placeholder="Xác nhận mật khẩu"
                           class="form-control">
                    <p class="error"></p>
                </div>
                <button class="">Đăng ký</button>
            </form>
        </div>
        <div class="form-container sign-in">
            <form action="{{ route('user.login') }}" method="post">
                @csrf
                <h1>Đăng nhập</h1>
                <div class="social-icons">
                    <a href="#" class="icon"><i class="fa-brands fa-google-plus-g"></i></a>
                    <a href="#" class="icon"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="#" class="icon"><i class="fa-brands fa-github"></i></a>
                    <a href="#" class="icon"><i class="fa-brands fa-linkedin-in"></i></a>
                </div>
                <span>Hoặc sử dụng tài khoản đã đăng ký</span>
                <div>
                    @if(Session::has('error'))
                        <div class="alert alert-danger error">
                            {{ Session::get('error') }}
                        </div>
                    @elseif(Session::has('success'))
                        <div class="alert alert-success error">
                            {{ Session::get('success') }}
                        </div>
                    @endif
                </div>
                <div class="div-input">
                    <input type="email" name="login_email" id="login_email" placeholder="Email"
                           class="form-control @error('login_email') is-invalid @enderror"
                           value="{{ old('login_email') }}">
                    @error('login_email')
                    <p class="error invalid-feedback">{{ $message }}</p>
                    @enderror
                </div>
                <div class="div-input">
                    <input type="password" name="login_password" id="login_password" placeholder="Mật khẩu"
                           class="form-control @error('login_password') is-invalid @enderror">
                    <p class="text-end error text-dark m-0" onclick="show()">Ẩn/hiện mật khẩu <i class="fa-solid fa-eye" id="eye"></i></p>
                    @error('login_password')
                    <p class="error invalid-feedback">{{ $message }}</p>
                    @enderror
                </div>
                <a href="{{ route('user.forgot-password') }}">Quên mật khẩu?</a>
                <button type="submit">Đăng nhập</button>
            </form>
        </div>
        <div class="toggle-container">
            <div class="toggle">
                <div class="toggle-panel toggle-left">
                    <h2>XIN CHÀO, QUÝ KHÁCH!</h2>
                    <p>Bạn đã có tài khoản, hãy đăng nhập để trãi nghiệm mua hàng.</p>
                    <button class="hidden" id="login">Đăng nhập</button>
                </div>
                <div class="toggle-panel toggle-right">
                    <h2>XIN CHÀO, QUÝ KHÁCH!</h2>
                    <p>Hãy đăng ký tài khoản để mua sắm tại cửa hàng!</p>
                    <button class="hidden" id="register">Đăng ký ngay</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script type="text/javascript">
        const container = document.getElementById('auth-container');
        const registerBtn = document.getElementById('register');
        const loginBtn = document.getElementById('login');

        registerBtn.addEventListener('click', () => {
            container.classList.add("active");
        });

        loginBtn.addEventListener('click', () => {
            container.classList.remove("active");
        });

        // process submit
        $('#email').siblings("p").first().addClass('invalid-feedback').html('errors');
        $('#password').siblings("p").first().addClass('invalid-feedback').html('errors');

        // submit form
        // register
        $('#register-form').submit(function (event) {
            event.preventDefault();

            $.ajax({
                url: '{{ route('user.register') }}',
                type: 'post',
                dataType: 'json',
                data: $(this).serializeArray(),
                success: function (response) {
                    if (response["status"]) {
                        let delayInMilliseconds = 2000; //1 second
                        Swal.fire({
                            title: "Đăng ký thành công!",
                            text: "Bạn đã đăng ký thành công!",
                            icon: "success"
                        });
                        setTimeout(function () {
                            //Code to be executed after 1 second
                            window.location.href = "{{ route('shop.account') }}";
                        }, delayInMilliseconds);
                    } else {
                        let errors = response["error"];
                        $(".auth-container").addClass('auth-container-error');
                        const name = $("#name");
                        const email = $("#email");
                        const phone = $("#phone");
                        const password = $("#password");
                        const passwordConfirm = $("#passwordConfirm");

                        name.removeClass('is-invalid').siblings('p').removeClass(
                            'invalid-feedback').html('');
                        email.removeClass('is-invalid').siblings('p').removeClass(
                            'invalid-feedback').html('');
                        phone.removeClass('is-invalid').siblings('p').removeClass(
                            'invalid-feedback').html('');
                        password.removeClass('is-invalid').siblings('p').removeClass(
                            'invalid-feedback').html('');
                        passwordConfirm.removeClass('is-invalid').siblings('p').removeClass(
                            'invalid-feedback').html('');

                        if (errors["name"]) {
                            name.addClass('is-invalid').siblings('p').addClass('invalid-feedback')
                                .html(errors["name"]);
                        }
                        if (errors["email"]) {
                            email.addClass('is-invalid').siblings('p').addClass(
                                'invalid-feedback').html(errors["email"]);
                        }
                        if (errors["phone"]) {
                            phone.addClass('is-invalid').siblings('p').addClass(
                                'invalid-feedback').html(errors["phone"]);
                        }
                        if (errors["password"]) {
                            password.addClass('is-invalid').siblings('p').addClass(
                                'invalid-feedback').html(errors["password"]);
                        }
                        if (errors["passwordConfirm"]) {
                            passwordConfirm.addClass('is-invalid').siblings('p').addClass(
                                'invalid-feedback').html(errors["passwordConfirm"]);
                        }
                    }
                },
                error: function (JQXHR, exception) {
                    console.log('Some thing went wrong!');
                }
            })

        })

        // show/hide password
        function show() {
            let a = document.getElementById("login_password");
            let b = $("#eye");
            if (a.type === "password") {
                a.type = "text";
                b.removeClass('fa-eye');
                b.addClass('fa-eye-slash');
            } else {
                a.type = "password";
                b.removeClass('fa-eye-slash');
                b.addClass('fa-eye');
            }
        }
    </script>
@endsection
