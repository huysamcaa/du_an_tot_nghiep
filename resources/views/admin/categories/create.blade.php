@extends('admin.layouts.app')

@section('title', 'Thêm danh mục sản phẩm')

@section('content')
<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4>Thêm danh mục sản phẩm</h4>
            <h6>Tạo một danh mục sản phẩm mới</h6>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            {{-- Hiển thị thông báo lỗi --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.categories.store') }}" method="POST">
                @csrf
                <div class="row">
                    {{-- Tên danh mục --}}
                    <div class="col-lg-6 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Tên danh mục <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" required value="{{ old('name') }}">
                        </div>
                    </div>

                    {{-- Slug --}}
                    <div class="col-lg-6 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Slug</label>
                            {{-- Slug sẽ tự động được tạo từ tên, người dùng có thể tùy chỉnh --}}
                            <input type="text" name="slug" class="form-control" value="{{ old('slug') }}">
                        </div>
                    </div>

                    {{-- Danh mục cha --}}
                    <div class="col-lg-6 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Danh mục cha</label>
                            <select name="parent_id" class="form-control">
                                <option value="">-- Không có (Danh mục cha) --</option>
                                @foreach($parentCategories as $parent)
                                    <option value="{{ $parent->id }}" {{ old('parent_id') == $parent->id ? 'selected' : '' }}>
                                        {{ $parent->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Icon --}}
                    <div class="col-lg-6 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Icon</label>
                            <input type="text" name="icon" class="form-control" placeholder="<i class='fa fa-icon'></i>" value="{{ old('icon') }}">
                        </div>
                    </div>

                    {{-- Thứ tự hiển thị --}}
                    <div class="col-lg-6 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Thứ tự hiển thị <span class="text-danger">*</span></label>
                            <input type="number" name="ordinal" class="form-control" required value="{{ old('ordinal', 0) }}">
                        </div>
                    </div>

                    {{-- Trạng thái --}}
                    <div class="col-lg-6 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Trạng thái <span class="text-danger">*</span></label>
                            <select name="is_active" class="form-control" required>
                                <option value="1" {{ old('is_active', '1') == '1' ? 'selected' : '' }}>Hiển thị</option>
                                <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>Ẩn</option>
                            </select>
                        </div>
                    </div>

                    {{-- Nút submit --}}
                    <div class="col-lg-12">
                        <button type="submit" class="btn btn-submit me-2">Lưu</button>
                        <a href="{{ route('admin.categories.index') }}" class="btn btn-cancel">Hủy</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
