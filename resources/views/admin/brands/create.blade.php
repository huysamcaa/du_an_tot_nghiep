@extends('admin.layouts.app')

@section('content')
<h1 class="mb-4">Thêm Thương Hiệu</h1>

{{-- Thông báo session --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<form action="{{ route('admin.brands.store') }}" method="POST" enctype="multipart/form-data" class="card p-4 shadow-sm">
    @csrf

    <div class="form-group mb-3">
        <label for="name">Tên thương hiệu <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control" value="{{ old('name') }}">
        @error('name') <small class="text-danger">{{ $message }}</small> @enderror
    </div>

    <div class="form-group mb-3">
        <label for="slug">Slug</label>
        <input type="text" name="slug" class="form-control" value="{{ old('slug') }}">
        @error('slug') <small class="text-danger">{{ $message }}</small> @enderror
    </div>

    <div class="form-group mb-3">
        <label for="logo">Logo</label>
        <input type="file" name="logo" class="form-control">
        @error('logo') <small class="text-danger">{{ $message }}</small> @enderror
    </div>

    <div class="form-check mb-4">
        <input type="checkbox" name="is_active" class="form-check-input" id="is_active" {{ old('is_active', true) ? 'checked' : '' }}>
        <label class="form-check-label" for="is_active">Hiển thị</label>
    </div>

    <div class="mt-3">
        <button type="submit" class="btn btn-success">Lưu</button>
        <a href="{{ route('admin.brands.index') }}" class="btn btn-warning">Quay lại</a>
    </div>
</form>
@endsection
