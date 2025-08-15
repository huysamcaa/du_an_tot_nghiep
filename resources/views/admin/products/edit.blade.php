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
                                    <div class="form-group mb-3">
                                        <label>Ảnh đại diện</label>
                                        <div class="image-upload-wrapper">
                                            <input type="file" name="thumbnail" id="thumbnailInput" accept="image/*">
                                            <div class="image-uploads text-center p-3">
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
                            </div>

                            {{-- Tab Biến thể --}}
                            <div class="tab-pane fade" id="product-variants" role="tabpanel" aria-labelledby="variants-tab">
                                <div class="card p-4">
                                    <h5 class="mb-3">Biến thể hiện có</h5>
                                    @if ($product->variants->count())
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Giá</th>
                                                    <th>SKU</th>
                                                    <th>Ảnh</th>
                                                    <th>Số lượng</th>
                                                    <th>Thuộc tính</th>
                                                    <th>Hiển thị</th>
                                                    <th>Hành động</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($product->variants as $i => $variant)
                                                    <tr data-variant-id="{{ $variant->id }}">
                                                        <td>
                                                            <input type="number" name="variants[{{ $i }}][price]" class="form-control" value="{{ old("variants.$i.price", $variant->price) }}">
                                                        </td>
                                                        <td>
                                                            <input type="text" name="variants[{{ $i }}][sku]" class="form-control" value="{{ old("variants.$i.sku", $variant->sku) }}">
                                                        </td>
                                                        <td>
                                                            <input type="file" name="variants[{{ $i }}][thumbnail]" class="form-control mb-1" accept="image/*">
                                                            @if ($variant->thumbnail)
                                                                <img src="{{ asset('storage/' . $variant->thumbnail) }}" width="60" class="img-thumbnail mt-1">
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <input type="number" name="variants[{{ $i }}][stock]" class="form-control" value="{{ old("variants.$i.stock", $variant->stock) }}">
                                                        </td>
                                                        <td>
                                                            @foreach($variant->attributeValues as $av)
                                                                <span class="badge bg-secondary">{{ $av->attribute->name }}: {{ $av->value }}</span><br>
                                                            @endforeach
                                                        </td>
                                                        <td>
                                                            <input type="checkbox" name="variants[{{ $i }}][is_active]" value="1" {{ $variant->is_active ? 'checked' : '' }}>
                                                        </td>
                                                        <td>
                                                            <button type="button" class="btn btn-danger btn-sm remove-existing-variant" data-variant-id="{{ $variant->id }}"><i class="fa fa-times"></i></button>
                                                            <input type="hidden" name="variants[{{ $i }}][id]" value="{{ $variant->id }}">
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    @endif

                                    <h5 class="mt-4 mb-3">Thêm biến thể mới</h5>
                                    <div id="new-variants-container"></div>
                                    <button type="button" class="btn btn-primary mt-3" id="add-variant-btn">
                                        <i class="fa fa-plus"></i> Thêm biến thể mới
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
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
                                <label>Bắt đầu sale</label>
                                <input type="date" name="sale_price_start_at" class="form-control" value="{{ old('sale_price_start_at', optional($product->sale_price_start_at)->format('Y-m-d')) }}">
                            </div>
                            <div class="form-group mb-3">
                                <label>Kết thúc sale</label>
                                <input type="date" name="sale_price_end_at" class="form-control" value="{{ old('sale_price_end_at', optional($product->sale_price_end_at)->format('Y-m-d')) }}">
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

@endsection

@section('scripts')
<script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function() {
        CKEDITOR.replace('editor');
        $('.select2').select2({
            placeholder: "-- Chọn một tùy chọn --",
            allowClear: true
        });

        let newVariantIndex = 0;

        document.getElementById('add-variant-btn').addEventListener('click', function() {
            let html = `
            <div class="row mb-3 new-variant-row align-items-center border-bottom pb-3">
                <div class="col-md-2">
                    <label>Giá</label>
                    <input type="number" name="new_variants[${newVariantIndex}][price]" class="form-control" placeholder="Giá" required>
                </div>
                <div class="col-md-2">
                    <label>SKU</label>
                    <input type="text" name="new_variants[${newVariantIndex}][sku]" class="form-control" placeholder="SKU">
                </div>
                <div class="col-md-2">
                    <label>Ảnh</label>
                    <input type="file" name="new_variants[${newVariantIndex}][thumbnail]" class="form-control" accept="image/*">
                </div>
                <div class="col-md-2">
                    <label>Số lượng</label>
                    <input type="number" name="new_variants[${newVariantIndex}][stock]" class="form-control" placeholder="Số lượng" value="0">
                </div>
                <div class="col-md-2">
                    <label>Màu</label>
                    <select name="new_variants[${newVariantIndex}][color_id]" class="form-control select2" required>
                        <option value="">-- Chọn màu --</option>
                        @foreach ($colors as $color)
                            <option value="{{ $color->id }}">{{ $color->value }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label>Kích thước</label>
                    <select name="new_variants[${newVariantIndex}][size_id]" class="form-control select2" required>
                        <option value="">-- Chọn kích thước --</option>
                        @foreach ($sizes as $size)
                            <option value="{{ $size->id }}">{{ $size->value }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-12 mt-2 d-flex justify-content-end align-items-center">
                    <label class="form-check-label me-3">
                        <input type="checkbox" name="new_variants[${newVariantIndex}][is_active]" value="1" checked> Hiển thị
                    </label>
                    <button type="button" class="btn btn-danger btn-sm remove-new-variant-btn"><i class="fa fa-times"></i></button>
                </div>
            </div>
            `;
            document.getElementById('new-variants-container').insertAdjacentHTML('beforeend', html);
            // Re-init select2 for new elements
            $('.new-variant-row select').select2({
                placeholder: "-- Chọn một tùy chọn --",
                allowClear: true
            });
            newVariantIndex++;
        });

        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-new-variant-btn')) {
                e.target.closest('.new-variant-row').remove();
            }
            if (e.target.classList.contains('remove-existing-variant')) {
                if (confirm('Bạn có chắc chắn muốn xóa biến thể này? Thao tác này không thể hoàn tác.')) {
                     e.target.closest('tr').remove();
                }
            }
        });

        // Handle thumbnail preview
        $('#thumbnailInput').on('change', function() {
            const file = this.files[0];
            const previewArea = $('#thumbnailPreviewArea');
            const previewImage = $('#thumbnailPreview');
            const uploadWrapper = $(this).closest('.image-upload-wrapper').find('.image-uploads');

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImage.attr('src', e.target.result);
                    previewArea.show();
                    uploadWrapper.hide();
                }
                reader.readAsDataURL(file);
            } else {
                previewArea.hide();
                uploadWrapper.show();
            }
        });

        $('#removeThumbnail').on('click', function() {
            $('#thumbnailInput').val('');
            $('#thumbnailPreviewArea').hide();
            $('#thumbnailInput').closest('.image-upload-wrapper').find('.image-uploads').show();
        });
    });
</script>
@endsection
