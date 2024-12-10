@php
    use Illuminate\Support\Facades\Auth;
    $categoriesHeader = \App\Models\Category::orderBy('id', 'desc')->take(6)->get();
@endphp
<header class="mb-2">
    <div class="container">
        <nav class="navbar navbar-expand-xl p-sm-0" id="navbar">
            <div class="mobile-logo">
                <a href="{{ route('home') }}" class="text-decoration-none fs-7">
                    <span class="text-uppercase text-primary">THẾ GIỚI</span>
                    <span class="text-uppercase text-dark px-2">GIA DỤNG</span>
                </a>
                <a href="{{ route('shop.shop-now') }}" class="fs-7 text-uppercase text-dark p-2 product-all"><i
                        class="fa-solid fa-bag-shopping"></i>
                    SẢN PHẨM</a>
            </div>
            <button class="navbar-toggler menu-btn" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false"
                    aria-label="Toggle navigation">
                <!-- <span class="navbar-toggler-icon icon-menu"></span> -->
                <i class="navbar-toggler-icon fas fa-bars text-dark"></i>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    {{-- show category --}}
                    @if ($categoriesHeader->count() > 0)
                        @foreach ($categoriesHeader as $category)
                            <li class="nav-item dropdown">
                                <button class="btn-nav-header dropdown-toggle" data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                    {{ $category->name }}
                                </button>
                                <ul class="dropdown-menu dropdown-menu-dark">
                                    @foreach ($category->subcategories as $subcategory)
                                        <li><a class="dropdown-item nav-link"
                                               href="{{ route('shop.shop-now', [$category->slug, $subcategory->slug]) }}">{{ $subcategory->name }}</a>
                                        </li>
                                    @endforeach
                                </ul>
                            </li>
                        @endforeach
                    @endif
                </ul>
                <div class="row d-flex d-lg-none border-top pt-2 bg-dark">
                    @if (!empty(Auth::user()->name))
                        <div class="dropdown">
                            <p class="w-25 bg-dark text-white me-2 nav-link text-dark dropdown-toggle"
                               data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa-solid fa-user"></i>
                                {{ Auth::user()->name }}
                            </p>
                            <ul class="dropdown-menu dropdown-menu-dark">
                                <li class=""><a href="{{ route('user.profile', ['id' => Auth::user()->id]) }}"
                                                class="dropdown-item nav-link"><i class="fa-regular fa-user"></i> Trang
                                        cá
                                        nhân</a>
                                </li>

                                <li class=""><a href="{{ route('chat.index') }}"
                                                class="dropdown-item nav-link"><i class="fas fa-message"></i> Tin nhắn</a>
                                </li>

                                <li class=""><a href="{{ route('user.my-order') }}"
                                                class="dropdown-item nav-link"><i class="fa-solid fa-receipt"></i> Hóa
                                        đơn</a>
                                </li>

                                <li class=""><a href="{{ route('user.wishlist') }}"
                                                class="dropdown-item nav-link"><i class="fa-regular fa-heart"></i> Yêu
                                        thích</a>
                                </li>
                                <li class="dropdown-item nav-link text-warning"><a href="{{ route('user.logout') }}"
                                                                                   class="text-reset">Đăng xuất</a>
                                </li>
                            </ul>
                        </div>
                    @else
                        <a href="{{ route('shop.account') }}" class="nav-link text-light col-4"><i
                                class="fa-solid fa-user"></i> Tài
                            khoản</a>
                    @endif
                    <form action="{{ route('shop.shop-now') }}" method="get" class="col-8">
                        <div class="input-group d-flex m-sm-2">
                            <input type="text" name="search_text" placeholder="Tìm sản phẩm..." class="form-control"
                                   value="{{ Request::get('search_text') }}">
                            <button
                                class="pl-4 pr-4 rounded-end border-0 border-light bg-light text-dark align-content-center"
                                type="submit">
                                <i class="fa fa-search"></i>
                            </button>
                        </div>
                    </form>
                </div>

            </div>
            <div class="right-nav">
                <a href="{{ route('shopping.cart') }}" class="ml-3 d-flex fs-4 position-relative">
                    <i class="fas fa-shopping-bag text-dark"></i>
                    @if (!empty(Auth::user()->name))
                        @php
                            $cartCount = \App\Models\Cart::where('user_id', Auth::user()->id)->get();
                        @endphp
                        <span class="badge badge-cart" id="cart_count">{{ $cartCount->count() }}</span>
                    @else
                        <span class="badge badge-cart" id="cart_count">0</span>
                    @endif
                </a>
            </div>
        </nav>
    </div>
</header>
