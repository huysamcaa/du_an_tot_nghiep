@extends('admin.layouts.app')

@section('content')
<div class="breadcrumbs">
    <div class="breadcrumbs-inner">
        <div class="row m-0">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1>Thuộc tính</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
                            <li><a href="{{ route('admin.dashboard') }}">Trang chủ</a></li>
                            <li class="active">Thuộc tính</li>
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
                @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <a href="{{ route('admin.attributes.create') }}" class="btn btn-success mb-3">
                    <i class="fa fa-plus"></i> Thêm thuộc tính
                </a>

                <div class="card">
                    <div class="card-header">
                        <strong class="card-title">Danh sách thuộc tính</strong>
                    </div>
                    <div class="card-body">
                        <table id="bootstrap-data-table" class="table table-striped table-bordered text-center">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Tên</th>
                                    <th>Slug</th>
                                    <th>Biến thể?</th>
                                    <th>Hiển thị?</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($attributes as $attr)
                                <tr class="align-middle">
                                    <td>{{ $attr->id }}</td>
                                    <td>{{ $attr->name }}</td>
                                    <td>{{ $attr->slug }}</td>
                                    <td>
                                        @if($attr->is_variant)
                                        <span class="badge badge-success">✔</span>
                                        @else
                                        <span class="badge badge-danger">✘</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($attr->is_active)
                                        <span class="badge badge-success">✔</span>
                                        @else
                                        <span class="badge badge-danger">✘</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.attributes.edit', $attr) }}"
                                            class="btn btn-sm btn-outline-warning"
                                            title="Sửa thuộc tính">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.attributes.destroy', $attr) }}"
                                            method="POST"
                                            style="display:inline-block;"
                                            onsubmit="return confirm('Bạn có chắc muốn xóa thuộc tính này?')">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger" title="Xóa thuộc tính">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">
                                        Chưa có thuộc tính nào.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div><!-- .card-body -->
                </div><!-- .card -->
            </div><!-- .col -->
        </div><!-- .row -->
    </div><!-- .animated -->
</div><!-- .content -->
@endsection
