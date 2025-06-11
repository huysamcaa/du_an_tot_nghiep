@extends('admin.layouts.app')

@section('content')
<div class="animated fadeIn">
    <h1>Danh sách khuyến mãi</h1>
    <a href="{{route('admin.promotions.create')}}" class="btn btn-success">Thêm khuyến mãi</a>
    @if (session('success'))
    <div class="alert alert-success">{{session('success')}}</div>
    @endif
    <div class="card-body">
    <table class=" table table-bordered">
        <thead>
            <tr>
                <th>Tiêu đề</th>
                <th>Phầm trăm giảm giá</th>
                <th>Ngày Bắt đầu</th>
                <th>Ngày Kết thúc</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($promotions as $promotion )
            <tr>
                <td>{{$promotion->title}}</td>
                <td>{{ rtrim(rtrim($promotion->discount_percent, '0'), '.') }}%</td>
                <td>{{$promotion->start_date}}</td>
                <td>{{$promotion->end_date}}</td>
                <td>
                <a href="{{route('admin.promotions.edit',$promotion->id)}}" class="btn  btn-warning">Sửa</a>
                <form action="{{route('admin.promotions.destroy',$promotion->id)}}" method="post" class="d-inline"
                     onsubmit="return confirm('Bạn chắc chắn muốn xóa mã khuyến mãi ?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn  btn-danger ">Xóa</button>
                </form>

                </td>

            </tr>
            @endforeach

        </tbody>
    </table>
    </div>
@endsection
