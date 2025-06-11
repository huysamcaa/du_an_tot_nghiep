@extends('admin.layouts.app')

@section('content')
<div class="animated fadeIn">
   <div class="row mb-3">
    <div class="col-6">
    <h2>Quản lí nhà sản xuất</h2><br>

     <a href="{{route('admin.manufacturers.create')}}" class="btn btn-success">Thêm mới nhà sản xuất</a></div>
    <div class="">

    </div>
   </div>
<div class="card">
    <div class="card-body">
        <table class="table">
            <thead>
                <tr>
                <th>STT</th>
                <th>Tên</th>
                <th>Slug</th>
                <th>Logo</th>
                <th>Trạng thái</th>
                 <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($manufacturers as $m )
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{$m->name}}</td>
                    <td>{{$m->slug}}</td>
                    <td>@if ($m->logo_path)
                        <img src="{{asset('storage/'.$m->logo_path)}}" alt="logo" width="100px">
                    @endif</td>
                    <td><span class="badge{{$m->is_active?'bg_success' : 'bg-secondary'}}">
                    {{$m->is_active?'Hiển thị':'Ẩn'}}
                    </span></td>
                    <td>
                        <a href="{{route('admin.manufacturers.edit', $m)}}" class=" btn  btn-warning">Sửa</a>
                        <form class="d-inline"  method="post" action="{{route('admin.manufacturers.destroy', $m)}}" onsubmit="return confirm('Xóa bản ghi này?')">
                            @csrf @method('DELETE')
                            <button class="btn  btn-danger">Xóa</button>
                        </form>
                    </td>
                </tr>

                @endforeach
            </tbody>
        </table>
        {{$manufacturers->links()}}
    </div>
</div>
@endsection
