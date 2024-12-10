@extends('front.layouts.app')
@section('title')
    Forgot password
@endsection
@section('content')
    <main>
        <section class=" section-10">
            <div class="container">
                <div class="login-form">
                    @if(Session::has('success'))
                        <div class="alert alert-success error">
                            {{ Session::get('success') }}
                        </div>
                    @elseif(Session::has('error'))
                        <div class="alert alert-danger error">
                            {{ Session::get('error') }}
                        </div>
                    @endif
                    <form action="{{ route('user.reset-password') }}" method="post"
                          class="rounded-3">
                        @csrf
                        <h4 class="modal-title">Quên mật khẩu</h4>
                        <p class="text-center">Vui lòng nhập email để khôi phục</p>
                        <div class="form-group">
                            <input type="text" name="email" class="form-control rounded @error('email')
                                is-invalid
                            @enderror" placeholder="Email">
                            @error('email')
                            <p class="invalid-feedback">{{ $message }}</p>
                            @enderror
                        </div>
                        <input type="submit" class="btn btn-dark btn-lg w-100 rounded" value="Xác nhận">
                    </form>
                    <div class="text-center small">Chưa có tài khoản? <a href="{{ route('shop.account') }}">Đăng ký</a>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
@section('js')
    <script type="text/javascript">
    </script>
@endsection
