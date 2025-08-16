@extends('admin.layouts.app')

@section('title', 'Quản lý thuộc tính')

@section('content')
<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4>Quản lý thuộc tính</h4>
            <h6>Danh sách các thuộc tính sản phẩm</h6>
        </div>
        <div class="page-btn">
            <a href="{{ route('admin.attributes.create') }}" class="btn btn-success">
                <i class="fas fa-plus me-2"></i> Thêm thuộc tính
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

            <div class="mb-4">
                <div class="row g-3 align-items-center">
                    <div class="col-md-6">
                        <form method="GET" class="row gx-2 gy-3 align-items-center">
                            <div class="col-auto">
                                <select name="perPage" class="form-select" onchange="this.form.submit()">
                                    <option value="10" {{ request('perPage') == 10 ? 'selected' : '' }}>10 bản ghi</option>
                                    <option value="25" {{ request('perPage') == 25 ? 'selected' : '' }}>25 bản ghi</option>
                                    <option value="50" {{ request('perPage') == 50 ? 'selected' : '' }}>50 bản ghi</option>
                                    <option value="100" {{ request('perPage') == 100 ? 'selected' : '' }}>100 bản ghi</option>
                                </select>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-6">
                        <form method="GET" class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Tìm tên, slug..." value="{{ request('search') }}">
                            <button class="btn btn-primary" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                            @if (request('search'))
                                <a href="{{ route('admin.attributes.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-sync-alt"></i>
                                </a>
                            @endif
                        </form>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th width="5%">ID</th>
                            <th>Tên</th>
                            <th>Slug</th>
                            <th>Biến thể</th>
                            <th>Hiển thị</th>
                            <th width="150" class="text-end">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($attributes as $attr)
                        <tr>
                            <td>{{ $attr->id }}</td>
                            <td>{{ $attr->name }}</td>
                            <td>{{ $attr->slug }}</td>
                            <td>
                                <span class="badge bg-{{ $attr->is_variant ? 'success' : 'danger' }}">
                                    {{ $attr->is_variant ? 'Có' : 'Không' }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-{{ $attr->is_active ? 'success' : 'danger' }}">
                                    {{ $attr->is_active ? 'Hiển thị' : 'Ẩn' }}
                                </span>
                            </td>
                            <td class="text-end">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('admin.attributes.edit', $attr) }}" 
                                       class="btn btn-sm btn-warning" title="Sửa">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.attributes.destroy', $attr) }}" 
                                          method="POST" 
                                          onsubmit="return confirm('Bạn có chắc muốn xóa thuộc tính này?')">
                                        @csrf 
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Xóa">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">Không có thuộc tính nào</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($attributes->hasPages())
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="text-muted">
                    Hiển thị <b>{{ $attributes->firstItem() }}</b> đến <b>{{ $attributes->lastItem() }}</b> trong tổng số <b>{{ $attributes->total() }}</b> bản ghi
                </div>
                <div class="pagination-wrap">
                    {{ $attributes->appends(request()->query())->links() }}
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
    .badge {
        font-size: 0.85em;
        font-weight: 500;
        padding: 0.35em 0.65em;
    }
    .table th {
        white-space: nowrap;
    }
</style>
@endpush