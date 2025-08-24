@extends('admin.layouts.app')

@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Thêm sản phẩm</h4>
                <h6>Tạo sản phẩm mới trong kho</h6>
            </div>
        </div>
        @if ($errors->has('variants'))
            <div class="alert alert-danger">
                Vui lòng thêm biến thể.
            </div>
        @endif
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <div class="col-lg-8">
                            <ul class="nav nav-tabs nav-tabs-solid mb-4" id="productTab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="basic-tab" data-bs-toggle="tab"
                                        data-bs-target="#basic-info" type="button" role="tab"
                                        aria-controls="basic-info" aria-selected="true">
                                        <i class="fa fa-info-circle me-1"></i> Thông tin cơ bản
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="images-tab" data-bs-toggle="tab"
                                        data-bs-target="#product-images" type="button" role="tab"
                                        aria-controls="product-images" aria-selected="false">
                                        <i class="fa fa-images me-1"></i> Hình ảnh
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="variants-tab" data-bs-toggle="tab"
                                        data-bs-target="#product-variants" type="button" role="tab"
                                        aria-controls="product-variants" aria-selected="false">
                                        <i class="fa fa-sitemap me-1"></i> Biến thể
                                    </button>
                                </li>
                            </ul>

                            <div class="tab-content" id="productTabContent">
                                {{-- Tab Thông tin cơ bản --}}
                                <div class="tab-pane fade show active" id="basic-info" role="tabpanel"
                                    aria-labelledby="basic-tab">
                                    <div class="card p-4">
                                        <div class="form-group mb-3">
                                            <label>Tên sản phẩm <span class="text-danger">*</span></label>
                                            <input type="text" name="name" class="form-control"
                                                value="{{ old('name') }}">
                                            @error('name')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div class="form-group mb-3">
                                            <label>Mô tả ngắn <span class="text-danger">*</span></label>
                                            <textarea name="short_description"  class="form-control summernote">{{ old('short_description') }}</textarea>
                                            @error('short_description')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div class="form-group mb-3">
                                            <label>Mô tả chi tiết <span class="text-danger">*</span></label>
                                          <textarea name="description" class="form-control summernote">{{ old('description') }}</textarea>
                                            @error('description')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror

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
                                                        <input type="file" name="thumbnail" id="thumbnailInput"
                                                            accept="image/*">
                                                        <div class="image-uploads text-center p-3">
                                                            <img src="{{ asset('assets/admin/img/icons/upload.svg') }}"
                                                                alt="upload" class="mb-2">
                                                            <h4>Kéo thả hoặc nhấn vào để tải lên</h4>
                                                        </div>
                                                        <div class="image-preview mt-3" id="thumbnailPreviewArea"
                                                            style="display:none;">
                                                            <img id="thumbnailPreview" src="" alt="Ảnh đại diện"
                                                                class="img-thumbnail w-100">
                                                            <button type="button" class="btn btn-sm btn-danger mt-2 w-100"
                                                                id="removeThumbnail">Xóa ảnh</button>
                                                        </div>
                                                    </div>
                                                    @error('thumbnail')
                                                        <small class="text-danger">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-sm-12 mb-3">
                                                <div class="form-group">
                                                    <label>Bộ sưu tập ảnh</label>
                                                    <div class="image-upload-wrapper">
                                                        <input type="file" name="images[]" id="imagesInput"
                                                            accept="image/*" multiple>
                                                        <div class="image-uploads text-center p-3">
                                                            <img src="{{ asset('assets/admin/img/icons/upload.svg') }}"
                                                                alt="upload" class="mb-2">
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
                                <div class="tab-pane fade" id="product-variants" role="tabpanel"
                                    aria-labelledby="variants-tab">
                                    <div class="card p-4">
                                        <div class="form-group">
                                            <div class="form-check mb-3">
                                                <input class="form-check-input" type="checkbox" name="has_variants"
                                                    value="1" id="has_variants"
                                                    {{ old('has_variants') ? 'checked' : '' }}>
                                                <label class="form-check-label" for="has_variants">Sản phẩm có biến thể
                                                    (ví dụ: kích thước, màu sắc)</label>
                                            </div>

                                            <div id="variant-options-area" style="display:none;">
                                                <button type="button" class="btn btn-outline-primary w-100 mb-3"
                                                    id="toggle-attributes">
                                                    <i class="fa fa-cogs me-1"></i> Quản lý thuộc tính & Biến thể
                                                </button>
                                                <div id="attributes-select-area" style="display:none;"
                                                    class="mt-3 p-3 border rounded">
                                                    @foreach ($attributes as $attribute)
                                                        <div class="mb-3">
                                                            <label class="form-label">
                                                                <strong>{{ $attribute->name }}</strong>
                                                            </label>
                                                            <select name="attribute_values[{{ $attribute->id }}][]"
                                                                class="form-control attribute-value-select select2"
                                                                multiple>
                                                                @foreach ($attribute->attributeValues as $value)
                                                                    <option value="{{ $value->id }}"
                                                                        data-attribute-id="{{ $attribute->id }}"
                                                                        data-attribute-name="{{ $attribute->name }}">
                                                                        {{ $value->value }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    @endforeach
                                                    <div class="mt-3 d-flex flex-wrap gap-2">
                                                        <button type="button" class="btn btn-outline-success"
                                                            id="generate-variants">
                                                            <i class="fa fa-magic me-1"></i> Tạo tự động
                                                        </button>
                                                        <button type="button" class="btn btn-outline-secondary"
                                                            id="add-variant">
                                                            <i class="fa fa-plus me-1"></i> Thêm thủ công
                                                        </button>
                                                        <button type="button" class="btn btn-outline-danger"
                                                            id="clear-variants">
                                                            <i class="fa fa-trash me-1"></i> Xóa tất cả
                                                        </button>
                                                    </div>
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
                                    <select name="category_id" class="form-control select2">
                                        <option value="">-- Chọn danh mục --</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}"
                                                {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="form-group mb-3">
                                    <label>Nhà sản xuất <span class="text-danger">*</span></label>
                                    <select name="brand_id" class="form-control select2">
                                        <option value="">-- Chọn nhà sản xuất --</option>
                                        @foreach ($brands as $brand)
                                            <option value="{{ $brand->id }}"
                                                {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                                                {{ $brand->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('brand_id')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="form-group mb-3">
                                    <label class="d-block mb-1">Tùy chọn</label>
                                    {{-- Các checkbox này sẽ chỉ áp dụng cho sản phẩm không có biến thể hoặc là các thuộc tính chung --}}
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="is_active" value="1"
                                            id="is_active" {{ old('is_active', 1) ? 'checked' : '' }}>
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
            $attributesData[$attribute->id] = $attribute->attributeValues
                ->map(fn($v) => ['id' => $v->id, 'value' => $v->value])
                ->toArray();
        }
    @endphp

    @push('scripts')
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">


        <script>
            $(function() {

                    $('.summernote').summernote({
                        height: 200, // chiều cao editor
                        toolbar: [
                            ['style', ['style']],
                            ['font', ['bold', 'italic', 'underline', 'clear']],
                            ['fontname', ['fontname']],
                            ['fontsize', ['fontsize']],
                            ['color', ['color']],
                            ['para', ['ul', 'ol', 'paragraph']],
                            ['height', ['height']],
                            ['insert', ['link', 'picture', 'video']],
                            ['view', ['fullscreen', 'codeview', 'help']]
                        ]
                    });


                $('.select2').select2({
                    placeholder: "-- Chọn một tùy chọn --",
                    allowClear: true
                });

                // Initialize Flatpickr for date inputs (will be applied dynamically)
                flatpickr(".flatpickr", {
                    enableTime: true,
                    dateFormat: "Y-m-d H:i",
                    altInput: true,
                    altFormat: "d/m/Y H:i",
                    locale: "vn" // If you have a Vietnamese locale for Flatpickr
                });
                const attributesData = @json($attributesData);
                let manualVariantIndex = 0; // Để quản lý index cho các biến thể thêm thủ công

                // Function để tính toán tích Descartes (Cartesian product) của các mảng
                function cartesian(arr) {
                    return arr.reduce((a, b) => a.flatMap(d => b.map(e => Array.isArray(d) ? [...d, e] : [d, e])), [
                        []
                    ]);
                }

                // Function để tạo hoặc cập nhật bảng biến thể
                function generateVariantsTable(combos, isManual = false) {
                    let html = `<div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Tên biến thể</th>
                            <th>Giá gốc <span class="text-danger">*</span></th>
                            <th>Giá sale</th>
                            <th>Bắt đầu sale</th>
                            <th>Kết thúc sale</th>
                            <th>Đang sale</th>
                            <th>Số lượng <span class="text-danger">*</span></th>
                            <th>Ảnh</th>
                            <th></th> </tr>
                    </thead>
                    <tbody>`;

                    if (isManual) {
                        // Thêm một hàng biến thể thủ công mới
                        html += `<tr data-variant-index="manual_${manualVariantIndex}">
                    <td><input type="text" name="variants[manual_${manualVariantIndex}][name]" class="form-control" placeholder="Tên biến thể" ></td>
                    <td><input type="number" name="variants[manual_${manualVariantIndex}][price]" step="0.01" class="form-control" min="0" ></td>
                    <td><input type="number" name="variants[manual_${manualVariantIndex}][sale_price]" step="0.01" class="form-control" min="0"></td>
                    <td><input type="text" name="variants[manual_${manualVariantIndex}][sale_price_start_at]" class="form-control flatpickr"></td>
                    <td><input type="text" name="variants[manual_${manualVariantIndex}][sale_price_end_at]" class="form-control flatpickr"></td>
                    <td><input type="checkbox" name="variants[manual_${manualVariantIndex}][is_sale]" value="1" class="form-check-input"></td>
                    <td><input type="number" name="variants[manual_${manualVariantIndex}][stock]" class="form-control" min="0" value="0" ></td>
                    <td>
                        <input type="file" name="variants[manual_${manualVariantIndex}][thumbnail]" class="form-control variant-thumbnail-input" accept="image/*">
                        <div class="variant-thumbnail-preview mt-2" style="display:none;">
                            <img src="#" alt="Thumbnail" class="img-thumbnail" style="width: 50px; height: 50px; object-fit: cover;">
                            <button type="button" class="btn btn-sm btn-outline-danger mt-1 remove-variant-thumbnail">Xóa</button>
                        </div>
                    </td>
                    <td><button type="button" class="btn btn-danger btn-sm remove-variant"><i class="fa fa-times"></i></button></td>
                </tr>`;
                        manualVariantIndex++;
                    } else {
                        // Tạo các hàng biến thể từ tổ hợp thuộc tính
                        combos.forEach((combo, i) => {
                            // Tạo tên biến thể từ các giá trị thuộc tính
                            const label = combo.map(v => v.text).join(' - ');
                            const uniqueIndex =
                                `${combo.map(v => v.id).join('_')}_${i}`; // Tạo index duy nhất dựa trên ID thuộc tính và thứ tự

                            html += `<tr data-variant-index="${uniqueIndex}">
                        <td>`;
                            // Thêm các input hidden cho attribute_id và attribute_value_id
                            combo.forEach(v => {
                                html +=
                                    `<input type="hidden" name="variants[${uniqueIndex}][attribute_id][]" value="${v.attribute_id}">`;
                                html +=
                                    `<input type="hidden" name="variants[${uniqueIndex}][attribute_value_id][]" value="${v.id}">`;
                            });
                            html += `<span class="fw-bold">${label}</span></td>
                        <td><input type="number" name="variants[${uniqueIndex}][price]" step="0.01" class="form-control" min="0" ></td>
                        <td><input type="number" name="variants[${uniqueIndex}][sale_price]" step="0.01" class="form-control" min="0"></td>
                        <td><input type="text" name="variants[${uniqueIndex}][sale_price_start_at]" class="form-control flatpickr"></td>
                        <td><input type="text" name="variants[${uniqueIndex}][sale_price_end_at]" class="form-control flatpickr"></td>
                        <td><input type="checkbox" name="variants[${uniqueIndex}][is_sale]" value="1" class="form-check-input"></td>
                        <td><input type="number" name="variants[${uniqueIndex}][stock]" class="form-control" min="0" value="0" ></td>
                        <td>
                            <input type="file" name="variants[${uniqueIndex}][thumbnail]" class="form-control variant-thumbnail-input" accept="image/*">
                            <div class="variant-thumbnail-preview mt-2" style="display:none;">
                                <img src="#" alt="Thumbnail" class="img-thumbnail" style="width: 50px; height: 50px; object-fit: cover;">
                                <button type="button" class="btn btn-sm btn-outline-danger mt-1 remove-variant-thumbnail">Xóa</button>
                            </div>
                        </td>
                        <td><button type="button" class="btn btn-danger btn-sm remove-variant"><i class="fa fa-times"></i></button></td>
                    </tr>`;
                        });
                    }
                    html += `</tbody></table></div>`;
                    $('#variants-table').html(html);

                    // Re-initialize Flatpickr for newly added date inputs
                    flatpickr(".flatpickr", {
                        enableTime: true,
                        dateFormat: "Y-m-d H:i",
                        altInput: true,
                        altFormat: "d/m/Y H:i",
                        locale: "default"
                    });
                }

                // Toggle visibility of variant options based on 'has_variants' checkbox
                $('#has_variants').on('change', function() {
                    if ($(this).is(':checked')) {
                        $('#variant-options-area').slideDown();
                    } else {
                        $('#variant-options-area').slideUp();
                        $('#attributes-select-area').slideUp(); // Hide attributes area if variants are disabled
                        $('#variants-table').html(''); // Clear variants table
                        $('#toggle-attributes').html(
                            '<i class="fa fa-cogs me-1"></i> Quản lý thuộc tính & Biến thể'
                        ); // Reset button text
                    }
                }).trigger('change'); // Trigger on page load to set initial state

                // Toggle attributes select area
                $('#toggle-attributes').on('click', function() {
                    const attributesArea = $('#attributes-select-area');
                    const isHidden = attributesArea.css('display') === 'none';
                    attributesArea.slideToggle();
                    $(this).html(isHidden ? '<i class="fa fa-eye-slash me-1"></i> Ẩn thuộc tính & Biến thể' :
                        '<i class="fa fa-cogs me-1"></i> Quản lý thuộc tính & Biến thể');
                });

                // Clear all variants and selected attributes
                $('#clear-variants').on('click', function() {
                    $('#variants-table').html('');
                    $('.attribute-value-select').val(null).trigger('change');
                });

                // Generate variants automatically based on selected attributes
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
                        $('#variants-table').html(
                            '<p class="text-danger">Vui lòng chọn ít nhất một giá trị thuộc tính để tạo biến thể.</p>'
                        );
                        return;
                    }

                    const combos = cartesian(allValues);
                    generateVariantsTable(combos);
                });

                // Add a single variant manually
                $('#add-variant').on('click', function() {
                    let table = $('#variants-table');
                    if ($.trim(table.html()) === '') {
                        generateVariantsTable([], true); // Tạo bảng mới nếu chưa có
                    } else {
                        // Chỉ thêm hàng mới nếu bảng đã tồn tại
                        let tbody = table.find('tbody');
                        let html = `<tr data-variant-index="manual_${manualVariantIndex}">
                    <td><input type="text" name="variants[manual_${manualVariantIndex}][name]" class="form-control" placeholder="Tên biến thể" ></td>
                    <td><input type="number" name="variants[manual_${manualVariantIndex}][price]" step="0.01" class="form-control" min="0" ></td>
                    <td><input type="number" name="variants[manual_${manualVariantIndex}][sale_price]" step="0.01" class="form-control" min="0"></td>
                    <td><input type="text" name="variants[manual_${manualVariantIndex}][sale_price_start_at]" class="form-control flatpickr"></td>
                    <td><input type="text" name="variants[manual_${manualVariantIndex}][sale_price_end_at]" class="form-control flatpickr"></td>
                    <td><input type="checkbox" name="variants[manual_${manualVariantIndex}][is_sale]" value="1" class="form-check-input"></td>
                    <td><input type="number" name="variants[manual_${manualVariantIndex}][stock]" class="form-control" min="0" value="0" ></td>
                    <td>
                        <input type="file" name="variants[manual_${manualVariantIndex}][thumbnail]" class="form-control variant-thumbnail-input" accept="image/*">
                        <div class="variant-thumbnail-preview mt-2" style="display:none;">
                            <img src="#" alt="Thumbnail" class="img-thumbnail" style="width: 50px; height: 50px; object-fit: cover;">
                            <button type="button" class="btn btn-sm btn-outline-danger mt-1 remove-variant-thumbnail">Xóa</button>
                        </div>
                    </td>
                    <td><button type="button" class="btn btn-danger btn-sm remove-variant"><i class="fa fa-times"></i></button></td>
                </tr>`;
                        tbody.append(html);
                        manualVariantIndex++;

                        // Re-initialize Flatpickr for the new date inputs
                        flatpickr(".flatpickr", {
                            enableTime: true,
                            dateFormat: "Y-m-d H:i",
                            altInput: true,
                            altFormat: "d/m/Y H:i",
                            locale: "vn"
                        });
                    }
                });


                // Remove a variant row
                $(document).on('click', '.remove-variant', function() {
                    $(this).closest('tr').remove();
                    if ($('#variants-table tbody tr').length === 0) {
                        $('#variants-table').html(''); // Xóa bảng nếu không còn hàng nào
                    }
                });

                // Handle variant thumbnail upload preview
                $(document).on('change', '.variant-thumbnail-input', function(e) {
                    const file = e.target.files[0];
                    const previewContainer = $(this).siblings('.variant-thumbnail-preview');
                    const previewImg = previewContainer.find('img');
                    const removeBtn = previewContainer.find('.remove-variant-thumbnail');

                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            previewImg.attr('src', e.target.result);
                            previewContainer.show();
                        };
                        reader.readAsDataURL(file);
                    } else {
                        previewContainer.hide();
                        previewImg.attr('src', '');
                    }
                });

                // Handle removing variant thumbnail
                $(document).on('click', '.remove-variant-thumbnail', function() {
                    const input = $(this).closest('.variant-thumbnail-preview').siblings(
                        '.variant-thumbnail-input');
                    const previewContainer = $(this).closest('.variant-thumbnail-preview');
                    const previewImg = previewContainer.find('img');

                    input.val(''); // Clear the file input
                    previewContainer.hide();
                    previewImg.attr('src', '');
                });


                // Main product thumbnail handling
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

                // Gallery images handling
                $('#imagesInput').on('change', function(e) {
                    $('#imagesPreviewArea').html('');
                    Array.from(e.target.files).forEach(file => {
                        if (file && file.type.startsWith('image/')) {
                            const reader = new FileReader();
                            reader.onload = function(e) {
                                const img = $('<img>').attr('src', e.target.result).addClass(
                                    'img-thumbnail');
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
