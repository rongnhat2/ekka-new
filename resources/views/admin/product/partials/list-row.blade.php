<tr>
    <td>
        <div class="id-order">{{ $product->proID }}</div>
    </td>
    <td>
        <p class="m-b-5">{{ $product->proName }}</p>
    </td>
    <td>
        <p class="m-b-5">Danh mục: <span class="meta-item-table d-inline-block">{{ $product->category_name ?? 'Chưa có' }}</span></p>
        <p class="m-b-5">Thương hiệu: <span class="meta-item-table d-inline-block">{{ $product->brand_name ?? 'Chưa có' }}</span></p>
        <p class="m-b-0" style="white-space: normal">Mô tả ngắn: {{ $product->proDesc ?? 'Chưa có' }}</p>
    </td>
    <td>
        @php
        $images = $product->IMG && $product->IMG !== '[]'
        ? array_filter(array_map('trim', explode(',', $product->IMG)))
        : [];
        @endphp
        @if (count($images))
        <div class="product-image-preview-list clearfix">
            @foreach ($images as $img)
            <div class="image-table-preview" style="background-image:url('/{{ $img }}')"></div>
            @endforeach
        </div>
        @endif
    </td>
    <td>
        @if (count($product->variants))
        <table class="meta-variant-table">
            <thead>
                <tr>
                    <th>Kích cỡ</th>
                    <th>Màu sắc</th>
                    <th>Chất liệu</th>
                    <th>Số lượng</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($product->variants as $var)
                <tr>
                    <td><span class="meta-item-table">{{ $var->size_name }}</span></td>
                    <td><span class="meta-item-table">{{ $var->color_name }}</span></td>
                    <td><span class="meta-item-table">{{ $var->material_name }}</span></td>
                    <td>
                        <span class="meta-item-table {{ (int) $var->minQuantity >= (int) $var->stock ? 'stock-warning' : '' }}"
                            title="Quản lý tại kho hàng">{{ $var->stock }}</span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <span class="text-muted">Chưa có biến thể</span>
        @endif
    </td>
    <td>
        <div class="view-data" style="cursor:pointer">
            <a href="#" class="btn btn-default btn-sm p-0 border-0 bg-transparent"
                data-action="show-product-edit"
                data-id="{{ $product->proID }}"><i class="feather-edit"></i></a>
        </div>
        <form action="{{ route('admin.product.delete', $product->proID) }}" method="POST" class="d-inline view-data" onsubmit="return confirm('Xóa sản phẩm và tất cả biến thể?')">
            @csrf
            <button type="submit" class="btn btn-default btn-sm p-0 border-0 bg-transparent"><i class="feather-trash"></i></button>
        </form>
    </td>
</tr>