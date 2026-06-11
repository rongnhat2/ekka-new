@extends('customer.layout')
@section('title', 'Giỏ hàng')

@section('css') 
@endsection()


@section('body')

    <div class="sticky-header-next-sec  ec-breadcrumb section-space-mb">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="row ec_breadcrumb_inner">
                        <div class="col-md-6 col-sm-12">
                            <h2 class="ec-breadcrumb-title">Cập nhật mật khẩu</h2>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <ul class="ec-breadcrumb-list">
                                <li class="ec-breadcrumb-item"><a href="/">Trang chủ</a></li>
                                <li class="ec-breadcrumb-item active">Cập nhật mật khẩu</li>
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
                        <h2 class="ec-bg-title">Cập nhật mật khẩu</h2> 
                    </div>
                </div>
                <div class="ec-login-wrapper">
                    <div class="ec-login-container">
                        <div class="ec-login-form">
                            @if ( Session::has('error') )
                                <div class="alert alert-danger" role="alert">
                                    {{ Session::get('error') }}
                                </div>
                            @endif
                            @if ( Session::has('success') )
                                <div class="alert alert-success" role="alert">
                                    {{ Session::get('success') }}
                                </div>
                            @endif
                            <form  action="{{ route('customer.reset') }}" method="post">
                                @csrf
                                <span class="ec-login-wrap">
                                    <label>Mật khẩu mới*</label>
                                    <input type="password" name="password" placeholder="Password" required />
                                    <input type="hidden" name="email" value="<?php echo $email ?>" required />
                                    <input type="hidden" name="code" value="<?php echo $code ?>" required />
                                </span>  
                                <span class="ec-login-wrap ec-login-btn">
                                    <button class="btn btn-primary" type="submit">Cập nhật</button> 
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