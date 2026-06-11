@extends('admin.layout')

@section('title', 'Tạo đơn offline')

@section('body')

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row m-b-20">
                            <div class="col-md-8">
                                <h4 class="card-title m-b-5">Tạo đơn hàng tại quầy (offline)</h4>
                                <p class="text-muted m-b-0">Nhân viên nhập thông tin khách và sản phẩm, hệ thống trừ kho và ghi nhận doanh thu ngay.</p>
                            </div>
                            <div class="col-md-4 text-md-right">
                                <a href="{{ route('admin.order.index') }}" class="btn btn-default btn-sm">Quay lại đơn hàng</a>
                            </div>
                        </div>

                        @if (session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif

                        <form action="{{ route('admin.order.store') }}" method="post">
                            @csrf
                            <div class="row m-b-20">
                                <div class="col-md-3">
                                    <label>Tên khách hàng <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="customer_name" required placeholder="Nguyễn Văn A">
                                </div>
                                <div class="col-md-3">
                                    <label>Số điện thoại <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="customer_phone" required placeholder="0901234567">
                                </div>
                                <div class="col-md-3">
                                    <label>Email</label>
                                    <input type="email" class="form-control" name="customer_email" placeholder="email@example.com">
                                </div>
                                <div class="col-md-3">
                                    <label>Giảm giá đơn (%)</label>
                                    <input type="number" class="form-control" name="discount" min="0" max="100" value="0">
                                </div>
                            </div>
                            <div class="row m-b-20">
                                <div class="col-md-12">
                                    <label>Địa chỉ</label>
                                    <input type="text" class="form-control" name="customer_address" placeholder="Địa chỉ khách hàng (tuỳ chọn)">
                                </div>
                            </div>

                            <h5 class="m-b-15">Sản phẩm</h5>
                            <div class="warehouse-modal">
                                <div class="item-list" id="offline-order-rows">
                                    @include('admin.order.partials.offline-row', ['index' => 0, 'products' => $products])
                                </div>
                                <button type="button" class="btn btn-success btn-sm m-t-10" id="add-offline-row">
                                    <i class="fas fa-plus m-r-5"></i>Thêm dòng
                                </button>
                            </div>

                            <div class="m-t-20">
                                <button type="submit" class="btn btn-primary">Xác nhận tạo đơn</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<template id="offline-order-row-template">
    @include('admin.order.partials.offline-row', ['index' => '__INDEX__', 'products' => $products])
</template>

@endsection

@section('js')
<script>
    window.OfflineOrderForm = {
        variantsUrl: "{{ url('admin/warehouse/product') }}"
    };
</script>
<script src="{{ asset('manager/assets/js/page/offline-order-form.js') }}"></script>
@endsection
