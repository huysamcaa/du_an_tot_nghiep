@extends('admin.layouts.app')

@section('content')
    <h1>Danh sách sản phẩm</h1>
    <a href="{{ route('admin.products.create') }}" class="btn btn-primary mb-3">Thêm sản phẩm</a>
    <a href="{{ route('admin.products.trashed') }}" class="btn btn-secondary mb-3">Sản phẩm đã xóa</a>

    <div class="breadcrumbs">
        <div class="breadcrumbs-inner">
            <div class="row m-0">
                <div class="col-sm-4">
                    <div class="page-header float-left">
                        <div class="page-title">
                            <h1>Admin</h1>
                        </div>
                    </div>
                </div>
                <div class="col-sm-8">
                    <div class="page-header float-right">
                        <div class="page-title">
                            <ol class="breadcrumb text-right">
                                <li><a href="#">Trang chủ</a></li>
                                <li><a href="#">Sản phẩm</a></li>
                                <li class="active">Danh sách sản phẩm</li>
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
                    <div class="card">
                        <div class="card-header">
                            <strong class="card-title">Danh sách sản phẩm</strong>
                        </div>
                        <div class="card-body">
                            <form method="GET" action="{{ route('admin.products.index') }}" class="mb-3 d-flex" style="gap: 12px; align-items: center;">
                                <div>
                                    <label for="per_page" style="font-weight:600;">Hiển thị:</label>
                                    <select name="per_page" id="per_page" class="form-control d-inline-block"
                                        style="width:auto;display:inline-block;" onchange="this.form.submit()">
                                        <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                                        <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                                        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                                        <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                                    </select>
                                </div>
                            </form>

                            <form method="GET" action="{{ route('admin.products.index') }}" class="mb-3" style="max-width:350px;">
                                <div class="input-group">
                                    <input type="text" name="keyword" class="form-control"
                                        placeholder="Tìm kiếm tên sản phẩm..." value="{{ request('keyword') }}">
                                    <button class="btn btn-primary" type="submit">Tìm kiếm</button>
                                </div>
                            </form>

                            <table id="bootstrap-data" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Ảnh</th>
                                        <th>Tên</th>
                                        <th>Danh mục</th>
                                        <th>Thương hiệu</th>
                                        <th>Số lượng</th>
                                        <th>Lượt xem</th>
                                        <th>Giá gốc</th>
                                        <th>Giá sale</th>
                                        <th>Trạng thái</th>
                                        <th>Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($products as $product)
                                        <tr>
                                            <td>
                                                @if ($product->thumbnail)
                                                    <img src="{{ asset('storage/' . $product->thumbnail) }}" width="60">
                                                @else
                                                    <span>Không có ảnh</span>
                                                @endif
                                            </td>
                                            <td>{{ $product->name }}</td>
                                            <td>
                                                @if ($product->category)
                                                    @php
                                                        $category = $product->category;
                                                        $names = [];
                                                        while ($category) {
                                                            $names[] = $category->name;
                                                            $category = $category->parent;
                                                        }
                                                        $names = array_reverse($names);
                                                    @endphp
                                                    {{ implode(' > ', $names) }}
                                                @else
                                                    <span>Chưa có danh mục</span>
                                                @endif
                                            </td>
                                            <td>{{ $product->brand->name ?? '' }}</td>
                                            <td>{{ $product->stock }}</td>
                                            <td>{{ $product->views }}</td>
                                            <td>{{ number_format($product->price, 0, ',', '.') }} đ</td>
                                            <td>
                                                @if ($product->is_sale)
                                                    {{ number_format($product->sale_price, 0, ',', '.') }} đ
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>
                                                {{ $product->is_active ? 'Hiển thị' : 'Ẩn' }}
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.products.show', $product->id) }}"
                                                    class="btn btn-info btn-sm">Chi tiết</a>
                                                <a href="{{ route('admin.products.edit', $product) }}"
                                                    class="btn btn-sm btn-warning">Sửa</a>
                                                @if ($product->is_active)
                                                    <form action="{{ route('admin.products.destroy', $product) }}" method="POST" style="display:inline-block">
                                                        @csrf @method('DELETE')
                                                        <button onclick="return confirm('Xóa sản phẩm này?')" class="btn btn-sm btn-danger">Xóa</button>
                                                    </form>
                                                @else
                                                    <form action="{{ route('admin.products.restore', $product->id) }}" method="POST" style="display:inline-block">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="btn btn-success btn-sm">Hiện lại</button>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div><!-- .content -->

    <div class="clearfix"></div>
@endsection
