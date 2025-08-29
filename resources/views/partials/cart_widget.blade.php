@foreach ($cartItems as $item)
    <div class="cartWidgetProduct">
        <img src="{{ asset('storage/' . ($item->variant->thumbnail ?? $item->product->thumbnail)) }}"
            alt="ảnh" style="height:50px; width:50px"/>
        <a
            href="{{ route('product.detail', ['id' => $item->product->id]) }}" style="font-size: small;">{{ $item->product->name }}</a>
        <div class="cartProductPrice clearfix">
            @php
            $variant = $item->variant;
            $price = ($variant && $variant->exists)
                ? ($variant->is_sale ? $variant->sale_price : $variant->price)
                : ($item->product->is_sale ? $item->product->sale_price : $item->product->price);

            @endphp
                <span class="price">{{ number_format($price) }}đ</span>
        </div>
        <a href="{{ route('cart.destroy', $item->id) }}"
            class="cartRemoveProducts"><i class="fa-solid fa-xmark"></i></a>
    </div>
@endforeach
<div class="totalPrice" id="cart-total">Tổng tiền: <span
        class="price">{{ number_format($total) }}đ</span></div>
<div class="cartWidgetBTN clearfix">
    <a class="checkout" href="{{ route('checkout') }}">Giỏ hàng</a>
</div>
