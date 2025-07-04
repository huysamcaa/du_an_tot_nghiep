@extends('admin.layouts.app')

@section('content')
<h1 class="mb-4">Danh Sách Mã Giảm Giá</h1>

<a href="{{ route('admin.coupon.create') }}" class="btn btn-primary mb-4">Thêm Mới</a>

<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>STT</th>
            <th>Mã Giảm Giá</th>
            <th>Tiêu Đề</th>
            <th>Giảm Giá</th>
            <th>Trạng Thái</th>
            <th>Ngày Tạo</th>
            <th>Ngày Sửa</th>
            <th>Hành Động</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($coupons as $index => $coupon)
            <tr>
                <td>{{ $loop->iteration + ($coupons->currentPage() - 1) * $coupons->perPage() }}</td>
                <td>{{ $coupon->code }}</td>
                <td>{{ $coupon->title }}</td>
                <td>{{ $coupon->discount_value }} {{ $coupon->discount_type == 'percent' ? '%' : 'VND' }}</td>
                <td>
                    <span class="badge badge-{{ $coupon->is_active ? 'success' : 'secondary' }}">
                        {{ $coupon->is_active ? 'Kích Hoạt' : 'Tắt' }}
                    </span>
                </td>
                <td>{{ $coupon->created_at->format('d/m/Y H:i') }}</td>
                <td>
                    @if ($coupon->updated_at != $coupon->created_at)
                        {{ $coupon->updated_at->format('d/m/Y H:i') }}
                    @else
                        <span class="text-muted">--</span>
                    @endif
                </td>
               <td class="d-flex" style="gap: 5px;">
    <a href="{{ route('admin.coupon.edit', $coupon->id) }}" class="btn btn-sm btn-warning" title="Sửa">
        <i class="fa fa-pencil"></i>
    </a>
    <form action="{{ route('admin.coupon.destroy', $coupon->id) }}" method="POST" style="display:inline;">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-sm btn-danger" title="Xoá" onclick="return confirm('Bạn có chắc muốn xóa không?')">
            <i class="fa fa-trash"></i>
        </button>
    </form>
</td>

            </tr>
        @endforeach
    </tbody>
</table>

<!-- Phân trang -->
<div class="d-flex justify-content-center">
    {{ $coupons->links() }}
</div>
@endsection
