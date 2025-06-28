@extends('client.layouts.app')

@section('content')
<section class="pageBannerSection">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="pageBannerContent text-center">
                            <h2>Giỏ Hàng</h2>
                            <div class="pageBannerPath">
                                <a href="{{ route('client.home') }}">Trang chủ</a>&nbsp;&nbsp;&gt;&nbsp;&nbsp;<span>Giỏ hàng</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
</section>
<section class="cartPageSection woocommerce">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="cartHeader">
                            <h3>Các sản phẩm</h3>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <table class="shop_table cart_table">
                            <thead>
                                <tr>
                                    <th class="product-thumbnail">Tên sản phẩm</th>
                                    <th class="product-name">&nbsp;</th>
                                    <th class="product-variation">Phân loại</th>
                                    <th class="product-price">Giá tiền</th>
                                    <th class="product-quantity">Số lượng</th>
                                    <th class="product-subtotal">Tổng tiền</th>
                                    <th class="product-remove">&nbsp;</th>
                                </tr>
                            </thead>
                                @php 
                                    $total = 0; 
                                    $totalQuantity = 0;
                                @endphp
                                @foreach($cartItems as $item)
                                @php 
                                    $price = $item->product->sale_price ?? $item->product->price; // Lấy sale_price nếu có, nếu không lấy price
                                    $total += $price * $item->quantity; 
                                    $totalQuantity += $item->quantity;
                                @endphp
                            <tbody>
                                <tr data-id="{{ $item->product_id }}">
                                    <td class="product-thumbnail">
                                        <a href="shop_details1.html"><img src="{{ asset('storage/' . $item->product->thumbnail) }}" alt="Cart Item"></a>
                                    </td>
                                    <td class="product-name">
                                        <a href="shop_details1.html">{{ $item->product->name }}</a>
                                    </td>
                                    <td class="product-variant">
                                    @if($item->variant)
                                        @foreach($item->variant->attributeValues as $attributeValue)
                                            <p>{{ $attributeValue->attribute->name }}: {{ $attributeValue->value }}</p>
                                        @endforeach
                                    @else
                                        <p>Chưa cấu hình thuộc tính</p>
                                    @endif
                                    </td>
                                    <td class="product-price">
                                        <div class="pi01Price">
                                            <ins>{{ number_format($price) }}đ</ins>
                                        </div>
                                    </td>
                                    <td class="product-quantity">
                                        <div class="quantity clearfix">
                                            <form class="update-cart-form mt-3" method="POST">
                                                @csrf
                                                <input type="hidden" name="cart_item_id" value="{{ $item->id }}">
                                                <button class="change-qty" type="submit" data-action="decrease">_</button>
                                                <span class="quantity-num">{{ $item->quantity }}</span>
                                                <button class="change-qty" type="submit" data-action="increase">+</button>
                                            </form>
                                        </div>
                                    </td>
                                    <td class="product-subtotal" data-id="{{$item->id}}">
                                        <div class="pi01Price">
                                            <ins>{{ number_format($price * $item->quantity) }}đ</ins>
                                        </div>
                                    </td>
                                    <td class="product-remove">
                                        <a href="{{ route('cart.destroy', $item->id) }}" class="remove"><span>
                                        </span></a>
                                    </td>
                            </tbody>
                            @endforeach
                            <tfoot>
                                <tr class="actions">
                                    <td colspan="2" class="text-start">
                                        <a href="shop_full_width.html" class="ulinaBTN"><span>Tiếp tục mua sắm</span></a>  
                                    </td>
                                    <td colspan="4" class="text-end">
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <div class="row cartAccessRow">
                    <div class="col-md-6 col-lg-4">
                    </div>
                    <div class="col-md-6 col-lg-4">

                    </div>
                    <div class="col-lg-4">
                        <div class="col-sm-12 cart_totals">
                            <table class="shop_table shop_table_responsive">
                                <tbody><tr class="cart-subtotal">
                                    <th>Tổng tiền hàng</th>
                                    <td data-title="Subtotal">
                                        <div class="pi01Price" id="cart-total">
                                            <ins>{{ number_format($total) }}đ</ins>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="cart-shipping">
                                    <th>Phí vận chuyển</th>
                                    <td data-title="Subtotal">
                                        <div class="pi01Price">
                                            <ins>30,000đ</ins>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="order-total">
                                    <th>Thành tiền</th>
                                    <td data-title="Subtotal">
                                        <div class="pi01Price" id="grand-total">
                                            <ins>{{ number_format($total + 30000)}}đ</ins>
                                        </div>
                                    </td>
                                </tr>
                            </tbody></table>
                            <a href="{{ route('checkout') }}" class="checkout-button ulinaBTN">
                                <span>Tiến hành thanh toán</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
</div>    
</div>

@endsection
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>

<script>
$(document).ready(function(){
    $('.change-qty').click(function(e){
        e.preventDefault();

        let button = $(this);
        let form = button.closest('form');
        let action = button.data('action');
        let cartItemId = form.find('input[name="cart_item_id"]').val();


        $.ajax({
            url: '{{ route("cart.update") }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                cart_item_id: cartItemId,
                quantity: action
            },
            success: function(res){
                if(res.success){
                    form.find('.quantity-num').text(res.new_quantity);
                    form.closest('tr').find('.product-subtotal ins').text(res.item_total + 'đ');
                    $('#cart-total ins').text(res.cart_total + 'đ');
                    $('#grand-total').text(res.grand_total + 'đ');
                } else {
                    alert('Không cập nhật được sản phẩm');
                }
            },
            error: function(err){
                alert('Đã xảy ra lỗi.');
                console.log(err);
            }
        });
    });
});

</script>
