<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('admin.dashboard') }}" class="brand-link text-center">
        <span class="brand-text font-weight-bold">THẾ GIỚI GIA DỤNG</span>
    </a>
    <!-- Sidebar -->
    <div class="sidebar">
        <ul class="nav nav-pills nav-sidebar flex-column mt-2">
            <li class="nav-item">
                <a href="{{ route('admin.dashboard') }}"
                   class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-tachometer-alt dashboard-icon"></i>
                    <p>Trang chủ</p>
                </a>
            </li>
            <hr class="divider">
            @can('quan-ly-tai-khoan')
                <li class="nav-item">
                    <a href="{{ route('admin.user.index') }}"
                       class="nav-link {{ request()->routeIs('admin.user.index') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-users user-icon"></i>
                        <p>Tài khoản khách hàng</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.staff.index') }}"
                       class="nav-link {{ request()->routeIs('admin.staff.index') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-users user-icon"></i>
                        <p>Nhân viên</p>
                    </a>
                </li>
                <hr class="divider">
            @endcan

            @can('quan-ly-danh-gia')
                <li class="nav-item">
                    <a href="{{ route('admin.products.rating') }}"
                       class="nav-link {{ request()->routeIs('admin.products.rating') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-comment rating-icon"></i>
                        <p>Đánh giá sản phẩm</p>
                    </a>
                </li>
                <hr class="divider">
            @endcan

            @can('quan-ly-san-pham')
                <li class="nav-item">
                    <a href="{{ route('admin.categories.index') }}"
                       class="nav-link {{ request()->routeIs('admin.categories.index') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-file-alt category-icon"></i>
                        <p>Danh mục</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.subcategories.index') }}"
                       class="nav-link {{ request()->routeIs('admin.subcategories.index') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-file-alt category-icon"></i>
                        <p>Danh mục con</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.brands.index') }}"
                       class="nav-link {{ request()->routeIs('admin.brands.index') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-building category-icon"></i>
                        <p>Thương hiệu</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.products.index') }}"
                       class="nav-link {{ request()->routeIs('admin.products.index') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tag product-icon"></i>
                        <p>Sản phẩm</p>
                    </a>
                </li>
                <hr class="divider">
            @endcan

            @can('quan-ly-kho')
                <li class="nav-item">
                    <a href="{{ route('admin.producer.index') }}"
                       class="nav-link {{ request()->routeIs('admin.producer.index') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-building stock-icon"></i>
                        <p>Nhà cung cấp</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.product-stocks.receipt') }}"
                       class="nav-link {{ request()->routeIs('admin.product-stocks.receipt') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-file-import stock-icon"></i>
                        <p>Phiếu nhập hàng</p>
                    </a>
                </li>
                <hr class="divider">
            @endcan

            @can('quan-ly-van-chuyen-khuyen-mai')
                <li class="nav-item">
                    <a href="{{ route('admin.discount.index') }}"
                       class="nav-link {{ request()->routeIs('admin.discount.index') ? 'active' : '' }}">
                        <i class="nav-icon fa fa-percent discount-icon" aria-hidden="true"></i>
                        <p>Giảm giá</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.shipping.index') }}"
                       class="nav-link {{ request()->routeIs('admin.shipping.index') ? 'active' : '' }}">
                        <i class="fas fa-truck nav-icon shipping-icon"></i>
                        <p>Vận chuyển</p>
                    </a>
                </li>
                <hr class="divider">
            @endcan

            @can('quan-ly-hoa-don')
                <li class="nav-item">
                    <a href="{{ route('admin.orders.index') }}"
                       class="nav-link {{ request()->routeIs('admin.orders.index') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-shopping-bag order-icon"></i>
                        <p>Hóa đơn</p>
                    </a>
                </li>
                <hr class="divider">
            @endcan

            @can('quan-ly-bai-viet')
                <li class="nav-item">
                    <a href="{{ route('admin.page.index') }}"
                       class="nav-link {{ request()->routeIs('admin.page.index') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-file page-icon"></i>
                        <p>Trang</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.blog.index') }}"
                       class="nav-link {{ request()->routeIs('admin.blog.index') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-file page-icon"></i>
                        <p>Bài viết</p>
                    </a>
                </li>
                <hr class="divider">
            @endcan

            @can('quan-ly-quyen')
                <li class="nav-item">
                    <a href="{{ route('admin.permission.index') }}"
                       class="nav-link {{ request()->routeIs('admin.permission.index') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user-shield permission-icon"></i>
                        <p>Quyền</p>
                    </a>
                </li>
                <hr class="divider">
            @endcan

            <li class="nav-item">
                <a href="{{ route('admin.customer-chat.index') }}"
                   class="nav-link {{ request()->routeIs('admin.customer-chat.index') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-comments order-icon"></i>
                    <p>Tin nhắn</p>
                </a>
            </li>
        </ul>
    </div>
</aside>
