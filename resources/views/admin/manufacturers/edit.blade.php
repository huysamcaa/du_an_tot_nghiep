@extends('admin.layouts.app')

@section('content')
<div class="animated fadeIn">
   <h2>Chỉnh sửa nhà sản xuất</h2>
   @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
  @if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $e)
                <li>{{ $e }}</li>
            @endforeach
        </ul>
    </div>
@endif

   <form action="{{route('admin.manufacturers.update',$manufacturer)}}" method="post" enctype="multipart/form-data">
   @csrf
   @method('put')
      <div class="row">
        <div class="col-12 mb-3">
            <label for="" class="form-label">Tên nhà sản xuất</label>
            <input type="name" name="name" class="form-control" value="{{old('name',$manufacturer->name)}}" required>
        </div>
        <div class="col-12 mb-3">
            <label for="" class="form-label">Slug</label>
            <input type="text" name="slug" class="form-control" value="{{old('slug',$manufacturer->slug)}}" required>
        </div><br>
        <div class="col-12 mb-3">
            <label for="" class="form-label">Logo hiện tại </label>
            @if ($manufacturer->logo_path)
            <img src="{{asset('storage/'.$manufacturer->logo_path)}}" alt="" width="150px" class="img-thumbnail ">
            @else
            <p class="text-muted">Chưa có logo</p>
            @endif
            <br>
            <label for="" class="form-label">Chọn logo mới</label>
            <input type="file" name="logo" accept="image/*" class="form-control"><br>
            <small>Bỏ trống nếu muốn giữ logo cũ</small>
        </div>
        <div class="col-12 mb-3">
            <label for="" class="form-label">Website</label>
            <input type="url" name="website" class="form-control" value="{{old('website',$manufacturer->website)}}" required>
        </div>
        <div class="col-12 mb-3">
            <label for="" class="form-label">Mô tả</label>
            <textarea name="description" id="" rows="3" class="form-control">{{old('description',$manufacturer->description)}}</textarea>
        </div>
        <div class="form-check col-12 mb-3">
            <input type="checkbox" class="form-check-input" name="is_active" value="1" {{old('is_active',true)?'checked':''}}>
            <label for="" class="form-check-label">Kích hoạt</label>
        </div>
        <div class="col-12 mb-3">
            <button class="btn btn-primary">Cập nhật</button>
        </div>
      </div>
</form>
@endsection
