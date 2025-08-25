@extends('admin.layouts.app')

@section('content')

@section('title', 'Quản lý thương hiệu')

<div class="content">
    {{-- Page Header --}}
    <div class="page-header">
        <div class="page-title">
            <h4>Quản lý thương hiệu</h4>
            <h6>Danh sách thương hiệu</h6>
        </div>
        <div class="page-btn d-flex flex-wrap" style="gap: 10px;">
            <a href="{{ route('admin.brands.create') }}" class="btn btn-added">
                <img src="{{ asset('assets/admin/img/icons/plus.svg') }}" class="me-2" alt="img"> Thêm thương hiệu
            </a>
            <a href="{{ route('admin.brands.trash') }}" class="btn btn-secondary">
                Thương hiệu đã xóa
            </a>
        </div>
    </div>

    {{-- Alerts --}}
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
    @if (session('warning'))
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        {{ session('warning') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif
    @if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <ul class="mb-0 ps-3">
            @foreach ($errors->all() as $err)
            <li>{{ $err }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="card mb-4">
        <div class="card-body">
            {{-- Search and Filter Form --}}
            <form method="GET" action="{{ route('admin.brands.index') }}" class="d-flex align-items-center mb-3 flex-nowrap" style="gap: 10px; overflow-x: auto;">
                {{-- Các trường lọc của bạn --}}
                @foreach (request()->except(['page', 'search', 'perPage', 'is_active', 'has_products', 'start_date', 'end_date']) as $k => $v)
                <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                @endforeach

                <div style="min-width: 150px;">
                    <input type="text" name="search" class="form-control" placeholder="Tên hoặc slug..."
                        value="{{ request('search') }}">
                </div>

                <div style="min-width: 180px;">
                    <select name="is_active" class="form-control">
                        <option value="">-- Tất cả trạng thái --</option>
                        <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>Hiển thị</option>
                        <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>Ẩn</option>
                    </select>
                </div>

                <div style="min-width: 180px;">
                    <select name="has_products" class="form-control">
                        <option value="">-- Tất cả sản phẩm --</option>
                        <option value="yes" {{ request('has_products') === 'yes' ? 'selected' : '' }}>Có sản phẩm</option>
                        <option value="no" {{ request('has_products') === 'no' ? 'selected' : '' }}>Chưa có</option>
                    </select>
                </div>

                <div>
                    <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                </div>

                <div>
                    <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                </div>

                {{-- Nhóm nút "Lọc" và "Xóa lọc" lại với nhau --}}
                <div class="d-flex" style="gap: 6px;">
                    <div>
                        <button type="submit" class="btn btn-primary">Tìm</button>
                    </div>
                    @if (request()->hasAny(['search', 'is_active', 'has_products', 'start_date', 'end_date']))
                    <div>
                        <a href="{{ route('admin.brands.index') }}" class="btn btn-outline-secondary">Xóa </a>
                    </div>
                    @endif
                </div>
            </form>

            {{-- Bulk Delete Form --}}
            <form id="bulk-delete-form" method="POST" action="{{ route('admin.brands.bulkDestroy') }}">
                @csrf
                @method('DELETE')

                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle text-center mb-0">
                        <thead class="table-light">
                            {{-- Toolbar --}}
                            <tr>
                                <th colspan="9" class="p-3">
                                    <div class="d-flex justify-content-between align-items-center flex-wrap" style="gap:12px;">
                                        <div>
                                            <button type="submit" id="btn-bulk-delete" class="btn btn-danger d-none" title="Xóa đã chọn"
                                                onclick="return confirm('Bạn có chắc chắn muốn xóa các thương hiệu đã chọn không? Thao tác này sẽ di chuyển chúng vào thùng rác.')">
                                                <i class="fa fa-trash me-1"></i> Xóa đã chọn
                                            </button>
                                        </div>
                                    </div>
                                </th>
                            </tr>

                            {{-- Column Headers --}}
                            <tr>
                                <th style="width:48px;">
                                    <input type="checkbox" id="select-all">
                                </th>
                                <th>Ảnh</th>
                                <th>Tên</th>
                                <th>Slug</th>
                                <th>Số lượng sản phẩm</th>
                                <th>Trạng thái</th>
                                <th>Ngày tạo</th>
                                <th>Ngày sửa</th>
                                <th class="text-center">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($brands as $brand)
                            <tr>
                                <td>
                                    <input
                                        type="checkbox"
                                        class="row-check"
                                        name="ids[]"
                                        value="{{ $brand->id }}"
                                        data-products-count="{{ $brand->products_count }}"
                                        {{ $brand->products_count > 0 ? 'disabled' : '' }}
                                        title="{{ $brand->products_count > 0 ? 'Không thể xóa: còn sản phẩm' : '' }}">
                                </td>
                                <td>
                                    @if ($brand->logo)
                                    <img src="{{ asset('storage/' . $brand->logo) }}" width="60" class="img-thumbnail" alt="Logo">
                                    @else
                                    <span class="text-muted">Không có ảnh</span>
                                    @endif
                                </td>
                                <td class="text-start">{{ $brand->name }}</td>
                                <td class="text-muted">{{ $brand->slug }}</td>
                                <td>{{ $brand->products_count }}</td>
                                <td>
                                    <span class="badges {{ $brand->is_active ? 'bg-lightgreen' : 'bg-lightyellow' }}">
                                        {{ $brand->is_active ? 'Hiển thị' : 'Ẩn' }}
                                    </span>
                                </td>
                                <td>{{ $brand->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    @if ($brand->updated_at != $brand->created_at)
                                    {{ $brand->updated_at->format('d/m/Y H:i') }}
                                    @else
                                    -
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex align-items-center justify-content-center" style="gap:10px;">
                                        <a href="{{ route('admin.brands.edit', $brand->id) }}" title="Sửa">
                                            <img src="{{ asset('assets/admin/img/icons/edit.svg') }}" alt="Sửa">
                                        </a>
                                        <a href="{{ route('admin.brands.show', $brand->id) }}" title="Xem">
                                            <img src="{{ asset('assets/admin/img/icons/eye.svg') }}" alt="Xem">
                                        </a>
                                        <form action="{{ route('admin.brands.destroy', $brand->id) }}" method="POST" class="d-inline"
                                            onsubmit="return confirm('Bạn có chắc chắn muốn xóa thương hiệu này không? Thao tác này sẽ di chuyển thương hiệu vào thùng rác.')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-link p-0" title="Xóa" {{ $brand->products_count > 0 ? 'disabled' : '' }}>
                                                <img src="{{ asset('assets/admin/img/icons/delete.svg') }}" alt="Xóa">
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted">Chưa có thương hiệu nào.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination and Per Page Select --}}
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div class="text-muted">
                        Hiển thị từ {{ $brands->firstItem() ?? 0 }} đến {{ $brands->lastItem() ?? 0 }} trên tổng số {{ $brands->total() }} thương hiệu
                    </div>
                    <div class="d-flex align-items-center" style="gap: 10px;">
                        <form method="GET" action="{{ route('admin.brands.index') }}" class="d-flex align-items-center" style="gap:8px;">
                            @foreach (request()->except(['perPage', 'page']) as $k => $v)
                            <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                            @endforeach
                            <label for="perPage" class="mb-0" style="font-weight:600;">Hiển thị:</label>
                            <select name="perPage" id="perPage" class="form-control form-control-sm"
                                style="width:90px; border:1px solid #cfd4da; border-radius:8px; padding:6px 10px; background:#f9fafb;"
                                onchange="this.form.submit()">
                                @foreach ([10, 25, 50, 100] as $n)
                                <option value="{{ $n }}" {{ request('perPage', 10) == (string) $n ? 'selected' : '' }}>
                                    {{ $n }}
                                </option>
                                @endforeach
                            </select>
                        </form>
                        {!! $brands->appends(request()->query())->onEachSide(1)->links('pagination::bootstrap-4') !!}
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(function() {
        // Bulk delete logic
        $('#select-all').on('change', function() {
            const checked = this.checked;
            $('.row-check:not(:disabled)').prop('checked', checked).trigger('change');
        });

        function toggleBulkBtn() {
            const anyChecked = $('.row-check:checked').length > 0;
            $('#btn-bulk-delete').toggleClass('d-none', !anyChecked);
        }
        $(document).on('change', '.row-check', toggleBulkBtn);
        toggleBulkBtn();

        // Tooltips
        $('[title]').tooltip({
            placement: 'top'
        });
    });
</script>
@endsection
