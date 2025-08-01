@extends('admin.layouts.app')

@section('content')
<h2>Danh sách bài viết</h2>
<a href="{{ route('admin.blogs.create') }}" class="btn btn-success mb-2">Thêm bài viết</a>
<table class="table">
    <thead>
        <tr>
            <th>Tiêu đề</th>
            <th>Ảnh</th>
            <th>Thao tác</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($blogs as $blog)
        <tr>
            <td>{{ $blog->title }}</td>
            <td>
                @if($blog->image)
                    <img src="{{ asset('storage/' . $blog->image) }}" width="100">
                @endif
            </td>
            <td>
                <a href="{{ route('admin.blogs.edit', $blog->id) }}" class="btn btn-sm btn-warning">Sửa</a>
                <form action="{{ route('admin.blogs.destroy', $blog->id) }}" method="POST" style="display:inline-block">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger" onclick="return confirm('Xóa bài viết này?')">Xóa</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
{{ $blogs->links() }}
@endsection
