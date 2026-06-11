@extends('admin.layout')

@section('body')

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row m-b-20">
                            <div class="col-sm-12 col-md-8">
                                <h4 class="card-title m-b-5">Chi tiết phiếu nhập #{{ $import->importID }}</h4>
                                <p class="text-muted m-b-0">
                                    Người nhập: <strong>{{ $import->adminEmail ?? 'N/A' }}</strong> —
                                    Thời gian: <strong>{{ $import->improtDate ?? $import->created_at }}</strong>
                                </p>
                            </div>
                            <div class="col-sm-12 col-md-4 text-md-right">
                                <a href="{{ route('admin.warehouse.history') }}" class="btn btn-default btn-sm">Quay lại</a>
                            </div>
                        </div>

                        <table class="table table-bordered sub-warehouse">
                            <thead>
                                <tr>
                                    <th>Sản phẩm</th>
                                    <th>Biến thể</th>
                                    <th>Số lượng</th>
                                    <th>Đơn giá nhập</th>
                                    <th>Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $total = 0; @endphp
                                @foreach ($details as $detail)
                                @php
                                    $qty = $detail->Quantity ?? $detail->quantity ?? 0;
                                    $unitPrice = $detail->unitPrice ?? $detail->price ?? 0;
                                    $lineTotal = $qty * $unitPrice;
                                    $total += $lineTotal;
                                @endphp
                                <tr>
                                    <td>{{ $detail->product_name }}</td>
                                    <td>{{ $detail->size_name }} / {{ $detail->color_name }} / {{ $detail->material_name }}</td>
                                    <td>{{ number_format($qty) }}</td>
                                    <td>{{ number_format($unitPrice) }} đ</td>
                                    <td>{{ number_format($lineTotal) }} đ</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="4" class="text-right">Tổng cộng</th>
                                    <th>{{ number_format($total) }} đ</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
