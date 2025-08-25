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

            <form action="{{ route('admin.products.update', $product->id) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')

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
                                            value="{{ old('name', $product->name) }}">
                                    </div>
                                    <div class="form-group mb-3">
                                        <label>Mô tả ngắn</label>
                                        <textarea name="short_description" rows="2" class="form-control summernote">{{ old('short_description', $product->short_description) }}</textarea>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label>Mô tả chi tiết</label>
                                        <textarea name="description" rows="5" class="form-control summernote">{{ old('description', $product->description) }}</textarea>
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
                                                    <input type="file" name="thumbnail" id="thumbnailInput"
                                                        accept="image/*">
                                                    <div class="image-uploads text-center p-3"
                                                        style="{{ $product->thumbnail ? 'display: none;' : '' }}">
                                                        <img src="{{ asset('assets/admin/img/icons/upload.svg') }}"
                                                            alt="upload" class="mb-2">
                                                        <h4>Kéo thả hoặc nhấn vào để tải lên</h4>
                                                    </div>
                                                    <div class="image-preview mt-3" id="thumbnailPreviewArea"
                                                        style="{{ $product->thumbnail ? 'display:block;' : 'display:none;' }}">
                                                        <img id="thumbnailPreview"
                                                            src="{{ $product->thumbnail ? asset('storage/' . $product->thumbnail) : '' }}"
                                                            alt="Ảnh đại diện" class="img-thumbnail w-100">
                                                        <button type="button" class="btn btn-sm btn-danger mt-2 w-100"
                                                            id="removeThumbnail">Xóa ảnh</button>
                                                    </div>
                                                </div>
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
                                                <div class="mt-3 d-flex flex-wrap gap-2" id="imagesPreviewArea">
                                                    {{-- Thêm trường input ẩn này vào trong thẻ <form> của bạn --}}
                                                    <input type="hidden" name="kept_images" id="existingImagesInput"
                                                        value="{{ $product->galleries->pluck('id')->implode(',') }}">

                                                    {{-- Giữ nguyên vòng lặp hiển thị ảnh --}}
                                                    @if ($product->galleries)
                                                    @foreach ($product->galleries as $image)
                                                    <div class="image-container position-relative"
                                                        data-image-id="{{ $image->id }}">
                                                        <img src="{{ asset('storage/' . $image->image) }}"
                                                            alt="Gallery Image" class="img-thumbnail"
                                                            width="100" height="100"
                                                            style="object-fit: cover;">
                                                        <button type="button"
                                                            class="btn btn-danger btn-sm remove-existing-image"
                                                            data-image-id="{{ $image->id }}"
                                                            style="position: absolute; top: -5px; right: 5px; border-radius: 50%; padding: 0.1rem 0.4rem;">&times;</button>
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
                            <div class="tab-pane fade" id="product-variants" role="tabpanel"
                                aria-labelledby="variants-tab">
                                <div class="card p-4">
                                    <h5 class="mb-3">Biến thể hiện có</h5>
                                    @if ($product->variants->count())
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Tên biến thể</th>
                                                    <th>Giá gốc</th>
                                                    <th>Giá sale</th>
                                                    <th>Bắt đầu</th>
                                                    <th>Kết thúc</th>
                                                    <th>Số lượng</th>
                                                    <th>Ảnh</th>
                                                    <th>Đang sale</th>
                                                    <th>Trạng thái</th>
                                                    <th>Hành động</th>
                                                </tr>
                                            </thead>
                                            <tbody id="existing-variants-table-body">
                                                @foreach ($product->variants as $variant)
                                                <tr data-variant-id="{{ $variant->id }}">
                                                    <td>
                                                        @foreach ($variant->attributeValues as $av)
                                                        <span
                                                            class="badge bg-secondary">{{ $av->attribute->name }}:
                                                            {{ $av->value }}</span>
                                                        @endforeach
                                                    </td>
                                                    <td>
                                                        <input type="number"
                                                            name="variants[{{ $variant->id }}][price]"
                                                            step="0.01" class="form-control"
                                                            value="{{ old("variants.{$variant->id}.price", $variant->price) }}">
                                                    </td>
                                                    <td>
                                                        <input type="number"
                                                            name="variants[{{ $variant->id }}][sale_price]"
                                                            step="0.01" class="form-control"
                                                            value="{{ old("variants.{$variant->id}.sale_price", $variant->sale_price) }}">
                                                    </td>
                                                    <td>
                                                        <input type="datetime-local"
                                                            name="variants[{{ $variant->id }}][sale_price_start_at]"
                                                            class="form-control"
                                                            value="{{ old("variants.{$variant->id}.sale_price_start_at", $variant->sale_price_start_at ? \Carbon\Carbon::parse($variant->sale_price_start_at)->format('Y-m-d\TH:i') : '') }}">
                                                    </td>
                                                    <td>
                                                        <input type="datetime-local"
                                                            name="variants[{{ $variant->id }}][sale_price_end_at]"
                                                            class="form-control"
                                                            value="{{ old("variants.{$variant->id}.sale_price_end_at", $variant->sale_price_end_at ? \Carbon\Carbon::parse($variant->sale_price_end_at)->format('Y-m-d\TH:i') : '') }}">
                                                    </td>
                                                    <td>
                                                        <input type="number"
                                                            name="variants[{{ $variant->id }}][stock]"
                                                            class="form-control" min="0"
                                                            value="{{ old("variants.{$variant->id}.stock", $variant->stock) }}">
                                                    </td>
                                                    <td>
                                                        <input type="file"
                                                            name="variants[{{ $variant->id }}][thumbnail]"
                                                            class="form-control mb-1" accept="image/*">
                                                        @if ($variant->thumbnail)
                                                        <img src="{{ asset('storage/' . $variant->thumbnail) }}"
                                                            width="60" class="img-thumbnail mt-1"
                                                            alt="Variant Image">
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-switch">
                                                            <input class="form-check-input" type="checkbox"
                                                                name="variants[{{ $variant->id }}][is_sale]"
                                                                value="1"
                                                                {{ old("variants.{$variant->id}.is_sale", $variant->is_sale) ? 'checked' : '' }}>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-switch">
                                                            <input class="form-check-input" type="checkbox"
                                                                name="variants[{{ $variant->id }}][is_active]"
                                                                value="1"
                                                                {{ old("variants.{$variant->id}.is_active", $variant->is_active) ? 'checked' : '' }}>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <button type="button"
                                                            class="btn btn-danger btn-sm remove-existing-variant"
                                                            data-variant-id="{{ $variant->id }}"><i
                                                                class="fa fa-times"></i></button>
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
                                <select name="category_id" class="form-control select2">
                                    <option value="">-- Chọn danh mục --</option>
                                    @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group mb-3">
                                <label>Nhà sản xuất <span class="text-danger">*</span></label>
                                <select name="brand_id" class="form-control select2">
                                    <option value="">-- Chọn nhà sản xuất --</option>
                                    @foreach ($brands as $brand)
                                    <option value="{{ $brand->id }}"
                                        {{ old('brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }}>
                                        {{ $brand->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group mb-3">
                                <label class="d-block mb-1">Tùy chọn</label>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="is_active" value="1"
                                        id="is_active" {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
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
    <div class="row new-variant-row mb-3 align-items-center border-bottom pb-3">
        {{-- Vòng lặp để tạo các select box cho các thuộc tính --}}
        @foreach ($attributes as $attribute)
        <div class="col-md-3 mb-2">
            <div class="form-group mb-0">
                <label>{{ $attribute->name }}</label>
                <select name="new_variants[0][attribute_values][]" class="form-control select2-variant">
                    <option value="">-- Chọn {{ $attribute->name }} --</option>
                    @foreach ($attribute->attributeValues as $value)
                    <option value="{{ $value->id }}">{{ $value->value }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        @endforeach
        {{-- End vòng lặp --}}
        <div class="col-md-2 mb-2">
            <div class="form-group mb-0">
                <label>Giá gốc</label>
                <input type="number" name="new_variants[0][price]" class="form-control" step="0.01"
                    min="0">
            </div>
        </div>
        <div class="col-md-2 mb-2">
            <div class="form-group mb-0">
                <label>Giá sale</label>
                <input type="number" name="new_variants[0][sale_price]" class="form-control" step="0.01"
                    min="0">
            </div>
        </div>
        <div class="col-md-2 mb-2">
            <div class="form-group mb-0">
                <label>Bắt đầu sale</label>
                <input type="datetime-local" name="new_variants[0][sale_price_start_at]" class="form-control">
            </div>
        </div>
        <div class="col-md-2 mb-2">
            <div class="form-group mb-0">
                <label>Kết thúc sale</label>
                <input type="datetime-local" name="new_variants[0][sale_price_end_at]" class="form-control">
            </div>
        </div>
        <div class="col-md-2 mb-2">
            <div class="form-group mb-0">
                <label>Số lượng</label>
                <input type="number" name="new_variants[0][stock]" class="form-control" min="0">
            </div>
        </div>
        <div class="col-md-2 mb-2">
            <div class="form-group mb-0">
                <label>Ảnh</label>
                <input type="file" name="new_variants[0][thumbnail]" class="form-control new-variant-image-input"
                    accept="image/*">
                <div class="new-variant-image-preview mt-2" style="display: none;">
                    <img src="#" alt="Ảnh biến thể" class="img-thumbnail" width="60">
                </div>
            </div>
        </div>
        <div class="col-md-1 mb-2">
            <div class="form-group mb-0">
                <label>Sale?</label>
                <div class="form-check form-switch mt-2">
                    <input class="form-check-input" type="checkbox" name="new_variants[0][is_sale]" value="1">
                </div>
            </div>
        </div>
        <div class="col-md-1 mb-2">
            <div class="form-group mb-0">
                <label>Ẩn/Hiện</label>
                <div class="form-check form-switch mt-2">
                    <input class="form-check-input" type="checkbox" name="new_variants[0][is_active]" value="1"
                        checked>
                </div>
            </div>
        </div>
        <div class="col-md-1 text-center d-flex align-items-end mb-2">
            <button type="button" class="btn btn-danger btn-sm remove-new-variant w-100"><i
                    class="fa fa-times"></i></button>
        </div>
    </div>
</template>

@push('scripts')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
{{-- Cần thêm thư viện Toastr để hiển thị thông báo thành công/thất bại --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

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
        // Khởi tạo Select2 cho danh mục và nhà sản xuất
        $('.select2').select2();

        // Cấu hình Toastr
        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "timeOut": "3000",
        };

        // Xử lý xem trước ảnh đại diện (thumbnail)
        $('#thumbnailInput').on('change', function(e) {
            const file = e.target.files[0];
            const previewArea = $('#thumbnailPreviewArea');
            const previewImg = $('#thumbnailPreview');
            const uploadWrapper = $('.image-uploads');

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.attr('src', e.target.result);
                    previewArea.show();
                    uploadWrapper.hide();
                };
                reader.readAsDataURL(file);
            } else {
                previewArea.hide();
                uploadWrapper.show();
                previewImg.attr('src', '');
            }
        });

        // Xóa ảnh đại diện
        $('#removeThumbnail').on('click', function() {
            $('#thumbnailInput').val(''); // Xóa giá trị input file
            $('#thumbnailPreviewArea').hide(); // Ẩn phần xem trước
            $('.image-uploads').show(); // Hiển thị lại khu vực tải ảnh
            $('#thumbnailPreview').attr('src', ''); // Xóa src ảnh
            // Có thể cần thêm logic để gửi yêu cầu AJAX xóa ảnh trên server nếu ảnh đã được lưu
        });

        // Xử lý xem trước bộ sưu tập ảnh (cho ảnh mới)
        $('#imagesInput').on('change', function(e) {
            // Xóa các ảnh xem trước mới cũ trước khi thêm ảnh mới
            $('#imagesPreviewArea .new-image-preview').remove();
            Array.from(e.target.files).forEach(file => {
                if (file && file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const img = $('<img>').attr('src', e.target.result).addClass(
                                'img-thumbnail')
                            .css({
                                width: '100%',
                                height: '100%',
                                'object-fit': 'cover'
                            });
                        const imgContainer = $('<div>')
                            .addClass('image-container new-image-preview position-relative')
                            .css({
                                width: '100px',
                                height: '100px',
                                'object-fit': 'cover'
                            }); // Đảm bảo kích thước cố định
                        const removeButton = $('<button>')
                            .attr('type', 'button')
                            .addClass('btn btn-danger btn-sm remove-added-image')
                            .css({
                                position: 'absolute',
                                top: '-5px',
                                right: '5px',
                                'border-radius': '50%',
                                padding: '0.1rem 0.4rem'
                            })
                            .html('&times;');

                        imgContainer.append(img, removeButton);
                        $('#imagesPreviewArea').append(imgContainer);
                    };
                    reader.readAsDataURL(file);
                }
            });
        });

        // Xóa ảnh mới được thêm vào từ bộ sưu tập ảnh (trên client-side)
        $(document).on('click', '.remove-added-image', function() {
            $(this).closest('.new-image-preview').remove();
            // Cần cập nhật lại input file nếu muốn loại bỏ file đã chọn (phức tạp hơn)
            // Trong trường hợp này, khi submit form, Laravel sẽ chỉ nhận các file mới được chọn.
            // Nếu muốn xóa file đã chọn khỏi input, cần tạo lại DataTransfer object.
        });


        // Xóa ảnh hiện có trong bộ sưu tập (AJAX)
        $(document).on('click', '.remove-existing-image', function() {
            if (!confirm('Bạn có chắc chắn muốn xóa ảnh này?')) {
                return;
            }

            const button = $(this);
            const imageId = button.data('image-id');
            const container = button.closest('.image-container');
            const existingImagesInput = $('#existingImagesInput');

            // Lấy danh sách ID hiện tại
            let existingIds = existingImagesInput.val().split(',').filter(id => id.length > 0);

            // Xóa ID của ảnh vừa click khỏi mảng
            const index = existingIds.indexOf(String(imageId));
            if (index > -1) {
                existingIds.splice(index, 1);
            }

            // Cập nhật lại giá trị của trường input ẩn
            existingImagesInput.val(existingIds.join(','));

            // Xóa ảnh khỏi giao diện
            container.remove();

            toastr.success('Ảnh sẽ được xóa khi bạn cập nhật sản phẩm.');
        });

        // Xóa biến thể hiện có (AJAX)
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
                    toastr.error('Lỗi server: ' + (xhr.responseJSON.message ||
                        'Có lỗi xảy ra.'));
                }
            });
        });

        // Thêm biến thể mới
        let newVariantCount = 0;
        $('#add-new-variant').on('click', function() {
            const template = $('#new-variant-template').html();
            // Thay thế tất cả các [0] bằng chỉ số mới
            const newRowHtml = template.replace(/new_variants\[0\]/g,
                `new_variants[${newVariantCount}]`);
            const newRow = $(newRowHtml);
            $('#new-variants-container').append(newRow);

            // Khởi tạo lại select2 cho các selectbox trong hàng biến thể mới
            newRow.find('.select2-variant').select2({
                dropdownParent: newRow // Đảm bảo dropdown hiển thị đúng vị trí
            });

            newVariantCount++;
        });

        // Xóa biến thể mới (chỉ trên client-side)
        $(document).on('click', '.remove-new-variant', function() {
            $(this).closest('.new-variant-row').remove();
        });

        // Xử lý xem trước ảnh cho biến thể mới được thêm
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
        z-index: 10;
        /* Đảm bảo input nằm trên để có thể click */
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
        overflow: hidden;
        /* Đảm bảo ảnh không tràn ra ngoài container */
    }

    #imagesPreviewArea img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .select2-container .select2-selection--multiple {
        border: 1px solid #e9ecef !important;
    }

    /* Style cho các trường trong biến thể mới để hiển thị tốt hơn */
    .new-variant-row .form-group label {
        font-size: 0.85em;
        /* Giảm kích thước label cho hàng biến thể */
        margin-bottom: 0.25rem;
    }

    .new-variant-row .form-control {
        font-size: 0.9em;
        /* Giảm kích thước font của input */
        padding: 0.375rem 0.75rem;
        /* Giảm padding */
    }

    .new-variant-row .select2-container .select2-selection--single {
        height: calc(1.8rem + 2px);
        /* Điều chỉnh chiều cao cho select2 */
    }

    .new-variant-row .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 1.8rem;
    }

    .new-variant-row .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 1.8rem;
    }

    .new-variant-row .form-check-input {
        margin-top: 0.5rem;
        /* Căn chỉnh checkbox/switch */
    }
</style>
@endsection
