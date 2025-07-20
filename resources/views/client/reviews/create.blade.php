@extends('client.layouts.app')

@section('content')
<div class="container">
    <h2>Viết Đánh Giá</h2>

    <form action="{{ route('client.reviews.store') }}" method="POST">
        @csrf

        <input type="hidden" name="product_id" value="{{ $product->id }}">
        <input type="hidden" name="order_id" value="{{ $order->id }}">

        <div class="form-group">
            <label>Sản phẩm:</label>
            <input type="text" class="form-control" value="{{ $product->name }}" readonly>
        </div>

        <div class="form-group">
            <label>Đơn hàng:</label>
            <input type="text" class="form-control" value="{{ $order->id }}" readonly>
        </div>

        <div class="form-group">
            <label>Xếp hạng:</label>
            <select name="rating" class="form-control" required>
                @for ($i = 1; $i <= 5; $i++)
                    <option value="{{ $i }}">{{ $i }} Sao</option>
                @endfor
            </select>
        </div>

        <div class="form-group">
            <label>Nội dung đánh giá:</label>
            <textarea name="review_text" class="form-control" rows="5" required>{{ old('review_text') }}</textarea>
        </div>

        <button type="submit" class="btn btn-success">Gửi đánh giá</button>
        <a href="{{ route('client.reviews.index') }}" class="btn btn-secondary">Hủy</a>
    </form>
</div>
@endsection
