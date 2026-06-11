<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Ekka Thời trang - @yield('title')</title>

    <link rel="icon" href="assets/images/favicon/favicon.png" sizes="32x32" />
    <link rel="apple-touch-icon" href="assets/images/favicon/favicon.png" />
    <meta name="msapplication-TileImage" content="assets/images/favicon/favicon.png" />

    <link rel="stylesheet" href="{{ asset("customer/assets/css/vendor/ecicons.min.css") }}" />

    <link rel="stylesheet" href="{{ asset("customer/assets/css/plugins/animate.css") }}" />
    <link rel="stylesheet" href="{{ asset("customer/assets/css/plugins/swiper-bundle.min.css") }}" />
    <link rel="stylesheet" href="{{ asset("customer/assets/css/plugins/jquery-ui.min.css") }}" />
    <link rel="stylesheet" href="{{ asset("customer/assets/css/plugins/countdownTimer.css") }}" />
    <link rel="stylesheet" href="{{ asset("customer/assets/css/plugins/slick.min.css") }}" />
    <link rel="stylesheet" href="{{ asset("customer/assets/css/plugins/bootstrap.css") }}" />
    <link rel="stylesheet" href="{{ asset("customer/assets/css/plugins/nouislider.css") }}" />
    <link rel="stylesheet" href="{{ asset("customer/assets/css/owl.carousel.min.css") }}" />

    <link rel="stylesheet" href="{{ asset("customer/assets/css/demo1.css") }}" />
    <link rel="stylesheet" href="{{ asset("customer/assets/css/style.css") }}" />
    <link rel="stylesheet" href="{{ asset("customer/assets/css/responsive.css") }}" />

    <link rel="stylesheet" id="bg-switcher-css" href="{{ asset("customer/assets/css/backgrounds/bg-4.css") }}">
    <link rel="stylesheet" href="{{ asset("customer/assets/css/custom.css") }}" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
</head>

