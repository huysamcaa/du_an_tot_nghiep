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

    <div class="card">
        <div class="card-body">
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

            <div class="mb-4">
                <div class="row g-3 align-items-center">
                    <div class="col-md-6">
                        <form method="GET" class="row gx-2 gy-3 align-items-center">
                            <div class="col-auto">
                                <select name="per_page" class="form-select" onchange="this.form.submit()">
                                    <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                                    <option value="20" {{ request('per_page') == 20 ? 'selected' : '' }}>20</option>
                                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                                </select>
                            </div>
                            <div class="col-auto flex-grow-1">
                                <div class="input-group">
                                    <input type="text" name="keyword" class="form-control" placeholder="Tìm kiếm..." value="{{ request('keyword') }}">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-search"></i>
                                    </button>
                                    <a href="{{ route('admin.categories.trashed') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-sync-alt"></i>
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th width="50">STT</th>
                            <th>Tên danh mục</th>
                            <th>Danh mục cha</th>
                            <th>Ngày xóa</th>
                            <th width="150" class="text-end">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $index => $category)
                        <tr>
                            <td>{{ $index + $categories->firstItem() }}</td>
                            <td>{{ $category->name }}</td>
                            <td>{{ $category->parent ? $category->parent->name : '---' }}</td>
                            <td>{{ $category->deleted_at->format('d/m/Y H:i') }}</td>
                            <td class="text-end">
                                <div class="d-flex justify-content-end gap-2">
                                    <form action="{{ route('admin.categories.restore', $category->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success" title="Khôi phục">
                                            <i class="fas fa-trash-restore"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.categories.forceDelete', $category->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Xóa vĩnh viễn" onclick="return confirm('Bạn có chắc chắn muốn xóa VĨNH VIỄN danh mục này?')">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-4">Không có danh mục nào đã xóa</td>
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
                <div class="pagination-wrap">
                    {{ $categories->appends(request()->query())->links() }}
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .pagination {
        margin-bottom: 0;
    }
    .page-item.active .page-link {
        background-color: #7367f0;
        border-color: #7367f0;
    }
    .page-link {
        color: #7367f0;
    }
    .table th {
        white-space: nowrap;
    }
    .form-select, .form-control {
        height: calc(1.5em + 1rem + 2px);
    }
</style>
@endpush