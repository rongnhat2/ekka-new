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
                                <h4 class="card-title">Lịch sử nhập kho</h4>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <div class="align-justify-center">
                                    <a href="{{ route('admin.warehouse.index') }}" class="btn btn-default btn-sm flex-right">Quản lý kho</a>
                                </div>
                            </div>
                        </div>

                        <table class="table dt-responsive nowrap" id="data-table">
                            <thead>
                                <tr>
                                    <th width="5%">ID</th>
                                    <th>Người nhập kho</th>
                                    <th>Tổng giá trị</th>
                                    <th>Thời gian</th>
                                    <th width="10%">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($imports as $import)
                                <tr>
                                    <td><div class="id-order">{{ $import->importID }}</div></td>
                                    <td>{{ $import->adminEmail ?? 'N/A' }}</td>
                                    <td>{{ number_format($import->total_price) }} đ</td>
                                    <td>{{ $import->improtDate ?? $import->created_at }}</td>
                                    <td>
                                        <a href="{{ route('admin.warehouse.show', $import->importID) }}" class="btn btn-default btn-sm" title="Xem chi tiết">
                                            <i class="feather-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">Chưa có lịch sử nhập kho</td>
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

@endsection
