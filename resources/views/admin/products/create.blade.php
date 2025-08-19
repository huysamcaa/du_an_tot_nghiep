@extends('admin.layouts.app')

@section('content')
<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4>Chỉnh sửa sản phẩm</h4>
            <h6>Cập nhật thông tin sản phẩm</h6>
        </div>
    </div>

    <div class="card">
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

            {{-- Hiển thị thông báo thành công/lỗi từ session (ví dụ: không đổi tên được) --}}
            @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
            @endif
            @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
            @endif

            {{-- FORM CẬP NHẬT SẢN PHẨM --}}
            {{-- Đổi action sang route update và thêm @method('PUT') --}}
            <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT') {{-- BẮT BUỘC ĐỂ GỬI YÊU CẦU PUT --}}

                <div class="row">
                    <div class="col-lg-8">
                        <ul class="nav nav-tabs nav-tabs-solid mb-4" id="productTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="basic-tab" data-bs-toggle="tab" data-bs-target="#basic-info" type="button" role="tab" aria-controls="basic-info" aria-selected="true">
                                    <i class="fa fa-info-circle me-1"></i> Thông tin cơ bản
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="images-tab" data-bs-toggle="tab" data-bs-target="#product-images" type="button" role="tab" aria-controls="product-images" aria-selected="false">
                                    <i class="fa fa-images me-1"></i> Hình ảnh
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="variants-tab" data-bs-toggle="tab" data-bs-target="#product-variants" type="button" role="tab" aria-controls="product-variants" aria-selected="false">
                                    <i class="fa fa-sitemap me-1"></i> Biến thể
                                </button>
                            </li>
                        </ul>

                        <div class="tab-content" id="productTabContent">
                            {{-- Tab Thông tin cơ bản --}}
                            <div class="tab-pane fade show active" id="basic-info" role="tabpanel" aria-labelledby="basic-tab">
                                <div class="card p-4">
                                    <div class="form-group mb-3">
                                        <label>Tên sản phẩm <span class="text-danger">*</span></label>
                                        {{-- Điền dữ liệu cũ hoặc dữ liệu từ sản phẩm hiện tại --}}
                                        <input type="text" name="name" class="form-control" value="{{ old('name', $product->name) }}" required>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label>Mô tả ngắn <span class="text-danger">*</span></label>
                                        <textarea name="short_description" rows="2" class="form-control" required>{{ old('short_description', $product->short_description) }}</textarea>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label>Mô tả chi tiết <span class="text-danger">*</span></label>
                                        <textarea name="description" id="editor" rows="5" class="form-control" required>{{ old('description', $product->description) }}</textarea>
                                    </div>
                                    {{-- Thêm trường weight vào đây --}}
                                    <div class="form-group mb-3">
                                        <label>Cân nặng (kg)</label>
                                        <input type="number" step="0.01" name="weight" class="form-control" value="{{ old('weight', $product->weight) }}">
                                    </div>
                                </div>
                            </div>

                            {{-- Tab Hình ảnh --}}
                            <div class="tab-pane fade" id="product-images" role="tabpanel" aria-labelledby="images-tab">
                                <div class="card p-4">
                                    <div class="row">
                                        <div class="col-lg-6 col-sm-12 mb-3">
                                            <div class="form-group">
                                                <label>Ảnh đại diện</label> {{-- Bỏ required nếu có thể không thay ảnh --}}
                                                <div class="image-upload-wrapper">
                                                    <input type="file" name="thumbnail" id="thumbnailInput" accept="image/*">
                                                    <div class="image-uploads text-center p-3" @if($product->thumbnail) style="display:none;" @endif> {{-- Ẩn nếu đã có ảnh --}}
                                                        <img src="{{ asset('assets/admin/img/icons/upload.svg') }}" alt="upload" class="mb-2">
                                                        <h4>Kéo thả hoặc nhấn vào để tải lên</h4>
                                                    </div>
                                                    <div class="image-preview mt-3" id="thumbnailPreviewArea" @if(!$product->thumbnail) style="display:none;" @endif>
                                                        <img id="thumbnailPreview" src="{{ $product->thumbnail ? asset('storage/' . $product->thumbnail) : '' }}" alt="Ảnh đại diện" class="img-thumbnail w-100">
                                                        <button type="button" class="btn btn-sm btn-danger mt-2 w-100" id="removeThumbnail">Xóa ảnh</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-sm-12 mb-3">
                                            <div class="form-group">
                                                <label>Bộ sưu tập ảnh</label>
                                                <div class="image-upload-wrapper">
                                                    <input type="file" name="images[]" id="imagesInput" accept="image/*" multiple>
                                                    <div class="image-uploads text-center p-3">
                                                        <img src="{{ asset('assets/admin/img/icons/upload.svg') }}" alt="upload" class="mb-2">
                                                        <h4>Kéo thả hoặc nhấn vào để tải lên</h4>
                                                    </div>
                                                </div>
                                                <div class="mt-3 d-flex flex-wrap gap-2" id="imagesPreviewArea">
                                                    {{-- Hiển thị ảnh gallery hiện có --}}
                                                    @foreach($product->images as $image)
                                                        <div class="position-relative">
                                                            <img src="{{ asset('storage/' . $image->path) }}" alt="Gallery Image" class="img-thumbnail" style="width: 100px; height: 100px; object-fit: cover;">
                                                            <button type="button" class="btn btn-danger btn-sm delete-gallery-image" data-image-id="{{ $image->id }}" style="position: absolute; top: -5px; right: -5px; border-radius: 50%;">
                                                                <i class="fa fa-times"></i>
                                                            </button>
                                                            <input type="hidden" name="existing_images[]" value="{{ $image->id }}"> {{-- Giữ ID của ảnh hiện có --}}
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Tab Biến thể --}}
                            <div class="tab-pane fade" id="product-variants" role="tabpanel" aria-labelledby="variants-tab">
                                <div class="card p-4">
                                    <div class="form-group">
                                        <button type="button" class="btn btn-outline-primary w-100 mb-3" id="toggle-attributes">
                                            <i class="fa fa-cogs me-1"></i> Quản lý thuộc tính & Biến thể
                                        </button>
                                        <div id="attributes-select-area" style="display:none;" class="mt-3 p-3 border rounded">
                                            @foreach($attributes as $attribute)
                                            <div class="mb-3">
                                                <label class="form-label">
                                                    <strong>{{ $attribute->name }}</strong>
                                                </label>
                                                <select name="attribute_values[{{ $attribute->id }}][]" class="form-control attribute-value-select select2" multiple>
                                                    @foreach($attribute->attributeValues as $value)
                                                    <option value="{{ $value->id }}"
                                                        {{ in_array($value->id, old('attribute_values.'.$attribute->id, $product->variants->pluck('attributeValues')->flatten()->pluck('id')->toArray())) ? 'selected' : '' }}
                                                        >{{ $value->value }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            @endforeach

                                            <div class="mt-3 d-flex flex-wrap gap-2">
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
                                    <div class="mt-4" id="variants-table">
                                        {{-- Các biến thể hiện có sẽ được load bởi JS hoặc được đổ ra trực tiếp ở đây --}}
                                        {{-- Dạng dữ liệu cho biến thể hiện có --}}
                                        <div class="table-responsive">
                                            <table class="table table-striped table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>Tên biến thể</th>
                                                        <th>Giá</th>
                                                        <th>Số lượng</th>
                                                        <th>SKU</th>
                                                        <th>Chất liệu</th> {{-- TRƯỜNG MỚI --}}
                                                        <th>Ảnh</th>
                                                        <th>Hoạt động</th>
                                                        <th>Hành động</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($product->variants as $variant)
                                                    <tr data-variant-id="{{ $variant->id }}">
                                                        <td>
                                                            @php
                                                                $variantAttributes = $variant->attributeValues->pluck('value')->implode(' - ');
                                                            @endphp
                                                            <span class="fw-bold">{{ $variantAttributes ?: 'Biến thể thủ công' }}</span>
                                                            {{-- Thêm hidden inputs cho id và các thuộc tính nếu là biến thể tự động --}}
                                                            @foreach($variant->attributeValues as $attrValue)
                                                                <input type="hidden" name="variants[{{ $variant->id }}][attribute_id][]" value="{{ $attrValue->attribute_id }}">
                                                                <input type="hidden" name="variants[{{ $variant->id }}][attribute_value_id][]" value="{{ $attrValue->id }}">
                                                            @endforeach
                                                            {{-- Nếu là biến thể thủ công, cần input cho tên biến thể --}}
                                                            @if(empty($variantAttributes))
                                                                <input type="text" name="variants[{{ $variant->id }}][name]" class="form-control mt-1" placeholder="Tên biến thể thủ công" value="{{ old('variants.' . $variant->id . '.name', $variant->name) }}">
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <input type="number" name="variants[{{ $variant->id }}][price]" step="0.01" class="form-control" min="0" value="{{ old('variants.' . $variant->id . '.price', $variant->price) }}" required>
                                                        </td>
                                                        <td>
                                                            <input type="number" name="variants[{{ $variant->id }}][stock]" class="form-control" min="0" value="{{ old('variants.' . $variant->id . '.stock', $variant->stock) }}" required>
                                                        </td>
                                                        <td>
                                                            <input type="text" name="variants[{{ $variant->id }}][sku]" class="form-control" value="{{ old('variants.' . $variant->id . '.sku', $variant->sku) }}">
                                                        </td>
                                                        <td>
                                                            <input type="text" name="variants[{{ $variant->id }}][material]" class="form-control" value="{{ old('variants.' . $variant->id . '.material', $variant->material) }}"> {{-- TRƯỜNG MỚI --}}
                                                        </td>
                                                        <td>
                                                            <input type="file" name="variants[{{ $variant->id }}][thumbnail]" class="form-control" accept="image/*">
                                                            @if($variant->thumbnail)
                                                                <div class="mt-2 text-center">
                                                                    <img src="{{ asset('storage/' . $variant->thumbnail) }}" alt="Ảnh biến thể" class="img-thumbnail" style="width: 50px; height: 50px; object-fit: cover;">
                                                                    <button type="button" class="btn btn-sm btn-danger remove-variant-thumbnail" data-variant-id="{{ $variant->id }}">Xóa ảnh</button>
                                                                    <input type="hidden" name="variants[{{ $variant->id }}][current_thumbnail]" value="{{ $variant->thumbnail }}">
                                                                </div>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <div class="form-check form-switch">
                                                                <input class="form-check-input" type="checkbox" name="variants[{{ $variant->id }}][is_active]" role="switch" id="variant_active_{{ $variant->id }}" value="1" {{ old('variants.' . $variant->id . '.is_active', $variant->is_active) ? 'checked' : '' }}>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <button type="button" class="btn btn-danger btn-sm remove-existing-variant" data-variant-id="{{ $variant->id }}">
                                                                <i class="fa fa-times"></i>
                                                            </button>
                                                            {{-- Input hidden để đánh dấu biến thể sẽ bị xóa --}}
                                                            <input type="hidden" name="variants[{{ $variant->id }}][delete]" class="delete-flag" value="0">
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        {{-- Cột bên phải: Tổ chức & Tùy chọn --}}
                        <div class="card p-4 sticky-top" style="top: 100px;">
                            <h5 class="card-title">Tổ chức & Tùy chọn</h5>
                            <div class="form-group mb-3">
                                <label>Danh mục <span class="text-danger">*</span></label>
                                <select name="category_id" class="form-control select2" required>
                                    <option value="">-- Chọn danh mục --</option>
                                    @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group mb-3">
                                <label>Nhà sản xuất <span class="text-danger">*</span></label>
                                <select name="brand_id" class="form-control select2" required>
                                    <option value="">-- Chọn nhà sản xuất --</option>
                                    @foreach ($brands as $brand)
                                    <option value="{{ $brand->id }}" {{ old('brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }}>
                                        {{ $brand->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group mb-3">
                                <label class="d-block mb-1">Tùy chọn</label>
                                <div class="form-check form-check-inline me-4">
                                    <input class="form-check-input" type="checkbox" name="is_sale" value="1" id="is_sale" {{ old('is_sale', $product->is_sale) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_sale">Đang sale</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active" {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">Hiển thị</label>
                                </div>
                            </div>
                            <div class="text-end mt-4">
                                <button type="submit" class="btn btn-primary me-2">
                                    <i class="fa fa-save"></i> Cập nhật sản phẩm
                                </button>
                                <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                                    <i class="fa fa-arrow-left"></i> Quay lại
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Scripts --}}
@php
// Chuẩn bị dữ liệu thuộc tính để truyền sang JS, bao gồm cả các thuộc tính đã chọn của sản phẩm này
$attributesData = [];
foreach ($attributes as $attribute) {
    $attributeValues = $attribute->attributeValues->map(fn($v) => ['id' => $v->id, 'value' => $v->value])->toArray();
    $attributesData[$attribute->id] = $attributeValues;
}

// Chuẩn bị dữ liệu biến thể hiện có để truyền sang JS
$existingVariants = $product->variants->map(function($variant) {
    return [
        'id' => $variant->id,
        'price' => $variant->price,
        'stock' => $variant->stock,
        'sku' => $variant->sku,
        'material' => $variant->material, // Thêm trường mới
        'thumbnail' => $variant->thumbnail ? asset('storage/' . $variant->thumbnail) : null,
        'is_active' => $variant->is_active,
        'attributes' => $variant->attributeValues->map(function($av) {
            return [
                'id' => $av->id,
                'value' => $av->value,
                'attribute_id' => $av->attribute_id,
            ];
        })->toArray(),
        'name' => $variant->name // Dùng cho biến thể thủ công nếu không có thuộc tính
    ];
})->toArray();
@endphp

@push('scripts')
<script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(function() {
        CKEDITOR.replace('editor');
        $('.select2').select2({
            placeholder: "-- Chọn một tùy chọn --",
            allowClear: true
        });

        const attributesData = @json($attributesData);
        let manualVariantIndex = {{ count($product->variants) > 0 ? $product->variants->max('id') + 1 : 0 }}; // Bắt đầu index cho biến thể mới từ ID lớn nhất + 1 hoặc 0

        // Tải các biến thể hiện có vào một biến JavaScript để quản lý dễ hơn
        let currentVariants = @json($existingVariants);

        function cartesian(arr) {
            return arr.reduce((a, b) => a.flatMap(d => b.map(e => Array.isArray(d) ? [...d, e] : [d, e])), [
                []
            ]);
        }

        // Hàm để render lại bảng biến thể
        function renderVariantsTable() {
            let html = `<div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Tên biến thể</th>
                            <th>Giá</th>
                            <th>Số lượng</th>
                            <th>SKU</th>
                            <th>Chất liệu</th> {{-- TRƯỜNG MỚI --}}
                            <th>Ảnh</th>
                            <th>Hoạt động</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>`;

            if (currentVariants.length === 0) {
                html += `<tr><td colspan="8" class="text-center text-muted">Chưa có biến thể nào được thêm.</td></tr>`;
            } else {
                currentVariants.forEach((variant, index) => {
                    const isNewVariant = !variant.id; // Nếu không có ID, đây là biến thể mới được thêm
                    const inputNamePrefix = isNewVariant ? `new_variants[${variant.temp_id}]` : `variants[${variant.id}]`;

                    let variantLabel = 'Biến thể thủ công';
                    if (variant.attributes && variant.attributes.length > 0) {
                        variantLabel = variant.attributes.map(attr => attr.value).join(' - ');
                    } else if (variant.name) {
                        variantLabel = variant.name; // Dùng tên nếu là biến thể thủ công
                    }

                    html += `<tr data-variant-id="${variant.id || variant.temp_id}">
                        <td>`;
                    if (isNewVariant) {
                        html += `<input type="hidden" name="${inputNamePrefix}[color_id]" value="${variant.attributes.find(a => a.attribute_id == 1)?.id || ''}">`; // Giả định ID thuộc tính Color là 1
                        html += `<input type="hidden" name="${inputNamePrefix}[size_id]" value="${variant.attributes.find(a => a.attribute_id == 2)?.id || ''}">`; // Giả định ID thuộc tính Size là 2
                    } else {
                        // Đối với biến thể hiện có, cần các hidden inputs cho attribute_values
                        variant.attributes.forEach(attr => {
                            html += `<input type="hidden" name="${inputNamePrefix}[attribute_id][]" value="${attr.attribute_id}">`;
                            html += `<input type="hidden" name="${inputNamePrefix}[attribute_value_id][]" value="${attr.id}">`;
                        });
                    }
                    html += `<span class="fw-bold">${variantLabel}</span>`;
                    // Nếu là biến thể thủ công (hoặc biến thể tự động chưa có thuộc tính rõ ràng), cho phép chỉnh sửa tên
                    if (isNewVariant && (!variant.attributes || variant.attributes.length === 0)) {
                         html += `<input type="text" name="${inputNamePrefix}[name]" class="form-control mt-1" placeholder="Tên biến thể thủ công" value="${variant.name || ''}">`;
                    }
                    html += `</td>
                        <td>
                            <input type="number" name="${inputNamePrefix}[price]" step="0.01" class="form-control" min="0" value="${variant.price || 0}" required>
                        </td>
                        <td>
                            <input type="number" name="${inputNamePrefix}[stock]" class="form-control" min="0" value="${variant.stock || 0}" required>
                        </td>
                        <td>
                            <input type="text" name="${inputNamePrefix}[sku]" class="form-control" value="${variant.sku || ''}">
                        </td>
                        <td>
                            <input type="text" name="${inputNamePrefix}[material]" class="form-control" value="${variant.material || ''}"> {{-- TRƯỜNG MỚI --}}
                        </td>
                        <td>
                            <input type="file" name="${inputNamePrefix}[thumbnail]" class="form-control variant-thumbnail-input" accept="image/*" data-variant-id="${variant.id || variant.temp_id}">
                            <div class="variant-thumbnail-preview mt-2 text-center" id="variantThumbnailPreview_${variant.id || variant.temp_id}" ${variant.thumbnail ? '' : 'style="display:none;"'}>
                                <img src="${variant.thumbnail || ''}" alt="Ảnh biến thể" class="img-thumbnail" style="width: 50px; height: 50px; object-fit: cover;">
                                <button type="button" class="btn btn-sm btn-danger remove-variant-thumbnail" data-variant-id="${variant.id || variant.temp_id}">Xóa ảnh</button>
                                ${!isNewVariant && variant.thumbnail ? `<input type="hidden" name="${inputNamePrefix}[current_thumbnail_path]" value="${variant.thumbnail.replace('/storage/', '')}">` : ''}
                            </div>
                        </td>
                        <td>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="${inputNamePrefix}[is_active]" role="switch" id="${inputNamePrefix}_is_active" value="1" ${variant.is_active ? 'checked' : ''}>
                            </div>
                        </td>
                        <td>
                            <button type="button" class="btn btn-danger btn-sm remove-variant" data-variant-id="${variant.id || variant.temp_id}">
                                <i class="fa fa-times"></i>
                            </button>
                            ${!isNewVariant ? `<input type="hidden" name="variants[${variant.id}][delete]" class="delete-flag" value="0">` : ''}
                        </td>
                    </tr>`;
                });
            }

            html += `</tbody></table></div>`;
            $('#variants-table').html(html);

            // Re-initialize event listeners for newly added elements
            initVariantThumbnailUploads();
        }

        // Khởi tạo các sự kiện cho thumbnail biến thể
        function initVariantThumbnailUploads() {
            $('.variant-thumbnail-input').off('change').on('change', function(e) {
                const file = e.target.files[0];
                const variantId = $(this).data('variant-id');
                const previewArea = $(`#variantThumbnailPreview_${variantId}`);
                const previewImg = previewArea.find('img');
                const removeBtn = previewArea.find('.remove-variant-thumbnail');

                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewImg.attr('src', e.target.result);
                        previewArea.show();
                    };
                    reader.readAsDataURL(file);
                } else {
                    previewArea.hide();
                    previewImg.attr('src', '');
                }
            });

            $('.remove-variant-thumbnail').off('click').on('click', function() {
                const variantId = $(this).data('variant-id');
                const input = $(`input[name^="variants[${variantId}]"][type="file"], input[name^="new_variants[${variantId}]"][type="file"]`);
                const previewArea = $(`#variantThumbnailPreview_${variantId}`);

                input.val(''); // Xóa file đã chọn
                previewArea.hide();
                previewArea.find('img').attr('src', '');

                // Xóa input hidden lưu đường dẫn ảnh hiện tại (nếu có)
                previewArea.find('input[type="hidden"][name$="[current_thumbnail_path]"]').remove();
            });
        }

        // Tải biến thể hiện có khi trang load
        renderVariantsTable();

        $('#toggle-attributes').on('click', function() {
            const attributesArea = $('#attributes-select-area');
            const isHidden = attributesArea.css('display') === 'none';
            attributesArea.slideToggle();
            $(this).html(isHidden ? '<i class="fa fa-eye-slash me-1"></i> Ẩn thuộc tính & Biến thể' : '<i class="fa fa-cogs me-1"></i> Quản lý thuộc tính & Biến thể');
        });

        $('#clear-variants').on('click', function() {
            currentVariants = []; // Xóa tất cả biến thể trong mảng JS
            renderVariantsTable();
            $('.attribute-value-select').val(null).trigger('change');
        });

        $('#generate-variants').on('click', function() {
            const selects = $('.attribute-value-select');
            const selectedAttributeValues = []; // Lưu các giá trị thuộc tính đã chọn

            selects.each(function() {
                const sel = $(this);
                const attrIdMatch = sel.attr('name').match(/\d+/);
                const attrId = attrIdMatch ? attrIdMatch[0] : null;
                const vals = sel.find(':selected').map(function() {
                    return {
                        id: $(this).val(),
                        value: $(this).text(),
                        attribute_id: attrId
                    };
                }).get();
                if (vals.length) selectedAttributeValues.push(vals);
            });

            if (selectedAttributeValues.length === 0) {
                $('#variants-table').html('<p class="text-danger">Vui lòng chọn ít nhất một giá trị thuộc tính để tạo biến thể.</p>');
                return;
            }

            const generatedCombos = cartesian(selectedAttributeValues);
            const newVariants = [];

            generatedCombos.forEach(combo => {
                const comboAttributes = combo.sort((a, b) => a.attribute_id - b.attribute_id); // Đảm bảo thứ tự
                const comboKey = comboAttributes.map(v => v.id).join('-'); // Tạo key duy nhất cho combo

                // Kiểm tra xem biến thể này đã tồn tại trong currentVariants hay chưa
                const existing = currentVariants.find(v => {
                    if (!v.attributes || v.attributes.length === 0) return false; // Bỏ qua biến thể thủ công
                    const existingComboKey = v.attributes.map(a => a.id).sort((a,b)=>a-b).join('-');
                    return existingComboKey === comboKey;
                });

                if (existing) {
                    newVariants.push(existing); // Giữ lại biến thể hiện có
                } else {
                    // Tạo biến thể mới nếu chưa có
                    newVariants.push({
                        temp_id: `new_${manualVariantIndex++}`, // ID tạm thời cho biến thể mới
                        price: 0,
                        stock: 0,
                        sku: '',
                        material: '', // Thêm trường mới
                        thumbnail: null,
                        is_active: true,
                        attributes: comboAttributes,
                    });
                }
            });

            currentVariants = newVariants; // Cập nhật mảng biến thể
            renderVariantsTable();
        });

        $('#add-variant').on('click', function() {
            currentVariants.push({
                temp_id: `new_${manualVariantIndex++}`, // ID tạm thời
                name: '', // Cho biến thể thủ công
                price: 0,
                stock: 0,
                sku: '',
                material: '', // Thêm trường mới
                thumbnail: null,
                is_active: true,
                attributes: [] // Không có thuộc tính nếu là thủ công
            });
            renderVariantsTable();
        });

        $(document).on('click', '.remove-variant', function() {
            const variantIdToRemove = $(this).data('variant-id');

            // Nếu là biến thể hiện có (có ID thật), đánh dấu để xóa
            if (String(variantIdToRemove).indexOf('new_') === -1) { // Kiểm tra nếu không phải biến thể mới
                // Tìm input hidden delete-flag và set value = 1
                const deleteFlagInput = $(`tr[data-variant-id="${variantIdToRemove}"] .delete-flag`);
                if (deleteFlagInput.length) {
                    deleteFlagInput.val('1');
                }
                // Ẩn hàng thay vì xóa hẳn để giữ input hidden
                $(this).closest('tr').hide();
                // Xóa khỏi currentVariants (nếu bạn muốn xóa hẳn khỏi danh sách hiển thị)
                currentVariants = currentVariants.filter(v => v.id !== variantIdToRemove);
            } else {
                // Nếu là biến thể mới (có temp_id), xóa hẳn khỏi DOM và mảng JS
                $(this).closest('tr').remove();
                currentVariants = currentVariants.filter(v => v.temp_id !== variantIdToRemove);
            }

            if ($('#variants-table tbody tr:visible').length === 0 && currentVariants.length === 0) {
                $('#variants-table').html('<p class="text-center text-muted">Chưa có biến thể nào được thêm.</p>');
            }
        });

        // Xử lý upload ảnh đại diện sản phẩm
        $('#thumbnailInput').on('change', function(e) {
            const file = e.target.files[0];
            const previewArea = $('#thumbnailPreviewArea');
            const previewImg = $('#thumbnailPreview');
            const uploadBox = $('.image-uploads');

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.attr('src', e.target.result);
                    previewArea.show();
                    uploadBox.hide(); // Ẩn ô upload khi có ảnh
                };
                reader.readAsDataURL(file);
            } else {
                // Nếu không có file mới được chọn, kiểm tra xem sản phẩm có ảnh cũ không
                if ("{{ $product->thumbnail }}") {
                     previewImg.attr('src', "{{ asset('storage/' . $product->thumbnail) }}");
                     previewArea.show();
                     uploadBox.hide();
                } else {
                    previewArea.hide();
                    uploadImg.attr('src', '');
                    uploadBox.show(); // Hiện ô upload nếu không có ảnh
                }
            }
        });

        $('#removeThumbnail').on('click', function() {
            $('#thumbnailInput').val(''); // Xóa file đã chọn trong input
            $('#thumbnailPreviewArea').hide(); // Ẩn vùng preview
            $('#thumbnailPreview').attr('src', ''); // Xóa src của ảnh preview
            $('.image-uploads').show(); // Hiển thị lại ô upload

            // Thêm một input hidden để báo hiệu rằng ảnh đại diện cũ đã bị xóa
            // Controller sẽ xử lý việc xóa ảnh trong storage
            $('#productTabContent').append('<input type="hidden" name="remove_thumbnail" value="1">');
        });

        // Khởi tạo trạng thái ban đầu cho thumbnail sản phẩm
        if ($('#thumbnailPreview').attr('src')) {
            $('.image-uploads').hide();
            $('#thumbnailPreviewArea').show();
        } else {
            $('.image-uploads').show();
            $('#thumbnailPreviewArea').hide();
        }


        // Xử lý bộ sưu tập ảnh (Gallery)
        $('#imagesInput').on('change', function(e) {
            $('#imagesPreviewArea .new-image-preview').remove(); // Xóa các ảnh mới đã xem trước trước đó
            Array.from(e.target.files).forEach(file => {
                if (file && file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const imgWrapper = $('<div class="position-relative new-image-preview">');
                        const img = $('<img>').attr('src', e.target.result).addClass('img-thumbnail').css({'width': '100px', 'height': '100px', 'object-fit': 'cover'});
                        const removeBtn = $('<button type="button" class="btn btn-danger btn-sm remove-new-gallery-image" style="position: absolute; top: -5px; right: -5px; border-radius: 50%;"><i class="fa fa-times"></i></button>');

                        imgWrapper.append(img).append(removeBtn);
                        $('#imagesPreviewArea').append(imgWrapper);

                        removeBtn.on('click', function() {
                            $(this).closest('.new-image-preview').remove();
                            // Nếu muốn, bạn có thể xóa file khỏi danh sách `e.target.files` ở đây, nhưng phức tạp hơn
                            // Hiện tại, chỉ xóa khỏi preview, file vẫn sẽ được gửi nếu không có logic phức tạp hơn
                        });
                    };
                    reader.readAsDataURL(file);
                }
            });
        });

        // Xử lý xóa ảnh gallery hiện có
        $(document).on('click', '.delete-gallery-image', function() {
            const imageId = $(this).data('image-id');
            $(this).closest('.position-relative').remove(); // Xóa khỏi DOM

            // Thêm một input hidden để báo hiệu ảnh này cần được xóa trong controller
            $('#productTabContent').append(`<input type="hidden" name="deleted_gallery_images[]" value="${imageId}">`);
        });

        // Khởi tạo các sự kiện cho thumbnail biến thể khi DOM load (để xử lý các biến thể cũ)
        initVariantThumbnailUploads();
    });
</script>
@endpush

<style>
    .image-upload-wrapper {
        border: 2px dashed #ddd;
        border-radius: 8px;
        transition: all 0.3s ease;
        position: relative;
    }
    .image-upload-wrapper:hover {
        border-color: #0d6efd;
    }
    .image-upload-wrapper input[type="file"] {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        cursor: pointer;
    }
    .image-uploads {
        padding: 20px;
        color: #6c757d;
    }
    .image-uploads img {
        width: 48px;
        height: 48px;
    }
    .image-uploads h4 {
        font-size: 14px;
        color: #495057;
    }
    .image-preview img {
        border: 1px solid #ddd;
        border-radius: 4px;
        max-height: 200px;
        object-fit: contain;
    }
    #imagesPreviewArea img, .variant-thumbnail-preview img {
        border: 1px solid #ddd;
        border-radius: 4px;
    }
    #imagesPreviewArea img {
        width: 100px;
        height: 100px;
        object-fit: cover;
    }
    .select2-container .select2-selection--multiple {
        border: 1px solid #e9ecef !important;
    }
</style>
@endsection