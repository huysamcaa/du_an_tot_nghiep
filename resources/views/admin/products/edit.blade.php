@extends('admin.layouts.app')

@section('content')
    <!-- Existing breadcrumbs and header code remains unchanged -->
    <div class="breadcrumbs">
        <div class="breadcrumbs-inner">
            <div class="row m-0">
                <div class="col-sm-4">
                    <div class="page-header float-left">
                        <div class="page-title">
                            <h1>Sửa sản phẩm</h1>
                        </div>
                    </div>
                </div>
                <div class="col-sm-8">
                    <div class="page-header float-right">
                        <div class="page-title">
                            <ol class="breadcrumb text-right">
                                <li><a href="{{ route('admin.dashboard') }}">Trang chủ</a></li>
                                <li><a href="{{ route('admin.products.index') }}">Sản phẩm</a></li>
                                <li class="active">Sửa sản phẩm</li>
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
                    <h5 class="mb-0">Sửa sản phẩm</h5>
                </div>

                <div class="card">
                    <div class="card-body">
                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                            </div>
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

                            <!-- Existing fields for product details (name, category, brand, etc.) remain unchanged -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="font-weight-bold">Tên sản phẩm <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control"
                                        value="{{ old('name', $product->name) }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="font-weight-bold">Danh mục <span class="text-danger">*</span></label>
                                    <select name="category_id" class="form-control" required>
                                        <option value="">-- Chọn danh mục --</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}"
                                                {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="font-weight-bold">Nhà sản xuất <span class="text-danger">*</span></label>
                                    <select name="brand_id" class="form-control" required>
                                        <option value="">-- Chọn nhà sản xuất --</option>
                                        @foreach ($brands as $brand)
                                            <option value="{{ $brand->id }}"
                                                {{ old('brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }}>
                                                {{ $brand->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="font-weight-bold">Mô tả ngắn</label>
                                    <textarea name="short_description" rows="2" class="form-control">{{ old('short_description', $product->short_description) }}</textarea>
                                </div>
                            </div>

                            {{-- <div class="row mb-3">
                                <div class="col-md-4">
                                    <label class="font-weight-bold">Giá gốc <span class="text-danger">*</span></label>
                                    <input type="number" name="price" step="0.01" class="form-control"
                                        value="{{ old('price', $product->price) }}" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="font-weight-bold">Giá sale</label>
                                    <input type="number" name="sale_price" step="0.01" class="form-control"
                                        value="{{ old('sale_price', $product->sale_price) }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="font-weight-bold">Số lượng</label>
                                    <input type="number" name="stock" class="form-control"
                                        value="{{ old('stock', $product->stock ?? 0) }}">
                                </div>
                            </div> --}}

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="font-weight-bold">Bắt đầu sale</label>
                                    <input type="date" name="sale_price_start_at" class="form-control"
                                        value="{{ old('sale_price_start_at', optional($product->sale_price_start_at)->format('Y-m-d')) }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="font-weight-bold">Kết thúc sale</label>
                                    <input type="date" name="sale_price_end_at" class="form-control"
                                        value="{{ old('sale_price_end_at', optional($product->sale_price_end_at)->format('Y-m-d')) }}">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="font-weight-bold d-block">Tùy chọn</label>
                                    <div class="form-check form-check-inline me-4">
                                        <input class="form-check-input" type="checkbox" name="is_sale" value="1"
                                            {{ old('is_sale', $product->is_sale) ? 'checked' : '' }}>
                                        <label class="form-check-label">Đang sale</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="is_active" value="1"
                                            {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                                        <label class="form-check-label">Hiển thị</label>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label class="font-weight-bold">Mô tả chi tiết</label>
                                    <textarea name="description" rows="5" class="form-control">{{ old('description', $product->description) }}</textarea>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="font-weight-bold">Ảnh đại diện</label>
                                    <input type="file" name="thumbnail" class="form-control" accept="image/*">
                                    @if ($product->thumbnail)
                                        <div class="mt-3">
                                            <img src="{{ asset('storage/' . $product->thumbnail) }}" alt="Thumbnail"
                                                class="img-thumbnail" style="max-width: 200px;">
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Existing Variants -->
                            <h5 class="mt-4 mb-3">Biến thể sản phẩm</h5>
                            @if ($product->variants->count())
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="existing-variants-table">
                                        <thead>
                                            <tr>
                                                <th>Giá</th>
                                                <th>SKU</th>
                                                <th>Ảnh</th>
                                                <th>Số lượng</th>
                                                <th>Giá trị thuộc tính</th>
                                                <th>Xóa</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($product->variants as $i => $variant)
                                                <tr>
                                                    <td>
                                                        <input type="number" name="variants[{{ $i }}][price]"
                                                            class="form-control"
                                                            value="{{ old("variants.$i.price", $variant->price) }}">
                                                    </td>
                                                    <td>
                                                        <input type="text" name="variants[{{ $i }}][sku]"
                                                            class="form-control"
                                                            value="{{ old("variants.$i.sku", $variant->sku) }}">
                                                    </td>
                                                    <td>
                                                        @if ($variant->thumbnail)
                                                            <img src="{{ asset('storage/' . $variant->thumbnail) }}"
                                                                alt="thumb" width="60"><br>
                                                        @endif
                                                        <input type="file"
                                                            name="variants[{{ $i }}][thumbnail]"
                                                            class="form-control-file" accept="image/*">
                                                    </td>
                                                    <td>
                                                        <input type="number" name="variants[{{ $i }}][stock]"
                                                            class="form-control"
                                                            value="{{ old("variants.$i.stock", $variant->stock) }}">
                                                    </td>
                                                    <td>
                                                        @foreach ($variant->attributeValues as $attrValue)
                                                            <span
                                                                class="badge bg-info text-white mb-1">{{ $attrValue->attribute->name }}:
                                                                {{ $attrValue->value }}</span>
                                                            <input type="hidden"
                                                                name="variants[{{ $i }}][attribute_value_id][]"
                                                                value="{{ $attrValue->id }}">
                                                        @endforeach
                                                        <!-- Dropdown to select attributes for existing variant -->
                                                        {{-- <select name="variants[{{ $i }}][attribute_value_id][]"
                                                            class="form-control" multiple>
                                                            @foreach ($colors as $color)
                                                                <option value="{{ $color->id }}"
                                                                    {{ $variant->attributeValues->contains($color->id) ? 'selected' : '' }}>
                                                                    Màu: {{ $color->value }}
                                                                </option>
                                                            @endforeach
                                                            @foreach ($sizes as $size)
                                                                <option value="{{ $size->id }}"
                                                                    {{ $variant->attributeValues->contains($size->id) ? 'selected' : '' }}>
                                                                    Kích thước: {{ $size->value }}
                                                                </option>
                                                            @endforeach
                                                        </select> --}}
                                                    </td>
                                                    <td>
                                                        <input type="checkbox"
                                                            name="variants[{{ $i }}][delete]" value="1">
                                                        Xóa
                                                    </td>
                                                    <input type="hidden" name="variants[{{ $i }}][id]"
                                                        value="{{ $variant->id }}">
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p>Chưa có biến thể nào cho sản phẩm này.</p>
                            @endif

                            <!-- New Variants Section -->
                            <h5 class="mt-4 mb-3">Thêm biến thể mới</h5>
                            <div id="new-variants">
                                <!-- JavaScript will append new variant rows here -->
                            </div>
                            <button type="button" class="btn btn-success mt-3" id="add-variant-btn">
                                <i class="fa fa-plus me-1"></i> Thêm biến thể
                            </button>

                            <!-- Nút hành động -->
                            <div class="mt-4 d-flex justify-content-between">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-save me-1"></i> Cập nhật sản phẩm
                                </button>
                                <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                                    <i class="fa fa-arrow-left me-1"></i> Quay lại danh sách
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div><!-- .animated -->
    </div><!-- .content -->

    <!-- JavaScript for Adding New Variants -->
    @section('scripts')
        <script>
            let variantIndex = {{ $product->variants->count() }};

            document.getElementById('add-variant-btn').addEventListener('click', function() {
                const newVariantRow = `
                    <div class="row mb-3 new-variant-row">
                        <div class="col-md-3">
                            <label class="font-weight-bold">Giá <span class="text-danger">*</span></label>
                            <input type="number" name="variants[${variantIndex}][price]" class="form-control" required>
                        </div>
                        <div class="col-md-3">
                            <label class="font-weight-bold">SKU</label>
                            <input type="text" name="variants[${variantIndex}][sku]" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label class="font-weight-bold">Số lượng</label>
                            <input type="number" name="variants[${variantIndex}][stock]" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label class="font-weight-bold">Ảnh</label>
                            <input type="file" name="variants[${variantIndex}][thumbnail]" class="form-control-file" accept="image/*">
                        </div>
                        <div class="col-md-12 mt-2">
                            <label class="font-weight-bold">Giá trị thuộc tính <span class="text-danger">*</span></label>
                            <select name="variants[${variantIndex}][attribute_value_id][]" class="form-control" multiple required>
                                @foreach ($colors as $color)
                                    <option value="{{ $color->id }}">Màu: {{ $color->value }}</option>
                                @endforeach
                                @foreach ($sizes as $size)
                                    <option value="{{ $size->id }}">Kích thước: {{ $size->value }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12 mt-2">
                            <button type="button" class="btn btn-danger btn-sm remove-variant-btn">Xóa biến thể</button>
                        </div>
                    </div>
                `;
                document.getElementById('new-variants').insertAdjacentHTML('beforeend', newVariantRow);
                variantIndex++;
            });

            // Remove new variant row
            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-variant-btn')) {
                    e.target.closest('.new-variant-row').remove();
                }
            });
        </script>
    @endsection
@endsection
