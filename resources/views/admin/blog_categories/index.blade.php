@extends('admin.layouts.app')

@section('content')
<div class="content">
  <div class="card">
    <div class="card-header">
      <h5>Danh mục bài viết</h5>
      <a href="{{ route('admin.blog_categories.create') }}" class="btn btn-primary btn-sm float-right">+ Thêm mới</a>
    </div>
    <div class="card-body">
      @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
      @endif
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>ID</th>
            <th>Tên</th>
            <th>Slug</th>
            <th>Trạng thái</th>
            <th width="150">Hành động</th>
          </tr>
        </thead>
        <tbody>
          @foreach($categories as $cat)
          <tr>
            <td>{{ $cat->id }}</td>
            <td>{{ $cat->name }}</td>
            <td>{{ $cat->slug }}</td>
            <td>{{ $cat->is_active ? 'Hiển thị' : 'Ẩn' }}</td>
            <td>
                  <div class="d-flex align-items-center justify-content-center" style="gap: 10px;">
              <a href="{{ route('admin.blog_categories.edit', $cat) }}" class=""><img src="{{ asset('assets/admin/img/icons/edit.svg') }}" alt="Sửa"></a>
              <form action="{{ route('admin.blog_categories.destroy', $cat) }}" method="POST" style="display:inline-block">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-link p-0" onclick="return confirm('Bạn chắc chắn muốn xóa?')"> <img src="{{ asset('assets/admin/img/icons/delete.svg') }}" alt="Xóa"></button>
              </form>
                  </div>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
      {{ $categories->links() }}
    </div>
  </div>
</div>
@endsection
