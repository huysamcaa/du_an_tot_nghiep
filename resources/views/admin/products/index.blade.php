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
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-md-12">
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
                        <table id="bootstrap-data-table" class="table table-striped table-bordered text-center">
                            <thead>
                                <tr>
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
                                <tr class="text-center align-middle">
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
                                    <td class="text-right">
                                        {{ number_format($product->price, 0, ',', '.') }} đ
                                    </td>
                                    <td>
                                        @if($product->is_active)
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
                                    <td colspan="8" class="text-center text-muted">Chưa có sản phẩm nào.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- .animated -->
</div><!-- .content -->
@endsection
