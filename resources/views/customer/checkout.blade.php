@extends('customer.layout')
@section('title', 'Đặt hàng')

@section('body')

<div class="sticky-header-next-sec ec-breadcrumb section-space-mb">
    <div class="container"><h2 class="ec-breadcrumb-title">Thanh toán</h2></div>
</div>

<section class="ec-page-content section-space-p">
    <div class="container">
        @if (session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif
        <div class="row">
            <div class="col-lg-8">
                <form action="{{ route('customer.checkout.store') }}" method="post">
                    @csrf
                    <h4 class="m-b-20">Thông tin giao hàng</h4>
                    <div class="form-group">
                        <label>Họ tên *</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $user['name']) }}" required>
                    </div>
                    <div class="form-group">
                        <label>Email *</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email', $user['email']) }}" required>
                    </div>
                    <div class="form-group">
                        <label>Số điện thoại *</label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone', $user['phone']) }}" required>
                    </div>
                    <div class="form-group">
                        <label>Địa chỉ *</label>
                        <input type="text" name="address" class="form-control" value="{{ old('address', $user['address']) }}" required>
                    </div>
                    <div class="form-group">
                        <label>Thanh toán</label>
                        <div><label><input type="radio" name="payment_method" value="1" checked> COD — Thanh toán khi nhận hàng</label></div>
                        <div><label><input type="radio" name="payment_method" value="2"> Thanh toán online</label></div>
                    </div>
                    <button type="submit" class="btn btn-primary">Xác nhận đặt hàng</button>
                </form>
            </div>
            <div class="col-lg-4">
                <div class="card"><div class="card-body">
                    <h5>Đơn hàng</h5>
                    @foreach ($lines as $line)
                    <div class="d-flex justify-content-between m-b-10">
                        <span>{{ $line->name }} × {{ $line->quantity }}</span>
                        <span>{{ number_format($line->line_total) }} đ</span>
                    </div>
                    @endforeach
                    <hr>
                    <div class="d-flex justify-content-between font-weight-bold">
                        <span>Tổng</span>
                        <span>{{ number_format($subtotal) }} đ</span>
                    </div>
                </div></div>
            </div>
        </div>
    </div>
</section>

@endsection
