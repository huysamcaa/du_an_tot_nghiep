@extends('admin.layouts.app')

@section('content')
<div class="content col-md-12">

    {{-- Alerts --}}
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

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-0">Quản lý trạng thái đơn hàng</h4>
            <small class="text-muted">Danh sách trạng thái đơn hàng</small>
        </div>
        <div>
            <a href="{{ route('admin.order_statuses.create') }}"
               style="background-color: #ffa200; color: #fff; border: none; padding: 8px 15px; border-radius: 6px; display: inline-flex; align-items: center; gap: 6px; text-decoration: none;"
               onmouseover="this.style.backgroundColor='#e68a00'"
               onmouseout="this.style.backgroundColor='#ffa200'">
                <i class="fa fa-plus"></i> Thêm trạng thái
            </a>
        </div>
    </div>

    {{-- Form tìm kiếm --}}
    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.order_statuses.index') }}" class="row g-2">
                @foreach (request()->except(['page','search']) as $k => $v)
                    <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                @endforeach

                <div class="col-md-6">
                    <label class="form-label mb-1">Tìm kiếm</label>
                    <input type="text" name="search" class="form-control" placeholder="Tên trạng thái..."
                           value="{{ request('search') }}">
                </div>

                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn w-100" style="background:#ffa200;color:#fff;">Tìm</button>
                </div>

                @if (request()->has('search'))
                    <div class="col-md-2 d-flex align-items-end">
                        <a href="{{ route('admin.order_statuses.index') }}" class="btn btn-outline-secondary w-100">Xóa tìm</a>
                    </div>
                @endif
            </form>
        </div>
    </div>

    {{-- Bảng --}}
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table id="status-table" class="table table-bordered table-hover align-middle text-center mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width:10%;">ID</th>
                            <th class="text-start">Tên trạng thái</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($statuses as $status)
                            <tr>
                                <td>{{ $status->id }}</td>
                                <td class="text-start">{{ $status->name }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-muted text-center">Không có trạng thái nào.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Footer controls --}}
    <div id="status-footer-controls" class="d-flex justify-content-between align-items-center px-3 py-3"
        style="position:sticky; bottom:0; background:#fff; border-top:1px solid #eef0f2; z-index:5;">
        <form method="GET" action="{{ route('admin.order_statuses.index') }}" class="d-flex align-items-center"
            style="gap:8px; margin:0;">
            @foreach (request()->except(['perPage','page']) as $k => $v)
                <input type="hidden" name="{{ $k }}" value="{{ $v }}">
            @endforeach
            <label for="perPage" class="mb-0 fw-semibold">Hiển thị:</label>
            <select name="perPage" id="perPage" class="form-control"
                style="width:90px; border:1px solid #cfd4da; border-radius:8px; padding:6px 10px; background:#f9fafb;"
                onchange="this.form.submit()">
                @foreach ([10,25,50,100] as $n)
                    <option value="{{ $n }}" {{ request('perPage') == (string)$n ? 'selected':'' }}>{{ $n }}</option>
                @endforeach
            </select>
        </form>

        <div class="d-flex align-items-center flex-wrap" style="gap:10px;">
            <small class="text-muted">
                Hiển thị từ {{ $statuses->firstItem() ?? 0 }} đến {{ $statuses->lastItem() ?? 0 }} / {{ $statuses->total() }} mục
            </small>
            <div class="pagination pagination-sm mb-0">
                {!! $statuses->appends(request()->query())->onEachSide(1)->links('pagination::bootstrap-4') !!}
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#status-table').DataTable({
            "paging": false,
            "searching": false,
            "info": false,
            "order": [[0, "asc"]],
            "columnDefs": [
                { "orderable": false, "targets": [2] }
            ],
            "language": {
                "emptyTable": "Không có trạng thái nào",
                "zeroRecords": "Không tìm thấy kết quả phù hợp"
            }
        });
    });
</script>
@endsection
