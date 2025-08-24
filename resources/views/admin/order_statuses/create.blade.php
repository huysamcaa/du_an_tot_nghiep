@extends('admin.layouts.app')

@section('title', 'Thêm mới trạng thái đơn hàng')

@section('content')
    {{-- Thông báo session --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="content">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h4 class="mb-0">Thêm Trạng Thái Đơn Hàng</h4>
                <small class="text-muted">Tạo mới trạng thái đơn hàng</small>
            </div>
        </div>

        <form action="{{ route('admin.order_statuses.store') }}" method="POST" class="card p-4 shadow-sm">
            @csrf

            <div class="form-group mb-3">
                <label for="name">Tên trạng thái <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                @error('name')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="mt-3">
                <button type="submit" class="btn btn-success">Lưu</button>
                <a href="{{ route('admin.order_statuses.index') }}" class="btn btn-warning">Quay lại</a>
            </div>
        </form>
    </div>
@endsection
