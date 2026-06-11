@extends('admin.layout')

@section('body')

<div class="page-content">
    <div class="container-fluid">
        <div id="product-var-list" class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row m-b-10">
                            <div class="col-sm-12 col-md-6">
                                <h4 class="card-title">Biến thể sản phẩm</h4>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <div class="align-justify-center">
                                    <a href="#" class="btn btn-default btn-sm flex-right" data-action="show-create-form" data-module="product-var">Thêm biến thể<i class="fas fa-plus m-l-5"></i></a>
                                </div>
                            </div>
                        </div>
                        <table class="table dt-responsive nowrap">
                            <thead>
                                <tr>
                                    <th>Sản phẩm</th>
                                    <th>Màu</th>
                                    <th>Size</th>
                                    <th>Chất liệu</th>
                                    <th>SKU</th>
                                    <th>Giá</th>
                                    <th>Tồn kho</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($productVars as $var)
                                <tr>
                                    <td>{{ $var->product_name }}</td>
                                    <td>{{ $var->color_name }}</td>
                                    <td>{{ $var->size_name }}</td>
                                    <td>{{ $var->material_name }}</td>
                                    <td>{{ $var->codeSKU }}</td>
                                    <td>{{ number_format($var->prices) }}</td>
                                    <td>{{ $var->stock }}</td>
                                    <td>
                                        <a href="#" class="btn btn-default btn-sm"
                                           data-action="show-edit-form"
                                           data-module="product-var"
                                           data-id="{{ $var->id }}"
                                           data-product-id="{{ $var->product_id }}"
                                           data-color-id="{{ $var->color_id }}"
                                           data-size-id="{{ $var->size_id }}"
                                           data-material-id="{{ $var->material_id }}"
                                           data-codeSKU="{{ $var->codeSKU }}"
                                           data-prices="{{ $var->prices }}"
                                           data-stock="{{ $var->stock }}"
                                           data-minQuantity="{{ $var->minQuantity }}"><i class="feather-edit"></i></a>
                                        <form action="{{ route('admin.product_var.delete', $var->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Xóa biến thể này?')">
                                            @csrf
                                            <button type="submit" class="btn btn-default btn-sm"><i class="feather-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="product-var-create-form" class="d-none">
    <form class="row" action="{{ route('admin.product_var.store') }}" method="post">
        @csrf
        <div class="col-8 offset-2">
            <div class="card">
                <div class="card-body">
                    <div class="form-group">
                        <label>Sản phẩm</label>
                        <select class="form-control" name="product_id" required>
                            <option value="">-- Chọn sản phẩm --</option>
                            @foreach ($products as $product)
                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label>Màu</label>
                            <select class="form-control" name="color_id" required>
                                <option value="">-- Chọn màu --</option>
                                @foreach ($colors as $color)
                                <option value="{{ $color->id }}">{{ $color->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 form-group">
                            <label>Size</label>
                            <select class="form-control" name="size_id" required>
                                <option value="">-- Chọn size --</option>
                                @foreach ($sizes as $size)
                                <option value="{{ $size->id }}">{{ $size->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 form-group">
                            <label>Chất liệu</label>
                            <select class="form-control" name="material_id" required>
                                <option value="">-- Chọn chất liệu --</option>
                                @foreach ($materials as $material)
                                <option value="{{ $material->id }}">{{ $material->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>Mã SKU</label>
                            <input type="text" class="form-control" name="codeSKU" placeholder="Mã SKU">
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Giá</label>
                            <input type="number" class="form-control" name="prices" min="0" placeholder="Giá bán">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>Tồn kho</label>
                            <input type="number" class="form-control" name="stock" min="0" placeholder="Số lượng tồn">
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Số lượng tối thiểu</label>
                            <input type="number" class="form-control" name="minQuantity" min="1" value="1">
                        </div>
                    </div>
                    <div class="form-group">
                        <button type="button" class="btn btn-default m-r-10" data-action="hide-form" data-module="product-var">Hủy</button>
                        <button type="submit" class="btn btn-primary">Tạo mới</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<div id="product-var-edit-form" class="d-none">
    <form class="row" action="{{ route('admin.product_var.update') }}" method="post">
        @csrf
        <input type="hidden" name="id">
        <div class="col-8 offset-2">
            <div class="card">
                <div class="card-body">
                    <div class="form-group">
                        <label>Sản phẩm</label>
                        <select class="form-control" name="product_id" required>
                            <option value="">-- Chọn sản phẩm --</option>
                            @foreach ($products as $product)
                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label>Màu</label>
                            <select class="form-control" name="color_id" required>
                                <option value="">-- Chọn màu --</option>
                                @foreach ($colors as $color)
                                <option value="{{ $color->id }}">{{ $color->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 form-group">
                            <label>Size</label>
                            <select class="form-control" name="size_id" required>
                                <option value="">-- Chọn size --</option>
                                @foreach ($sizes as $size)
                                <option value="{{ $size->id }}">{{ $size->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 form-group">
                            <label>Chất liệu</label>
                            <select class="form-control" name="material_id" required>
                                <option value="">-- Chọn chất liệu --</option>
                                @foreach ($materials as $material)
                                <option value="{{ $material->id }}">{{ $material->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>Mã SKU</label>
                            <input type="text" class="form-control" name="codeSKU" placeholder="Mã SKU">
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Giá</label>
                            <input type="number" class="form-control" name="prices" min="0" placeholder="Giá bán">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>Tồn kho</label>
                            <input type="number" class="form-control" name="stock" min="0" placeholder="Số lượng tồn">
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Số lượng tối thiểu</label>
                            <input type="number" class="form-control" name="minQuantity" min="1" value="1">
                        </div>
                    </div>
                    <div class="form-group">
                        <button type="button" class="btn btn-default m-r-10" data-action="hide-form" data-module="product-var">Hủy</button>
                        <button type="submit" class="btn btn-primary">Cập nhật</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@endsection

@section('js')
<script src="{{ asset('manager/assets/js/page/admin-list-form.js') }}"></script>
@endsection
