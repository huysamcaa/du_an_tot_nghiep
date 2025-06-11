@extends('admin.layouts.app')

@section('content')
<div class="animated fadeIn">
   <h2>Thêm mới nhà sản xuất</h2><br>
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
   <form action="{{route('admin.manufacturers.store')}}" method="post" enctype="multipart/form-data">
   @csrf


      <div class="row">
        <div class="col-12 mb-3">
            <label for="" class="form-label">Tên nhà sản xuất</label>
            <input type="text" name="name" class="form-control" value="{{old('name')}}" required>
        </div>
        <div class="col-12 mb-3">
            <label for="" class="form-label">Slug</label>
            <input type="text" name="slug" class="form-control" value="{{old('slug')}}" required>
        </div>
        <div class="col-12 mb-3">
            <label for="" class="form-label">Logo</label>
            <input type="file" name="logo" class="form-control" value="{{old('logo')}}" accept="image/*">
        </div>
        <div class="col-12 mb-3">
            <label for="" class="form-label">Website</label>
            <input type="url" name="website" class="form-control" value="{{old('website')}}" required>
        </div>
        <div class="col-12 mb-3">
            <label for="" class="form-label">Mô tả</label>
            <textarea name="description" id="" rows="3" class="form-control">{{old('description')}}</textarea>
        </div>
        <div class="form-check col-12 mb-3">
            <input type="checkbox" class="form-check-input" name="is_active" value="1" {{old('is_active',true)?'checked':''}}>
            <label for="" class="form-check-label">Kích hoạt</label>
        </div>
        <br>
        <div class="col-12 mb-3">
            <button class="btn btn-success">Thêm mới</button>
        </div>
      </div>
</form>
@endsection
