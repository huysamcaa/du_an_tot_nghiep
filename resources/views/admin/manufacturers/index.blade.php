@extends('admin.layouts.app')

@section('content')
    <h1>Danh sách nhà sản xuất</h1>
    @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif
    <a href="{{ route('admin.manufacturers.create') }}" class="btn btn-success mb-3">Thêm mới</a>

    <table class="table table-striped table-bordered">
        <thead class="table-light">
            <tr>
                <th>Stt</th>
                <th>Tên</th>
                <th>Địa chỉ</th>
                <th>SĐT</th>
                <th>Trạng thái</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach($manufacturers as $item)
                <tr>
                    <td>{{ $item->id }}</td>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->address }}</td>
                    <td>{{ $item->phone }}</td>
                    <td>{{ $item->is_active ? 'Hoạt động' : 'Không' }}</td>
                    <td>
                        <a href="{{ route('admin.manufacturers.edit', $item) }}" class="btn btn-warning">Sửa</a>
                        <form action="{{ route('admin.manufacturers.destroy', $item) }}" method="POST" style="display:inline;">
                            @csrf @method('DELETE')
                            <button type="submit" onclick="return confirm('Bạn chắc chắn muốn xóa?')" class="btn btn-danger">Xóa</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="d-flex justify-content-end">
        {{ $manufacturers->links() }}
    </div>
@endsection
