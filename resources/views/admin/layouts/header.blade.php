<nav class="main-header navbar navbar-expand navbar-secondary navbar-white">
    <!-- Right navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
    </ul>
    <ul class="navbar-nav ml-auto">
        <li class="nav-item">
            <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                <i class="fas fa-expand-arrows-alt"></i>
            </a>
        </li>
        <li class="nav-item dropdown">
            <a class="nav-link p-0 pr-3" data-toggle="dropdown" href="#">
                <img src="{{ asset('admin-assets/img/avatar4.png') }}" class='img-circle elevation-2' width="40"
                    height="40" alt="">
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right p-3">
                <h4 class="h4 mb-0"><strong>{{ Auth::guard('admin')->user()->name }}</strong></h4>
                <div class="mb-3">{{ Auth::guard('admin')->user()->email }}</div>
                <div class="dropdown-divider"></div>
                {{-- <a href="#" class="dropdown-item">
                    <i class="fas fa-user-cog mr-2"></i> Cài đặt
                </a> --}}
                <div class="dropdown-divider"></div>
                <a href="{{ route('admin.change-password') }}" class="dropdown-item">
                    <i class="fas fa-lock mr-2"></i> Đổi mật khẩu
                </a>
                <div class="dropdown-divider"></div>
                <a href="{{ route('admin.logout') }}" class="dropdown-item text-danger">
                    <i class="fas fa-sign-out-alt mr-2"></i> Đăng xuất
                </a>
            </div>
        </li>
    </ul>
</nav>
