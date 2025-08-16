@extends('admin.layouts.app')

@section('content')
<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4>Sửa sản phẩm</h4>
            <h6>Cập nhật thông tin chi tiết của sản phẩm</h6>
        </div>
    </div>

    <div class="card">
        <div class="card-body">

            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

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
                                        <input type="text" name="name" class="form-control" value="{{ old('name', $product->name) }}" required>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label>Mô tả ngắn</label>
                                        <textarea name="short_description" rows="2" class="form-control">{{ old('short_description', $product->short_description) }}</textarea>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label>Mô tả chi tiết</label>
                                        <textarea name="description" id="editor" rows="5" class="form-control">{{ old('description', $product->description) }}</textarea>
                                    </div>
                                </div>
                            </div>

                            {{-- Tab Hình ảnh --}}
                            <div class="tab-pane fade" id="product-images" role="tabpanel" aria-labelledby="images-tab">
                                <div class="card p-4">
                                    <div class="row">
                                        <div class="col-lg-6 col-sm-12 mb-3">
                                            <div class="form-group">
                                                <label>Ảnh đại diện</label>
                                                <div class="image-upload-wrapper">
                                                    <input type="file" name="thumbnail" id="thumbnailInput" accept="image/*">
                                                    <div class="image-uploads text-center p-3" style="{{ $product->thumbnail ? 'display: none;' : '' }}">
                                                        <img src="{{ asset('assets/admin/img/icons/upload.svg') }}" alt="upload" class="mb-2">
                                                        <h4>Kéo thả hoặc nhấn vào để tải lên</h4>
                                                    </div>
                                                    <div class="image-preview mt-3" id="thumbnailPreviewArea" style="{{ $product->thumbnail ? 'display:block;' : 'display:none;' }}">
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
                                                    {{-- Hiển thị ảnh hiện có --}}
                                                    @if ($product->images)
                                                    @foreach ($product->images as $image)
                                                        <div class="image-container position-relative" data-image-id="{{ $image->id }}">
                                                            <img src="{{ asset('storage/' . $image->path) }}" alt="Gallery Image" class="img-thumbnail" width="100" height="100" style="object-fit: cover;">
                                                            <button type="button" class="btn btn-danger btn-sm remove-existing-image" data-image-id="{{ $image->id }}" style="position: absolute; top: -5px; right: 5px; border-radius: 50%; padding: 0.1rem 0.4rem;">&times;</button>
                                                        </div>
                                                    @endforeach
                                                    @endif
                                                    {{-- Ảnh mới sẽ được thêm vào đây --}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Tab Biến thể --}}
                            <div class="tab-pane fade" id="product-variants" role="tabpanel" aria-labelledby="variants-tab">
                                <div class="card p-4">
                                    <h5 class="mb-3">Biến thể hiện có</h5>
                                    @if ($product->variants->count())
                                        <div class="table-responsive">
                                            <table class="table table-striped table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>Tên biến thể</th>
                                                        <th>Giá</th>
                                                        <th>Số lượng</th>
                                                        <th>SKU</th>
                                                        <th>Ảnh</th>
                                                        <th>Trạng thái</th>
                                                        <th>Hành động</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="existing-variants-table-body">
                                                    @foreach ($product->variants as $variant)
                                                    <tr data-variant-id="{{ $variant->id }}">
                                                        <td>
                                                            @foreach($variant->attributeValues as $av)
                                                                <span class="badge bg-secondary">{{ $av->attribute->name }}: {{ $av->value }}</span>
                                                            @endforeach
                                                        </td>
                                                        <td>
                                                            <input type="number" name="variants[{{ $variant->id }}][price]" step="0.01" class="form-control" value="{{ old("variants.{$variant->id}.price", $variant->price) }}" required>
                                                        </td>
                                                        <td>
                                                            <input type="number" name="variants[{{ $variant->id }}][stock]" class="form-control" min="0" value="{{ old("variants.{$variant->id}.stock", $variant->stock) }}" required>
                                                        </td>
                                                        <td>
                                                            <input type="text" name="variants[{{ $variant->id }}][sku]" class="form-control" value="{{ old("variants.{$variant->id}.sku", $variant->sku) }}">
                                                        </td>
                                                        <td>
                                                            <input type="file" name="variants[{{ $variant->id }}][thumbnail]" class="form-control mb-1" accept="image/*">
                                                            @if ($variant->thumbnail)
                                                                <img src="{{ asset('storage/' . $variant->thumbnail) }}" width="60" class="img-thumbnail mt-1" alt="Variant Image">
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <div class="form-check form-switch">
                                                                <input class="form-check-input" type="checkbox" name="variants[{{ $variant->id }}][is_active]" value="1" {{ old("variants.{$variant->id}.is_active", $variant->is_active) ? 'checked' : '' }}>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <button type="button" class="btn btn-danger btn-sm remove-existing-variant" data-variant-id="{{ $variant->id }}"><i class="fa fa-times"></i></button>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <p class="text-muted">Sản phẩm này chưa có biến thể nào.</p>
                                    @endif

                                    <hr class="mt-4 mb-4">
                                    <h5 class="mb-3">Thêm biến thể mới</h5>
                                    <div id="new-variants-container">
                                        {{-- Các hàng biến thể mới sẽ được thêm vào đây bằng JavaScript --}}
                                    </div>
                                    <button type="button" class="btn btn-primary mt-3" id="add-new-variant">
                                        <i class="fa fa-plus-circle me-1"></i> Thêm biến thể mới
                                    </button>
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
                                    <i class="fa fa-save"></i> Cập nhật
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

