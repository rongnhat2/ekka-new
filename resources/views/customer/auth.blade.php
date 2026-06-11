@extends('customer.layout')
@section('title', 'Đăng nhập')

@section('css') 
@endsection()


@section('body')

    <div class="sticky-header-next-sec  ec-breadcrumb section-space-mb">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="row ec_breadcrumb_inner">
                        <div class="col-md-6 col-sm-12">
                            <h2 class="ec-breadcrumb-title">Đăng nhập</h2>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <ul class="ec-breadcrumb-list">
                                <li class="ec-breadcrumb-item"><a href="/">Trang chủ</a></li>
                                <li class="ec-breadcrumb-item active">Đăng nhập</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <section class="ec-page-content section-space-p">
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center">
                    <div class="section-title">
                        <h2 class="ec-bg-title">Đăng nhập</h2>
                        <h2 class="ec-title">Đăng nhập</h2>
                        <p class="sub-title mb-3">Đăng nhập để xem tiến trình giao hàng</p>
                    </div>
                </div>
                <div class="ec-login-wrapper">
                    <div class="ec-login-container">
                        <div class="ec-login-form">
                            @if ( Session::has('error_login') )
                                <div class="alert alert-danger" role="alert">
                                    {{ Session::get('error_login') }}
                                </div>
                            @endif
                            @if ( Session::has('success_logout') )
                                <div class="alert alert-success" role="alert">
                                    {{ Session::get('success_logout') }}
                                </div>
                            @endif
                            <form  action="{{ route('customer.login') }}" method="post">
                            	@csrf
                                <span class="ec-login-wrap">
                                    <label>Email*</label>
                                    <input type="text" name="email" placeholder="Email" required />
                                </span>
                                <span class="ec-login-wrap">
                                    <label>Mật khẩu*</label>
                                    <input type="password" name="password" placeholder="Mật khẩu" required />
                                </span>
                                <a href="/forgot" style="margin-left: auto;">Quên mật khẩu</a>
                                <span class="ec-login-wrap ec-login-btn">
                                    <button class="btn btn-primary" type="submit">Đăng nhập</button>
                                    <a href="register" class="btn btn-secondary">Đăng kí</a>
                                </span>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection()


@section('sub_layout') 
@endsection()


@section('js')



@endsection()