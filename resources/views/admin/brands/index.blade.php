@extends('admin.layouts.app')

@section('content')
    <div class="content col-md-12">
        {{-- Alerts (Bootstrap 5) --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
            </div>
        @endif

        @if (session('warning'))
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                {{ session('warning') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
            </div>
        @endif

        {{-- Header + nút thêm / thùng rác --}}
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h4 class="mb-0"> Quản lý thương hiệu</h4>
                <small class="text-muted">Danh sách thương hiệu</small>
            </div>
            <div class="mb-3 d-flex" style="gap: 10px;">
                <a href="{{ route('admin.brands.create') }}"
                    style="background-color: #ffa200; color: #fff; border: none; padding: 8px 15px; border-radius: 6px; display: inline-flex; align-items: center; gap: 6px; text-decoration: none;"
                    onmouseover="this.style.backgroundColor='#e68a00'" onmouseout="this.style.backgroundColor='#ffa200'">
                    <i class="fa fa-plus"></i> Thêm thương hiệu
                </a>

                <a href="{{ route('admin.brands.trash') }}"
                    style="background-color: #ffa200; color: #fff; border: none; padding: 8px 15px; border-radius: 6px; display: inline-flex; align-items: center; gap: 6px; text-decoration: none;"
                    onmouseover="this.style.backgroundColor='#e68a00'" onmouseout="this.style.backgroundColor='#ffa200'">
                    <i class="fa fa-trash"></i> Thương hiệu đã xóa
                </a>
            </div>


        </div>


<div class="card">
          <div class="card-body">
       <form method="GET" action="{{ route('admin.brands.index') }}" class="row g-2 mb-3">
    @foreach (request()->except(['page']) as $k => $v)
        @continue(in_array($k, ['search','perPage','is_active','has_products','start_date','end_date']))
        <input type="hidden" name="{{ $k }}" value="{{ $v }}">
    @endforeach

    <div class="col-md-3">
        <label class="form-label mb-1">Tìm kiếm</label>
        <input type="text" name="search" class="form-control" placeholder="Tên hoặc slug..."
               value="{{ request('search') }}">
    </div>

    <div class="col-md-2">
        <label class="form-label mb-1">Trạng thái</label>
        <select name="is_active" class="form-control">
            <option value="">-- Tất cả --</option>
            <option value="1" {{ request('is_active')==='1' ? 'selected' : '' }}>Hiển thị</option>
            <option value="0" {{ request('is_active')==='0' ? 'selected' : '' }}>Ẩn</option>
        </select>
    </div>

    <div class="col-md-2">
        <label class="form-label mb-1">Sản phẩm</label>
        <select name="has_products" class="form-control">
            <option value="">-- Tất cả --</option>
            <option value="yes" {{ request('has_products')==='yes' ? 'selected' : '' }}>Có sản phẩm</option>
            <option value="no"  {{ request('has_products')==='no'  ? 'selected' : '' }}>Chưa có</option>
        </select>
    </div>

    <div class="col-md-2">
        <label class="form-label mb-1">Ngày bắt đầu</label>
        <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
    </div>

    <div class="col-md-2">
        <label class="form-label mb-1">Ngày kết thúc</label>
        <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
    </div>

    <div class="col-md-1 d-flex align-items-end">
        <button type="submit" class="btn w-100" style="background:#ffa200;color:#fff;">Lọc</button>
    </div>

    @if (request()->hasAny(['search','is_active','has_products','start_date','end_date']))
        <div class="col-md-1 d-flex align-items-end">
            <a href="{{ route('admin.brands.index') }}" class="btn btn-outline-secondary w-100">Xóa Lọc</a>
        </div>
    @endif
</form>
 </div>
  </div>

        {{-- BULK FORM: chỉ dùng để xoá hàng loạt --}}
        <form id="bulk-delete-form" method="POST" action="{{ route('admin.brands.bulkDestroy') }}">
            @csrf
            @method('DELETE')

       <div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table id="brand-table" class="table table-bordered table-hover align-middle text-center mb-0">
                <thead class="table-light">
                    {{-- Hàng toolbar --}}
                    <tr>
                        <th colspan="9" class="p-3">
                            <div class="d-flex justify-content-between align-items-center flex-wrap" style="gap:12px;">
                                <div>
                                    <button type="submit" id="btn-bulk-delete" class="btn btn-outline-danger btn-sm" title="Xóa đã chọn">
                                        <i class="fa fa-trash"></i> Xóa đã chọn
                                    </button>
                                </div>
                            </div>
                        </th>
                    </tr>

                    {{-- Hàng tiêu đề cột --}}
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
                                    title="{{ $brand->products_count > 0 ? 'Không thể xóa: còn sản phẩm' : '' }}"
                                >
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
                                @if ($brand->is_active)
                                    <span class="badge bg-success">Hiển thị</span>
                                @else
                                    <span class="badge bg-secondary">Ẩn</span>
                                @endif
                            </td>

                            <td>{{ $brand->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                @if ($brand->updated_at != $brand->created_at)
                                    {{ $brand->updated_at->format('d/m/Y H:i') }}
                                @endif
                            </td>

                            <td>
                                <div class="d-flex align-items-center justify-content-center" style="gap:10px;">
                                    <a href="{{ route('admin.brands.show', $brand->id) }}" class="btn btn-sm btn-outline-info" title="Xem">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.brands.edit', $brand->id) }}" class="btn btn-sm btn-outline-warning" title="Sửa">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.brands.destroy', $brand->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Xóa thương hiệu này?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Xóa">
                                            <i class="fa fa-trash"></i>
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
    </div>
</div>

    {{-- FOOTER CONTROLS (sticky) --}}
    <div id="brand-footer-controls" class="d-flex justify-content-between align-items-center px-3 py-3"
        style="position:sticky; bottom:0; background:#fff; border-top:1px solid #eef0f2; z-index:5;">
        {{-- Bên trái: chọn số hiển thị --}}
        <form method="GET" action="{{ route('admin.brands.index') }}" class="d-flex align-items-center"
            style="gap:8px; margin:0;">
            @foreach (request()->except(['perPage', 'page']) as $k => $v)
                <input type="hidden" name="{{ $k }}" value="{{ $v }}">
            @endforeach

            <label for="perPage" class="mb-0" style="font-weight:600;">Hiển thị:</label>
            <select name="perPage" id="perPage" class="form-control"
                style="width:90px; border:1px solid #cfd4da; border-radius:8px; padding:6px 10px; background:#f9fafb;"
                onchange="this.form.submit()">
                @foreach ([10, 25, 50, 100] as $n)
                    <option value="{{ $n }}" {{ request('perPage') == (string) $n ? 'selected' : '' }}>
                        {{ $n }}</option>
                @endforeach
            </select>
        </form>

        {{-- Bên phải: phân trang + tới trang nhanh --}}
        <div class="d-flex align-items-center flex-wrap" style="gap:10px;">
            <small class="text-muted me-2">
                Hiển thị từ {{ $brands->firstItem() ?? 0 }} đến {{ $brands->lastItem() ?? 0 }} /
                {{ $brands->total() }} mục
            </small>

            <nav aria-label="Pagination">
                <div class="pagination pagination-sm mb-0">
                    {!! $brands->appends(request()->query())->onEachSide(1)->links('pagination::bootstrap-4') !!}
                </div>
            </nav>

            {{-- Tới trang nhanh --}}

        </div>
    </div>

    </div>
    </form>


@endsection

@section('scripts')
    

    <script>
        $(function() {
            $('#brand-table').DataTable({
                order: [
                    [2, 'asc']
                ],
                paging: false,
                searching: false,
                info: false,
                columnDefs: [{
                    orderable: false,
                    targets: [0, 1, 8]
                }],
                language: {
                    emptyTable: "Không có thương hiệu nào trong bảng",
                    zeroRecords: "Không tìm thấy thương hiệu nào phù hợp"
                }
            });

            // Chọn tất cả (bỏ qua checkbox disabled)
            $('#select-all').on('change', function() {
                const checked = this.checked;
                $('.row-check:not(:disabled)').prop('checked', checked).trigger('change');
            });

            // Bật/tắt nút Xóa đã chọn
            function toggleBulkBtn() {
                const anyChecked = $('.row-check:checked').length > 0;
                $('#btn-bulk-delete').toggleClass('d-none', !anyChecked);
            }
            $(document).on('change', '.row-check', toggleBulkBtn);
            toggleBulkBtn();

            // perPage: đổi là submit form brandFilters (về trang 1)
            $('#perPageSelect').on('change', function() {
                const form = document.getElementById('brandFilters');
                // reset page về 1
                let reset = document.createElement('input');
                reset.type = 'hidden';
                reset.name = 'page';
                reset.value = '1';
                form.appendChild(reset);
                form.submit();
            });

            // Tooltip
            $('[title]').tooltip({
                placement: 'top'
            });
        });
    </script>
@endsection
