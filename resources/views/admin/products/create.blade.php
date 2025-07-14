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
        <label for="brand_id">Nhà sản xuất (Brand)</label>
        <select name="brand_id" id="brand_id" class="form-control" required>
            <option value="">-- Chọn nhà sản xuất --</option>
            @foreach ($brands as $brand)
                <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                    {{ $brand->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label for="name">Tên sản phẩm</label>
        <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" >
    </div>

    <div class="form-group">
        <label for="short_description">Mô tả ngắn</label>
        <textarea class="form-control" id="short_description" name="short_description" rows="3" >{{ old('short_description') }}</textarea>
    </div>

    <div class="form-group">
        <label for="description">Mô tả dài</label>
        <textarea class="form-control" id="description" name="description" rows="5" >{{ old('description') }}</textarea>
    </div>
    <div class="form-group">
        <label for="stock">Số lượng</label>
        <input type="number" class="form-control" id="stock" name="stock" value="{{ old('stock', 0) }}" min="0" required>
    </div>
    <div class="form-group">
        <label for="thumbnail">Ảnh đại diện (thumbnail) <span class="text-danger">*</span></label>
        <input type="file" class="form-control-file" id="thumbnail" name="thumbnail" accept="image/*" >
    </div>

    <div class="form-group">
        <label for="images">Hình ảnh chi tiết</label>
        <input type="file" class="form-control-file" id="images" name="images[]" accept="image/*" multiple>
    </div>
    <div class="form-group">
        <label for="price">Giá gốc</label>
        <input type="number" step="0.01" class="form-control" id="price" name="price" value="{{ old('price') }}" >
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

   <div class="form-group">
    <button type="button" class="btn btn-info mb-2" id="toggle-attributes">Chọn thuộc tính và giá trị biến thể</button>

    <div id="attributes-select-area" style="display:none;">
    @foreach($attributes as $attribute)
        <div class="mb-2">
            <strong>{{ $attribute->name }}</strong>
            <select class="form-control attribute-value-select" name="attribute_values[{{ $attribute->id }}][]" multiple>
                @foreach($attribute->attributeValues as $value)
                    <option value="{{ $value->id }}">{{ $value->value }}{{ $value->hex ? ' ('.$value->hex.')' : '' }}</option>
                @endforeach
            </select>
        </div>

    @endforeach

    <button type="button" class="btn btn-danger mt-2" id="clear-variants">Xóa tất cả biến thể</button>
    <button type="button" class="btn btn-info mt-2" id="generate-variants">Tạo các biến thể</button>
</div>
</div>
<div id="variants-table"></div>
<button type="button" class="btn btn-success mb-3" id="add-variant">+ Thêm biến thể</button>

@php
    $attributesData = [];
    foreach ($attributes as $attribute) {
        $attributesData[$attribute->id] = $attribute->attributeValues->map(function($v) {
            return ['id' => $v->id, 'value' => $v->value];
        });
    }
@endphp

<script>
const attributesData = @json($attributesData);

// Hàm sinh tổ hợp
function cartesian(arr) {
    return arr.reduce(function(a, b) {
        return a.flatMap(d => b.map(e => [d, e].flat()));
    });
}

document.getElementById('generate-variants').onclick = function() {
    // Lấy các giá trị đã chọn
    let selects = document.querySelectorAll('.attribute-value-select');
    let allValues = [];
    let attributeIds = [];
    selects.forEach(sel => {
        // Lấy attribute_id từ name (attribute_values[ID][])
        let attrIdMatch = sel.name.match(/\d+/);
        let attrId = attrIdMatch ? attrIdMatch[0] : null;
        let vals = Array.from(sel.selectedOptions).map(opt => ({
            id: opt.value,
            text: opt.text,
            attribute_id: attrId
        }));
        if (vals.length) allValues.push(vals);
    });
    if (allValues.length < 1) return;

    // Sinh tổ hợp

   let combos = allValues.reduce((a, b) => a.flatMap(d => b.map(e => [].concat(d, e))));
let html = '<table class="table"><tr><th>Biến thể</th><th>Giá</th><th>Số lượng</th><th>SKU</th><th>Ảnh</th></tr>';
combos.forEach((combo, i) => {
    let label = combo.map(v => v.text).join(' - ');
    html += `<tr>
        <td>`;
    combo.forEach(v => {
        html += `<input type="hidden" name="variants[${i}][attribute_id][]" value="${v.attribute_id}">`;
        html += `<input type="hidden" name="variants[${i}][attribute_value_id][]" value="${v.id}">`;

    });
    html += `${label}</td>
        <td><input type="number" name="variants[${i}][price]" class="form-control" required></td>
        <td><input type="number" name="variants[${i}][quantity]" class="form-control" min="0" value="0" required></td>
        <td><input type="text" name="variants[${i}][sku]" class="form-control"></td>
        <td><input type="file" name="variants[${i}][thumbnail]" class="form-control" accept="image/*"></td>
    </tr>`;
});
html += '</table>';
document.getElementById('variants-table').innerHTML = html;
};

// Toggle ẩn/hiện phần chọn thuộc tính và đổi text nút
const toggleBtn = document.getElementById('toggle-attributes');
const attributesArea = document.getElementById('attributes-select-area');
toggleBtn.onclick = function() {
    if (attributesArea.style.display === '' || attributesArea.style.display === 'none') {
        attributesArea.style.display = 'block';
        toggleBtn.textContent = 'Ẩn thuộc tính và giá trị biến thể';
    } else {
        attributesArea.style.display = 'none';
        toggleBtn.textContent = 'Chọn thuộc tính và giá trị biến thể';
    }
};
</script>

<button type="submit" class="btn btn-primary mt-3">Thêm sản phẩm</button>
</form>
<a href="{{ route('admin.attributes.create') }}" class="btn btn-success mb-2">Thêm thuộc tính</a>
@endsection