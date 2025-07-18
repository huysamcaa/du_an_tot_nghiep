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
                            <li class="active">Danh sách sản phẩm </li>
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
                        <table id="bootstrap-data-table" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Stt</th>
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
                                @foreach($products as $product)
                                <tr>
                                    <td>{{ $products->count() - $loop->index }}</td>
                                    <td>
                                        @if($product->thumbnail)
                                        <img src="{{ asset('storage/' . $product->thumbnail) }}" width="60">
                                        @else
                                        <span>Không có ảnh</span>
                                        @endif
                                    </td>
                                    <td>{{ $product->name }}</td>
                                    <td>{{ $product->category->name ?? '' }}</td>
                                    <td>{{ $product->brand->name ?? '' }}</td>
                                    <td>{{ $product->stock }}</td>
                                    <td>{{ $product->views }}</td>
                                    <td>{{ number_format($product->price, 0, ',', '.') }} đ</td>
                                    <td>
                                        @if($product->is_sale)
                                        {{ number_format($product->sale_price, 0, ',', '.') }} đ
                                        @else
                                        -
                                        @endif
                                    </td>
                                    <td>
                                        {{ $product->is_active ? 'Hiển thị' : 'Ẩn' }}
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.products.show', $product->id) }}" class="btn btn-info btn-sm">Chi tiết</a>
                                        <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-warning">Sửa</a>
                                        @if($product->is_active)
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

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>

<script>
      $(document).ready(function() {
    $('#bootstrap-data-table').DataTable({
        order: [[0, 'desc']] // Sắp xếp cột 9 - ngày tạo giảm dần
    });
});

    // Xử lý sự kiện khi người dùng nhấn nút "Xóa"
    $(document).on('click', '.btn-danger', function(e) {
        e.preventDefault();
        if (confirm('Bạn có chắc chắn muốn xóa sản phẩm này?')) {
            $(this).closest('form').submit();
        }
    });
    // Thêm sự kiện cho nút "Sửa"
    $(document).on('click', '.btn-warning', function(e) {
        e.preventDefault();
        var editUrl = $(this).attr('href');
        window.location.href = editUrl;
    });
    // Thêm sự kiện cho nút "Thêm sản phẩm"
    $(document).on('click', '.btn-primary', function(e) {
        e.preventDefault();
        var createUrl = $(this).attr('href');
        window.location
.href = createUrl;
    });
</script>
@endsection
