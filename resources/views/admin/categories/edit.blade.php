@extends('admin.layouts.app')

@section('title', 'Sửa danh mục sản phẩm')

@section('content')
<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4>Sửa danh mục sản phẩm</h4>
            <h6>Chỉnh sửa danh mục sản phẩm hiện có</h6>
        </div>
        <div class="page-btn">
            <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary me-2">
                <i class="fa fa-arrow-left me-1"></i> Quay lại
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Đảm bảo form có thể xử lý file upload --}}
            <form action="{{ route('admin.categories.update', $category->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    {{-- Danh mục cha --}}
                    <div class="col-lg-6 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Chọn danh mục cha</label>
                            <select name="parent_id" id="parent_id" class="form-control"
                                {{ $hasChildren ? 'disabled' : '' }}>
                                <option value=""
                                    {{ is_null($category->parent_id) ? 'selected' : '' }}>
                                    -- Không chọn (Danh mục cha) --
                                </option>
                                @foreach($parentCategories as $parent)
                                    <option value="{{ $parent->id }}"
                                        {{ $category->parent_id == $parent->id ? 'selected' : '' }}>
                                        {{ $parent->name }}
                                    </option>
                                @endforeach
                            </select>

                            {{-- Hiển thị thông báo nếu dropdown bị vô hiệu hóa --}}
                            @if($hasChildren)
                                <small class="text-danger mt-2">
                                    <i>Không thể gán danh mục này làm con vì nó đang chứa các danh mục con khác.</i>
                                </small>
                            @endif
                        </div>
                    </div>

                    {{-- Tên danh mục --}}
                    <div class="col-lg-6 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Tên danh mục <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" required value="{{ old('name', $category->name) }}">
                        </div>
                    </div>

                    {{-- Slug --}}
                    <div class="col-lg-6 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Slug</label>
                            <input type="text" name="slug" class="form-control" value="{{ old('slug', $category->slug) }}">
                            <small class="text-muted"><i>Nếu để trống, slug sẽ được tự động tạo từ tên.</i></small>
                        </div>
                    </div>

                    {{-- Icon --}}
                    <div class="col-lg-6 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Icon</label>
                            <div class="mb-2">
                                @if($category->icon)
                                    <img src="{{ asset('storage/' . $category->icon) }}" alt="Current Icon" class="rounded" style="max-width: 100px;">
                                @else
                                    <span class="text-muted">Chưa có icon</span>
                                @endif
                            </div>
                            <input type="file" name="icon" class="form-control" accept="image/*">
                            <small class="text-muted"><i>Chọn ảnh mới để thay thế icon hiện tại.</i></small>
                            <input type="hidden" name="old_icon" value="{{ $category->icon }}">
                        </div>
                    </div>

                    {{-- Thứ tự --}}
                    <div class="col-lg-6 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Thứ tự hiển thị <span class="text-danger">*</span></label>
                            <input type="number" name="ordinal" class="form-control" required value="{{ old('ordinal', $category->ordinal) }}">
                        </div>
                    </div>

                    {{-- Trạng thái --}}
                    <div class="col-lg-6 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Trạng thái <span class="text-danger">*</span></label>
                            <select name="is_active" class="form-control" required>
                                <option value="1" {{ $category->is_active ? 'selected' : '' }}>Hiển thị</option>
                                <option value="0" {{ !$category->is_active ? 'selected' : '' }}>Ẩn</option>
                            </select>
                        </div>
                    </div>

                    {{-- Nút submit --}}
                    <div class="col-lg-12">
                        <button type="submit" class="btn btn-submit me-2">Cập nhật</button>
                        <a href="{{ route('admin.categories.index') }}" class="btn btn-cancel">Hủy</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
