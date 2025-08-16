@extends('admin.layouts.app')

@section('content')
<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4>Thêm sản phẩm</h4>
            <h6>Tạo sản phẩm mới trong kho</h6>
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

            <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

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
                                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label>Mô tả ngắn <span class="text-danger">*</span></label>
                                        <textarea name="short_description" rows="2" class="form-control" required>{{ old('short_description') }}</textarea>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label>Mô tả chi tiết <span class="text-danger">*</span></label>
                                        <textarea name="description" id="editor" rows="5" class="form-control" required>{{ old('description') }}</textarea>
                                    </div>
                                </div>
                            </div>

                            {{-- Tab Hình ảnh --}}
                            <div class="tab-pane fade" id="product-images" role="tabpanel" aria-labelledby="images-tab">
                                <div class="card p-4">
                                    <div class="row">
                                        <div class="col-lg-6 col-sm-12 mb-3">
                                            <div class="form-group">
                                                <label>Ảnh đại diện <span class="text-danger">*</span></label>
                                                <div class="image-upload-wrapper">
                                                    <input type="file" name="thumbnail" id="thumbnailInput" accept="image/*" required>
                                                    <div class="image-uploads text-center p-3">
                                                        <img src="{{ asset('assets/admin/img/icons/upload.svg') }}" alt="upload" class="mb-2">
                                                        <h4>Kéo thả hoặc nhấn vào để tải lên</h4>
                                                    </div>
                                                    <div class="image-preview mt-3" id="thumbnailPreviewArea" style="display:none;">
                                                        <img id="thumbnailPreview" src="" alt="Ảnh đại diện" class="img-thumbnail w-100">
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
                                                <div class="mt-3 d-flex flex-wrap gap-2" id="imagesPreviewArea"></div>
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
                                                    <option value="{{ $value->id }}">{{ $value->value }}</option>
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
                                    <div class="mt-4" id="variants-table"></div>
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
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
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
                                    <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                                        {{ $brand->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group mb-3">
                                <label class="d-block mb-1">Tùy chọn</label>
                                <div class="form-check form-check-inline me-4">
                                    <input class="form-check-input" type="checkbox" name="is_sale" value="1" id="is_sale" {{ old('is_sale') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_sale">Đang sale</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active" {{ old('is_active', 1) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">Hiển thị</label>
                                </div>
                            </div>
                            <div class="text-end mt-4">
                                <button type="submit" class="btn btn-primary me-2">
                                    <i class="fa fa-save"></i> Lưu sản phẩm
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
$attributesData = [];
foreach ($attributes as $attribute) {
$attributesData[$attribute->id] = $attribute->attributeValues->map(fn($v) => ['id' => $v->id, 'value' => $v->value])->toArray();
}
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
        let manualVariantIndex = 0;

        function cartesian(arr) {
            return arr.reduce((a, b) => a.flatMap(d => b.map(e => Array.isArray(d) ? [...d, e] : [d, e])), [
                []
            ]);
        }

        function generateVariantsTable(combos, isManual = false) {
            let html = `<div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Tên biến thể</th>
                            <th>Giá</th>
                            <th>Số lượng</th>
                            <th>SKU</th>
                            <th>Ảnh</th>
                            <th> </th>
                        </tr>
                    </thead>
                    <tbody>`;

            if (isManual) {
                html += `<tr data-variant-index="manual_${manualVariantIndex}">
                    <td><input type="text" name="manual_variants[${manualVariantIndex}][name]" class="form-control" placeholder="Tên biến thể" required></td>
                    <td><input type="number" name="manual_variants[${manualVariantIndex}][price]" step="0.01" class="form-control" required></td>
                    <td><input type="number" name="manual_variants[${manualVariantIndex}][stock]" class="form-control" min="0" value="0" required></td>
                    <td><input type="text" name="manual_variants[${manualVariantIndex}][sku]" class="form-control"></td>
                    <td><input type="file" name="manual_variants[${manualVariantIndex}][thumbnail]" class="form-control" accept="image/*"></td>
                    <td><button type="button" class="btn btn-danger btn-sm remove-variant"><i class="fa fa-times"></i></button></td>
                </tr>`;
                manualVariantIndex++;
            } else {
                combos.forEach((combo, i) => {
                    const label = combo.map(v => v.text).join(' - ');
                    html += `<tr data-variant-index="${i}">
                        <td>`;
                    combo.forEach(v => {
                        html += `<input type="hidden" name="variants[${i}][attribute_id][]" value="${v.attribute_id}">`;
                        html += `<input type="hidden" name="variants[${i}][attribute_value_id][]" value="${v.id}">`;
                    });
                    html += `<span class="fw-bold">${label}</span></td>
                       <td><input type="number" name="variants[${i}][price]" step="0.01" class="form-control" min="0" required></td>
                        <td><input type="number" name="variants[${i}][stock]" class="form-control" min="0" value="0" required></td>
                        <td><input type="text" name="variants[${i}][sku]" class="form-control"></td>
                        <td><input type="file" name="variants[${i}][thumbnail]" class="form-control" accept="image/*"></td>
                        <td><button type="button" class="btn btn-danger btn-sm remove-variant"><i class="fa fa-times"></i></button></td>
                    </tr>`;
                });
            }

            html += `</tbody></table></div>`;
            $('#variants-table').html(html);
        }

        $('#toggle-attributes').on('click', function() {
            const attributesArea = $('#attributes-select-area');
            const isHidden = attributesArea.css('display') === 'none';
            attributesArea.slideToggle();
            $(this).html(isHidden ? '<i class="fa fa-eye-slash me-1"></i> Ẩn thuộc tính & Biến thể' : '<i class="fa fa-cogs me-1"></i> Quản lý thuộc tính & Biến thể');
        });

        $('#clear-variants').on('click', function() {
            $('#variants-table').html('');
            $('.attribute-value-select').val(null).trigger('change');
        });

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
            generateVariantsTable(combos);
        });

        $('#add-variant').on('click', function() {
            let table = $('#variants-table');
            if ($.trim(table.html()) === '') {
                generateVariantsTable([], true);
            } else {
                let tbody = table.find('tbody');
                let html = `<tr data-variant-index="manual_${manualVariantIndex}">
                    <td><input type="text" name="manual_variants[${manualVariantIndex}][name]" class="form-control" placeholder="Tên biến thể" required></td>
                    <td><input type="number" name="manual_variants[${manualVariantIndex}][price]" step="0.01" class="form-control" min="0" required></td>
                    <td><input type="number" name="manual_variants[${manualVariantIndex}][stock]" class="form-control" min="0" value="0" required></td>
                    <td><input type="text" name="manual_variants[${manualVariantIndex}][sku]" class="form-control"></td>
                    <td><input type="file" name="manual_variants[${manualVariantIndex}][thumbnail]" class="form-control" accept="image/*"></td>
                    <td><button type="button" class="btn btn-danger btn-sm remove-variant"><i class="fa fa-times"></i></button></td>
                </tr>`;
                tbody.append(html);
                manualVariantIndex++;
            }
        });

        $(document).on('click', '.remove-variant', function() {
            $(this).closest('tr').remove();
            if ($('#variants-table tbody tr').length === 0) {
                $('#variants-table').html('');
            }
        });

        $('#thumbnailInput').on('change', function(e) {
            const file = e.target.files[0];
            const previewArea = $('#thumbnailPreviewArea');
            const previewImg = $('#thumbnailPreview');
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

        $('#removeThumbnail').on('click', function() {
            $('#thumbnailInput').val('');
            $('#thumbnailPreviewArea').hide();
            $('#thumbnailPreview').attr('src', '');
        });

        $('#imagesInput').on('change', function(e) {
            $('#imagesPreviewArea').html('');
            Array.from(e.target.files).forEach(file => {
                if (file && file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const img = $('<img>').attr('src', e.target.result).addClass('img-thumbnail');
                        $('#imagesPreviewArea').append(img);
                    };
                    reader.readAsDataURL(file);
                }
            });
        });
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
