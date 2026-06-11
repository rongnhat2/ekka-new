<tr>
    <td>{{ $item->proID ?? $item->product_id }}</td>
    <td>
        @php
        $imgSource = $item->IMG ?? $item->images ?? '';
        $img = $imgSource && $imgSource !== '[]'
            ? trim(explode(',', $imgSource)[0])
            : '';
        @endphp
        <div class="media align-items-center">
            @if ($img)
            <div class="avatar avatar-image rounded m-r-10">
                <img src="/{{ $img }}" alt="" style="width:60px;height:60px;object-fit:cover">
            </div>
            @endif
            <span>{{ $item->proName ?? $item->name }}</span>
        </div>
    </td>
    <td>{{ number_format($item->{$qtyField}) }}</td>
    <td>{{ number_format($item->stock) }}</td>
</tr>
