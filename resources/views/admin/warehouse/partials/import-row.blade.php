<div class="item-product warehouse-import-row">
    <div class="warehouse-import-col">
        <select class="form-control warehouse-product-select" name="items[{{ $index }}][product_id]" required>
            <option value="">-- Chọn sản phẩm --</option>
            @foreach ($products as $product)
            <option value="{{ $product->proID }}">{{ $product->proID }} - {{ $product->proName }}</option>
            @endforeach
        </select>
    </div>
    <div class="warehouse-import-col">
        <select class="form-control warehouse-var-select" name="items[{{ $index }}][product_var_id]" required>
            <option value="">-- Chọn biến thể --</option>
        </select>
    </div>
    <div class="warehouse-import-col">
        <input type="number" class="form-control" name="items[{{ $index }}][quantity]" min="1" placeholder="Số lượng" required>
    </div>
    <div class="warehouse-import-col">
        <input type="number" class="form-control" name="items[{{ $index }}][price]" min="0" placeholder="Đơn giá nhập" required>
    </div>
    <div class="warehouse-import-col warehouse-import-action">
        <button type="button" class="btn btn-danger btn-sm remove-import-row" title="Xóa dòng"><i class="fas fa-times"></i></button>
    </div>
</div>
