@extends('admin.layouts.app')

@section('content')
<div class="animated fadeIn">
<h1>Thêm khuyến mãi mới</h1>
<form action="{{route('admin.promotions.store')}}" method="post">
    @csrf
    <div class="card-body">
    <div class="mb-3">
        <label for="" name="title" class="">Tiêu đề: </label>
        <input type="text" name="title" class="form-control" id="" required>
    </div>
    <div class="mb-3">
        <label for="discount_percent" name="discount_percent" class="">Phần trăm giảm giá: </label>
        <input type="number" name="discount_percent" class="form-control" id="" required>
    </div>

    <div class="mb-3">
        <label for="start_date" name="start_date" class="">Ngày bắt đầu: </label>
        <input type="date" name="start_date" class="form-control" id="" required>
    </div>
    <div class="mb-3">
        <label for="" name="end_date" class=""> Ngày kết thúc: </label>
        <input type="date" name="end_date" class="form-control" id="" required>
    </div>
    <button type="submit" class="btn btn-primary">Thêm mã giảm giá</button>
    <a href="{{route('admin.promotions.index')}}" class="btn btn-warning">Quay lại</a>
</form>
</div>
@endsection
