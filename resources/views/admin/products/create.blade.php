@extends('admin.layouts.app')

@section('content')
<div class="breadcrumbs">
    <div class="breadcrumbs-inner">
        <div class="row m-0">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1>Thêm sản phẩm</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
                            <li><a href="{{ route('admin.dashboard') }}">Trang chủ</a></li>
                            <li><a href="{{ route('admin.products.index') }}">Sản phẩm</a></li>
                            <li class="active">Thêm mới</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="animated fadeIn">
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Thêm sản phẩm mới</h5>
            </div>
            <div class="card-body">

                {{-- Hiển thị lỗi --}}
                @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                {{-- Form --}}
                <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    {{-- Dòng 1: Tên, Danh mục --}}
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="font-weight-bold">Tên sản phẩm <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="font-weight-bold">Danh mục <span class="text-danger">*</span></label>
                            <select name="category_id" class="form-control" required>
                                <option value="">-- Chọn danh mục --</option>
                                @foreach ($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Dòng 2: Brand, Mô tả ngắn --}}
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="font-weight-bold">Nhà sản xuất <span class="text-danger">*</span></label>
                            <select name="brand_id" class="form-control" required>
                                <option value="">-- Chọn nhà sản xuất --</option>
                                @foreach ($brands as $brand)
                                <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                                    {{ $brand->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="font-weight-bold">Mô tả ngắn <span class="text-danger">*</span></label>
                            <textarea name="short_description" rows="2" class="form-control" required>{{ old('short_description') }}</textarea>
                        </div>
                    </div>

                    {{-- Dòng 3: Giá, Sale, Số lượng --}}
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="font-weight-bold">Giá gốc <span class="text-danger">*</span></label>
                            <input type="number" name="price" step="0.01" class="form-control" value="{{ old('price') }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="font-weight-bold">Giá sale</label>
                            <input type="number" name="sale_price" step="0.01" class="form-control" value="{{ old('sale_price') }}">
                        </div>
                        {{-- <div class="col-md-4">
                            <label class="font-weight-bold">Số lượng <span class="text-danger">*</span></label>
                            <input type="number" name="stock" min="0" class="form-control" value="{{ old('stock', 0) }}" required>
                        </div> --}}
                    </div>

                    {{-- Dòng 4: Ngày sale --}}
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="font-weight-bold">Bắt đầu sale</label>
                            <input type="date" name="sale_price_start_at" class="form-control" value="{{ old('sale_price_start_at') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="font-weight-bold">Kết thúc sale</label>
                            <input type="date" name="sale_price_end_at" class="form-control" value="{{ old('sale_price_end_at') }}">
                        </div>
                    </div>

                    {{-- Dòng 5: Tùy chọn hiển thị --}}
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="font-weight-bold d-block">Tùy chọn</label>
                            <div class="form-check form-check-inline me-4">
                                <input class="form-check-input" type="checkbox" name="is_sale" value="1" {{ old('is_sale') ? 'checked' : '' }}>
                                <label class="form-check-label">Đang sale</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="is_active" value="1" {{ old('is_active', 1) ? 'checked' : '' }}>
                                <label class="form-check-label">Hiển thị</label>
                            </div>
                        </div>
                    </div>

                    {{-- Mô tả chi tiết --}}
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label class="font-weight-bold">Mô tả chi tiết <span class="text-danger">*</span></label>
                            <textarea name="description" rows="5" class="form-control" required>{{ old('description') }}</textarea>
                        </div>
                    </div>

                    {{-- Ảnh đại diện và ảnh chi tiết --}}
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="font-weight-bold">Ảnh đại diện</label>
                            <input type="file" name="thumbnail" id="thumbnailInput" class="form-control" accept="image/*" required>
                            <div class="mt-3" id="thumbnailPreviewArea" style="display:none;">
                                <img id="thumbnailPreview" src="" alt="Preview" class="img-thumbnail" style="max-width: 200px;">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="font-weight-bold">Ảnh chi tiết (có thể chọn nhiều)</label>
                            <input type="file" name="images[]" id="imagesInput" class="form-control" accept="image/*" multiple>
                            <div class="mt-3 d-flex flex-wrap gap-2" id="imagesPreviewArea"></div>
                        </div>
                    </div>

                    {{-- Thuộc tính và biến thể --}}
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label class="font-weight-bold">Quản lý biến thể</label>
                            <button type="button" class="btn btn-outline-primary mb-3" id="toggle-attributes">
                                <i class="fa fa-cogs me-1"></i> Chọn thuộc tính và giá trị biến thể
                            </button>
                            <div id="attributes-select-area" style="display:none;">
                                {{-- Thuộc tính động --}}
                                @foreach($attributes as $attribute)
                                <div class="mb-3">
                                    <strong>{{ $attribute->name }}</strong>
                                    <select name="attribute_values[{{ $attribute->id }}][]" class="form-control attribute-value-select" multiple>
                                        @foreach($attribute->attributeValues as $value)
                                        <option value="{{ $value->id }}">{{ $value->value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @endforeach

                                {{-- Nút quản lý biến thể --}}
                                <div class="mt-3 d-flex gap-2 flex-wrap">
                                    <button type="button" class="btn btn-outline-success" id="generate-variants">
                                        <i class="fa fa-magic me-1"></i> Tạo tự động
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary" id="add-variant">
                                        <i class="fa fa-plus me-1"></i> Thêm thủ công
                                    </button>
                                    <button type="button" class="btn btn-outline-danger" id="clear-variants">
                                        <i class="fa fa-trash me-1"></i> Xóa tất cả
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- Bảng biến thể --}}
                        <div class="col-12 mt-3" id="variants-table"></div>
                    </div>

                    {{-- Nút lưu, quay lại và thêm thuộc tính mới --}}
                    <div class="row mt-4 align-items-center">
                        <div class="col-md-6">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="fa fa-save"></i> Lưu sản phẩm
                            </button>
                            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                                <i class="fa fa-arrow-left"></i> Quay lại
                            </a>
                        </div>
                        <div class="col-md-6 text-end">
                            <a href="{{ route('admin.attributes.create') }}" class="btn btn-success">
                                <i class="fa fa-plus me-1"></i> Thêm thuộc tính mới
                            </a>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

{{-- Script biến thể --}}
@php
$attributesData = [];
foreach ($attributes as $attribute) {
$attributesData[$attribute->id] = $attribute->attributeValues->map(fn($v) => ['id' => $v->id, 'value' => $v->value])->toArray();
}
@endphp

@push('scripts')
<script>
    $(function() {
        const attributesData = @json($attributesData);
        let manualVariantIndex = 0;

        function cartesian(arr) {
            return arr.reduce((a, b) =>
                a.flatMap(d => b.map(e =>
                    Array.isArray(d) ? [...d, e] : [d, e]
                ))
            );
        }

        // Toggle thuộc tính
        $('#toggle-attributes').on('click', function() {
            const attributesArea = $('#attributes-select-area');
            const isHidden = attributesArea.css('display') === 'none';
            attributesArea.toggle();
            $(this).html(isHidden ? '<i class="fa fa-eye-slash me-1"></i> Ẩn thuộc tính và giá trị biến thể' : '<i class="fa fa-cogs me-1"></i> Chọn thuộc tính và giá trị biến thể');
        });

        // Xóa tất cả biến thể
        $('#clear-variants').on('click', function() {
            $('#variants-table').html('');
        });

        // Tạo biến thể tự động
        $('#generate-variants').on('click', function() {
            const selects = $('.attribute-value-select');
            const allValues = [];

            selects.each(function() {
                const sel = $(this);
                const attrIdMatch = sel.attr('name').match(/\d+/);
                const attrId = attrIdMatch ? attrIdMatch[0] : null;

                const vals = sel.find(':selected').map(function() {
                    return {
                        id: $(this).val(),
                        text: $(this).text(),
                        attribute_id: attrId
                    };
                }).get();

                if (vals.length) allValues.push(vals);
            });

            if (allValues.length === 0) {
                $('#variants-table').html('<p class="text-danger">Vui lòng chọn ít nhất một giá trị thuộc tính để tạo biến thể.</p>');
                return;
            }

            const combos = cartesian(allValues);
            let html = '<table class="table"><thead><tr>' +
                '<th>Biến thể</th><th>Giá</th><th>Số lượng</th><th>SKU</th><th>Ảnh</th><th>Hành động</th>' +
                '</tr></thead><tbody>';

            combos.forEach((combo, i) => {
                const label = combo.map(v => v.text).join(' - ');
                html += `<tr data-variant-index="${i}"><td>`;
                combo.forEach(v => {
                    html += `<input type="hidden" name="variants[${i}][attribute_id][]" value="${v.attribute_id}">`;
                    html += `<input type="hidden" name="variants[${i}][attribute_value_id][]" value="${v.id}">`;
                });
                html += `${label}</td>`;
                html += `<td><input type="number" name="variants[${i}][price]" step="0.01" class="form-control" required></td>`;
                html += `<td><input type="number" name="variants[${i}][stock]" class="form-control" min="0" value="0" required></td>`;
                html += `<td><input type="text" name="variants[${i}][sku]" class="form-control"></td>`;
                html += `<td><input type="file" name="variants[${i}][thumbnail]" class="form-control" accept="image/*"></td>`;
                html += `<td><button type="button" class="btn btn-danger btn-sm remove-variant"><i class="fa fa-times"></i></button></td>`;
                html += '</tr>';
            });

            html += '</tbody></table>';
            $('#variants-table').html(html);
        });

        // Thêm biến thể thủ công
        $('#add-variant').on('click', function() {
            let table = $('#variants-table');

            if ($.trim(table.html()) === '') {
                table.html(`<table class="table"><thead><tr>
                <th>Tên biến thể</th><th>Giá</th><th>Số lượng</th><th>SKU</th><th>Ảnh</th><th>Hành động</th>
                </tr></thead><tbody></tbody></table>`);
            }

            let tbody = table.find('tbody');

            let html = `<tr data-variant-index="manual_${manualVariantIndex}">
            <td><input type="text" name="manual_variants[${manualVariantIndex}][name]" class="form-control" placeholder="Tên biến thể" required></td>
            <td><input type="number" name="manual_variants[${manualVariantIndex}][price]" step="0.01" class="form-control" required></td>
            <td><input type="number" name="manual_variants[${manualVariantIndex}][stock]" class="form-control" min="0" value="0" required></td>
            <td><input type="text" name="manual_variants[${manualVariantIndex}][sku]" class="form-control"></td>
            <td><input type="file" name="manual_variants[${manualVariantIndex}][thumbnail]" class="form-control" accept="image/*"></td>
            <td><button type="button" class="btn btn-danger btn-sm remove-variant"><i class="fa fa-times"></i></button></td>
        </tr>`;

            tbody.append(html);
            manualVariantIndex++;
        });

        // Remove variant row
        $(document).on('click', '.remove-variant', function() {
            $(this).closest('tr').remove();
            // If no rows left, clear the table header too
            if ($('#variants-table tbody tr').length === 0) {
                $('#variants-table').html('');
            }
        });

        // Preview ảnh đại diện
        $('#thumbnailInput').on('change', function() {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    $('#thumbnailPreview').attr('src', e.target.result);
                    $('#thumbnailPreviewArea').show();
                };
                reader.readAsDataURL(this.files[0]);
            } else {
                $('#thumbnailPreviewArea').hide();
                $('#thumbnailPreview').attr('src', '');
            }
        });

        // Preview nhiều ảnh chi tiết
        $('#imagesInput').on('change', function() {
            $('#imagesPreviewArea').html('');

            Array.from(this.files).forEach(file => {
                if (file && file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const imgWrapper = $('<div></div>').css({
                            border: '1px solid #ddd',
                            padding: '5px',
                            marginRight: '10px',
                            marginBottom: '10px',
                            maxWidth: '150px'
                        });

                        const img = $('<img>').attr('src', e.target.result).css('width', '100%'); // Changed to 100% to fit wrapper

                        imgWrapper.append(img);
                        $('#imagesPreviewArea').append(imgWrapper);
                    };
                    reader.readAsDataURL(file);
                }
            });
        });
    });
</script>
@endpush

@endsection
