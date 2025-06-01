@extends('admin.layouts.app')

@section('content')
<div class="container">
    <h1>Sửa danh mục</h1>

    <form action="{{ route('admin.categories.update', $category->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="parent_id">Danh mục cha</label>
            <select name="parent_id" id="parent_id" class="form-control">
                <option value="">-- Không có --</option>
                @foreach ($parentCategories as $parent)
                    <option value="{{ $parent->id }}" {{ $category->parent_id == $parent->id ? 'selected' : '' }}>
                        {{ $parent->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="name">Tên danh mục *</label>
            <input type="text" name="name" id="name" class="form-control" required value="{{ $category->name }}">
        </div>

        <div class="form-group">
            <label for="icon">Icon</label>
            <input type="text" name="icon" id="icon" class="form-control" value="{{ $category->icon }}">
        </div>

        <div class="form-group">
            <label for="ordinal">Thứ tự hiển thị *</label>
            <input type="number" name="ordinal" id="ordinal" class="form-control" required value="{{ $category->ordinal }}">
        </div>

        <div class="form-group">
            <label for="is_active">Trạng thái *</label>
            <select name="is_active" id="is_active" class="form-control" required>
                <option value="1" {{ $category->is_active ? 'selected' : '' }}>Hiển thị</option>
                <option value="0" {{ !$category->is_active ? 'selected' : '' }}>Ẩn</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Cập nhật</button>
        <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Hủy</a>
    </form>
</div>
@endsection