{{-- Template cho biến thể mới --}}
<template id="new-variant-template">
    <div class="row new-variant-row mb-3 align-items-center">
        <div class="col-md-3">
            <div class="form-group mb-0">
                <label>Màu sắc</label>
                <select name="new_variants[0][color_id]" class="form-control select2">
                    <option value="">-- Chọn màu --</option>
                    @foreach($colors as $color)
                        <option value="{{ $color->id }}">{{ $color->value }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group mb-0">
                <label>Kích thước</label>
                <select name="new_variants[0][size_id]" class="form-control select2">
                    <option value="">-- Chọn size --</option>
                    @foreach($sizes as $size)
                        <option value="{{ $size->id }}">{{ $size->value }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group mb-0">
                <label>Giá</label>
                <input type="number" name="new_variants[0][price]" class="form-control" step="0.01" min="0" required>
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group mb-0">
                <label>Số lượng</label>
                <input type="number" name="new_variants[0][stock]" class="form-control" min="0" required>
            </div>
        </div>
        {{-- THÊM MỚI: Trường ảnh biến thể --}}
        <div class="col-md-2">
            <div class="form-group mb-0">
                <label>Ảnh</label>
                <input type="file" name="new_variants[0][thumbnail]" class="form-control new-variant-image-input" accept="image/*">
                <div class="new-variant-image-preview mt-2" style="display: none;">
                    <img src="#" alt="Ảnh biến thể" class="img-thumbnail" width="60">
                </div>
            </div>
        </div>
        <div class="col-md-1 text-center">
            <button type="button" class="btn btn-danger btn-sm remove-new-variant mt-4">&times;</button>
        </div>
    </div>
</template>

@push('scripts')
<script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(function() {
        CKEDITOR.replace('editor');
        $('.select2').select2();

        // Thumbnail Preview
        $('#thumbnailInput').on('change', function(e) {
            const file = e.target.files[0];
            const previewArea = $('#thumbnailPreviewArea');
            const previewImg = $('#thumbnailPreview');
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.attr('src', e.target.result);
                    previewArea.show();
                    $('.image-uploads').hide();
                };
                reader.readAsDataURL(file);
            } else {
                previewArea.hide();
                $('.image-uploads').show();
                previewImg.attr('src', '');
            }
        });

        $('#removeThumbnail').on('click', function() {
            $('#thumbnailInput').val('');
            $('#thumbnailPreviewArea').hide();
            $('.image-uploads').show();
            $('#thumbnailPreview').attr('src', '');
        });

        // Image Gallery Preview (for new images)
        $('#imagesInput').on('change', function(e) {
            $('#imagesPreviewArea .new-image-preview').remove();
            Array.from(e.target.files).forEach(file => {
                if (file && file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const img = $('<img>').attr('src', e.target.result).addClass('img-thumbnail');
                        const imgContainer = $('<div>').addClass('image-container new-image-preview');
                        imgContainer.append(img);
                        $('#imagesPreviewArea').append(imgContainer);
                    };
                    reader.readAsDataURL(file);
                }
            });
        });

        // Remove existing gallery image (AJAX)
        $(document).on('click', '.remove-existing-image', function() {
            if (!confirm('Bạn có chắc chắn muốn xóa ảnh này?')) {
                return;
            }

            const button = $(this);
            const imageId = button.data('image-id');
            const container = button.closest('.image-container');

            $.ajax({
                url: `{{ url('admin/products/' . $product->id . '/images') }}/${imageId}`,
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        container.remove();
                        toastr.success(response.message);
                    } else {
                        toastr.error('Lỗi khi xóa ảnh: ' + response.message);
                    }
                },
                error: function(xhr) {
                    toastr.error('Lỗi server: ' + xhr.responseJSON.message);
                }
            });
        });

        // Remove existing variant row (AJAX)
        $(document).on('click', '.remove-existing-variant', function() {
            if (!confirm('Bạn có chắc chắn muốn xóa biến thể này?')) {
                return;
            }

            const button = $(this);
            const row = button.closest('tr');
            const variantId = button.data('variant-id');

            $.ajax({
                url: `{{ url('admin/products/variants') }}/${variantId}`,
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        row.remove();
                        toastr.success(response.message);
                    } else {
                        toastr.error('Lỗi khi xóa biến thể: ' + response.message);
                    }
                },
                error: function(xhr) {
                    toastr.error('Lỗi server: ' + xhr.responseJSON.message);
                }
            });
        });

        // Biến thể mới
        let newVariantCount = 0;
        $('#add-new-variant').on('click', function() {
            const template = $('#new-variant-template').html();
            const newRow = $(template.replace(/\[0\]/g, `[${newVariantCount}]`));
            $('#new-variants-container').append(newRow);

            // Khởi tạo lại select2 cho biến thể mới
            newRow.find('.select2').select2();

            newVariantCount++;
        });

        $(document).on('click', '.remove-new-variant', function() {
            $(this).closest('.new-variant-row').remove();
        });

        // THÊM MỚI: Xử lý xem trước ảnh cho biến thể mới
        $(document).on('change', '.new-variant-image-input', function(e) {
            const file = e.target.files[0];
            const container = $(this).closest('.form-group');
            const previewArea = container.find('.new-variant-image-preview');
            const previewImg = previewArea.find('img');

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.attr('src', e.target.result);
                    previewArea.show();
                };
                reader.readAsDataURL(file);
            } else {
                previewArea.hide();
                previewImg.attr('src', '#');
            }
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
    #imagesPreviewArea .image-container {
        width: 100px;
        height: 100px;
        position: relative;
    }
    #imagesPreviewArea img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .select2-container .select2-selection--multiple {
        border: 1px solid #e9ecef !important;
    }
</style>
@endsection
