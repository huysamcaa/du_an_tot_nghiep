@extends('admin.layouts.app')

@section('content')
<h1>Danh sách sản phẩm đã ẩn</h1>
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif
<table class="table table-bordered">
    <thead>
        <tr>
            <th>STT</th>
            <th>Tên</th>
            <th>Ảnh</th>
            <th>Thao tác</th>
        </tr>
    </thead>
    <tbody>
        @foreach($products as $index => $product)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $product->name }}</td>
            <td>
                @if($product->thumbnail)
                    <img src="{{ asset('storage/' . $product->thumbnail) }}" width="60">
                @else
                    <span>Không có ảnh</span>
                @endif
            </td>
            <td>
                <form action="{{ route('admin.products.restore', $product->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('PATCH')
                    <button class="btn btn-success btn-sm">Khôi phục</button>
                </form>
                @if(!$product->cartItems()->exists())
                    <form action="{{ route('admin.products.forceDelete', $product->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Xóa vĩnh viễn sản phẩm này?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm">Xóa cứng</button>
                    </form>
                @else
                    <button class="btn btn-danger btn-sm" disabled title="Sản phẩm đã từng có trong giỏ, không thể xóa cứng">Xóa cứng</button>
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
