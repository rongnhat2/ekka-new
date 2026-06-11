<tr>
    <td><div class="id-order">{{ $order->ordID }}</div></td>
    <td>
        @if (!empty($order->order_type))
        <span class="badge badge-info badge-pill m-b-5">Offline</span>
        @endif
        <p class="m-b-5"><i class="far fa-user m-r-10"></i>{{ $order->username }}</p>
        <p class="m-b-5"><i class="far fa-envelope m-r-10"></i>{{ $order->email }}</p>
        <p class="m-b-0"><i class="fas fa-phone-alt m-r-10"></i>{{ $order->telephone }}</p>
    </td>
    <td>
        <div class="d-flex align-items-center m-b-5">
            <div class="badge badge-primary badge-dot m-r-10"></div>
            <div>Tạm tính: {{ number_format($order->subtotal) }} đ</div>
        </div>
        <div class="d-flex align-items-center m-b-5">
            <div class="badge badge-secondary badge-dot m-r-10"></div>
            <div>Giảm giá: {{ $order->discount }} %</div>
        </div>
        <div class="d-flex align-items-center">
            <div class="badge badge-success badge-dot m-r-10"></div>
            <div>Thực tính: {{ number_format($order->totalPrice) }} đ</div>
        </div>
    </td>
    <td>{{ $order->ordDate }}</td>
    <td>
        <div class="badge {{ $statusBadges[$order->staValue] ?? 'badge-secondary' }} badge-pill m-b-5">
            {{ $statusLabels[$order->staValue] ?? 'N/A' }}
        </div>
        @if ($order->payment_status > 0)
        <div class="badge {{ $paymentBadges[$order->payment_status] ?? '' }} badge-pill">
            {{ $paymentLabels[$order->payment_status] ?? '' }}
        </div>
        @endif
    </td>
    <td>
        <span class="view-data modal-fs-control" style="cursor:pointer" data-id="{{ $order->ordID }}" title="Xem chi tiết">
            <i class="feather-eye"></i>
        </span>
    </td>
</tr>
