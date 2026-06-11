@extends('admin.layout')

@section('body')

<div class="page-content">
    <div class="container-fluid">
        <div id="warehouse-list" class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row m-b-10">
                            <div class="col-sm-12 col-md-6">
                                <h4 class="card-title">Quản lý kho hàng</h4>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <div class="align-justify-center">
                                    <a href="{{ route('admin.warehouse.history') }}" class="btn btn-default btn-sm m-r-10">Lịch sử nhập kho</a>
                                    <a href="#" class="btn btn-default btn-sm flex-right" data-action="show-create-form" data-module="warehouse">Nhập kho<i class="fas fa-plus m-l-5"></i></a>
                                </div>
                            </div>
                        </div>

                        <table class="table dt-responsive nowrap" id="data-table">
                            <thead>
                                <tr>
                                    <th width="5%">ID</th>
                                    <th>Tên sản phẩm</th>
                                    <th>Số lượng tồn</th>
                                    <th>Giá bán</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($stockItems as $item)
                                <tr>
                                    <td><div class="id-order">{{ $item->product_id ?? $item->proID }}</div></td>
                                    <td>{{ $item->name ?? $item->proName }}</td>
                                    <td>{{ number_format($item->quantity) }}</td>
                                    <td>{{ number_format($item->prices ?? $item->price) }} đ</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center">Chưa có hàng trong kho</td>
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

<div id="warehouse-create-form" class="d-none">
    <form action="{{ route('admin.warehouse.store') }}" method="post">
        @csrf
        <div class="col-12">
            <div class="card">
                <div class="card-body warehouse-modal">
                    <h5 class="card-title m-b-20">Phiếu nhập kho</h5>
                    <div class="item-list" id="warehouse-import-rows">
                        @include('admin.warehouse.partials.import-row', ['index' => 0, 'products' => $products])
                    </div>
                    <button type="button" class="btn btn-success btn-sm m-t-10" id="add-import-row">
                        <i class="fas fa-plus m-r-5"></i>Thêm dòng
                    </button>
                    <div class="m-t-20">
                        <button type="button" class="btn btn-default m-r-10" data-action="hide-form" data-module="warehouse">Hủy</button>
                        <button type="submit" class="btn btn-primary">Xác nhận nhập kho</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<template id="warehouse-import-row-template">
    @include('admin.warehouse.partials.import-row', ['index' => '__INDEX__', 'products' => $products])
</template>

@endsection

@section('js')
<script>
    window.WarehouseForm = {
        variantsUrl: "{{ url('admin/warehouse/product') }}"
    };
</script>
<script src="{{ asset('manager/assets/js/page/admin-list-form.js') }}"></script>
<script src="{{ asset('manager/assets/js/page/warehouse-form.js') }}"></script>
@endsection
