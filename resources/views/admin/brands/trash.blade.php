@extends('admin.layouts.app')

@section('content')
<div class="content">
  <div class="animated fadeIn">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <div>
        <h4 class="mb-0">Thương Hiệu Đã Xóa</h4>
        <small class="text-muted">Danh sách thương hiệu đã xóa</small>
      </div>
      <a href="{{ route('admin.brands.index') }}" class="btn btn-outline-secondary">
        <i class="fa fa-arrow-left"></i> Quay lại danh sách
      </a>
    </div>

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
    @if (session('warning'))
      <div class="alert alert-warning alert-dismissible fade show mt-3" role="alert">
        {{ session('warning') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    @endif

    {{-- FORM KHÔI PHỤC HÀNG LOẠT --}}
    <form id="bulk-restore-form" method="POST" action="{{ route('admin.brands.bulkRestore') }}">
      @csrf

      <div class="card">
        <div class="card-body">
          {{-- Thanh công cụ --}}
          <div class="d-flex justify-content-between align-items-center mb-3" style="gap:12px; flex-wrap:wrap;">
            <button type="submit" class="btn btn-outline-success btn-sm" id="btn-bulk-restore">
              <i class="fa fa-undo"></i> Khôi phục mục đã chọn
            </button>

            {{-- (tuỳ chọn) ô tìm kiếm/ lọc nếu cần, bỏ qua nếu bạn không dùng --}}
          </div>

          <table class="table table-striped table-bordered text-center align-middle mb-0">
            <thead>
              <tr>
                <th style="width:48px;"><input type="checkbox" id="select-all"></th>
                <th>#</th>
                <th>Tên thương hiệu</th>
                <th>Slug</th>
                <th>Số sản phẩm</th>
                <th>Ngày xóa</th>
                <th>Hành động</th>
              </tr>
            </thead>
            <tbody>
              @forelse($brands as $brand)
                <tr>
                  <td>
                    <input type="checkbox" class="row-check" name="ids[]" value="{{ $brand->id }}">
                  </td>
                  <td>{{ $loop->iteration + ($brands->currentPage() - 1) * $brands->perPage() }}</td>
                  <td class="text-start">{{ $brand->name }}</td>
                  <td class="text-muted">{{ $brand->slug }}</td>
                  <td>{{ $brand->products_count }}</td>
                  <td>{{ $brand->deleted_at ? $brand->deleted_at->format('d/m/Y H:i') : '--' }}</td>
                  <td>
                    <form action="{{ route('admin.brands.restore', $brand->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Khôi phục thương hiệu này?')">
                      @csrf
                      <button type="submit" class="btn btn-sm btn-outline-success" title="Khôi phục">
                        <i class="fa fa-undo"></i>
                      </button>
                    </form>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="7" class="text-center text-muted">Không có thương hiệu nào đã bị xóa.</td>
                </tr>
              @endforelse
            </tbody>
          </table>

          {{-- Footer phân trang --}}
          <div class="d-flex justify-content-between align-items-center mt-3">
            <small class="text-muted">
              Hiển thị từ {{ $brands->firstItem() ?? 0 }} đến {{ $brands->lastItem() ?? 0 }} / {{ $brands->total() }} mục
            </small>
            <nav aria-label="Pagination">
              <div class="pagination pagination-sm mb-0">
                {!! $brands->appends(request()->query())->onEachSide(1)->links('pagination::bootstrap-4') !!}
              </div>
            </nav>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection

@section('scripts')
<script>
  // Chọn tất cả
  document.getElementById('select-all')?.addEventListener('change', function(){
    const checked = this.checked;
    document.querySelectorAll('.row-check').forEach(el => { el.checked = checked; });
  });
</script>
@endsection
