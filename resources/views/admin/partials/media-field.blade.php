<div class="media-field">
    <input type="hidden" class="media-value-input" name="{{ $name }}" value="{{ $value ?? '' }}">
    <div class="media-preview-list m-b-10"></div>
    <button type="button" class="btn btn-default btn-sm open-media-picker" data-mode="{{ $mode ?? 'multiple' }}">
        <i class="fas fa-images m-r-5"></i>{{ $label ?? 'Chọn từ thư viện' }}
    </button>
</div>
