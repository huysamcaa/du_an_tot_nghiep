@extends('admin.layouts.app')

@section('content')
{{-- Breadcrumbs --}}
<div class="breadcrumbs">
  <div class="breadcrumbs-inner">
    <div class="row m-0">
      <div class="col-sm-4">
        <div class="page-header float-left">
          <div class="page-title">
            <h1>Sửa thương hiệu</h1>
          </div>
        </div>
      </div>
      <div class="col-sm-8">
        <div class="page-header float-right">
          <div class="page-title">
            <ol class="breadcrumb text-right">
              <li><a href="{{ route('admin.dashboard') }}">Trang chủ</a></li>
              <li><a href="{{ route('admin.brands.index') }}">Thương hiệu</a></li>
              <li class="active">Sửa</li>
            </ol>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="content">
  <div class="animated fadeIn">
    {{-- Form cập nhật thương hiệu --}}
    <div class="card mb-4 shadow-sm">
      <div class="card-header bg-primary text-white">
        <h5 class="mb-0">Cập nhật thương hiệu</h5>
      </div>
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

        <form action="{{ route('admin.brands.update', $brand) }}" method="POST" enctype="multipart/form-data">
          @csrf
          @method('PUT')

          <div class="row mb-3">
            <div class="col-md-6">
              <label for="name" class="font-weight-bold">Tên thương hiệu <span class="text-danger">*</span></label>
              <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $brand->name) }}">
              @error('name') <small class="text-danger">{{ $message }}</small> @enderror
            </div>
            <div class="col-md-6">
              <label for="slug" class="font-weight-bold">Slug</label>
              <input type="text" name="slug" id="slug" class="form-control" value="{{ old('slug', $brand->slug) }}">
              @error('slug') <small class="text-danger">{{ $message }}</small> @enderror
            </div>
          </div>

          <div class="row mb-3">
            <div class="col-md-6">
              <label for="logo" class="font-weight-bold">Logo</label>
              <input type="file" name="logo" class="form-control-file">
              @error('logo') <small class="text-danger d-block">{{ $message }}</small> @enderror

              @if($brand->logo)
                <div class="mt-2">
                  <img src="{{ asset('storage/' . $brand->logo) }}" width="100" alt="Logo thương hiệu" class="img-thumbnail">
                </div>
              @endif
            </div>
            <div class="col-md-6">
              <label for="is_active" class="font-weight-bold">Trạng thái <span class="text-danger">*</span></label>
              <select name="is_active" id="is_active" class="form-control">
                <option value="1" {{ old('is_active', $brand->is_active) ? 'selected' : '' }}>Hiển thị</option>
                <option value="0" {{ old('is_active', $brand->is_active) ? '' : 'selected' }}>Ẩn</option>
              </select>
            </div>
          </div>

          <div class="text-end">
            <button type="submit" class="btn btn-primary me-2">
              <i class="fa fa-save"></i> Cập nhật
            </button>
            <a href="{{ route('admin.brands.index') }}" class="btn btn-secondary">
              <i class="fa fa-arrow-left"></i> Quay lại
            </a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
