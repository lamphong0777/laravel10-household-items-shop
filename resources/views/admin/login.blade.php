<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Admin Login</title>
        <!-- Google Font: Source Sans Pro -->
        <link rel="stylesheet"
            href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
            <!-- Font Awesome -->
            <link rel="stylesheet" href="{{ asset('admin-assets/plugins/fontawesome-free/css/all.min.css') }}">
            <!-- Theme style -->
            <link rel="stylesheet" href="{{ asset('admin-assets/css/adminlte.min.css') }}">
            <link rel="stylesheet" href="{{ asset('admin-assets/css/custom.css') }}">
            <style>
            .login-page {
                background-image: url("{{ asset('admin-assets/img/login-background.jpg') }}");
                background-size: cover;
            }
            </style>
        </head>
        <body class="hold-transition login-page">
            <div class="login-box">
                <!-- /.login-logo -->
                @include('admin.message')
                <div class="card card-primary">
                    <div class="card-header">
                        <h2 class="h2 border-bottom p-1 text-center bg-dark w-50 rounded">ĐĂNG NHẬP</h2>
                        <h4 class="h5 border-bottom">Welcome to Administrative Panel</h4>
                    </div>
                    <div class="card-body p-5">
                        {{--                <p class="login-box-msg">Sign in to start your session</p> --}}
                        <form action="{{ route('admin.authenticate') }}" method="post">
                            @csrf
                            <div class="input-group mb-3">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-envelope"></span>
                                    </div>
                                </div>
                                <input type="email" name="email" id="email"
                                class="form-control @error('email') is-invalid @enderror" placeholder="Email..."
                                value={{ old('email') }}>
                                @error('email')
                                <p class="invalid-feedback">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="input-group mb-3">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-lock"></span>
                                    </div>
                                </div>
                                <input type="password" name="password" id="password"
                                class="form-control @error('password') is-invalid @enderror" placeholder="Mật khẩu...">
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-outline-secondary" id="togglePassword">
                                    <i class="fas fa-eye"></i> <!-- Icon để hiển thị trạng thái -->
                                    </button>
                                </div>
                                @error('password')
                                <p class="invalid-feedback">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="row">
                                <div class="col-8">
                                    <div class="icheck-primary">
                                        <input type="checkbox" id="remember" name="remember">
                                        <label for="remember">
                                            Nhớ tài khoản
                                        </label>
                                    </div>
                                </div>
                                <!-- /.col -->
                                <div class="col-6">
                                    <button type="submit" class="btn btn-primary btn-block">Đăng nhập</button>
                                </div>
                                <div class="col-6">
                                    <a href="#" class="btn btn-primary btn-block">Quên mật khẩu</a>
                                </div>
                                <!-- /.col -->
                            </div>
                        </form>
                        <p class="mb-1 mt-3 text-center">
                        </p>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <!-- ./wrapper -->
            <!-- jQuery -->
            <script src="{{ asset('admin-assets/plugins/jquery/jquery.min.js') }}"></script>
            <!-- Bootstrap 4 -->
            <script src="{{ asset('admin-assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
            <!-- AdminLTE App -->
            <script src="{{ asset('admin-assets/js/adminlte.min.js') }}"></script>
            <!-- AdminLTE for demo purposes -->
            <script src="{{ asset('admin-assets/js/demo.js') }}"></script>
            <script>
            const togglePassword = document.querySelector('#togglePassword');
            const password = document.querySelector('#password');
            togglePassword.addEventListener('click', function() {
                // Toggle the type attribute
                const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                password.setAttribute('type', type);
                // Toggle the icon
                this.querySelector('i').classList.toggle('fa-eye');
                this.querySelector('i').classList.toggle('fa-eye-slash');
            });
            </script>
        </body>
    </html>