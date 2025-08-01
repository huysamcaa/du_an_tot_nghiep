@extends('admin.layouts.app')

@section('content')
<h2>{{ isset($blog) ? 'Sửa' : 'Thêm' }} bài viết</h2>
<form action="{{ isset($blog) ? route('admin.blogs.update', $blog->id) : route('admin.blogs.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @if(isset($blog)) @method('PUT') @endif

    <div class="mb-3">
        <label>Tiêu đề</label>
        <input type="text" name="title" value="{{ old('title', $blog->title ?? '') }}" class="form-control">
    </div>

    <div class="mb-3">
        <label>Nội dung</label>
        <textarea name="content" id="ckeditor" rows="10" class="form-control">{{ old('content', $blog->content ?? '') }}</textarea>
    </div>

    <div class="mb-3">
        <label>Ảnh</label>
        <input type="file" name="image" class="form-control">
        @if(isset($blog) && $blog->image)
            <img src="{{ asset('storage/' . $blog->image) }}" width="120" class="mt-2">
        @endif
    </div>

    <button class="btn btn-primary">{{ isset($blog) ? 'Cập nhật' : 'Tạo mới' }}</button>
</form>
@endsection

@section('scripts')
<script src="https://cdn.ckeditor.com/4.21.0/standard/ckeditor.js"></script>
<script>
    CKEDITOR.replace('ckeditor');
</script>
@endsection
