@extends('customer.layout')
@section('title', 'Trang chủ')

@section('body')

<section class="section ec-product-tab section-space-p">
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <div class="section-title">
                    <h2 class="ec-bg-title">Top sản phẩm theo danh mục</h2>
                    <h2 class="ec-title">Top sản phẩm theo danh mục</h2>
                </div>
            </div>
            <div class="col-md-12 text-center">
                <ul class="ec-pro-tab-nav nav justify-content-center">
                    @foreach ($categories as $cat)
                    <li class="nav-item">
                        <a class="nav-link {{ ($activeCategory && $activeCategory->id == $cat->id) ? 'active' : '' }}"
                            href="{{ route('customer.view.category', ['tag' => $cat->id]) }}">{{ $cat->name }}</a>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
        <div class="row">
            @forelse ($categoryProducts as $product)
            @include('customer.partials.product-card', ['product' => $product])
            @empty
            <div class="col-12 text-center">Chưa có sản phẩm</div>
            @endforelse
        </div>
        @if ($activeCategory)
        <div class="col-sm-12 text-center m-t-20">
            <a href="{{ route('customer.view.category', ['tag' => $activeCategory->id]) }}" class="btn btn-primary">Xem thêm</a>
        </div>
        @endif
    </div>
</section>

<section class="section ec-new-product section-space-p">
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <div class="section-title">
                    <h2 class="ec-bg-title">Sản phẩm mới</h2>
                    <h2 class="ec-title">Sản phẩm mới</h2>
                </div>
            </div>
        </div>
        <div class="row">
            @foreach ($newProducts as $product)
            @include('customer.partials.product-card', ['product' => $product])
            @endforeach
        </div>
    </div>
</section>

@endsection
