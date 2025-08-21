@extends('admin.layouts.app')

@section('content')

@section('title', 'Danh mục sản phẩm')

@php
    // Xác định chế độ xem hiện tại: 'parents' (mặc định) hoặc 'children'
    $mode = request('mode', 'parents');
@endphp

<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4>Danh mục sản phẩm</h4>
            <h6>Quản lý danh mục cha và danh mục con</h6>
        </div>
        <div class="page-btn d-flex flex-wrap" style="gap: 10px;">
            <a href="{{ route('admin.categories.create') }}" class="btn btn-added">
                <img src="{{ asset('assets/admin/img/icons/plus.svg') }}" class="me-2" alt="img"> Thêm danh mục
            </a>
            <a href="{{ route('admin.categories.trashed') }}" class="btn btn-secondary">
                Danh mục đã xóa
            </a>
        </div>
    </div>

    {{-- Thông báo thành công --}}
    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif
    @if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title">
                @if ($mode === 'parents')
                    Danh mục Cha
                @else
                    Danh mục Con
                @endif
            </h5>
            <div class="d-flex" style="gap: 10px;">
                <a href="{{ route('admin.categories.index', ['mode' => 'parents', 'page' => request('page')]) }}"
                   class="btn {{ $mode === 'parents' ? 'btn-primary' : 'btn-outline-primary' }}">
                    Xem Danh mục Cha
                </a>
                <a href="{{ route('admin.categories.index', ['mode' => 'children', 'page' => request('page')]) }}"
                   class="btn {{ $mode === 'children' ? 'btn-primary' : 'btn-outline-primary' }}">
                    Xem Danh mục Con
                </a>
            </div>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.categories.index') }}" class="d-flex flex-wrap align-items-center mb-3" style="gap: 10px;">
                {{-- Luôn giữ tham số mode trong form --}}
                <input type="hidden" name="mode" value="{{ $mode }}">

                {{-- Ô tìm kiếm --}}
                <div class="flex-grow-1">
                    <input type="text" name="search" class="form-control"
                        placeholder="Tìm kiếm..."
                        value="{{ request('search') }}">
                </div>

                {{-- Lọc theo danh mục cha (chỉ hiển thị khi ở chế độ xem danh mục con) --}}
                @if ($mode === 'children')
                <div>
                    <select name="parent_id" class="form-control" onchange="this.form.submit()">
                        <option value="">Lọc theo danh mục cha</option>
                        @foreach($parentCategoriesList as $parent)
                            <option value="{{ $parent->id }}" {{ request('parent_id') == $parent->id ? 'selected' : '' }}>
                                {{ $parent->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @endif

                {{-- Lọc theo trạng thái --}}
                <div>
                    <select name="is_active" class="form-control" onchange="this.form.submit()">
                        <option value="">Tất cả trạng thái</option>
                        <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>Hiển thị</option>
                        <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>Ẩn</option>
                    </select>
                </div>

                {{-- Lựa chọn số lượng hiển thị trên mỗi trang --}}
                <div>
                    <select name="perPage" class="form-control" onchange="this.form.submit()">
                        @foreach([10, 25, 50, 100] as $size)
                            <option value="{{ $size }}" {{ request('perPage', 10) == $size ? 'selected' : '' }}>
                                {{ $size }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="d-flex" style="gap: 6px;">
                    <button class="btn btn-primary" type="submit">Tìm kiếm</button>
                    @if (request()->hasAny(['search', 'parent_id', 'is_active', 'perPage']))
                    <a href="{{ route('admin.categories.index', ['mode' => $mode]) }}" class="btn btn-outline-secondary">Xóa</a>
                    @endif
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Tên Danh Mục</th>
                            {{-- Cột danh mục cha chỉ hiển thị khi ở chế độ xem danh mục con --}}
                            @if ($mode === 'children')
                                <th>Danh mục cha</th>
                            @endif
                            <th>Icon</th>
                            <th>Thứ tự</th>
                            <th>Trạng thái</th>
                            <th class="text-center">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- Vòng lặp bây giờ chỉ cần dùng một biến categories --}}
                        @forelse($categories as $category)
                            <tr>
                                <td>{{ $category->id }}</td>
                                <td>{{ $category->name }}</td>
                                {{-- Giá trị danh mục cha chỉ hiển thị khi ở chế độ xem danh mục con --}}
                                @if ($mode === 'children')
                                    <td>{{ $category->parent->name ?? '-' }}</td>
                                @endif
                                <td>
                                    @if($category->icon)
                                        <img src="{{ asset('storage/' . $category->icon) }}" alt="Icon" width="24" height="24">
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>{{ $category->ordinal }}</td>
                                <td>
                                    <span class="badges {{ $category->is_active ? 'bg-lightgreen' : 'bg-lightyellow' }}">
                                        {{ $category->is_active ? 'Hiển thị' : 'Ẩn' }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex align-items-center justify-content-center" style="gap: 10px;">
                                        <a href="{{ route('admin.categories.edit', $category->id) }}" title="Sửa">
                                            <img src="{{ asset('assets/admin/img/icons/edit.svg') }}" alt="Sửa">
                                        </a>
                                        <a href="{{ route('admin.categories.show', $category->id) }}" title="Xem">
                                            <img src="{{ asset('assets/admin/img/icons/eye.svg') }}" alt="Xem">
                                        </a>
                                        <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa danh mục này không? Thao tác này sẽ di chuyển danh mục vào thùng rác.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-link p-0" title="Xóa">
                                                <img src="{{ asset('assets/admin/img/icons/delete.svg') }}" alt="Xóa">
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ $mode === 'children' ? 7 : 6 }}" class="text-center text-muted">
                                    @if ($mode === 'parents')
                                        Chưa có danh mục cha nào.
                                    @else
                                        Chưa có danh mục con nào.
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Phân trang --}}
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div class="text-muted">
                    Hiển thị từ {{ $categories->firstItem() ?? 0 }} đến {{ $categories->lastItem() ?? 0 }} trên tổng số {{ $categories->total() }} danh mục
                </div>
                <div>
                    {{-- Đảm bảo phân trang giữ lại tất cả tham số lọc --}}
                    {!! $categories->appends(request()->except('page'))->onEachSide(1)->links('pagination::bootstrap-4') !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
