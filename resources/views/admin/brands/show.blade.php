@extends('admin.layouts.app')

@section('content')
<div class="breadcrumbs">
    <div class="breadcrumbs-inner">
        <div class="row m-0">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1>Chi tiết thương hiệu</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
                            <li><a href="{{ route('admin.dashboard') }}">Trang chủ</a></li>
                            <li><a href="{{ route('admin.brands.index') }}">Thương hiệu</a></li>
                            <li class="active">{{ $brand->name }}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="animated fadeIn">
        {{-- Thông tin thương hiệu --}}
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Thông tin cơ bản</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-4"><strong>Tên thương hiệu:</strong> {{ $brand->name }}</div>
                    <div class="col-md-4"><strong>Slug:</strong> {{ $brand->slug }}</div>
                    <div class="col-md-4">
                        <strong>Trạng thái:</strong>
                        <span class="badge badge-{{ $brand->is_active ? 'success' : 'secondary' }}">
                            {{ $brand->is_active ? 'Hiển thị' : 'Ẩn' }}
                        </span>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4"><strong>Tổng sản phẩm:</strong> {{ $brand->products_count ?? $brand->products()->count() }}</div>
                    <div class="col-md-4"><strong>Ngày tạo:</strong> {{ $brand->created_at->format('d/m/Y H:i') }}</div>
                    <div class="col-md-4">
                        <strong>Ngày cập nhật:</strong>
                        @if ($brand->updated_at != $brand->created_at)
                            {{ $brand->updated_at->format('d/m/Y H:i') }}
                        @else
                            <span class="text-muted">--</span>
                        @endif
                    </div>
                </div>
                <div class="row mb-0">
                    <div class="col-md-4"><strong>Logo:</strong></div>
                    <div class="col-md-8">
                        @if($brand->logo)
                            <img src="{{ asset('storage/' . $brand->logo) }}" class="img-thumbnail" width="120" alt="Logo thương hiệu">
                        @else
                            <span class="text-muted">Không có logo</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Nút hành động --}}
        <div class="mt-4 text-end">
            <a href="{{ route('admin.brands.edit', $brand->id) }}" class="btn btn-warning me-2">
                <i class="fa fa-edit me-1"></i> Sửa
            </a>
            <a href="{{ route('admin.brands.index') }}" class="btn btn-outline-secondary">
                <i class="fa fa-arrow-left me-1"></i> Quay lại
            </a>
        </div>
    </div>
</div>
@endsection
