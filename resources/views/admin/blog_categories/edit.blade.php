@extends('admin.layouts.app')

@section('content')
<div class="content">
  <div class="card">
    <div class="card-header">
      <h5>{{ isset($blog_category) ? 'Sửa' : 'Thêm' }} danh mục</h5>
    </div>
    <div class="card-body">
      <form action="{{ isset($blog_category) ? route('admin.blog_categories.update',$blog_category) : route('admin.blog_categories.store') }}" method="POST">
        @csrf
        @if(isset($blog_category)) @method('PUT') @endif
        <div class="form-group">
          <label for="name">Tên danh mục</label>
          <input type="text" name="name" value="{{ old('name',$blog_category->name ?? '') }}" class="form-control" required>
        </div>
        <div class="form-group form-check">
          <input type="hidden" name="is_active" value="0">
          <input type="checkbox" name="is_active" class="form-check-input" value="1" {{ old('is_active',$blog_category->is_active ?? true) ? 'checked' : '' }}>
          <label class="form-check-label">Kích hoạt</label>
        </div>
        <button type="submit" class="btn btn-success">Lưu</button>
        <a href="{{ route('admin.blog_categories.index') }}" class="btn btn-secondary">Quay lại</a>
      </form>
    </div>
  </div>
</div>
@endsection
