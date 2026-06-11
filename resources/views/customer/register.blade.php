@extends('customer.layout')
@section('title', 'Đăng ký')

@section('css') 
@endsection()


@section('body')

    <div class="sticky-header-next-sec  ec-breadcrumb section-space-mb">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="row ec_breadcrumb_inner">
                        <div class="col-md-6 col-sm-12">
                            <h2 class="ec-breadcrumb-title">Đăng ký</h2>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <ul class="ec-breadcrumb-list">
                                <li class="ec-breadcrumb-item"><a href="">Trang chủ</a></li>
                                <li class="ec-breadcrumb-item active">Đăng ký</li>
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
                        <h2 class="ec-bg-title">Đăng kí thành viên</h2>
                        <h2 class="ec-title">Đăng kí thành viên</h2>
                    </div>
                </div>
                <div class="ec-register-wrapper">
                    <div class="ec-register-container">
                        <div class="ec-register-form">
                            <form action="{{ route('customer.register') }}" method="post">
                                @csrf
                                @if (session('error'))
                                <div class="alert alert-danger">{{ session('error') }}</div>
                                @endif
                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                <span class="ec-register-wrap ec-register-half">
                                    <label>Họ và tên*</label>
                                    <input type="text" name="name" placeholder="Họ và tên" required />
                                </span>
                                <span class="ec-register-wrap ec-register-half">
                                    <label>Email*</label>
                                    <input type="email" name="email" placeholder="Email..." required />
                                </span>
                                <span class="ec-register-wrap ec-register-half">
                                    <label>Số điện thoại*</label>
                                    <input type="text" name="phone" placeholder=" Số điện thoại"
                                        required />
                                </span>
                                <span class="ec-register-wrap ec-register-half">
                                    <label>Mật khẩu*</label>
                                    <input type="password" name="password" placeholder="password"
                                        required />
                                </span>
                                <span class="ec-register-wrap ec-register-btn">
                                    <button class="btn btn-primary" type="submit">Đăng kí</button>
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