@extends('client.layouts.app') 

@section('title', 'Kết quả tìm kiếm')

@section('content')
    <div class="container mt-4">
        @if ($products->count() > 0)
            <div id="product-list">
                @include('client.components.product-list', ['products' => $products])
            </div>
        @else
            <p>Không tìm thấy sản phẩm nào phù hợp.</p>
        @endif
    </div>
@endsection
