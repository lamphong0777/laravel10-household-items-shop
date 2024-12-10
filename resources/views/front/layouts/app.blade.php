<!DOCTYPE html>
<html class="no-js" lang="en_AU">

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    @yield('head')
    <title>@yield('title')</title>
    <meta name="description" content=""/>
    <meta name="viewport"
          content="width=device-width, initial-scale=1, shrink-to-fit=no, maximum-scale=1, user-scalable=no"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('fe-assets/css/slick.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('fe-assets/css/slick-theme.css') }}"/>

    {{-- <link rel="stylesheet" type="text/css" href="{{ asset('fe-assets/css/video-js.css') }}" /> --}}
    <link rel="stylesheet" type="text/css" href="{{ asset('fe-assets/css/custom-css.css') }}"/>
    <link rel="stylesheet" type="text/css"
          href="{{ asset('fe-assets/plugins/ionRangeSlider/css/ion.rangeSlider.min.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('fe-assets/plugins/sweetalert2/sweetalert2.min.css') }}"/>
    <link rel="stylesheet" type="text/css"
          href="{{ asset('fe-assets/css/style.css') }}?v=<?php echo rand(111, 999); ?>"/>

    {{-- <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet"> --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;500&family=Raleway:ital,wght@0,400;0,600;0,800;1,200&family=Roboto+Condensed:wght@400;700&family=Roboto:wght@300;400;700;900&display=swap"
        rel="stylesheet">

    <!-- Fav Icon -->
    <link rel="shortcut icon" type="image/x-icon" href="#"/>
    {{-- back to top button --}}
    {{--     <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css"> --}}
    <link href="https://fonts.googleapis.com/css?family=Merriweather:400,900,900i" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    @yield('css')
    <style>
        /* Cấu hình cửa sổ chatbox ở góc dưới bên phải màn hình */
        .chatbox-container {
            position: fixed;
            bottom: 20px;
            left: 20px;
            width: 300px;
            height: 400px;
            border: 1px solid #ccc;
            background-color: #ffff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            z-index: 3000;
            transition: left 1s ease; /* Thêm hiệu ứng khi chuyển động */
        }

        .chatbox-container.hide {
            display: none;
        }

        /* Định dạng cửa sổ chat */
        #chatbox {
            width: 100%;
            height: calc(100% - 50px); /* Chiều cao chatbox trừ phần input */
            border-bottom: 1px solid #ccc;
            padding: 10px;
            overflow-y: scroll;
            font-family: Arial, sans-serif;
        }

        /* Định dạng tin nhắn */
        .message {
            margin: 10px 0;
            width: 100%;
        }

        .message.user {
            text-align: right;
            background-color: #e0f7fa;
            border-radius: 10px;
            padding: 5px 10px;
            display: inline-block;
            margin-left: auto;
            margin-right: 0;
            float: right;
            width: 70%;
        }

        .message.bot {
            text-align: left;
            background-color: #f1f1f1;
            border-radius: 10px;
            padding: 5px 10px;
            display: inline-block;
            margin-left: auto;
            margin-right: 0;
            float: left;
            width: 70%;
        }

        /* Định dạng input và button */
        #messageInput {
            width: calc(100% - 60px);
            padding: 4px;
            margin: 2px 3px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
        }

        #sendBtn {
            width: 40px;
            height: 30px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        #sendBtn:hover {
            background-color: #0056b3;
        }

        .hide-chatbot {
            position: absolute;
            width: 35px;
            height: 22px;
            right: 20px;
            text-align: center;
            align-items: center;
            border-radius: 5px;
            border: 1px solid black;
        }
        .hide-chatbot:hover {
            background-color: #0c63e4;
        }

        #chatbot {
            position: fixed;
            bottom: 20px; /* Khoảng cách từ dưới cùng của màn hình */
            left: 20px; /* Khoảng cách từ bên trái của màn hình */
            background-color: #4CAF50; /* Màu nền cho biểu tượng */
            color: white; /* Màu chữ/icon */
            border-radius: 50%; /* Làm tròn icon */
            padding: 15px; /* Kích thước của biểu tượng */
            font-size: 24px; /* Kích thước icon */
            display: flex;
            justify-content: center; /* Canh giữa icon */
            align-items: center; /* Canh giữa icon */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Đổ bóng nhẹ */
            z-index: 1000; /* Đảm bảo biểu tượng hiển thị trên tất cả các phần tử khác */
            transition: background-color 0.3s ease; /* Hiệu ứng chuyển màu nền khi hover */
        }

        #chatbot:hover {
            background-color: #45a049; /* Màu nền khi hover */
            cursor: pointer; /* Thay đổi con trỏ khi hover */
        }


    </style>
</head>

<body data-instant-intensity="mousedown">

<!-- Back to top button -->
<a id="to-top-button"></a>
<a href="javascript:void(0);" id="chatbot">
    <i class="fas fa-comments"></i> <!-- Icon của chat (Bạn có thể thay thế bằng bất kỳ biểu tượng nào bạn muốn) -->
</a>
<!-- Chatbot Container -->
<div id="chatboxContainer" class="chatbox-container hide">
    <div id="chatbox">
        <div class="chatbox-header text-end">
            <button class="hide-chatbot"><i class="fas fa-minus"></i></button>
        </div>
        <div class="message bot">Bạn cần trợ giúp?</div>
    </div>
    <div style="padding: 10px;">
        <input type="text" id="messageInput" placeholder="Nhập tin nhắn...">
        <button id="sendBtn"><i class="fa-regular fa-paper-plane"></i></button>
    </div>
