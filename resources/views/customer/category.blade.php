@extends('customer.layout')
@section('title', $currentCategory->name ?? 'Danh mục')

@section('body')

<div class="sticky-header-next-sec ec-breadcrumb section-space-mb">
    <div class="container">
        <h2 class="ec-breadcrumb-title">{{ $currentCategory->name ?? 'Tất cả sản phẩm' }}</h2>
    </div>
</div>

<section class="ec-page-content section-space-p">
    <div class="container">
        <div class="row m-b-20">
            <div class="col-md-8">
                <form method="get" class="form-inline">
                    <input type="hidden" name="tag" value="{{ $categoryId }}">
                    <input type="text" name="keyword" class="form-control m-r-10" placeholder="Tìm kiếm..." value="{{ $keyword }}">
                    <select name="sort" class="form-control m-r-10" onchange="this.form.submit()">
                        <option value="0" {{ $sort == 0 ? 'selected' : '' }}>Mới nhất</option>
                        <option value="2" {{ $sort == 2 ? 'selected' : '' }}>Tên A-Z</option>
                        <option value="3" {{ $sort == 3 ? 'selected' : '' }}>Tên Z-A</option>
                    </select>
                    <button type="submit" class="btn btn-primary btn-sm">Lọc</button>
                </form>
            </div>
        </div>
        <div class="row">
            @forelse ($products as $product)
            @include('customer.partials.product-card', ['product' => $product])
            @empty
            <div class="col-12 text-center">Không tìm thấy sản phẩm</div>
            @endforelse
        </div>
        <div class="m-t-20">{{ $products->links() }}</div>
    </div>
</section>

@endsection