<body>

    <header class="ec-header">
        <!--Ec Header Top Start -->
        <div class="header-top">
            <div class="container">
                <div class="row align-items-center">
                    <!-- Header Top responsive Action -->
                    <div class="col d-lg-none ">
                        <div class="ec-header-bottons">
                            <!-- Header User Start -->
                            <div class="ec-header-user dropdown ">
                                @if ($customer_data['is_login'])
                                <a title="My Account" href="{{ route('customer.view.profile') }}">
                                    <i class="far fa-user"></i>{{ $customer_data['name'] }}
                                </a>
                                @else
                                <button class="dropdown-toggle" data-bs-toggle="dropdown">
                                    <i class="far fa-user"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-right">
                                    <li><a class="dropdown-item" href="{{ route('customer.view.register') }}">Đăng kí</a></li>
                                    <li><a class="dropdown-item" href="{{ route('customer.view.login') }}">Đăng nhập</a></li>
                                </ul>
                                @endif
                            </div>
                            <a href="{{ route('customer.view.cart') }}" class="ec-header-btn ec-header-wishlist">
                                <div class="header-icon"><i class="fas fa-shopping-cart"></i></div>
                                <span class="ec-header-count cart-count-lable">{{ $cart_count }}</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Ec Header Top  End -->
        <!-- Ec Header Bottom  Start -->
        <div class="ec-header-bottom d-none d-lg-block">
            <div class="container position-relative">
                <div class="row">
                    <div class="ec-flex">
                        <!-- Ec Header Logo Start -->
                        <div class="align-self-center">
                            <div class="header-logo">
                                <a href="/" style="font-size: 48px; font-weight: bold; color: #000;">
                                    <!-- <img src="{{ asset("customer/assets/images/logo.png") }}" alt="Site Logo" /> -->
                                    EKKA
                                </a>
                            </div>
                        </div>
                        <div class="align-self-center">
                            <div class="header-search">
                                <div class="ec-btn-group-form" action="#">
                                    <input class="form-control product-search-field" placeholder="Tìm kiếm..."
                                        type="text">
                                    <button class="submit header-search-button" type="submit"><i
                                            class="fas fa-search"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="align-self-center">
                            <div class="ec-header-bottons">
                                <div class="ec-header-user dropdown">
                                    @if ($customer_data['is_login'])
                                    <a title="My Account" href="{{ route('customer.view.profile') }}" class="d-flex align-items-center">
                                        <i class="far fa-user m-r-10"></i>{{ $customer_data['name'] }}
                                    </a>
                                    @else
                                    <button class="dropdown-toggle" data-bs-toggle="dropdown"><i class="far fa-user"></i></button>
                                    <ul class="dropdown-menu dropdown-menu-right">
                                        <li><a class="dropdown-item" href="{{ route('customer.view.register') }}">Đăng kí</a></li>
                                        <li><a class="dropdown-item" href="{{ route('customer.view.login') }}">Đăng nhập</a></li>
                                    </ul>
                                    @endif
                                </div>
                                <a href="{{ route('customer.view.cart') }}" class="ec-header-btn">
                                    <div class="header-icon"><i class="fas fa-shopping-cart"></i></div>
                                    <span class="ec-header-count cart-count-lable">{{ $cart_count }}</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="ec-header-bottom d-lg-none">
            <div class="container position-relative">
                <div class="row ">
                    <div class="col">
                        <div class="header-logo">
                            EKKA
                        </div>
                    </div>
                    <div class="col">
                        <div class="header-search">
                            <form class="ec-btn-group-form" action="#">
                                <input class="form-control" placeholder="Enter Your Product Name..." type="text">
                                <button class="submit" type="submit"><i class="fas fa-search"></i></button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="ec-main-menu-desk" class="d-none d-lg-block sticky-nav">
            <div class="container position-relative">
                <div class="row">
                    <div class="col-md-12 align-self-center">
                        <div class="ec-main-menu">
                            <ul>
                                <li><a href="{{ route('customer.view.index') }}">Trang chủ</a></li>
                                <li><a href="{{ route('customer.view.category') }}">Tất cả sản phẩm</a></li>
                                <li class="dropdown"><a href="javascript:void(0)">Danh mục</a>
                                    <ul class="sub-menu">
                                        @foreach ($nav_categories as $cat)
                                        <li><a href="{{ route('customer.view.category', ['tag' => $cat->id]) }}">{{ $cat->name }}</a></li>
                                        @endforeach
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="ec-mobile-menu" class="ec-side-cart ec-mobile-menu">
            <div class="ec-menu-title">
                <span class="menu_title"> Menu</span>
                <button class="ec-close">×</button>
            </div>
            <div class="ec-menu-inner">
                <div class="ec-menu-content">
                    <ul>
                        <li><a href="{{ route('customer.view.index') }}">Trang chủ</a></li>
                        <li><a href="{{ route('customer.view.category') }}">Tất cả sản phẩm</a></li>
                        @foreach ($nav_categories as $cat)
                        <li><a href="{{ route('customer.view.category', ['tag' => $cat->id]) }}">{{ $cat->name }}</a></li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </header>

    @yield('body')

    <footer class="ec-footer section-space-mt">
        <div class="footer-container">
            <div class="footer-top section-space-footer-p">
                <div class="container">
                    <div class="row">
                        <!-- Liên hệ -->
                        <div class="col-sm-12 col-lg-3 ec-footer-contact">
                            <div class="ec-footer-widget">
                                <div class="ec-footer-logo mb-3">
                                    <a href="/">
                                        <img src="assets/images/logo/footer-logo.png" alt="Ekka Logo"
                                            style="height: 40px;">
                                        <img class="dark-footer-logo" src="assets/images/logo/dark-logo.png"
                                            alt="Site Logo" style="display: none; height: 40px;" />
                                    </a>
                                </div>
                                <h4 class="ec-footer-heading mb-2">Liên hệ</h4>
                                <div class="ec-footer-links">
                                    <ul class="list-unstyled">
                                        <li class="ec-footer-link mb-1"><i class="ecicon eci-map-marker"
                                                style="margin-right:5px"></i> Hai Bà Trưng, Hà Nội</li>
                                        <li class="ec-footer-link mb-1"><i class="ecicon eci-phone"
                                                style="margin-right:5px"></i> 0123456789</li>
                                        <li class="ec-footer-link"><i class="ecicon eci-mail"
                                                style="margin-right:5px"></i> info@ekka.com</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <!-- Thông tin -->
                        <div class="col-sm-12 col-lg-2 ec-footer-info">
                            <div class="ec-footer-widget">
                                <h4 class="ec-footer-heading mb-2">Thông tin</h4>
                                <ul class="ec-footer-links list-unstyled">
                                    <li><a href="/">Trang chủ</a></li>
                                    <li><a href="/category?tag=0">Sản phẩm</a></li>
                                    <li><a href="/news">Tin tức</a></li>
                                    <li><a href="/contact">Liên hệ</a></li>
                                </ul>
                            </div>
                        </div>
                        <!-- Tài khoản -->
                        <div class="col-sm-12 col-lg-2 ec-footer-account">
                            <div class="ec-footer-widget">
                                <h4 class="ec-footer-heading mb-2">Tài khoản</h4>
                                <ul class="ec-footer-links list-unstyled">
                                    <li><a href="#">Thông tin cá nhân</a></li>
                                    <li><a href="#">Đơn hàng</a></li>
                                    <li><a href="#">Đăng nhập</a></li>
                                    <li><a href="#">Đăng ký</a></li>
                                </ul>
                            </div>
                        </div>
                        <!-- Dịch vụ khách hàng -->
                        <div class="col-sm-12 col-lg-2 ec-footer-service">
                            <div class="ec-footer-widget">
                                <h4 class="ec-footer-heading mb-2">Dịch vụ</h4>
                                <ul class="ec-footer-links list-unstyled">
                                    <li><a href="#">Chính sách đổi trả</a></li>
                                    <li><a href="#">Chính sách bảo mật</a></li>
                                    <li><a href="#">Điều khoản sử dụng</a></li>
                                    <li><a href="#">Hỗ trợ khách hàng</a></li>
                                </ul>
                            </div>
                        </div>
                        <!-- Tin tức mới -->
                        <div class="col-sm-12 col-lg-3 ec-footer-news">
                            <div class="ec-footer-widget">
                                <h4 class="ec-footer-heading mb-2">Tin tức mới</h4>
                                <ul class="ec-footer-links list-unstyled">
                                </ul>
                                <form class="mt-3">
                                    <div class="input-group">
                                        <input type="email" class="form-control" placeholder="Email nhận tin">
                                        <button class="btn btn-primary" type="submit">Gửi</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <div class="container">
                    <div class="row align-items-center">
                        <!-- Footer social Start -->
                        <div class="col text-left footer-bottom-left">
                            <div class="footer-bottom-social">
                                <span class="social-text text-upper">Theo dõi chúng tôi:</span>
                                <ul class="mb-0">
                                    <li class="list-inline-item"><a class="hdr-facebook" href="#"><i
                                                class="ecicon eci-facebook"></i></a></li>
                                    <li class="list-inline-item"><a class="hdr-twitter" href="#"><i
                                                class="ecicon eci-twitter"></i></a></li>
                                    <li class="list-inline-item"><a class="hdr-instagram" href="#"><i
                                                class="ecicon eci-instagram"></i></a></li>
                                    <li class="list-inline-item"><a class="hdr-linkedin" href="#"><i
                                                class="ecicon eci-linkedin"></i></a></li>
                                </ul>
                            </div>
                        </div>
                        <!-- Footer social End -->
                        <!-- Footer Copyright Start -->
                        <div class="col text-center footer-copy">
                            <div class="footer-bottom-copy ">

                            </div>
                        </div>
                        <!-- Footer Copyright End -->
                        <!-- Footer payment -->
                        <div class="col footer-bottom-right">
                            <div class="footer-bottom-payment d-flex justify-content-end">

                            </div>
                        </div>
                        <!-- Footer payment -->
                    </div>
                </div>
            </div>
        </div>
    </footer>
    @yield('sub_layout')

    <!-- Cart Floating Button -->
    <div class="ec-cart-float">
        <a href="#ec-side-cart" class="ec-header-btn ec-side-toggle">
            <div class="header-icon"><img src="assets/images/icons/cart.svg" class="svg_img header_svg" alt="cart" />
            </div>
            <span class="ec-cart-count cart-count-lable">{{ $cart_count }}</span>
        </a>
    </div>
    <!-- Cart Floating Button end -->



    <script src="{{ asset("customer/assets/js/vendor/jquery-3.5.1.min.js") }}"></script>
    <script src="{{ asset("customer/assets/js/vendor/popper.min.js") }}"></script>
    <script src="{{ asset("customer/assets/js/vendor/bootstrap.min.js") }}"></script>
    <script src="{{ asset("customer/assets/js/vendor/jquery-migrate-3.3.0.min.js") }}"></script>
    <script src="{{ asset("customer/assets/js/vendor/modernizr-3.11.2.min.js") }}"></script>

    <script src="{{ asset("customer/assets/js/plugins/swiper-bundle.min.js") }}"></script>
    <script src="{{ asset("customer/assets/js/plugins/countdownTimer.min.js") }}"></script>
    <script src="{{ asset("customer/assets/js/plugins/scrollup.js") }}"></script>
    <script src="{{ asset("customer/assets/js/plugins/nouislider.js") }}"></script>
    <script src="{{ asset("customer/assets/js/plugins/jquery.zoom.min.js") }}"></script>
    <script src="{{ asset("customer/assets/js/plugins/slick.min.js") }}"></script>
    <script src="{{ asset("customer/assets/js/plugins/infiniteslidev2.js") }}"></script>
    <script src="{{ asset("customer/assets/js/vendor/jquery.magnific-popup.min.js") }}"></script>
    <script src="{{ asset("customer/assets/js/plugins/jquery.sticky-sidebar.js") }}"></script>

    <script src="{{ asset("customer/assets/js/vendor/index.js") }}"></script>
    <script src="https://kit.fontawesome.com/d8162761f2.js"></script>
    <script src="{{ asset("customer/assets/js/main.js") }}"></script>
    @yield('js')
</body>

</html>