@extends('admin.layout')

@section('body')

<div class="page-content">
    <div class="container-fluid">
        <div id="color-list" class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row m-b-10">
                            <div class="col-sm-12 col-md-6">
                                <h4 class="card-title">Màu sắc</h4>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <div class="align-justify-center">
                                    <a href="#" class="btn btn-default btn-sm flex-right" data-action="show-create-form" data-module="color">Thêm màu<i class="fas fa-plus m-l-5"></i></a>
                                </div>
                            </div>
                        </div>
                        <table class="table dt-responsive nowrap" id="data-table">
                            <thead>
                                <tr>
                                    <th>Tên màu</th>
                                    <th>Mã màu</th>
                                    <th width="15%">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($colors as $color)
                                <tr>
                                    <td>{{ $color->colorValue }}</td>
                                    <td>
                                        <span class="d-inline-block rounded" style="width:20px;height:20px;background:{{ $color->hex }};vertical-align:middle;"></span>
                                        {{ $color->hex }}
                                    </td>
                                    <td>
                                        <a href="#" class="btn btn-default btn-sm"
                                            data-action="show-edit-form"
                                            data-module="color"
                                            data-id="{{ $color->colorID }}"
                                            data-name="{{ $color->colorValue }}"
                                            data-hex="{{ $color->hex }}"><i class="feather-edit"></i></a>
                                        <form action="{{ route('admin.color.delete', $color->colorID) }}" method="POST" class="d-inline" onsubmit="return confirm('Xóa màu này?')">
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

<div id="color-create-form" class="d-none">
    <form class="row" action="{{ route('admin.color.store') }}" method="post">
        @csrf
        <div class="col-6 offset-3">
            <div class="card">
                <div class="card-body">
                    <div class="form-group">
                        <label for="color-create-name">Tên màu</label>
                        <input type="text" class="form-control" name="name" id="color-create-name" placeholder="Tên màu">
                    </div>
                    <div class="form-group">
                        <label for="color-create-hex">Mã màu (hex)</label>
                        <input type="color" class="form-control" name="hex" id="color-create-hex" value="#000000">
                    </div>
                    <div class="form-group">
                        <button type="button" class="btn btn-default m-r-10" data-action="hide-form" data-module="color">Hủy</button>
                        <button type="submit" class="btn btn-primary">Tạo mới</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<div id="color-edit-form" class="d-none">
    <form class="row" action="{{ route('admin.color.update') }}" method="post">
        @csrf
        <input type="hidden" name="id">
        <div class="col-6 offset-3">
            <div class="card">
                <div class="card-body">
                    <div class="form-group">
                        <label for="color-edit-name">Tên màu</label>
                        <input type="text" class="form-control" name="name" id="color-edit-name" placeholder="Tên màu">
                    </div>
                    <div class="form-group">
                        <label for="color-edit-hex">Mã màu (hex)</label>
                        <input type="color" class="form-control" name="hex" id="color-edit-hex">
                    </div>
                    <div class="form-group">
                        <button type="button" class="btn btn-default m-r-10" data-action="hide-form" data-module="color">Hủy</button>
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