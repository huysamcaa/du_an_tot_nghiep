@extends('admin.layouts.app')

@section('content')
<div class="container">
    <h2>Chi tiết sản phẩm</h2>
    <table class="table table-bordered">
        <tr>
            <th>ID</th>
            <td>{{ $product->id }}</td>
        </tr>
        <tr>
            <th>Tên sản phẩm</th>
            <td>{{ $product->name }}</td>
        </tr>
        <tr>
            <th>Giá gốc</th>
            <td>{{ number_format($product->price, 0, ',', '.') }} đ</td>
        </tr>
        <tr>
            <th>Ảnh đại diện</th>
            <td>
                @if($product->thumbnail)
                    <img src="{{ asset('storage/' . $product->thumbnail) }}" width="80">
                @endif
            </td>
        </tr>

        <tr>
            <th>Ảnh chi tiết</th>
            <td>
                @if($product->galleries && $product->galleries->count())
                    @foreach($product->galleries as $gallery)
                        <img src="{{ asset('storage/' . $gallery->image) }}" width="80" class="img-thumbnail me-2 mb-2">
                    @endforeach
                @else
                    <em>Không có ảnh chi tiết.</em>
                @endif
            </td>
        </tr>


        <tr>
            <th>Mô tả ngắn</th>
            <td>{{ $product->short_description }}</td>
        </tr>
        <tr>
            <th>Mô tả chi tiết</th>
            <td>{{ $product->description }}</td>
        </tr>
    </table>

    <h4 class="mt-4">Danh sách biến thể</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Ảnh</th>
                <th>SKU</th>
                <th>Giá</th>
                <th>Số lượng</th>
                <th>Màu</th>
                <th>Size</th>
            </tr>
        </thead>
        <tbody>
            @foreach($product->variants as $variant)
                <tr>
                    <td>
                        @if($variant->thumbnail)
                            <img src="{{ asset('storage/' . $variant->thumbnail) }}" width="60">
                        @endif
                    </td>
                    <td>{{ $variant->sku ?? '' }}</td>
                    <td>{{ number_format($variant->price, 0, ',', '.') }} đ</td>
                    <td>{{ $variant->stock }}</td>
                    <td>{{ optional($variant->color)->value }}</td>
                    <td>{{ optional($variant->size)->value }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Quay lại</a>
</div>
@endsection
