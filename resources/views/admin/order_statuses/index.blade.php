@extends('admin.layouts.app')

@section('content')
<h1>Danh sách trạng thái đơn hàng</h1>
<br>
<a href="{{ route('admin.order_statuses.create') }}" class="btn btn-primary mb-3">Thêm trạng thái đơn hàng </a>


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
                            <li><a href="{{ route('admin.order_statuses.index') }}">Trạng thái đơn hàng</a></li>
                            <li class="active">Danh sách đơn hàng </li>
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
                        <strong class="card-title">Danh sách trạng thái đơn hàng</strong>
                    </div>
                    <div class="card-body">
                        <table id="bootstrap-data-table" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Tên trạng thái</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($statuses as $status)
                                <tr>
                                    <td>{{ $status->id }}</td>
                                    <td>{{ $status->name }}</td>
                                    <td>
                                        <a href="{{ route('admin.order_statuses.edit', $status->id) }}" class="btn btn-warning btn-sm">Sửa</a>
                                        <form action="{{ route('admin.order_statuses.destroy', $status->id) }}" method="POST" style="display:inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc muốn xóa?')">Xóa</button>
                                        </form>
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
            order: [
                [2, 'desc']
            ] // Sắp xếp cột 9 - ngày tạo giảm dần
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
