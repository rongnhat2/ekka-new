@extends('admin.layout')

@section('body')

<div class="page-content">
    <div class="container-fluid">
        <div id="product-list" class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row m-b-10">
                            <div class="col-sm-12 col-md-6">
                                <h4 class="card-title">Sản phẩm</h4>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <div class="align-justify-center">
                                    <a href="#" class="btn btn-default btn-sm flex-right" data-action="show-create-form" data-module="product">Thêm sản phẩm<i class="fas fa-plus m-l-5"></i></a>
                                </div>
                            </div>
                        </div>
                        <table class="table dt-responsive nowrap" id="data-table">
                            <thead>
                                <tr>
                                    <th width="5%">ID</th>
                                    <th width="10%">Sản phẩm</th>
                                    <th width="10%">Thông tin</th>
                                    <th width="20%">Hình ảnh</th>
                                    <th width="30%">Biến thể</th>
                                    <th width="10%">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($products as $product)
                                @include('admin.product.partials.list-row', ['product' => $product])
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center">Chưa có sản phẩm</td>
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

<div id="product-create-form" class="d-none">
    <form action="{{ route('admin.product.store') }}" method="post">
        @csrf
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title m-b-20">Thông tin sản phẩm</h5>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>Tên sản phẩm</label>
                            <input type="text" class="form-control" name="name" placeholder="Tên sản phẩm" required>
                        </div>
                        <div class="col-md-3 form-group">
                            <label>Danh mục</label>
                            <select class="form-control" name="category_id" required>
                                <option value="">-- Chọn danh mục --</option>
                                @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 form-group">
                            <label>Thương hiệu</label>
                            <select class="form-control" name="brand_id" required>
                                <option value="">-- Chọn thương hiệu --</option>
                                @foreach ($brands as $brand)
                                <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Mô tả ngắn</label>
                            <textarea class="form-control" name="description" rows="2" placeholder="Mô tả ngắn"></textarea>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Chi tiết</label>
                            <textarea class="form-control" name="detail" rows="2" placeholder="Mô tả chi tiết"></textarea>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Ảnh sản phẩm</label>
                            @include('admin.partials.media-field', ['name' => 'images', 'mode' => 'multiple', 'label' => 'Chọn ảnh sản phẩm'])
                        </div>
                        <div class="col-md-3 form-group">
                            <label>Banner</label>
                            @include('admin.partials.media-field', ['name' => 'banner', 'mode' => 'single', 'label' => 'Chọn banner'])
                        </div>
                        <div class="col-md-3 form-group">
                            <label>Trạng thái</label>
                            <select class="form-control" name="status">
                                <option value="1">Hiển thị</option>
                                <option value="0">Ẩn</option>
                            </select>
                        </div>
                    </div>

                    <hr>
                    <div class="d-flex justify-content-between align-items-center m-b-15">
                        <div>
                            <h5 class="card-title m-0">Biến thể sản phẩm</h5>
                            <small class="text-muted">Tồn kho sẽ được quản lý tại module Kho hàng</small>
                        </div>
                        <button type="button" class="btn btn-default btn-sm add-var-row" data-target="product-create-vars">
                            <i class="fas fa-plus m-r-5"></i>Thêm biến thể
                        </button>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Màu</th>
                                    <th>Size</th>
                                    <th>Chất liệu</th>
                                    <th>SKU</th>
                                    <th>Giá</th>
                                    <th>SL tối thiểu</th>
                                    <th width="50"></th>
                                </tr>
                            </thead>
                            <tbody id="product-create-vars"></tbody>
                        </table>
                    </div>

                    <div class="m-t-20">
                        <button type="button" class="btn btn-default m-r-10" data-action="hide-form" data-module="product">Hủy</button>
                        <button type="submit" class="btn btn-primary">Tạo sản phẩm</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<div id="product-edit-form" class="d-none">
    <form action="{{ route('admin.product.update') }}" method="post">
        @csrf
        <input type="hidden" name="id">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title m-b-20">Cập nhật sản phẩm</h5>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>Tên sản phẩm</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        <div class="col-md-3 form-group">
                            <label>Danh mục</label>
                            <select class="form-control" name="category_id" required>
                                <option value="">-- Chọn danh mục --</option>
                                @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 form-group">
                            <label>Thương hiệu</label>
                            <select class="form-control" name="brand_id" required>
                                <option value="">-- Chọn thương hiệu --</option>
                                @foreach ($brands as $brand)
                                <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Mô tả ngắn</label>
                            <textarea class="form-control" name="description" rows="2"></textarea>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Chi tiết</label>
                            <textarea class="form-control" name="detail" rows="2"></textarea>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Ảnh sản phẩm</label>
                            @include('admin.partials.media-field', ['name' => 'images', 'mode' => 'multiple', 'label' => 'Chọn ảnh sản phẩm'])
                        </div>
                        <div class="col-md-3 form-group">
                            <label>Banner</label>
                            @include('admin.partials.media-field', ['name' => 'banner', 'mode' => 'single', 'label' => 'Chọn banner'])
                        </div>
                        <div class="col-md-3 form-group">
                            <label>Trạng thái</label>
                            <select class="form-control" name="status">
                                <option value="1">Hiển thị</option>
                                <option value="0">Ẩn</option>
                            </select>
                        </div>
                    </div>

                    <hr>
                    <div class="d-flex justify-content-between align-items-center m-b-15">
                        <div>
                            <h5 class="card-title m-0">Biến thể sản phẩm</h5>
                            <small class="text-muted">Tồn kho sẽ được quản lý tại module Kho hàng</small>
                        </div>
                        <button type="button" class="btn btn-default btn-sm add-var-row" data-target="product-edit-vars">
                            <i class="fas fa-plus m-r-5"></i>Thêm biến thể
                        </button>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Màu</th>
                                    <th>Size</th>
                                    <th>Chất liệu</th>
                                    <th>SKU</th>
                                    <th>Giá</th>
                                    <th>SL tối thiểu</th>
                                    <th width="50"></th>
                                </tr>
                            </thead>
                            <tbody id="product-edit-vars"></tbody>
                        </table>
                    </div>

                    <div class="m-t-20">
                        <button type="button" class="btn btn-default m-r-10" data-action="hide-form" data-module="product">Hủy</button>
                        <button type="submit" class="btn btn-primary">Cập nhật</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<template id="product-var-row-template">
    @include('admin.product.partials.var-row', [
    'index' => '__INDEX__',
    'variant' => null,
    'colors' => $colors,
    'sizes' => $sizes,
    'materials' => $materials,
    ])
</template>

@endsection

@section('js')
<script src="{{ asset('manager/assets/js/page/admin-list-form.js') }}"></script>
<script src="{{ asset('manager/assets/js/page/product-form.js') }}"></script>
@endsection