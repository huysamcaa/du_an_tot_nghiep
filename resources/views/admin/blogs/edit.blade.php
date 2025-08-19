@extends('admin.layouts.app')

@section('content')
<!-- Breadcrumbs -->
<div class="breadcrumbs">
  <div class="breadcrumbs-inner">
    <div class="row m-0">
      <div class="col-sm-4">
        <div class="page-header float-left">
          <div class="page-title">
            <h1>{{ isset($blog) ? 'Sửa bài viết' : 'Thêm bài viết' }}</h1>
          </div>
        </div>
      </div>
      <div class="col-sm-8">
        <div class="page-header float-right">
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Content -->
<div class="content">
  <div class="animated fadeIn">
    <div class="card mb-4 shadow-sm">
      <div class="card-header bg-primary text-white">
        <h5 class="mb-0">{{ isset($blog) ? 'Cập nhật bài viết' : 'Thêm bài viết mới' }}</h5>
      </div>
      <div class="card-body">

        @if($errors->any())
          <div class="alert alert-danger">
            <ul class="mb-0">
              @foreach($errors->all() as $e)
                <li>{{ $e }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <form action="{{ isset($blog) ? route('admin.blogs.update', $blog->id) : route('admin.blogs.store') }}" method="POST" enctype="multipart/form-data">
          @csrf
          @if(isset($blog)) @method('PUT') @endif

          <div class="row mb-3">
            <div class="col-md-12">
              <label for="title" class="font-weight-bold">Tiêu đề <span class="text-danger">*</span></label>
              <input type="text" name="title" id="title" value="{{ old('title', $blog->title ?? '') }}" class="form-control" required>
            </div>
          </div>

          <div class="row mb-3">
            <div class="col-md-12">
              <label for="content" class="font-weight-bold">Nội dung <span class="text-danger">*</span></label>
              <textarea name="content" id="ckeditor" rows="10" class="form-control" required>{{ old('content', $blog->content ?? '') }}</textarea>
            </div>
          </div>

          <div class="row mb-4">
            <div class="col-md-6">
              <label for="image" class="font-weight-bold">Ảnh đại diện</label>
              <input type="file" name="image" id="image" class="form-control">
              @if(isset($blog) && $blog->image)
                <div class="mt-2">
                  <img src="{{ asset('storage/' . $blog->image) }}" width="120" class="rounded border mb-2">
                  <div class="form-check">
                    <input type="checkbox" name="remove_image" id="remove_image" class="form-check-input">
                    <label for="remove_image" class="form-check-label">Xóa ảnh hiện tại</label>
                  </div>
                </div>
              @endif
            </div>

            <div class="col-md-6 text-right align-self-end">
              <button type="submit" class="btn btn-primary">
                <i class="fa fa-save"></i> {{ isset($blog) ? 'Cập nhật' : 'Tạo mới' }}
              </button>
              <a href="{{ route('admin.blogs.index') }}" class="btn btn-secondary">
                <i class="fa fa-arrow-left"></i> Quay lại
              </a>
            </div>
          </div>

        </form>

      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.ckeditor.com/4.21.0/standard/ckeditor.js"></script>
<script>
    CKEDITOR.replace('ckeditor');
</script>
@endsection