</div>

<div class="bg-light top-header">
    <div class="container">
        <div class="row align-items-center py-3 d-none d-lg-flex justify-content-between">
            <div class="col-lg-4 logo">
                <a href="{{ route('home') }}" class="text-decoration-none logo-link">
                        <span class="logo-part h1 text-uppercase text-primary bg-dark p-2 rounded-start">Thế
                            Giới</span>
                    <span class="logo-part h1 text-uppercase text-light bg-primary p-2 rounded-end">Gia
                            Dụng</span>
                </a>
            </div>
            <div class="col-lg-4"><a href="{{ route('shop.shop-now') }}"
                                     class="h4 text-uppercase text-dark p-2 product-all"><i
                        class="fa-solid fa-bag-shopping"></i>
                    SẢN PHẨM</a>
            </div>
            <div class="col-lg-4 col-4 text-left  d-flex justify-content-end align-items-center">
                <div class="">
                    @if (!empty(Auth::user()->name))
                        <div class="dropdown">
                                <span
                                    class="border bg-dark rounded-pill text-white me-2 nav-link text-dark dropdown-toggle"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fa-solid fa-user"></i>
                                    {{ Auth::user()->name }}
                                </span>
                            <ul class="dropdown-menu dropdown-menu-dark">
                                <li class=""><a
                                        href="{{ route('user.profile', ['id' => Auth::user()->id]) }}"
                                        class="dropdown-item nav-link"><i class="fa-regular fa-user"></i> Trang cá
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

                                <li class=""><a href="{{ route('user.change-password') }}"
                                                class="dropdown-item nav-link"><i class="fa-solid fa-lock"></i> Đổi
                                        mật khẩu</a>
                                </li>

                                <li class=""><a href="{{ route('user.logout') }}"
                                                class="dropdown-item nav-link text-warning"><i
                                            class="fas fa-sign-out-alt"></i> Đăng xuất</a>
                                </li>
                            </ul>
                        </div>
                    @else
                        <a href="{{ route('shop.account') }}" class="nav-link text-dark"><i
                                class="fa-solid fa-user"></i> Tài
                            khoản</a>
                    @endif
                </div>
                <form action="{{ route('shop.shop-now') }}" method="get">
                    <div class="input-group d-flex">
                        <input type="text" name="search_text" id="search_text" placeholder="Tìm sản phẩm..."
                               class="form-control" value="{{ Request::get('search_text') }}">
                        <button
                            class="pl-2 pr-2 rounded-end border-0 border-light bg-dark text-light align-content-center"
                            type="submit">
                            <i class="fa fa-search"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@include('front.layouts.header')
@yield('content')
@include('front.layouts.footer')
</body>

<script src="{{ asset('fe-assets/js/jquery-3.6.0.min.js') }}"></script>
<script src="{{ asset('fe-assets/js/bootstrap.bundle.5.1.3.min.js') }}"></script>
<script src="{{ asset('fe-assets/js/instantpages.5.1.0.min.js') }}"></script>
<script src="{{ asset('fe-assets/js/lazyload.17.6.0.min.js') }}"></script>
<script src="{{ asset('fe-assets/js/slick.min.js') }}"></script>
<script src="{{ asset('fe-assets/plugins/ionRangeSlider/js/ion.rangeSlider.min.js') }}"></script>
<script src="{{ asset('fe-assets/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
{{--chat bot--}}
{{--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>--}}
<script>
    window.onscroll = function () {
        myFunction()
    };

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    var navbar = document.getElementById("navbar");
    var sticky = navbar.offsetTop;

    function myFunction() {
        if (window.pageYOffset >= sticky) {
            navbar.classList.add("sticky")
        } else {
            navbar.classList.remove("sticky");
        }
    }

    $(document).ready(function () {
        // Get the CSRF token from the meta tag
        var csrfToken = $('meta[name="csrf-token"]').attr('content');

        $('#sendBtn').on('click', function () {
            let message = $('#messageInput').val();
            if (message.trim() !== '') {
                $('#chatbox').append('<div class="message user">' + message + '</div>');
                $('#messageInput').val('');
                // Gửi tin nhắn tới server với CSRF token
                $.ajax({
                    url: '/chatbot/message',
                    type: 'POST',
                    data: { message: message },
                    headers: {
                        'X-CSRF-TOKEN': csrfToken // Include CSRF token in the header
                    },
                    success: function (data) {
                        data.forEach(function (response) {
                            $('#chatbox').append('<div class="message bot">' + response.text + '</div>');
                        });
                    },
                    error: function (error) {
                        console.log("Error: ", error);
                    }
                });
            }
        });
    });

    $(document).ready(function () {
        // Xử lý sự kiện nhấn vào nút hide-chatbot
        $('.hide-chatbot').on('click', function () {
            $('#chatboxContainer').toggleClass('hide'); // Thêm hoặc loại bỏ class 'active'
        });

        $('#chatbot').on('click', function () {
            $('#chatboxContainer').removeClass('hide'); // Xóa class 'hide' để hiển thị chatbox
        });
    });
</script>
<script src="{{ asset('fe-assets/js/custom.js') }}"></script>
@yield('js')

</html>
