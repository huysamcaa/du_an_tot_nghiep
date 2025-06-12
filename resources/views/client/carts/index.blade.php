@extends('client.layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Giỏ hàng của bạn</h2>
    <div class="card shadow-sm">
    @php 
        $total = 0; 
        $totalQuantity = 0;
    @endphp
    @foreach($cartItems as $item)
    @php 
        $total += $item->product->price * $item->quantity; 
        $totalQuantity += $item->quantity;
    @endphp
    <div class="border rounded my-3 mx-3 p-3 bg-white">
        <div class="d-flex align-items-start mt-3" data-id="{{ $item->product_id }}">
            <div class="me-3">
                <img src="{{ asset($item->product->thumbnail) }}" width="80" alt="ảnh sản phẩm">
            </div>
            <div class="flex-grow-1">
                <div class="row">
                    <div class="col-3">
                        <h5 class="mb-1">{{ $item->product->name }}</h5>
                    </div>
                    <div class="col-2">
                        <p class="text-muted small">Phân loại hàng: {{$item->variant}}</p>
                    </div>
                    <div class="col-2">
                        <strong class=" me-3">{{ number_format($item->product->price) }}đ</strong>
                    </div>
                    <div class="col-2">
                        <div class="d-flex align-items-center">
                            <form action="{{ route('cart.update') }}" method="POST">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $item->product_id }}">
                                <button class="btn btn-outline-secondary btn-sm px-2 decrease-btn" type="submit" name="quantity" value="{{ max(1, $item->quantity - 1) }}">-</button>
                                <span class="mx-3 quantity">{{ $item->quantity }}</span>
                                <button class="btn btn-outline-secondary btn-sm px-2 increase-btn" type="submit" name="quantity" value="{{ $item->quantity + 1 }}">+</button>
                            </form>
                        </div>
                    </div>
                    <div class="col-2">
                            <p class="mb-0 text-danger item-total"><strong>{{ number_format($item->product->price * $item->quantity) }}đ</strong></p>
                    </div>
                    <div class="col-1">
                        <a href="{{ route('cart.destroy', $item->id) }}" onclick="return confirm('Xoá sản phẩm này?')" class="text-danger small">Xoá</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
    <div class="d-flex justify-content-end mt-3 border-top ">
        <h5 class="mx-2 my-3">Tổng cộng({{$totalQuantity}} sản phẩm): <span id="cart-total"  >{{ number_format($total) }}đ</span></h5>
        <a href="" class="btn btn-info px-3 py-3">Mua hàng ngay</a>
    </div>
</div>    
</div>

@endsection

@push('scripts')
<script>
    document.querySelectorAll('.increase-btn, .decrease-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const container = this.closest('[data-id]');
            const productId = container.dataset.id;
            const quantitySpan = container.querySelector('.quantity');
            let quantity = parseInt(quantitySpan.textContent);

            if(this.classList.contains('increase-btn')) {
                quantity++;
            }else if(this.classList.contains('decrease-btn') && quantity > 1){
                quantity--;
            }

            fetch("{{ route('cart.update') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{csrf_token() }}'
                },
                body: JSON.stringify({product_id: productId, quantity: quantity})
            })
            .then(res => res.json())
            .then(data =>{
                if(data.success) {
                    quantitySpan.textContent = data.quantity;
                    container.querySelector('.item-total strong').textContent = data.item_total + 'đ';
                    document.getElementById('cart-total').textContent = data.cart_total + 'đ';
                    document.querySelector('h5.mb-0').textContent =
                        `Tổng cộng (${data.total_quantity} sản phẩm): ${data.cart_total}đ`;
                }
            });
        });
    });
    document.querySelectorAll('.pi01Cart').forEach(button => {
        button.addEventListener('click', function(e) {
            const productId = this.closest('[data-product-id]').dataset.productId;

            fetch("{{ route('cart.add') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    product_id: productId
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    alert('Sản phẩm đã được thêm vào giỏ hàng!');
                } else {
                    alert('Thêm vào giỏ hàng thất bại!');
                }
            })
            .catch(err => {
                console.error(err);
                alert('⚠ Đã xảy ra lỗi khi thêm vào giỏ hàng!');
            });
        });
    });
</script>
@endpush