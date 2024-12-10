@php
    $staticPageLink = \App\Models\StaticPage::all();
@endphp
<footer class="mt-2 border-top">
    <div>
        <div class="container pt-3">
            <div class="row">
                <div class="col-md-4 text-dark">
                    <p>Gọi mua hàng</p>
                    <h4><i class="fa-solid fa-phone text-danger phone-shake"></i> 0123123123</h4>
                    <p>Tất cả các ngày</p>
                </div>
                <div class="col-md-4 text-dark">
                    <p>Góp ý, khiếu nại</p>
                    <h4><i class="fa-solid fa-phone text-danger phone-shake"></i> 0123123123</h4>
                    <p>Tất cả các ngày</p>
                </div>
                <div class="col-md-4 text-dark">
                    <p>Theo dõi chúng tôi</p>
                    <div class="d-flex">
                        <a href="" class="text-reset"><i class="fa-brands fa-facebook fs-1 me-3"></i></a>
                        <a href="" class="text-reset"><i class="fa-brands fa-instagram fs-1 me-3"></i></a>
                        <a href="" class="text-reset"><i class="fa-brands fa-youtube fs-1"></i></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="text-dark col-md-8 border mx-auto"></div>
        </div>
    </div>
    <div class="container pb-5 pt-3 text-dark">
        <div class="row">
            <div class="col-md-4">
                <div class="footer-card">
                    <h3 class="text-dark">Hỗ trợ khách hàng</h3>
                    <p class="text-uppercase"><i class="fas fa-shop"></i> Thế giới gia dụng</p>
                    <p><i class="fas fa-location-dot"></i> 123, Đường 3/2, P.Hưng Lợi, Q.Ninh Kiều, TP.Cần Thơ.</p>
                    <p><i class="fas fa-envelope"></i> thegioigiadung@example.com</p>
                    <p><i class="fas fa-phone"></i> 0123 321 123</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="footer-card">
                    <h3 class="text-dark">Về chúng tôi</h3>
                    <ul class="">
                        {{--                        <li><i class="fas fa-play me-1"></i><a class="footer-link" href="#" title="About">Giới thiệu</a></li> --}}
                        {{--                        <li><i class="fas fa-play me-1"></i><a class="footer-link" href="#" title="Contact Us">Chính sách bảo mật</a></li> --}}
                        {{--                        <li><i class="fas fa-play me-1"></i><a class="footer-link" href="#" title="Privacy">Chính sách thanh toán</a></li> --}}
                        {{--                        <li><i class="fas fa-play me-1"></i><a class="footer-link" href="#" title="Privacy">Chính sách giao nhận</a></li> --}}
                        {{--                        <li><i class="fas fa-play me-1"></i><a class="footer-link" href="#" title="Privacy">Chính sách đổi trả</a></li> --}}
                        @if ($staticPageLink->isNotEmpty())
                            @foreach ($staticPageLink as $page)
                                <li>
                                    <i class="fas fa-play me-1"></i>
                                    <a class="footer-link" href="{{ route('shop.static-page', $page->slug) }}"
                                        title="About">{{ $page->name }}</a>
                                </li>
                            @endforeach
                        @endif
                    </ul>
                </div>
            </div>

            <div class="col-md-4">
                <div class="footer-card">
                    <h3 class="text-dark">Hệ thống cửa hàng</h3>
                    <ul>
                        <li><i class="fas fa-play me-1"></i><a class="p-0" href="#" title="Sell">Facebook</a>
                        </li>
                        <li><i class="fas fa-play me-1"></i><a class="" href="#"
                                title="Advertise">Instargam</a></li>
                        <li><i class="fas fa-play me-1"></i><a class="" href="#"
                                title="Contact Us">Youtube</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="bg-dark text-light">
        <div class="container">
            <div class="row">
                <div class="col-12 mt-3">
                    <div class="copy-right text-center">
                        <p>© Copyright 2024 The giới gia dụng</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
