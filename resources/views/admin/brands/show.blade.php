@extends('admin.layouts.app')

@section('content')

<div class="content">
    <div class="animated fadeIn">
        {{-- Thông tin thương hiệu --}}
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h4 class="mb-0">Chi tiết Thương Hiệu</h4>
                <small class="text-muted">Xem chi tiết thương hiệu</small>
            </div>

        </div>
        <div class="card mb-4 shadow-sm">
  <div class="card-header" style="background:#ffa200; color:#fff;">
    <h5 class="mb-0">Thông tin cơ bản</h5>
  </div>

  <div class="card-body">
    <div class="row gy-3">
      <div class="col-md-4">
        <div class="text-muted small">Tên thương hiệu</div>
        <div class="fw-semibold">{{ $brand->name }}</div>
      </div>

      <div class="col-md-4">
        <div class="text-muted small">Slug</div>
        <div class="fw-semibold">{{ $brand->slug }}</div>
      </div>

      <div class="col-md-4">
        <div class="text-muted small">Trạng thái</div>
        @if ($brand->is_active)
          <span class="badge bg-info text-dark">Hiển Thị</span>
        @else
          <span class="badge bg-warning text-dark">Ẩn</span>
        @endif
      </div>

      <div class="col-md-4">
        <div class="text-muted small">Tổng sản phẩm</div>
        <div class="fw-semibold">{{ $brand->products_count ?? $brand->products()->count() }}</div>
      </div>

      <div class="col-md-4">
        <div class="text-muted small">Ngày tạo</div>
        <div class="fw-semibold">{{ $brand->created_at->format('d/m/Y H:i') }}</div>
      </div>

      <div class="col-md-4">
        <div class="text-muted small">Ngày cập nhật</div>
        <div class="fw-semibold">
          @if ($brand->updated_at != $brand->created_at)
            {{ $brand->updated_at->format('d/m/Y H:i') }}
          @else
            <span class="text-muted">--</span>
          @endif
        </div>
      </div>

      <div class="col-12">
        <div class="text-muted small mb-2">Logo thương hiệu</div>
@if ($brand->logo)
  <div class="p-3" style="border:1.5px dashed #ffb200; border-radius:10px; background:#fffdf7; display:inline-block;">
    <img
      src="{{ asset('storage/'.$brand->logo) }}"
      alt="Logo thương hiệu"
      loading="lazy"
      style="height:120px; max-width:100%; object-fit:contain; border-radius:8px; box-shadow:0 1px 6px rgba(0,0,0,.08); background:#fff;"
      onerror="this.closest('div').insertAdjacentHTML('afterend', '<div class=&quot;p-4 text-center text-muted&quot; style=&quot;border:1.5px dashed #e2e8f0; border-radius:10px; background:#f8fafc;&quot;>Không thể tải logo</div>'); this.remove();"
    >
  </div>
  <div class="mt-2">
    <small class="text-muted">{{ basename($brand->logo) }}</small>
  </div>
@else
  <div class="p-4 text-center text-muted"
       style="border:1.5px dashed #e2e8f0; border-radius:10px; background:#f8fafc;">
    Không có logo
  </div>
@endif


      </div>
    </div>
  </div>
</div>

{{-- Nút hành động --}}
<div class="mt-4 text-end">
  <a href="{{ route('admin.brands.edit', $brand->id) }}"
     class="btn btn-warning me-2">
    <i class="fa fa-edit me-1"></i> Sửa
  </a>
  <a href="{{ route('admin.brands.index') }}"
     class="btn btn-outline-secondary">
    <i class="fa fa-arrow-left me-1"></i> Quay lại
  </a>
</div>
    </div>
</div>
@endsection
