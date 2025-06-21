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
    <label for="category_id">Danh mục</label>
    <select class="form-control" id="category_id" name="category_id" required>
        <option value="">-- Chọn danh mục --</option>
        @foreach ($categories as $category)
            <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                {{ $category->name }}
            </option>
        @endforeach
    </select>
</div>
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
    <h4 class="mt-4">Biến thể sản phẩm</h4>
@if($product->variants->count())
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Giá</th>
                    <th>SKU</th>
                    <th>Ảnh</th>
                    <th>Giá trị thuộc tính</th>
                    <th>Xóa</th>
                </tr>
            </thead>
            <tbody>
                @foreach($product->variants as $i => $variant)
                    <tr>
                        <td>
                            <input type="number" name="variants[{{ $i }}][price]" class="form-control" value="{{ old("variants.$i.price", $variant->price) }}" required>
                        </td>
                        <td>
                            <input type="text" name="variants[{{ $i }}][sku]" class="form-control" value="{{ old("variants.$i.sku", $variant->sku) }}">
                        </td>
                        <td>
                            @if($variant->thumbnail)
                                <img src="{{ asset('storage/' . $variant->thumbnail) }}" alt="thumb" width="60"><br>
                            @endif
                            <input type="file" name="variants[{{ $i }}][thumbnail]" class="form-control-file" accept="image/*">
                        </td>
                        <td>
                            @foreach($variant->attributeValues as $attrValue)
                                <span class="badge badge-info">{{ $attrValue->attribute->name }}: {{ $attrValue->value }}</span>
                                <input type="hidden" name="variants[{{ $i }}][attribute_value_id][]" value="{{ $attrValue->id }}">
                            @endforeach
                        </td>
                        <td>
                            <input type="checkbox" name="variants[{{ $i }}][delete]" value="1"> Xóa
                        </td>
                        <input type="hidden" name="variants[{{ $i }}][id]" value="{{ $variant->id }}">
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@else
    <p>Chưa có biến thể nào cho sản phẩm này.</p>
@endif
<button type="submit" class="btn btn-primary mt-3">Cập nhật sản phẩm</button>
</form>


@endsection


