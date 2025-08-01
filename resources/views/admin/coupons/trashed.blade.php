@extends('admin.layouts.app')

@section('content')

<h1 class="mb-4">Danh Sách Mã Giảm Giá Đã Xóa</h1>

<a href="{{ route('admin.coupon.index') }}" class="btn btn-secondary mb-4">Quay lại danh sách</a>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Đóng">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

<table class="table table-bordered table-hover">
    <thead>
        <tr>
            <th>STT</th>
            <th>Mã</th>
            <th>Tiêu đề</th>
            <th>Ngày xóa</th>
            <th>Hành động</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($coupons as $coupon)
        <tr>
            <td>{{ $loop->iteration + ($coupons->currentPage() - 1) * $coupons->perPage() }}</td>
            <td>{{ $coupon->code }}</td>
            <td>{{ $coupon->title }}</td>
            <td>{{ $coupon->deleted_at->format('d/m/Y H:i') }}</td>
            <td>
                <form action="{{ route('admin.coupon.restore', $coupon->id) }}" method="POST" style="display:inline;">
                    @csrf
                    <button class="btn btn-sm btn-success" onclick="return confirm('Khôi phục mã này?')">Khôi phục</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="d-flex justify-content-between align-items-center mt-4">
    <div class="text-muted">
        Hiển thị từ {{ $coupons->firstItem() ?? 0 }} đến {{ $coupons->lastItem() ?? 0 }} trên tổng số {{ $coupons->total() }} mã
    </div>

    <div>
        @if ($coupons->hasPages())
            {!! $coupons->appends(request()->query())->onEachSide(1)->links('pagination::bootstrap-4') !!}
        @else
            {{-- Giữ bố cục nhất quán dù không có nhiều trang --}}
            <nav>
                <ul class="pagination mb-0">
                    <li class="page-item active"><span class="page-link">1</span></li>
                </ul>
            </nav>
        @endif
    </div>
</div>

@endsection
