<div class="modal fade" id="media-picker-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Chọn ảnh từ thư viện</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="d-flex justify-content-between align-items-center m-b-15">
                    <small class="text-muted media-picker-hint">Chọn ảnh rồi bấm Xác nhận</small>
                    <a href="{{ route('admin.media.index') }}" target="_blank" class="btn btn-default btn-sm">Mở thư viện</a>
                </div>
                <div id="media-picker-grid" class="media-grid media-picker-grid"></div>
                <p id="media-picker-empty" class="text-muted text-center d-none m-t-20">Thư viện trống. Hãy upload ảnh trước.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-primary" id="media-picker-confirm">Xác nhận</button>
            </div>
        </div>
    </div>
</div>
