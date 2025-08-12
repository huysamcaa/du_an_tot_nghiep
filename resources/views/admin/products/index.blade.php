@extends('admin.layouts.app')

@section('content')
<div class="breadcrumbs">
    <div class="breadcrumbs-inner">
        <div class="row m-0">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1>Sản phẩm</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
                            <li><a href="{{ route('admin.dashboard') }}">Trang chủ</a></li>
                            <li class="active">Sản phẩm</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="col-md-12">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Đóng">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        {{-- Nút thêm và sản phẩm đã xóa --}}
        <div class="mb-3 d-flex" style="gap: 10px;">
            <a href="{{ route('admin.products.create') }}" class="btn btn-success" title="Thêm sản phẩm">
                <i class="fa fa-plus"></i> Thêm sản phẩm
            </a>
            <a href="{{ route('admin.products.trashed') }}" class="btn btn-secondary" title="Sản phẩm đã xóa">
                <i class="fa fa-trash"></i> Sản phẩm đã xóa
            </a>
        </div>

        <div class="card">
            <div class="card-header">
                <strong class="card-title">Danh sách sản phẩm</strong>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <form method="GET" action="{{ route('admin.products.index') }}" class="d-flex align-items-center" style="gap: 12px;">
                        <div>
                            <label for="perPage" style="font-weight:600;">Hiển thị:</label>
                            <select name="perPage" id="perPage" class="form-control d-inline-block" style="width:auto;" onchange="this.form.submit()">
                                <option value="10" {{ request('perPage') == '10' ? 'selected' : '' }}>10</option>
                                <option value="25" {{ request('perPage') == '25' ? 'selected' : '' }}>25</option>
                                <option value="50" {{ request('perPage') == '50' ? 'selected' : '' }}>50</option>
                                <option value="100" {{ request('perPage') == '100' ? 'selected' : '' }}>100</option>
                            </select>
                        </div>
                    </form>

                    <form method="GET" action="{{ route('admin.products.index') }}" class="w-50">
                        <div class="d-flex">
                            <input type="text" name="search" class="form-control" placeholder="Tìm tên, danh mục, thương hiệu..." value="{{ request('search') }}">
                            <button class="btn btn-primary ml-1" type="submit">Tìm</button>
                            @if (request('search'))
                                <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary ml-1">Xóa</a>
                            @endif
                        </div>
                    </form>
                </div>

                <table id="product-table" class="table table-striped table-bordered text-center align-middle">
                    <thead>
                        <tr>
                            <th>STT</th>
                            <th>Ảnh</th>
                            <th>Tên</th>
                            <th>Danh mục</th>
                            <th>Thương hiệu</th>
                            <th>Số lượng</th>
                            <th>Tổng giá</th>
                            <th>Trạng thái</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($products as $product)
                            <tr>
                                <td>{{ $loop->iteration + ($products->currentPage() - 1) * $products->perPage() }}</td>
                                <td>
                                    @if ($product->thumbnail)
                                        <img src="{{ asset('storage/' . $product->thumbnail) }}" width="60">
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>{{ $product->name }}</td>
                                <td>{{ $product->category->name ?? '-' }}</td>
                                <td>{{ $product->brand->name ?? '-' }}</td>
                                <td>{{ $product->total_stock ?? 0 }}</td>
                                <td class="text-right">{{ number_format($product->price, 0, ',', '.') }} đ</td>
                                <td>
                                    @if ($product->is_active)
                                        <span class="badge badge-success">Hiển thị</span>
                                    @else
                                        <span class="badge badge-danger">Ẩn</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.products.show', $product) }}" class="btn btn-sm btn-outline-primary" title="Xem chi tiết">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-outline-warning" title="Sửa">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.products.destroy', $product) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Bạn có chắc chắn muốn xóa?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Xóa">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted">Chưa có sản phẩm nào.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div class="text-muted">
                        Hiển thị từ {{ $products->firstItem() ?? 0 }} đến {{ $products->lastItem() ?? 0 }} trên tổng số {{ $products->total() }} sản phẩm
                    </div>
                    <div>
                        {!! $products->appends(request()->query())->onEachSide(1)->links('pagination::bootstrap-4') !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
<script>
    $(document).ready(function() {
        $('#product-table').DataTable({
            "order": [[ 0, "asc" ]],
            "paging": false,
            "searching": false,
            "info": false,
            "columnDefs": [
                { "orderable": false, "targets": [1,8] }
            ],
            "language": {
                "emptyTable": "Không có sản phẩm nào trong bảng",
                "zeroRecords": "Không tìm thấy sản phẩm nào phù hợp"
            }
        });
    });
</script>
@endsection
