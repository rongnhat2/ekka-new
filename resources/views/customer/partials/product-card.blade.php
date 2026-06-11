<div class="col-lg-3 col-md-6 col-sm-6 col-xs-6 mb-6 ec-product-content">
    <div class="ec-product-inner">
        <div class="ec-pro-image-outer">
            <div class="ec-pro-image">
                <a href="{{ route('customer.view.product', $product->id) }}" class="image">
                    @if ($product->image_url ?? '')
                    <img class="main-image" src="{{ $product->image_url }}" alt="{{ $product->name }}" />
                    @else
                    <img class="main-image" src="{{ asset('customer/assets/images/product-image/1.jpg') }}" alt="{{ $product->name }}" />
                    @endif
                </a>
            </div>
        </div>
        <div class="ec-pro-content">
            <h5 class="ec-pro-title">
                <a href="{{ route('customer.view.product', $product->id) }}">{{ $product->name }}</a>
            </h5>
            @if (!empty($product->min_price))
            <span class="ec-price">
                <span class="new-price">{{ number_format($product->min_price) }} đ</span>
            </span>
            @endif
        </div>
    </div>
</div>
