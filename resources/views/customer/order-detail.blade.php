@extends('customer.layout')
@section('title', 'Đơn hàng #' . $order->id)

@section('body')

<div class="container section-space-p">
    <h3>Chi tiết đơn #{{ $order->id }}</h3>
    <p>Trạng thái: <strong>{{ ['Chờ xử lí','Chưa hoàn thiện','Đã hoàn thiện','Đã giao hàng','Hoàn trả'][$order->order_status] ?? '' }}</strong></p>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Sản phẩm</th>
                <th>Biến thể</th>
                <th>SL</th>
                <th>Giá</th>
                <th>Thành tiền</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($details as $d)
            <tr>
                <td>{{ $d->proName }}</td>
                <td>{{ $d->size_name }} / {{ $d->color_name }}</td>
                <td>{{ $d->quantity }}</td>
                <td>{{ number_format($d->price) }} đ</td>
                <td>{{ number_format($d->total_price) }} đ</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="4" class="text-right">Tổng</th>
                <th>{{ number_format($order->total) }} đ</th>
            </tr>
        </tfoot>
    </table>
    <a href="{{ route('customer.view.profile') }}" class="btn btn-default">Quay lại</a>
</div>

@endsection