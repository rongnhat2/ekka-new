@extends('customer.layout')
@section('title', 'Trang cá nhân')

@php
$statusMap = [0=>'Chờ xử lí',1=>'Chưa hoàn thiện',2=>'Đã hoàn thiện',3=>'Đã giao hàng',4=>'Hoàn trả'];
@endphp

@section('body')

<div class="sticky-header-next-sec ec-breadcrumb section-space-mb">
    <div class="container"><h2 class="ec-breadcrumb-title">Tài khoản</h2></div>
</div>

<section class="ec-page-content section-space-p">
    <div class="container">
        @if (session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif

        <div class="row">
            <div class="col-lg-5 m-b-30">
                <div class="card"><div class="card-body">
                    <h5>Thông tin cá nhân</h5>
                    <form action="{{ route('customer.profile.update') }}" method="post" class="m-t-15">
                        @csrf
                        <div class="form-group">
                            <label>Họ tên</label>
                            <input type="text" name="name" class="form-control" value="{{ $user['name'] }}" required>
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" class="form-control" value="{{ $user['email'] }}" disabled>
                        </div>
                        <div class="form-group">
                            <label>SĐT</label>
                            <input type="text" name="phone" class="form-control" value="{{ $user['phone'] }}" required>
                        </div>
                        <div class="form-group">
                            <label>Địa chỉ</label>
                            <input type="text" name="address" class="form-control" value="{{ $user['address'] }}">
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm">Lưu</button>
                    </form>
                    <form action="{{ route('customer.logout') }}" method="post" class="m-t-15">
                        @csrf
                        <button type="submit" class="btn btn-default btn-sm">Đăng xuất</button>
                    </form>
                </div></div>
            </div>
            <div class="col-lg-7">
                <div class="card"><div class="card-body">
                    <h5>Đơn hàng của tôi</h5>
                    <table class="table m-t-15">
                        <thead>
                            <tr><th>Mã</th><th>Ngày</th><th>Tổng</th><th>Trạng thái</th><th></th></tr>
                        </thead>
                        <tbody>
                            @forelse ($orders as $order)
                            <tr>
                                <td>#{{ $order->id }}</td>
                                <td>{{ $order->created_at }}</td>
                                <td>{{ number_format($order->total) }} đ</td>
                                <td>{{ $statusMap[$order->order_status] ?? 'N/A' }}</td>
                                <td><a href="{{ route('customer.view.order', $order->id) }}">Chi tiết</a></td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="text-center">Chưa có đơn hàng</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div></div>
            </div>
        </div>
    </div>
</section>

@endsection
