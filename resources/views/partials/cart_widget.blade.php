@foreach ($cartItems as $item)
    <div class="cartWidgetProduct">
        <img src="{{ asset('storage/' . ($item->variant->thumbnail ?? $item->product->thumbnail)) }}"
            alt="ảnh" style="height:100%; width:auto"/>
        <a
            href="{{ route('product.detail', ['id' => $item->product->id]) }}">{{ $item->product->name }}</a>
        <div class="cartProductPrice clearfix">
            @php
            $variant = $item->variant;
            $price = ($variant && $variant->exists)
                ? (($variant->sale_price > 0 && $variant->sale_price < $variant->price) ? $variant->sale_price : $variant->price)
                : $item->product->price;

            @endphp
                <span class="price">{{ number_format($price) }}đ</span>
        </div>
        <a href="{{ route('cart.destroy', $item->id) }}"
            class="cartRemoveProducts"><i class="fa-solid fa-xmark"></i></a>
    </div>
@endforeach
<div class="totalPrice" id="cart-total">Subtotal: <span
        class="price">{{ number_format($total) }}đ</span></div>
<div class="cartWidgetBTN clearfix">
    <a class="cart" href="{{ route('cart.index') }}">View Cart</a>
    <a class="checkout" href="{{ route('checkout') }}">Checkout</a>
</div>