<tr class="product-var-row">
    <td>
        <input type="hidden" name="variants[{{ $index }}][id]" value="{{ $variant->proVarID ?? '' }}" class="var-id">
        <select class="form-control form-control-sm" name="variants[{{ $index }}][color_id]" required>
            <option value="">Màu</option>
            @foreach ($colors as $color)
            <option value="{{ $color->colorID }}">{{ $color->colorValue }}</option>
            @endforeach
        </select>
    </td>
    <td>
        <select class="form-control form-control-sm" name="variants[{{ $index }}][size_id]" required>
            <option value="">Size</option>
            @foreach ($sizes as $size)
            <option value="{{ $size->sizeID }}">{{ $size->sizeValue }}</option>
            @endforeach
        </select>
    </td>
    <td>
        <select class="form-control form-control-sm" name="variants[{{ $index }}][material_id]" required>
            <option value="">Chất liệu</option>
            @foreach ($materials as $material)
            <option value="{{ $material->mateID }}">{{ $material->mateName }}</option>
            @endforeach
        </select>
    </td>
    <td>
        <input type="text" class="form-control form-control-sm" name="variants[{{ $index }}][codeSKU]" value="{{ $variant->codeSKU ?? '' }}" placeholder="SKU" required>
    </td>
    <td>
        <input type="number" class="form-control form-control-sm" name="variants[{{ $index }}][prices]" value="{{ $variant->price ?? '' }}" min="0" placeholder="Giá" required>
    </td>
    <td>
        <input type="number" class="form-control form-control-sm" name="variants[{{ $index }}][minQuantity]" value="{{ $variant->minQuantity ?? 1 }}" min="1" required>
    </td>
    <td class="text-center">
        <button type="button" class="btn btn-default btn-sm remove-var-row" title="Xóa dòng"><i class="feather-trash"></i></button>
    </td>
</tr>
