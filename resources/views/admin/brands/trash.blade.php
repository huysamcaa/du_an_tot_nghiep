@extends('admin.layouts.app')

@section('content')
{{-- Breadcrumbs --}}
<div class="breadcrumbs">
    <div class="breadcrumbs-inner">
        <div class="row m-0">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1>Thương hiệu đã xóa</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
                            <li><a href="{{ route('admin.dashboard') }}">Trang chủ</a></li>
                            <li><a href="{{ route('admin.brands.index') }}">Thương hiệu</a></li>
                            <li class="active">Đã xóa</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Content --}}
<div class="content">
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-md-12">

                {{-- Nút quay lại --}}
                <div class="mb-3 d-flex justify-content-between align-items-center">
                    <a href="{{ route('admin.brands.index') }}" class="btn btn-outline-secondary">
                        <i class="fa fa-arrow-left mr-1"></i> Quay lại
                    </a>
                </div>

                {{-- Flash messages --}}
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                    </div>
                @endif

                {{-- Bảng danh sách --}}
                <div class="card">
                    <div class="card-header">
                        <strong class="card-title">Danh sách thương hiệu đã xóa</strong>
                    </div>
                    <div class="card-body">
                        <table  id="bootstrap-data-table" class="table table-striped table-bordered text-center">
                            <thead>
                                <tr>
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
                                        <td>{{ $loop->iteration + ($brands->currentPage() - 1) * $brands->perPage() }}</td>
                                        <td>{{ $brand->name }}</td>
                                        <td>{{ $brand->slug }}</td>
                                        <td>{{ $brand->products_count }}</td>
                                        <td>
                                            {{ $brand->deleted_at ? $brand->deleted_at->format('d/m/Y H:i') : '--' }}
                                        </td>
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
                                        <td colspan="6" class="text-center text-muted">Không có thương hiệu nào đã bị xóa.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
