@extends('admin.layout')

@section('title', 'Thống kê')

@section('body')

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0 font-size-18">Thống kê tổng quan</h4>
                    <a href="{{ route('admin.order.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus m-r-5"></i>Tạo đơn offline
                    </a>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 col-xl-3">
                <div class="card bg-primary border-primary">
                    <div class="card-body">
                        <h5 class="card-title mb-0 text-white">Doanh thu</h5>
                        <h2 class="d-flex align-items-center mb-0 text-white m-t-15">{{ number_format($summary['turnover']) }} đ</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3">
                <div class="card bg-success border-success">
                    <div class="card-body">
                        <h5 class="card-title mb-0 text-white">Sản phẩm đã bán</h5>
                        <h2 class="d-flex align-items-center mb-0 text-white m-t-15">{{ number_format($summary['items_sold']) }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3">
                <div class="card bg-warning border-warning">
                    <div class="card-body">
                        <h5 class="card-title mb-0 text-white">Đơn hàng</h5>
                        <h2 class="d-flex align-items-center mb-0 text-white m-t-15">{{ number_format($summary['order_count']) }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3">
                <div class="card bg-primary border-primary">
                    <div class="card-body">
                        <h5 class="card-title mb-0 text-white">Khách hàng</h5>
                        <h2 class="d-flex align-items-center mb-0 text-white m-t-15">{{ number_format($summary['customer_count']) }}</h2>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form method="get" class="form-inline m-b-20">
                            <label class="m-r-10">Tháng</label>
                            <select name="month" class="form-control m-r-15">
                                @for ($m = 1; $m <= 12; $m++)
                                <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>Tháng {{ $m }}</option>
                                @endfor
                            </select>
                            <label class="m-r-10">Năm</label>
                            <select name="year" class="form-control m-r-15">
                                @for ($y = date('Y'); $y >= date('Y') - 4; $y--)
                                <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                                @endfor
                            </select>
                            <button type="submit" class="btn btn-default btn-sm">Xem</button>
                        </form>
                        <div class="row">
                            <div class="col-xl-6">
                                <h4 class="card-title">Doanh thu theo ngày (Tháng {{ $month }}/{{ $year }})</h4>
                                <canvas id="revenue-day-chart" height="120"></canvas>
                            </div>
                            <div class="col-xl-6">
                                <h4 class="card-title">Doanh thu theo tháng (Năm {{ $year }})</h4>
                                <canvas id="revenue-month-chart" height="120"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Top mặt hàng bán chạy</h4>
                        <div class="table-responsive">
                            <table class="table table-centered table-hover table-xl mb-0">
                                <thead>
                                    <tr>
                                        <th class="border-top-0">Mã SP</th>
                                        <th class="border-top-0">Hình ảnh</th>
                                        <th class="border-top-0">Đã bán</th>
                                        <th class="border-top-0">Tồn kho</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($bestSellers as $item)
                                    @include('admin.statistic.partials.product-row', ['item' => $item, 'qtyField' => 'sold'])
                                    @empty
                                    <tr><td colspan="4" class="text-center">Chưa có dữ liệu</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Mặt hàng bán chậm</h4>
                        <div class="table-responsive">
                            <table class="table table-centered table-hover table-xl mb-0">
                                <thead>
                                    <tr>
                                        <th class="border-top-0">Mã SP</th>
                                        <th class="border-top-0">Hình ảnh</th>
                                        <th class="border-top-0">Đã bán</th>
                                        <th class="border-top-0">Tồn kho</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($worstSellers as $item)
                                    @include('admin.statistic.partials.product-row', ['item' => $item, 'qtyField' => 'sold'])
                                    @empty
                                    <tr><td colspan="4" class="text-center">Chưa có dữ liệu</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Mặt hàng sắp hết</h4>
                        <div class="table-responsive">
                            <table class="table table-centered table-hover table-xl mb-0">
                                <thead>
                                    <tr>
                                        <th class="border-top-0">Mã SP</th>
                                        <th class="border-top-0">Hình ảnh</th>
                                        <th class="border-top-0">Tồn kho</th>
                                        <th class="border-top-0">Tối thiểu</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($lowStock as $item)
                                    <tr>
                                        <td>{{ $item->product_id }}</td>
                                        <td>
                                            @php
                                            $img = $item->images && $item->images !== '[]'
                                                ? trim(explode(',', $item->images)[0])
                                                : '';
                                            @endphp
                                            <div class="media align-items-center">
                                                @if ($img)
                                                <div class="avatar avatar-image rounded m-r-10">
                                                    <img src="/{{ $img }}" alt="" style="width:60px;height:60px;object-fit:cover">
                                                </div>
                                                @endif
                                                <span>{{ $item->name }}</span>
                                            </div>
                                        </td>
                                        <td><span class="text-danger font-weight-semibold">{{ number_format($item->stock) }}</span></td>
                                        <td>{{ number_format($item->min_quantity) }}</td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="4" class="text-center">Không có sản phẩm sắp hết</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('js')
<script src="{{ asset('manager/assets/vendors/chartjs/Chart.min.js') }}"></script>
<script>
    window.StatisticCharts = {
        byDay: @json($revenueByDay),
        byMonth: @json($revenueByMonth)
    };
</script>
<script src="{{ asset('manager/assets/js/page/statistic-page.js') }}"></script>
@endsection
