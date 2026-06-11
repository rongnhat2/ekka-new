@extends('admin.layout')

@section('title', 'Đơn hàng')

@section('body')

@php
$tabs = [
    'all' => ['label' => 'Tất cả đơn hàng', 'badge' => 'badge-primary'],
    0 => ['label' => 'Chờ xử lí', 'badge' => 'badge-warning'],
    1 => ['label' => 'Chưa hoàn thiện', 'badge' => 'badge-secondary'],
    2 => ['label' => 'Đã hoàn thiện', 'badge' => 'badge-info'],
    3 => ['label' => 'Đã giao hàng', 'badge' => 'badge-success'],
    4 => ['label' => 'Hoàn trả', 'badge' => 'badge-danger'],
];
$statusBadges = [
    0 => 'badge-warning',
    1 => 'badge-secondary',
    2 => 'badge-info',
    3 => 'badge-success',
    4 => 'badge-danger',
];
$paymentBadges = [
    0 => '',
    1 => 'badge-gold',
    2 => 'badge-green',
];
@endphp

<div class="page-content">
    <div class="container-fluid">
        <div class="row I-warehouse">
            <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2 col-xl-2">
                <div class="card">
                    <div class="card-body">
                        <div class="status-list">
                            @foreach ($tabs as $status => $tab)
                            <a href="{{ $status === 'all' ? route('admin.order.index', ['status' => 'all']) : route('admin.order.index', ['status' => $status]) }}"
                                class="status-event {{ (string) $currentStatus === (string) $status ? 'is-select' : '' }}">
                                <div class="d-flex align-items-center">
                                    <div class="badge {{ $tab['badge'] }} badge-dot m-r-10"></div>
                                    <div>{{ $tab['label'] }}</div>
                                </div>
                            </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-10 col-lg-10 col-xl-10">
                <div class="card">
                    <div class="card-body">
                        <div class="row m-b-10">
                            <div class="col-md-12 text-md-right">
                                <a href="{{ route('admin.order.create') }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-plus m-r-5"></i>Tạo đơn offline
                                </a>
                            </div>
                        </div>
                        @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                        @endif
                        @if (request('updated'))
                        <div class="alert alert-success">Đã cập nhật trạng thái đơn hàng.</div>
                        @endif
                        <div class="m-t-25 data-table-wrapper">
                            <table id="data-table" class="table dt-responsive nowrap">
                                <thead>
                                    <tr>
                                        <th width="5%">ID</th>
                                        <th>Thông tin</th>
                                        <th>Đơn hàng</th>
                                        <th>Ngày đặt</th>
                                        <th>Trạng thái</th>
                                        <th width="10%">Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($orders as $order)
                                    @include('admin.order.partials.list-row', [
                                        'order' => $order,
                                        'statusBadges' => $statusBadges,
                                        'paymentBadges' => $paymentBadges,
                                        'statusLabels' => $statusLabels,
                                        'paymentLabels' => $paymentLabels,
                                    ])
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center">Không có đơn hàng</td>
                                    </tr>
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

@section('sub_layout')

<div class="modal-fullscreen" id="update-modal">
    <div class="fs-wrapper">
        <div class="fs-body">
            <div class="fs-title">
                <h4 class="modal-title"></h4>
                <div class="modal-close">
                    <i class="fas fa-times"></i>
                </div>
            </div>
            <div class="fs-content is-scrolling">
                <div class="fs-content-wrapper"></div>
            </div>
            <div class="fs-footer">
                <button type="button" class="btn btn-default close-modal m-r-10"></button>
                <button type="button" class="btn btn-primary push-modal" atr="Push"></button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('js')
<script>
    window.OrderDetail = {
        dataUrl: "{{ url('admin/order') }}",
        updateUrl: "{{ route('admin.order.update') }}",
        indexUrl: "{{ route('admin.order.index') }}",
        csrf: "{{ csrf_token() }}"
    };
</script>
<script src="{{ asset('manager/assets/js/page/order-detail.js') }}"></script>
@endsection
