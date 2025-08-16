@extends('admin.layouts.app')

@section('title', 'Sửa danh mục sản phẩm')

@section('content')
<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4>Sửa danh mục sản phẩm</h4>
            <h6>Chỉnh sửa danh mục sản phẩm hiện có</h6>
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

            <form action="{{ route('admin.categories.update', $category->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    {{-- Loại danh mục --}}
                    <div class="col-lg-6 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Loại danh mục</label>
                            <div class="d-flex">
                                <div class="form-check mr-3">
                                    <input class="form-check-input" type="radio" id="type_parent" name="type" value="parent" {{ $category->type === 'parent' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="type_parent">Cha</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" id="type_child" name="type" value="child" {{ $category->type === 'child' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="type_child">Con</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Danh mục cha --}}
                    <div class="col-lg-6 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Chọn danh mục cha</label>
                            <select name="parent_id" id="parent_id" class="form-control">
                                <option value="">-- Không chọn --</option>
                                @foreach($parentCategories as $parent)
                                    <option value="{{ $parent->id }}" {{ $category->parent_id == $parent->id ? 'selected' : '' }}>{{ $parent->name }}</option>
                                @endforeach
                            </select>
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
                        </div>
                    </div>

                    {{-- Icon --}}
                    <div class="col-lg-6 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Icon</label>
                            <input type="text" name="icon" class="form-control" placeholder="<i class='fa fa-icon'></i>" value="{{ old('icon', $category->icon) }}">
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const parentRadio = document.getElementById('type_parent');
    const childRadio  = document.getElementById('type_child');
    const parentSel   = document.getElementById('parent_id');

    function toggleParent() {
        parentSel.disabled = parentRadio.checked;
        if (parentRadio.checked) parentSel.value = "";
    }

    parentRadio.addEventListener('change', toggleParent);
    childRadio.addEventListener('change', toggleParent);
    toggleParent();
});
</script>
@endpush
@endsection