@extends('admin.layouts.app')

@section('content')
{{-- Breadcrumbs --}}
<div class="breadcrumbs">
    <div class="breadcrumbs-inner">
        <div class="row m-0">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1>Bài viết</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
                            <li><a href="{{ route('admin.dashboard') }}">Trang chủ</a></li>
                            <li class="active">Bài viết</li>
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
        {{-- Thông báo --}}
        @if(session('success'))
        <div class="alert alert-success py-2 px-3">{{ session('success') }}</div>
        @endif
        @if($errors->any())
        <div class="alert alert-danger py-2 px-3">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        <div class="row">
            <div class="col-md-12">
                {{-- Nút thêm bài viết --}}
                <div class="mb-3">
                    <a href="{{ route('admin.blogs.create') }}" class="btn btn-success">
                        <i class="fa fa-plus"></i> Thêm bài viết
                    </a>
                </div>

                {{-- Bảng dữ liệu --}}
                <div class="card">
                    <div class="card-header">
                        <strong class="card-title">Danh sách bài viết</strong>
                    </div>
                    <div class="card-body">
                        <table id="bootstrap-data-table" class="table table-striped table-bordered text-center">
                            <thead>
                                <tr>
                                    <th>Tiêu đề</th>
                                    <th>Ảnh</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($blogs as $blog)
                                <tr>
                                    <td class="align-middle">{{ $blog->title }}</td>
                                    <td class="align-middle">
                                        @if($blog->image)
                                            <img src="{{ asset('storage/' . $blog->image) }}" width="100" style="object-fit: cover;">
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="align-middle">
                                        <a href="{{ route('admin.blogs.edit', $blog->id) }}" class="btn btn-sm btn-outline-warning" title="Sửa">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.blogs.destroy', $blog->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Xóa bài viết này?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger" title="Xóa">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-muted text-center">Chưa có bài viết nào.</td>
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
