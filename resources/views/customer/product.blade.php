@extends('customer.layout')
@section('title', $product->name)

@section('body')

<div class="sticky-header-next-sec ec-breadcrumb section-space-mb">
    <div class="container">
        <h2 class="ec-breadcrumb-title">{{ $product->name }}</h2>
    </div>
</div>

<section class="ec-page-content section-space-p">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                @if (count($product->image_list))
                <img src="/{{ ltrim($product->image_list[0], '/') }}" class="img-fluid" alt="{{ $product->name }}">
                @endif
            </div>
            <div class="col-lg-6">
                <h3>{{ $product->name }}</h3>
                <p class="text-muted">{{ $product->category_name }} · {{ $product->brand_name }}</p>
                <div class="m-b-15">{!! nl2br(e($product->description ?? '')) !!}</div>

                <form action="{{ route('customer.cart.add') }}" method="post">
                    @csrf
                    <div class="form-group m-b-15">
                        <label>Chọn biến thể</label>
                        <select name="product_var_id" class="form-control" required id="product-var-select">
                            <option value="">-- Chọn size / màu --</option>
                            @foreach ($product->variants as $var)
                            <option value="{{ $var->id }}" data-price="{{ $var->prices }}" data-stock="{{ $var->stock }}">
                                {{ $var->size_name }} / {{ $var->color_name }} — {{ number_format($var->prices) }}đ (Tồn: {{ $var->stock }})
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <p class="m-b-15"><strong id="product-price"></strong></p>
                    <div class="form-group m-b-15">
                        <label>Số lượng</label>
                        <input type="number" name="quantity" value="1" min="1" class="form-control" style="max-width:120px">
                    </div>
                    <button type="submit" class="btn btn-primary">Thêm vào giỏ</button>
                </form>
            </div>
        </div>

        @if (count($related))
        <div class="m-t-40">
            <h4>Sản phẩm liên quan</h4>
            <div class="row">
                @foreach ($related as $item)
                @include('customer.partials.product-card', ['product' => $item])
                @endforeach
            </div>
        </div>
        @endif
    </div>
</section>

@endsection

@section('js')
<script>
    $('#product-var-select').on('change', function () {
        const opt = $(this).find(':selected');
        const price = opt.data('price');
        $('#product-price').text(price ? Number(price).toLocaleString() + ' đ' : '');
    });
</script>
@endsection
