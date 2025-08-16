@extends('admin.layouts.app')

@section('content')

@section('title', 'Danh mục sản phẩm')

<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4>Danh mục sản phẩm</h4>
            <h6>Xem/Danh mục sản phẩm</h6>
        </div>
        <div class="page-btn">
            <a href="{{ route('admin.categories.create') }}" class="btn btn-added">
                <img src="{{ asset('assets/admin/img/icons/plus.svg') }}" class="me-1" alt="img">Thêm danh mục
            </a> <br>
            <a href="{{ route('admin.categories.trashed') }}" class="btn btn-added">
                <img src="{{ asset('assets/admin/img/icons/delete-2.svg') }}" class="me-1" alt="img">Thùng rác
            </a>
        </div>
    </div>
    

    <div class="card">
        <div class="card-body">
            <div class="table-top">
                <div class="search-set">
                    <div class="search-input">
                        <a class="btn btn-searchset">
                            <img src="{{ asset('assets/admin/img/icons/search-white.svg') }}" alt="img">
                        </a>
                    </div>
                </div>
                <div class="wordset">
                    <ul>
                        <li>
                            <a data-bs-toggle="tooltip" title="pdf">
                                <img src="{{ asset('assets/admin/img/icons/pdf.svg') }}" alt="img">
                            </a>
                        </li>
                        <li>
                            <a data-bs-toggle="tooltip" title="excel">
                                <img src="{{ asset('assets/admin/img/icons/excel.svg') }}" alt="img">
                            </a>
                        </li>
                        <li>
                            <a data-bs-toggle="tooltip" title="print">
                                <img src="{{ asset('assets/admin/img/icons/printer.svg') }}" alt="img">
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="card" id="filter_inputs">
                <div class="card-body pb-0">
                    <div class="row">
                        <div class="col-lg-1 col-sm-6 col-12 ms-auto">
                            <div class="form-group">
                                <a class="btn btn-filters ms-auto">
                                    <img src="{{ asset('assets/admin/img/icons/search-whites.svg') }}" alt="img">
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table datanew">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tên</th>
                            <th>Danh mục cha</th>
                            <th>Icon</th>
                            <th>Thứ tự</th>
                            <th>Trạng thái</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categories as $category)
                        <tr>

                            <td>{{ $category->id }}</td>
                            <td>{{ $category->name }}</td>
                            <td>{{ $category->parent->name ?? '-' }}</td>
                            <td>{!! $category->icon !!}</td>
                            <td>{{ $category->ordinal }}</td>
                            <td>
                                <span class="badges {{ $category->is_active ? 'bg-lightgreen' : 'bg-lightyellow' }}">
                                    {{ $category->is_active ? 'Hiển thị' : 'Ẩn' }}
                                </span>
                            </td>

                            <td>
                                <a href="{{ route('admin.categories.edit', $category) }}" class="me-3">
                                    <img src="{{ asset('assets/admin/img/icons/edit.svg') }}" alt="img">
                                </a>
                                <a class="me-3" href="{{ route('admin.categories.show', $category) }}">
                                    <img src="{{ asset('assets/admin/img/icons/eye.svg') }}" alt="xem">
                                </a>
                                <button type="button"
                                    class="confirm-text btn btn-link p-0"
                                    data-url="{{ route('admin.categories.destroy', $category) }}"
                                    data-id="{{ $category->id }}">
                                    <img src="{{ asset('assets/admin/img/icons/delete.svg') }}" alt="Xóa">
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
