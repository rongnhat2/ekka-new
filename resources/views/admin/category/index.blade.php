@extends('admin.layout')

@section('body')

<div class="page-content">
    <div class="container-fluid">
        <div id="category-list" class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row m-b-10">
                            <div class="col-sm-12 col-md-6">
                                <h4 class="card-title">Danh mục sản phẩm</h4>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <div class="align-justify-center">
                                    <a href="#" class="btn btn-default btn-sm flex-right" data-action="show-create-form" data-module="category">Danh mục<i class="fas fa-plus m-l-5"></i></a>
                                </div>
                            </div>
                        </div>

                        <table class="table dt-responsive nowrap" id="data-table">
                            <thead>
                                <tr>
                                    <th width="10%">ID</th>
                                    <th>Tên danh mục</th>
                                    <th width="15%">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($categories as $category)
                                <tr>
                                    <td>{{ $category->cateID }}</td>
                                    <td>{{ $category->cateName }}</td>
                                    <td>
                                        <a href="#" class="btn btn-default btn-sm"
                                            data-action="show-edit-form"
                                            data-module="category"
                                            data-id="{{ $category->cateID }}"
                                            data-name="{{ $category->cateName }}"><i class="feather-edit"></i></a>
                                        <form action="{{ route('admin.category.delete', $category->cateID) }}" method="POST" class="d-inline" onsubmit="return confirm('Xóa danh mục này?')">
                                            @csrf
                                            <button type="submit" class="btn btn-default btn-sm"><i class="feather-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center">Không có dữ liệu</td>
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

<div id="category-create-form" class="d-none">
    <form class="row" action="{{ route('admin.category.store') }}" method="post">
        @csrf
        <div class="col-6 offset-3">
            <div class="card">
                <div class="card-body">
                    <div class="form-group">
                        <label for="category-create-name">Tên danh mục</label>
                        <input type="text" class="form-control" name="name" id="category-create-name" placeholder="Tên danh mục">
                    </div>
                    <div class="form-group">
                        <button type="button" class="btn btn-default m-r-10" data-action="hide-form" data-module="category">Hủy</button>
                        <button type="submit" class="btn btn-primary">Tạo mới</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<div id="category-edit-form" class="d-none">
    <form class="row" action="{{ route('admin.category.update') }}" method="post">
        @csrf
        <input type="hidden" name="id">
        <div class="col-6 offset-3">
            <div class="card">
                <div class="card-body">
                    <div class="form-group">
                        <label for="category-edit-name">Tên danh mục</label>
                        <input type="text" class="form-control" name="name" id="category-edit-name" placeholder="Tên danh mục">
                    </div>
                    <div class="form-group">
                        <button type="button" class="btn btn-default m-r-10" data-action="hide-form" data-module="category">Hủy</button>
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
<script src="{{ asset('manager/assets/js/page/admin-table-filter.js') }}"></script>
@endsection