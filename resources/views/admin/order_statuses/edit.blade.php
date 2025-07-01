@extends('admin.layouts.app')

@section('content')
<h1>Cập nhật trạng thái đơn hàng</h1>
<br>
<form action="{{ route('admin.order_statuses.update', $status->id) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="mb-3">
        <label for="name">Tên trạng thái</label>
        <input type="text" name="name" class="form-control" value="{{ $status->name }}" required>
    </div>

    <button type="submit" class="btn btn-success">Cập nhật</button>
    <a href="{{ route('admin.order_statuses.index') }}" class="btn btn-secondary">Quay lại</a>
</form>

@endsection
