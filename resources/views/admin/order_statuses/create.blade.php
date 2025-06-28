@extends('admin.layouts.app')

@section('content')
<h1>Thêm mới trạng thái đơn hàng</h1>

<form action="{{ route('admin.order_statuses.store') }}" method="POST">
    @csrf
    <div class="mb-3">
        <label for="name">Tên trạng thái</label>
        <input type="text" name="name" class="form-control" required>
    </div>

    <button type="submit" class="btn btn-success">Lưu</button>
    <a href="{{ route('admin.order_statuses.index') }}" class="btn btn-secondary">Quay lại</a>
</form>

<div class="clearfix"></div>


@endsection
