@extends('admin.layouts.app')

@section('title', 'Danh mục đã xóa')

@section('content')
<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4>Danh mục đã xóa</h4>
            <h6>Quản lý các danh mục đã bị xóa</h6>
        </div>
        <div class="page-btn">
            <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
                <i class="fa fa-arrow-left me-2"></i> Quay lại
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <form method="GET" class="d-flex flex-wrap align-items-center mb-3" style="gap: 10px;">
                <div class="flex-grow-1">
                    <input type="text" name="keyword" class="form-control" placeholder="Tìm kiếm tên danh mục..." value="{{ request('keyword') }}">
                </div>
                <div>
                    <select name="per_page" class="form-control" onchange="this.form.submit()">
                        <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                        <option value="20" {{ request('per_page') == 20 ? 'selected' : '' }}>20</option>
                        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                    </select>
                </div>
                <div class="d-flex" style="gap: 6px;">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> Tìm kiếm
                    </button>
                    <a href="{{ route('admin.categories.trashed') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-sync-alt"></i> Xóa lọc
                    </a>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th width="50">STT</th>
                            <th>Icon</th>
                            <th>Tên danh mục</th>
                            <th>Danh mục cha</th>
                            <th>Ngày xóa</th>
                            <th class="text-center">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $index => $category)
                        <tr>
                            <td class="align-middle">{{ $index + $categories->firstItem() }}</td>
                            <td class="align-middle">
                                @if($category->icon)
                                    <img src="{{ asset('storage/' . $category->icon) }}" alt="Icon" width="40" height="40" class="rounded">
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td class="align-middle">{{ $category->name }}</td>
                            <td class="align-middle">{{ optional($category->parent)->name ?? '—' }}</td>
                            <td class="align-middle">{{ $category->deleted_at->format('d/m/Y H:i') }}</td>
                            <td class="text-center align-middle">
                                <div class="d-flex justify-content-center" style="gap: 10px;">
                                    {{-- Nút khôi phục --}}
                                    <form action="{{ route('admin.categories.restore', $category->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn khôi phục danh mục này không?');">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success" title="Khôi phục">
                                            <i class="fas fa-trash-restore"></i>
                                        </button>
                                    </form>

                                    {{-- Nút xóa vĩnh viễn --}}
                                    <form action="{{ route('admin.categories.forceDelete', $category->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa VĨNH VIỄN danh mục này không? Thao tác này không thể hoàn tác.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Xóa vĩnh viễn">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">Không có danh mục nào đã bị xóa.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($categories->hasPages())
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="text-muted">
                        Hiển thị <b>{{ $categories->firstItem() }}</b> đến <b>{{ $categories->lastItem() }}</b> trong tổng số <b>{{ $categories->total() }}</b> bản ghi
                    </div>
                    <div>
                        {{ $categories->appends(request()->query())->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
