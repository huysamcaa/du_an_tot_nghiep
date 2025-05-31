@extends('admin.layouts.app')

@section('content')
<style>
    body, .container {
        background: #ecf5f4 !important;
    }
    .container {
        max-width: 500px;
        margin: 40px auto;
        background: #fff;
        border-radius: 14px;
        box-shadow: 0 2px 16px rgba(44, 62, 80, 0.10);
        padding: 32px 28px 24px 28px;
    }
    h1 {
        color: #22577a;
        font-weight: 700;
        text-align: center;
        margin-bottom: 28px;
    }
    .form-group label {
        font-weight: 600;
        color: #22577a;
        margin-bottom: 6px;
    }
    .form-control {
        border-radius: 6px;
        border: 1px solid #b2dfdb;
        background: #ecf5f4;
        color: #22577a;
        font-size: 16px;
        padding: 8px 12px;
        margin-bottom: 12px;
        transition: border 0.2s;
    }
    .form-control:focus {
        border-color: #22577a;
        background: #fff;
        outline: none;
    }
    .btn-primary {
        background: #38b6ff;
        border: none;
        color: #fff;
        font-weight: 600;
        border-radius: 6px;
        padding: 8px 24px;
        margin-right: 8px;
        transition: background 0.2s;
    }
    .btn-primary:hover {
        background: #22577a;
        color: #fff;
    }
    .btn-secondary {
        background: #b2dfdb;
        border: none;
        color: #22577a;
        font-weight: 600;
        border-radius: 6px;
        padding: 8px 24px;
        transition: background 0.2s;
    }
    .btn-secondary:hover {
        background: #22577a;
        color: #fff;
    }
    .alert-danger {
        background: #ffd6d6;
        color: #d90429;
        border: none;
        border-radius: 6px;
        padding: 10px 16px;
        margin-bottom: 18px;
    }
</style>
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