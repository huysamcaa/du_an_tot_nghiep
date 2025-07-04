@extends('client.layouts.app')

@section('content')
<div class="container py-4">
    <div class="row">
        {{-- Cột trái: Danh mục và Bộ lọc --}}
        <div class="col-lg-3 col-md-4">
            <div class="category-sidebar bg-light p-3 rounded">
                {{-- Danh mục sản phẩm --}}
                <h5 class="mb-3">Danh mục sản phẩm</h5>
                <ul class="list-unstyled">
                    @foreach($categories as $category)
                        <li class="mb-1">
                            <a href="{{ route('category.products', $category->id) }}" class="text-dark fw-bold d-block">
                                {{ $category->name }}
                            </a>
                            @if($category->children->count())
                                <ul class="list-unstyled ps-3">
                                    @foreach($category->children as $child)
                                        <li>
                                            <a href="{{ route('category.products', $child->id) }}" class="text-secondary d-block">
                                                {{ $child->name }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </li>
                    @endforeach
                </ul>

                <hr class="my-4">

                {{-- Bộ lọc sản phẩm --}}
                <h5 class="mb-3">Lọc sản phẩm</h5>
                <form action="" method="GET">
                    {{-- Khoảng giá --}}
                    <div class="mb-4">
                        <h6>Khoảng giá</h6>
                        <input type="range" class="form-range" name="price" min="0" max="10000" step="10" oninput="document.getElementById('priceValue').innerText = this.value">
                        <small>Giá: $0 - $<span id="priceValue">10000</span></small>
                    </div>

                 {{-- Kích thước --}}
<div class="mb-4">
    <h6 class="mb-2">Kích thước</h6>
    <div class="d-flex flex-wrap gap-2">
        @foreach(['S', 'M', 'L', 'XL'] as $size)
            <input type="radio" class="btn-check size-check" name="size" value="{{ $size }}" id="size-{{ $size }}" autocomplete="off">
            <label class="btn btn-outline-secondary px-3 py-1 rounded" for="size-{{ $size }}">{{ $size }}</label>
        @endforeach
    </div>
</div>


                
{{-- Màu sắc --}}
<div class="mb-4">
    <h6 class="mb-2">Màu sắc</h6>
    <div class="d-flex flex-wrap gap-2">
        @foreach(['red', 'blue', 'green', 'orange', 'purple'] as $color)
            <input type="radio" class="btn-check color-check" name="color" value="{{ $color }}" id="color-{{ $color }}" autocomplete="off">
            <label class="color-circle" for="color-{{ $color }}" style="background-color: {{ $color }};" title="{{ ucfirst($color) }}"></label>
        @endforeach
    </div>
</div>




                    {{-- Thương hiệu --}}
                    <div class="mb-4">
                        <h6>Thương hiệu</h6>
                        @foreach(['Sony', 'Lenovo', 'Apple', 'Google', 'Unilever'] as $brand)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="brands[]" value="{{ $brand }}" id="brand-{{ $brand }}">
                                <label class="form-check-label" for="brand-{{ $brand }}">
                                    {{ $brand }}
                                </label>
                            </div>
                        @endforeach
                    </div>

                    <button class="btn btn-primary btn-sm mt-2" type="submit">Lọc</button>
                </form>
            </div>
        </div>

        {{-- Cột phải: Danh sách sản phẩm --}}
        <div class="col-lg-9 col-md-8">
            <div class="product-listing">
                <h4 class="mb-4">
                    @if(isset($currentCategory))
                        Sản phẩm thuộc danh mục: <strong>{{ $currentCategory->name }}</strong>
                    @endif
                </h4>

                <div class="row">
                    @forelse($products as $product)
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="card h-100">
                                <img src="{{ asset('images/products/' . $product->image) }}" class="card-img-top" alt="{{ $product->name }}">
                                <div class="card-body">
                                    <h5 class="card-title">
                                        <a href="{{ route('product.detail', $product->id) }}" class="text-decoration-none text-dark">
                                            {{ $product->name }}
                                        </a>
                                    </h5>
                                    <p class="card-text text-danger fw-bold">${{ $product->sale_price ?? $product->price }}</p>
                                </div>
                            </div>
                        </div>
                    @empty
                    @endforelse
                </div>

                <div class="mt-4">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
