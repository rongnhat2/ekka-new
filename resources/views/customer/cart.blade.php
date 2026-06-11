@extends('customer.layout')
@section('title', 'Giỏ hàng')

@section('body')

<div class="sticky-header-next-sec ec-breadcrumb section-space-mb">
    <div class="container"><h2 class="ec-breadcrumb-title">Giỏ hàng</h2></div>
</div>

<section class="ec-page-content section-space-p">
    <div class="container">
        @if (session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
        @if (session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif

        @if (count($lines))
        <form action="{{ route('customer.cart.update') }}" method="post">
            @csrf
            <table class="table">
                <thead>
                    <tr>
                        <th>Sản phẩm</th>
                        <th>Đơn giá</th>
                        <th>Số lượng</th>
                        <th>Thành tiền</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($lines as $line)
                    <tr>
                        <td>
                            <a href="{{ route('customer.view.product', $line->product_id) }}">
                                @if ($line->image_url)<img src="{{ $line->image_url }}" width="60" class="m-r-10">@endif
                                {{ $line->name }}
                            </a>
                            <div class="text-muted small">{{ $line->size_name }} / {{ $line->color_name }}</div>
                        </td>
                        <td>{{ number_format($line->price) }} đ</td>
                        <td>
                            <input type="number" name="quantities[{{ $line->var_id }}]" value="{{ $line->quantity }}" min="1" max="{{ $line->stock }}" class="form-control" style="width:80px">
                        </td>
                        <td>{{ number_format($line->line_total) }} đ</td>
                        <td>
                            <a href="{{ route('customer.cart.remove', $line->var_id) }}" class="text-danger" onclick="return confirm('Xóa sản phẩm?')">×</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="d-flex justify-content-between align-items-center">
                <a href="{{ route('customer.view.category') }}" class="btn btn-default">Tiếp tục mua hàng</a>
                <div>
                    <strong class="m-r-15">Tạm tính: {{ number_format($subtotal) }} đ</strong>
                    <button type="submit" class="btn btn-default m-r-10">Cập nhật</button>
                    @if ($customer_data['is_login'])
                    <a href="{{ route('customer.view.checkout') }}" class="btn btn-primary">Đặt hàng</a>
                    @else
                    <a href="{{ route('customer.view.login') }}" class="btn btn-primary">Đăng nhập để đặt hàng</a>
                    @endif
                </div>
            </div>
        </form>
        @else
        <p class="text-center">Giỏ hàng trống. <a href="{{ route('customer.view.category') }}">Mua sắm ngay</a></p>
        @endif
    </div>
</section>

@endsection
