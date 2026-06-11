@extends('admin.layout')



@section('body')

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row m-b-10">
                            <div class="col-sm-12 col-md-6">
                                <h4 class="card-title">Danh mục sản phẩm</h4>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <div class="align-justify-center">
                                    <a href="#" class="btn btn-default btn-sm flex-right modal-control" data-toggle="modal" atr="Create">Danh mục<i class="fas fa-plus m-l-5"></i></a>
                                </div>
                            </div>
                        </div>
                        <table class="table dt-responsive nowrap">
                            <thead>
                                <tr>
                                    <th>Tên danh mục</th>
                                    <th>Slug</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($categories as $category) : ?>
                                    <tr>
                                        <td><?php echo $category->name; ?></td>
                                        <td><?php echo $category->slug; ?></td>
                                        <td><a href="#" class="btn btn-default btn-sm">Sửa</a> <a href="#" class="btn btn-default btn-sm">Xóa</a></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<form class="row" action="/admin/category/store" method="post">
    @csrf
    <div class="col-6 offset-3">
        <div class="card">
            <div class="card-body">
                <div class="error-log"></div>
                <div class="form-group">
                    <label for="name">Tên danh mục</label>
                    <input type="text" class="form-control" name="name" id="name" placeholder="Tên danh mục">
                </div>
                <div class="form-group">
                    <button type="button" class="btn btn-default close-modal m-r-10">Hủy</button>
                    <button type="submit" class="btn btn-primary">Tạo mới</button>
                </div>
            </div>
        </div>
    </div>
</form>

@endsection

@section('js')

@endsection