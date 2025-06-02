@extends('admin.layouts.app')

@section('content')
<h1>Cập nhật sản phẩm</h1>

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="form-group">
        <label for="brand_id">ID thương hiệu (brand_id)</label>
        <input type="number" class="form-control" id="brand_id" name="brand_id" value="{{ old('brand_id', $product->brand_id) }}" required>
    </div>

    <div class="form-group">
        <label for="name">Tên sản phẩm</label>
        <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $product->name) }}" required>
    </div>

    <div class="form-group">
        <label for="short_description">Mô tả ngắn</label>
        <textarea class="form-control" id="short_description" name="short_description" rows="3" required>{{ old('short_description', $product->short_description) }}</textarea>
    </div>

    <div class="form-group">
        <label for="description">Mô tả dài</label>
        <textarea class="form-control" id="description" name="description" rows="5" required>{{ old('description', $product->description) }}</textarea>
    </div>

    <div class="form-group">
        <label for="thumbnail">Ảnh đại diện (thumbnail)</label>
        <input type="file" class="form-control-file" id="thumbnail" name="thumbnail" accept="image/*">
        @if ($product->thumbnail)
            <div class="mt-2">
                <img src="{{ asset('storage/' . $product->thumbnail) }}" alt="Thumbnail" width="100">
            </div>
        @endif
    </div>

    <div class="form-group">
        <label for="sku">Mã SKU</label>
        <input type="text" class="form-control" id="sku" name="sku" value="{{ old('sku', $product->sku) }}">
    </div>

    <div class="form-group">
        <label for="price">Giá gốc</label>
        <input type="number" step="0.01" class="form-control" id="price" name="price" value="{{ old('price', $product->price) }}" required>
    </div>

    <div class="form-group">
        <label for="sale_price">Giá sale</label>
        <input type="number" step="0.01" class="form-control" id="sale_price" name="sale_price" value="{{ old('sale_price', $product->sale_price) }}">
    </div>

    <div class="form-group">
        <label for="sale_price_start_at">Bắt đầu sale</label>
        <input type="date" class="form-control" id="sale_price_start_at" name="sale_price_start_at" value="{{ old('sale_price_start_at', optional($product->sale_price_start_at)->format('Y-m-d')) }}">
    </div>

    <div class="form-group">
        <label for="sale_price_end_at">Kết thúc sale</label>
        <input type="date" class="form-control" id="sale_price_end_at" name="sale_price_end_at" value="{{ old('sale_price_end_at', optional($product->sale_price_end_at)->format('Y-m-d')) }}">
    </div>

    <div class="form-check">
        <input type="checkbox" class="form-check-input" id="is_sale" name="is_sale" value="1" {{ old('is_sale', $product->is_sale) ? 'checked' : '' }}>
        <label class="form-check-label" for="is_sale">Đang sale</label>
    </div>

    <div class="form-check">
        <input type="checkbox" class="form-check-input" id="is_featured" name="is_featured" value="1" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}>
        <label class="form-check-label" for="is_featured">Sản phẩm nổi bật</label>
    </div>

    <div class="form-check">
        <input type="checkbox" class="form-check-input" id="is_trending" name="is_trending" value="1" {{ old('is_trending', $product->is_trending) ? 'checked' : '' }}>
        <label class="form-check-label" for="is_trending">Sản phẩm xu hướng</label>
    </div>

    <div class="form-check">
        <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
        <label class="form-check-label" for="is_active">Hiển thị</label>
    </div>

    <button type="submit" class="btn btn-primary mt-3">Cập nhật sản phẩm</button>
</form>
@endsection
