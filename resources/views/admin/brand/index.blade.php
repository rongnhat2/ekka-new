@extends('admin.layout')

@section('body')

<div class="page-content">
    <div class="container-fluid">
        <div id="brand-list" class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row m-b-10">
                            <div class="col-sm-12 col-md-6">
                                <h4 class="card-title">Thương hiệu</h4>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <div class="align-justify-center">
                                    <a href="#" class="btn btn-default btn-sm flex-right" data-action="show-create-form" data-module="brand">Thêm thương hiệu<i class="fas fa-plus m-l-5"></i></a>
                                </div>
                            </div>
                        </div>
                        <table class="table dt-responsive  nowrap" id="data-table">
                            <thead>
                                <tr>
                                    <th>Tên thương hiệu</th>
                                    <th>Mô tả</th>
                                    <th width="15%">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($brands as $brand)
                                <tr>
                                    <td>{{ $brand->brandName }}</td>
                                    <td>{{ $brand->brandDesc }}</td>
                                    <td>
                                        <a href="#" class="btn btn-default btn-sm"
                                            data-action="show-edit-form"
                                            data-module="brand"
                                            data-id="{{ $brand->brandID }}"
                                            data-name="{{ $brand->brandName }}"
                                            data-description="{{ $brand->brandDesc }}"><i class="feather-edit"></i></a>
                                        <form action="{{ route('admin.brand.delete', $brand->brandID) }}" method="POST" class="d-inline" onsubmit="return confirm('Xóa thương hiệu này?')">
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

<div id="brand-create-form" class="d-none">
    <form class="row" action="{{ route('admin.brand.store') }}" method="post">
        @csrf
        <div class="col-6 offset-3">
            <div class="card">
                <div class="card-body">
                    <div class="form-group">
                        <label for="brand-create-name">Tên thương hiệu</label>
                        <input type="text" class="form-control" name="name" id="brand-create-name" placeholder="Tên thương hiệu">
                    </div>
                    <div class="form-group">
                        <label for="brand-create-description">Mô tả</label>
                        <textarea class="form-control" name="description" id="brand-create-description" rows="3" placeholder="Mô tả thương hiệu"></textarea>
                    </div>
                    <div class="form-group">
                        <button type="button" class="btn btn-default m-r-10" data-action="hide-form" data-module="brand">Hủy</button>
                        <button type="submit" class="btn btn-primary">Tạo mới</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<div id="brand-edit-form" class="d-none">
    <form class="row" action="{{ route('admin.brand.update') }}" method="post">
        @csrf
        <input type="hidden" name="id">
        <div class="col-6 offset-3">
            <div class="card">
                <div class="card-body">
                    <div class="form-group">
                        <label for="brand-edit-name">Tên thương hiệu</label>
                        <input type="text" class="form-control" name="name" id="brand-edit-name" placeholder="Tên thương hiệu">
                    </div>
                    <div class="form-group">
                        <label for="brand-edit-description">Mô tả</label>
                        <textarea class="form-control" name="description" id="brand-edit-description" rows="3" placeholder="Mô tả thương hiệu"></textarea>
                    </div>
                    <div class="form-group">
                        <button type="button" class="btn btn-default m-r-10" data-action="hide-form" data-module="brand">Hủy</button>
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