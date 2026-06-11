@extends('admin.layout')

@section('body')

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row m-b-20">
                            <div class="col-sm-12 col-md-6">
                                <h4 class="card-title m-b-5">Thư viện ảnh</h4>
                                <p class="text-muted m-b-0">Upload và quản lý ảnh dùng chung cho sản phẩm</p>
                            </div>
                            <div class="col-sm-12 col-md-6 text-md-right">
                                <label class="btn btn-primary btn-sm m-b-0">
                                    <i class="fas fa-upload m-r-5"></i>Upload ảnh
                                    <input type="file" id="media-upload-input" accept="image/*" multiple hidden>
                                </label>
                            </div>
                        </div>

                        <div id="media-upload-zone" class="media-upload-zone m-b-20">
                            <i class="fas fa-cloud-upload-alt fa-2x m-b-10"></i>
                            <p class="m-b-0">Kéo thả ảnh vào đây hoặc bấm Upload ảnh</p>
                            <small class="text-muted">Tối đa 5MB / ảnh — JPG, PNG, GIF, WEBP</small>
                        </div>

                        <div id="media-grid" class="media-grid">
                            @forelse ($media as $item)
                            <div class="media-grid-item" data-id="{{ $item->id }}" data-path="{{ $item->path }}">
                                <div class="media-grid-thumb" style="background-image:url('/{{ $item->path }}')"></div>
                                <div class="media-grid-meta">
                                    <span class="media-grid-name" title="{{ $item->name }}">{{ $item->name }}</span>
                                    <button type="button" class="btn btn-default btn-sm media-delete-btn" data-id="{{ $item->id }}" title="Xóa">
                                        <i class="feather-trash"></i>
                                    </button>
                                </div>
                            </div>
                            @empty
                            <p class="text-muted media-grid-empty">Chưa có ảnh nào trong thư viện</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('js')
<script>
    window.MediaLibrary = {
        uploadUrl: "{{ route('admin.media.store') }}",
        deleteUrl: "{{ url('admin/media/delete') }}",
        csrf: "{{ csrf_token() }}"
    };
</script>
<script src="{{ asset('manager/assets/js/page/media-library.js') }}"></script>
@endsection
