@extends('admin.layouts.app')

@section('content')
<div class="animated fadeIn">
<h1>Chỉnh sửa khuyến mãi</h1>
<form action="{{route('admin.promotions.update',$promotion->id)}}" method="post">
    @csrf
    @method('put')
    <div class="mb-3">
        <label for="" name="title" class="">Tiêu đề: </label>
        <input type="text" name="title" class="form-control" value="{{old('title',$promotion->title)}}" id="" required>
    </div>
    <div class="mb-3">
        <label for="discount_percent" name="discount_percent" class="">Phần trăm giảm giá: </label>
        <input type="number" name="discount_percent" class="form-control" value="{{old('discount_percent',$promotion->discount_percent)}}" id="" required>
    </div>
<div class="form-group">
    <label for="code">Mã khuyến mãi</label>
    <input type="text" class="form-control" name="code" value="{{ old('code', $promotion->code) }}" placeholder="VD: SUMMER20">
</div>

    <div class="mb-3">
        <label for="start_date" name="start_date" class="">Ngày bắt đầu: </label>
        <input type="date" name="start_date" class="form-control" value="{{old('start_date',$promotion->start_date)}}" id="" required>
    </div>
    <div class="mb-3">
        <label for="" name="end_date" class=""> Ngày kết thúc: </label>
        <input type="end_date" name="end_date" class="form-control" id="" value="{{old('end_date',$promotion->end_date)}}" required>
    </div>
    <button type="submit" class="btn btn-success">Cập nhật</button>
    <a href="{{route('admin.promotions.index')}}" class="btn btn-primary">Quay lại</a>
</form>
@endsection
