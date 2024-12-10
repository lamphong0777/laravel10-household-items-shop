@extends('front.layouts.app')
@section('title')
    Reset password
@endsection
@section('content')
    <main>
        <section class=" section-10">
            <div class="container">
                <div class="login-form">
                    <form action="{{ route('user.update-password-token', $token) }}" method="post"
                          class="rounded-3">
                        @csrf
                        <h4 class="modal-title">Khôi phục mật khẩu</h4>
                        <p class="text-center">Vui lòng nhập mật khẩu mới</p>
                        <input type="hidden" name="token" value="{{ $token }}">
                        <div class="form-group">
                            <input type="password" name="new_password" class="form-control rounded @error('new_password')
                                is-invalid
                            @enderror" placeholder="Mật khẩu mới">
                            @error('new_password')
                            <p class="invalid-feedback">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="form-group">
                            <input type="password" name="confirm_password" class="form-control rounded @error('confirm_password')
                                is-invalid
                            @enderror" placeholder="Nhập lại mật khẩu">
                            @error('confirm_password')
                            <p class="invalid-feedback">{{ $message }}</p>
                            @enderror
                        </div>
                        <input type="submit" class="btn btn-dark btn-lg w-100 rounded" value="Xác nhận">
                    </form>
                    <div class="text-center small">Chưa có tài khoản? <a href="{{ route('shop.account') }}">Đăng ký</a></div>
                </div>
            </div>
        </section>
    </main>
@endsection
@section('js')
    <script type="text/javascript">
    </script>
@endsection
