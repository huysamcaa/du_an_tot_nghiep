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

            {{-- Thêm enctype="multipart/form-data" để xử lý tải lên tệp --}}
            <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
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

                    {{-- Icon (input file để tải ảnh lên) --}}
                    <div class="col-lg-6 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Icon (Tải lên hình ảnh) <span class="text-danger">*</span></label>
                            <input type="file" name="icon" class="form-control" id="icon-upload" required>
                            <div class="mt-2" id="icon-preview-container" style="display: none;">
                                <p class="text-muted">Ảnh xem trước:</p>
                                <img id="icon-preview" src="#" alt="Icon Preview" style="max-width: 100px; max-height: 100px;">
                            </div>
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

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const iconUpload = document.getElementById('icon-upload');
        const iconPreview = document.getElementById('icon-preview');
        const iconPreviewContainer = document.getElementById('icon-preview-container');

        iconUpload.addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    iconPreview.src = e.target.result;
                    iconPreviewContainer.style.display = 'block';
                }
                reader.readAsDataURL(file);
            } else {
                iconPreview.src = '#';
                iconPreviewContainer.style.display = 'none';
            }
        });
    });
</script>
@endpush
