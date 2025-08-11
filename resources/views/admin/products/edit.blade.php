@extends('admin.layouts.app')

@section('content')
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

                    {{-- Thông tin chung --}}
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label>Tên sản phẩm <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $product->name) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label>Danh mục <span class="text-danger">*</span></label>
                            <select name="category_id" class="form-control" required>
                                <option value="">-- Chọn danh mục --</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label>Nhà sản xuất <span class="text-danger">*</span></label>
                            <select name="brand_id" class="form-control" required>
                                <option value="">-- Chọn nhà sản xuất --</option>
                                @foreach ($brands as $brand)
                                    <option value="{{ $brand->id }}" {{ old('brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }}>
                                        {{ $brand->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label>Mô tả ngắn</label>
                            <textarea name="short_description" rows="2" class="form-control">{{ old('short_description', $product->short_description) }}</textarea>
                        </div>
                    </div>

                    {{-- Thời gian sale --}}
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label>Bắt đầu sale</label>
                            <input type="date" name="sale_price_start_at" class="form-control" value="{{ old('sale_price_start_at', optional($product->sale_price_start_at)->format('Y-m-d')) }}">
                        </div>
                        <div class="col-md-6">
                            <label>Kết thúc sale</label>
                            <input type="date" name="sale_price_end_at" class="form-control" value="{{ old('sale_price_end_at', optional($product->sale_price_end_at)->format('Y-m-d')) }}">
                        </div>
                    </div>

                    {{-- Tùy chọn --}}
                    <div class="mb-3">
                        <label class="d-block">Tùy chọn</label>
                        <div class="form-check form-check-inline">
                            <input type="checkbox" name="is_sale" value="1" class="form-check-input" {{ old('is_sale', $product->is_sale) ? 'checked' : '' }}>
                            <label class="form-check-label">Đang sale</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input type="checkbox" name="is_active" value="1" class="form-check-input" {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label">Hiển thị</label>
                        </div>
                    </div>

                    {{-- Mô tả chi tiết --}}
                    <div class="mb-3">
                        <label>Mô tả chi tiết</label>
                        <textarea name="description" rows="5" class="form-control">{{ old('description', $product->description) }}</textarea>
                    </div>

                    {{-- Ảnh đại diện --}}
                    <div class="mb-3">
                        <label>Ảnh đại diện</label>
                        <input type="file" name="thumbnail" class="form-control" accept="image/*">
                        @if ($product->thumbnail)
                            <div class="mt-2">
                                <img src="{{ asset('storage/' . $product->thumbnail) }}" width="200" class="img-thumbnail">
                            </div>
                        @endif
                    </div>

                    {{-- Biến thể --}}
                    <h5 class="mt-4 mb-3">Biến thể sản phẩm</h5>
                    @if ($product->variants->count())
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Giá</th>
                                        <th>SKU</th>
                                        <th>Ảnh</th>
                                        <th>Số lượng</th>
                                        <th>Màu</th>
                                        <th>Kích thước</th>
                                        <th>Hiển thị</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($product->variants as $i => $variant)
                                        <tr>
                                            <td>
                                                <input type="number" name="variants[{{ $i }}][price]" class="form-control" value="{{ old("variants.$i.price", $variant->price) }}">
                                            </td>
                                            <td>
                                                <input type="text" name="variants[{{ $i }}][sku]" class="form-control" value="{{ old("variants.$i.sku", $variant->sku) }}">
                                            </td>
                                            <td>
                                                @if ($variant->thumbnail)
                                                    <img src="{{ asset('storage/' . $variant->thumbnail) }}" width="60"><br>
                                                @endif
                                                <input type="file" name="variants[{{ $i }}][thumbnail]" class="form-control mt-1" accept="image/*">
                                            </td>
                                            <td>
                                                <input type="number" name="variants[{{ $i }}][stock]" class="form-control" value="{{ old("variants.$i.stock", $variant->stock) }}">
                                            </td>
                                            <td>
                                                {{-- Chỉ hiển thị, không cho sửa --}}
                                                @php
                                                    $colorValue = $variant->attributeValues->where('attribute_id', 1)->first()->value ?? '';
                                                    $colorId = $variant->attributeValues->where('attribute_id', 1)->first()->id ?? '';
                                                @endphp
                                                <span class="badge bg-info">{{ $colorValue }}</span>
                                                <input type="hidden" name="variants[{{ $i }}][color_id]" value="{{ $colorId }}">
                                            </td>
                                            <td>
                                                {{-- Chỉ hiển thị, không cho sửa --}}
                                                @php
                                                    $sizeValue = $variant->attributeValues->where('attribute_id', 2)->first()->value ?? '';
                                                    $sizeId = $variant->attributeValues->where('attribute_id', 2)->first()->id ?? '';
                                                @endphp
                                                <span class="badge bg-secondary">{{ $sizeValue }}</span>
                                                <input type="hidden" name="variants[{{ $i }}][size_id]" value="{{ $sizeId }}">
                                            </td>
                                            <td>
                                                <input type="checkbox" name="variants[{{ $i }}][is_active]" value="1" {{ $variant->is_active ? 'checked' : '' }}>
                                            </td>
                                            <input type="hidden" name="variants[{{ $i }}][id]" value="{{ $variant->id }}">
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif

                    {{-- Thêm biến thể mới --}}
                    <h5 class="mt-4 mb-3">Thêm biến thể mới</h5>
                    <div id="new-variants"></div>
                    <button type="button" class="btn btn-success mt-3" id="add-variant-btn">
                        <i class="fa fa-plus"></i> Thêm biến thể
                    </button>

                    <div class="mt-4 d-flex justify-content-between">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-save"></i> Cập nhật sản phẩm
                        </button>
                        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                            <i class="fa fa-arrow-left"></i> Quay lại danh sách
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
let variantIndex = {{ $product->variants->count() }};

document.getElementById('add-variant-btn').addEventListener('click', function() {
    let html = `
        <div class="row mb-3 new-variant-row">
            <div class="col-md-2">
                <input type="number" name="variants[${variantIndex}][price]" class="form-control" placeholder="Giá" required>
            </div>
            <div class="col-md-2">
                <input type="text" name="variants[${variantIndex}][sku]" class="form-control" placeholder="SKU">
            </div>
            <div class="col-md-2">
                <input type="number" name="variants[${variantIndex}][stock]" class="form-control" placeholder="Số lượng">
            </div>
            <div class="col-md-2">
                <input type="file" name="variants[${variantIndex}][thumbnail]" class="form-control" accept="image/*">
            </div>
            <div class="col-md-2">
                <select name="variants[${variantIndex}][color_id]" class="form-control" required>
                    <option value="">-- Màu --</option>
                    @foreach ($colors as $color)
                        <option value="{{ $color->id }}">{{ $color->value }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="variants[${variantIndex}][size_id]" class="form-control" required>
                    <option value="">-- Size --</option>
                    @foreach ($sizes as $size)
                        <option value="{{ $size->id }}">{{ $size->value }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-12 mt-2">
                <label>
                    <input type="checkbox" name="variants[${variantIndex}][is_active]" value="1" checked> Hiển thị
                </label>
                <button type="button" class="btn btn-danger btn-sm remove-variant-btn">Xóa</button>
            </div>
        </div>
    `;
    document.getElementById('new-variants').insertAdjacentHTML('beforeend', html);
    variantIndex++;
});

document.addEventListener('click', function(e) {
    if (e.target.classList.contains('remove-variant-btn')) {
        e.target.closest('.new-variant-row').remove();
    }
});
</script>
@endsection

@endsection
