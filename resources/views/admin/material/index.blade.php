@extends('admin.layout')

@section('body')

<div class="page-content">
    <div class="container-fluid">
        <div id="material-list" class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row m-b-10">
                            <div class="col-sm-12 col-md-6">
                                <h4 class="card-title">Chất liệu</h4>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <div class="align-justify-center">
                                    <a href="#" class="btn btn-default btn-sm flex-right" data-action="show-create-form" data-module="material">Thêm chất liệu<i class="fas fa-plus m-l-5"></i></a>
                                </div>
                            </div>
                        </div>
                        <table class="table dt-responsive nowrap" id="data-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Tên chất liệu</th>
                                    <th width="15%">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($materials as $material)
                                <tr>
                                    <td>{{ $material->id }}</td>
                                    <td>{{ $material->name }}</td>
                                    <td>
                                        <a href="#" class="btn btn-default btn-sm"
                                            data-action="show-edit-form"
                                            data-module="material"
                                            data-id="{{ $material->id }}"
                                            data-name="{{ $material->name }}"><i class="feather-edit"></i></a>
                                        <form action="{{ route('admin.material.delete', $material->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Xóa chất liệu này?')">
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

<div id="material-create-form" class="d-none">
    <form class="row" action="{{ route('admin.material.store') }}" method="post">
        @csrf
        <div class="col-6 offset-3">
            <div class="card">
                <div class="card-body">
                    <div class="form-group">
                        <label for="material-create-name">Tên chất liệu</label>
                        <input type="text" class="form-control" name="name" id="material-create-name" placeholder="VD: Cotton, Polyester">
                    </div>
                    <div class="form-group">
                        <button type="button" class="btn btn-default m-r-10" data-action="hide-form" data-module="material">Hủy</button>
                        <button type="submit" class="btn btn-primary">Tạo mới</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<div id="material-edit-form" class="d-none">
    <form class="row" action="{{ route('admin.material.update') }}" method="post">
        @csrf
        <input type="hidden" name="id">
        <div class="col-6 offset-3">
            <div class="card">
                <div class="card-body">
                    <div class="form-group">
                        <label for="material-edit-name">Tên chất liệu</label>
                        <input type="text" class="form-control" name="name" id="material-edit-name" placeholder="VD: Cotton, Polyester">
                    </div>
                    <div class="form-group">
                        <button type="button" class="btn btn-default m-r-10" data-action="hide-form" data-module="material">Hủy</button>
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