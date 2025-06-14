@extends('admin.layouts.app')

@section('content')
<h1>Thêm sản phẩm mới</h1>

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="form-group">
        <label for="category_id">Danh mục</label>
        <select name="category_id" id="category_id" class="form-control" >
            <option value="">-- Chọn danh mục --</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label for="brand_id">ID thương hiệu (brand_id)</label>
        <input type="number" class="form-control" id="brand_id" name="brand_id" value="{{ old('brand_id') }}" required>
    </div>

    <div class="form-group">
        <label for="name">Tên sản phẩm</label>
        <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
    </div>

    <div class="form-group">
        <label for="short_description">Mô tả ngắn</label>
        <textarea class="form-control" id="short_description" name="short_description" rows="3" required>{{ old('short_description') }}</textarea>
    </div>

    <div class="form-group">
        <label for="description">Mô tả dài</label>
        <textarea class="form-control" id="description" name="description" rows="5" required>{{ old('description') }}</textarea>
    </div>

    <div class="form-group">
        <label for="thumbnail">Ảnh đại diện (thumbnail) <span class="text-danger">*</span></label>
        <input type="file" class="form-control-file" id="thumbnail" name="thumbnail" accept="image/*" required>
    </div>

    <div class="form-group">
        <label for="images">Hình ảnh chi tiết</label>
        <input type="file" class="form-control-file" id="images" name="images[]" accept="image/*" multiple>
    </div>


    <div class="form-group">
        <label for="price">Giá gốc</label>
        <input type="number" step="0.01" class="form-control" id="price" name="price" value="{{ old('price') }}" required>
    </div>

    <div class="form-group">
        <label for="sale_price">Giá sale</label>
        <input type="number" step="0.01" class="form-control" id="sale_price" name="sale_price" value="{{ old('sale_price') }}">
    </div>

    <div class="form-group">
        <label for="sale_price_start_at">Bắt đầu sale</label>
        <input type="date" class="form-control" id="sale_price_start_at" name="sale_price_start_at" value="{{ old('sale_price_start_at') }}">
    </div>

    <div class="form-group">
        <label for="sale_price_end_at">Kết thúc sale</label>
        <input type="date" class="form-control" id="sale_price_end_at" name="sale_price_end_at" value="{{ old('sale_price_end_at') }}">
    </div>

    <div class="form-check">
        <input type="checkbox" class="form-check-input" id="is_sale" name="is_sale" value="1" {{ old('is_sale') ? 'checked' : '' }}>
        <label class="form-check-label" for="is_sale">Đang sale</label>
    </div>

    <div class="form-check">
        <input type="checkbox" class="form-check-input" id="is_featured" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
        <label class="form-check-label" for="is_featured">Sản phẩm nổi bật</label>
    </div>

    <div class="form-check">
        <input type="checkbox" class="form-check-input" id="is_trending" name="is_trending" value="1" {{ old('is_trending') ? 'checked' : '' }}>
        <label class="form-check-label" for="is_trending">Sản phẩm xu hướng</label>
    </div>

    <div class="form-check">
        <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" {{ old('is_active', 1) ? 'checked' : '' }}>
        <label class="form-check-label" for="is_active">Hiển thị</label>
    </div>
      <div id="variants">
    <div class="variant-row mb-3" style="display: flex; gap: 10px;">
        <select name="variants[0][color_id]" class="form-control" required>
            <option value="">Chọn màu</option>
            @foreach($colors as $color)
                <option value="{{ $color->id }}">{{ $color->value }}</option>
            @endforeach
        </select>
        <select name="variants[0][size_id]" class="form-control" required>
            <option value="">Chọn size</option>
            @foreach($sizes as $size)
                <option value="{{ $size->id }}">{{ $size->value }}</option>
            @endforeach
        </select>
        <input type="number" name="variants[0][price]" class="form-control" placeholder="Giá" required>
        <input type="number" name="variants[0][stock]" class="form-control" placeholder="Kho" required>

        <input type="file" name="variants[0][thumbnail]" class="form-control" accept="image/*">
        <button type="button" class="btn btn-danger remove-variant">X</button>
    </div>
</div>
<button type="button" class="btn btn-success mb-3" id="add-variant">+ Thêm biến thể</button>

<script>
let variantIndex = 1;
document.getElementById('add-variant').onclick = function() {
    let html = `
    <div class="variant-row mb-3" style="display: flex; gap: 10px;">
        <select name="variants[${variantIndex}][color_id]" class="form-control" required>
            <option value="">Chọn màu</option>

            @foreach($colors as $color)

                <option value="{{ $color->id }}">{{ $color->value }}</option>
            @endforeach
        </select>
        <select name="variants[${variantIndex}][size_id]" class="form-control" required>
            <option value="">Chọn size</option>
            @foreach($sizes as $size)
                <option value="{{ $size->id }}">{{ $size->value }}</option>
            @endforeach
        </select>
        <input type="number" name="variants[${variantIndex}][price]" class="form-control" placeholder="Giá" required>
        <input type="number" name="variants[${variantIndex}][stock]" class="form-control" placeholder="Kho" required>
        <input type="text" name="variants[${variantIndex}][sku]" class="form-control" placeholder="SKU (tùy chọn)">
        <input type="file" name="variants[${variantIndex}][thumbnail]" class="form-control" accept="image/*">
        <button type="button" class="btn btn-danger remove-variant">X</button>
    </div>
    `;
    document.getElementById('variants').insertAdjacentHTML('beforeend', html);
    variantIndex++;
};

document.addEventListener('click', function(e) {
    if (e.target.classList.contains('remove-variant')) {
        e.target.closest('.variant-row').remove();
    }
});
</script>
    <button type="submit" class="btn btn-primary mt-3">Thêm sản phẩm</button>
</form>
@endsection
