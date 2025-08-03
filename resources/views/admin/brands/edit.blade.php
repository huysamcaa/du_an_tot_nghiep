@extends('admin.layouts.app')

@section('content')
<h1 class="mb-4">Cập Nhật Thương Hiệu</h1>
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

<form action="{{ route('admin.brands.update', $brand) }}" method="POST" enctype="multipart/form-data" class="card p-4 shadow-sm">
    @csrf
    @method('PUT')

    <div class="form-group mb-3">
        <label for="name">Tên thương hiệu <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control" value="{{ old('name', $brand->name) }}" >
        @error('name') <small class="text-danger">{{ $message }}</small> @enderror
    </div>

    <div class="form-group mb-3">
        <label for="slug">Slug</label>
        <input type="text" name="slug" class="form-control" value="{{ old('slug', $brand->slug) }}">
        @error('slug') <small class="text-danger">{{ $message }}</small> @enderror
    </div>

    <div class="form-group mb-3">
        <label for="logo">Logo</label>
        <input type="file" name="logo" class="form-control-file">
        @error('logo') <small class="text-danger">{{ $message }}</small> @enderror

        @if($brand->logo)
            <div class="mt-2">
                <img src="{{ asset('storage/' . $brand->logo) }}" width="100" alt="Logo thương hiệu" class="img-thumbnail">
            </div>
        @endif
    </div>

    <div class="form-check mb-4">
        <input type="checkbox" name="is_active" class="form-check-input" id="is_active" {{ old('is_active', $brand->is_active) ? 'checked' : '' }}>
        <label class="form-check-label" for="is_active">Hiển thị</label>
    </div>

   <div class="mt-3">
    <button type="submit" class="btn btn-success w-auto d-inline-block me-2"> Cập Nhật</button>
    <a href="{{ route('admin.brands.index') }}" class="btn btn-warning w-auto d-inline-block"> Quay lại</a>
</div>

</form>
@endsection
