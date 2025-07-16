@extends('client.layouts.app')

@section('content')
<section class="pageBannerSection">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="pageBannerContent text-center">
                    <h2>Giỏ Hàng</h2>
                    <div class="pageBannerPath">
                        <a href="{{ route('client.home') }}">Trang chủ</a>  >  <span>Giỏ hàng</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="cartPageSection woocommerce">
    <div class="container">
        <form id="cart-form" method="GET" action="{{ route('checkout') }}">
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
                                <th class="product-select"><input type="checkbox" id="select-all"> Chọn</th>
                                <th class="product-thumbnail">Tên sản phẩm</th>
                                <th class="product-name">&nbsp;</th>
                                <th class="product-variation">Phân loại</th>
                                <th class="product-price">Giá tiền</th>
                                <th class="product-quantity">Số lượng</th>
                                <th class="product-subtotal">Tổng tiền</th>
                                <th class="product-remove">&nbsp;</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php 
                                $total = 0; 
                                $totalQuantity = 0;
                            @endphp
                            @foreach($cartItems as $item)
                            @php 
                                $price = $item->variant->sale_price > 0 ? $item->variant->sale_price : $item->variant->price;
                                $total += $price * $item->quantity; 
                                $totalQuantity += $item->quantity;
                            @endphp
                            <tr data-id="{{ $item->product_id }}" data-price="{{ $price }}" data-quantity="{{ $item->quantity }}">
                                <td class="product-select">
                                    <input type="checkbox" name="selected_items[]" value="{{ $item->id }}" class="select-item">
                                </td>
                                <td class="product-thumbnail">
                                    <a href="{{ route('product.detail', $item->product->id) }}"><img src="{{ asset('storage/' . $item->product->thumbnail) }}" alt="Cart Item"></a>
                                </td>
                                <td class="product-name">
                                    <a href="{{ route('product.detail', $item->product->id) }}">{{ $item->product->name }}</a>
                                </td>
                                <td class="product-variant">
                                    @if($item->variant)
                                        @if($item->variant->variant_name)
                                            <p>{{$item->variant->variant_name}}</p>
                                        @else
                                        <p>Chưa cấu hình thuộc tính</p>
                                        @endif
                                    @endif
                                </td>
                                <td class="product-price">
                                    <div class="pi01Price">
                                        <ins>{{ number_format($price) }}đ</ins>
                                    </div>
                                </td>
                                <td class="product-quantity">
                                    <div class="quantity clearfix">
                                        <form class="update-cart-form" method="POST">
                                            @csrf
                                            <input type="hidden" name="cart_item_id" value="{{ $item->id }}">
                                            <button class="change-qty" type="submit" data-action="decrease">_</button>
                                            <div class="mt-2">
                                                <span class="quantity-num mx-2" data-item-id="{{ $item->id }}">{{ $item->quantity }}</span>
                                            </div>
                                            <button class="change-qty" type="submit" data-action="increase">+</button>
                                        </form>

                                    </div>
                                </td>
                                <td class="product-subtotal" data-id="{{ $item->id }}">
                                    <div class="pi01Price">
                                        <ins>{{ number_format($price * $item->quantity) }}đ</ins>
                                    </div>
                                </td>
                                <td class="product-remove">
                                    <a href="{{ route('cart.destroy', $item->id) }}" class="remove"><span></span></a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="actions">
                                <td colspan="2" class="text-start">
                                    <a href="{{ route('client.categories.index') }}" class="ulinaBTN"><span>Tiếp tục mua sắm</span></a>  
                                </td>
                                <td colspan="6" class="text-end">
                                    <button type="button" id="delete-selected" class="ulinaBTN">
                                        <span>Xoá tất cả</span>
                                    </button>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
            </div>
        </div>
        <div class="row cartAccessRow">
            <div class="col-md-6 col-lg-4"></div>
            <div class="md-6 col-lg-4"></div>
            <div class="col-lg-4">
                <div class="col-sm-12 cart_totals">
                    <table class="shop_table shop_table_responsive">
                        <tbody>
                            <tr class="cart-subtotal">
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
                                        <ins>30.000đ</ins>
                                    </div>
                                </td>
                            </tr>
                            <tr class="order-total">
                                <th>Thành tiền</th>
                                <td data-title="Subtotal">
                                    <div class="pi01Price" id="grand-total">
                                        <ins>{{ number_format($total + 30000) }}đ</ins>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="text-end mt-3">
                        <button type="submit" class="ulinaBTN" >
                            <span>TIẾN HÀNH THANH TOÁN</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        </form>
    </div>
</section>

@endsection

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function(){
    // Hàm tính lại tổng tiền dựa trên các sản phẩm được chọn
    function updateCartTotal() {
        let total = 0;
        $('.select-item:checked').each(function() {
            let row = $(this).closest('tr');
            let price = parseFloat(row.data('price'));
            let quantity = parseInt(row.find('.quantity-num').text());
            total += price * quantity;
        });
        $('#cart-total ins').text(new Intl.NumberFormat('vi-VN').format(total) + 'đ');
        $('#grand-total ins').text(new Intl.NumberFormat('vi-VN').format(total + 30000) + 'đ');
    }

    // Gọi hàm tính tổng khi trang tải
    updateCartTotal();

    // Xử lý checkbox "Chọn tất cả"
    $('#select-all').click(function() {
        $('.select-item').prop('checked', this.checked);
        updateCartTotal();
    });

    // Xử lý khi checkbox từng sản phẩm thay đổi
    $('.select-item').change(function() {
        if ($('.select-item:checked').length === $('.select-item').length) {
            $('#select-all').prop('checked', true);
        } else {
            $('#select-all').prop('checked', false);
        }
        updateCartTotal();
    });

    // Xử lý tăng/giảm số lượng
    $('.change-qty').click(function(e){
        e.preventDefault();

        let button = $(this);
        let form = button.closest('form');
        let action = button.data('action');
        let row = button.closest('tr');
        let quantitySpan = row.find('.quantity-num');

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
                quantitySpan.text(res.new_quantity); //  cập nhật đúng số lượng
                row.find('.product-subtotal ins').text(res.item_total + 'đ'); //  cập nhật tiền sản phẩm
                row.data('quantity', res.new_quantity); //  cập nhật dữ liệu dòng
                updateCartTotal(); //  cập nhật tổng giỏ
                } else {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Thông báo',
                        text: res.message || 'Không cập nhật được sản phẩm',
                        confirmButtonText: 'OK'
                    });
                }
            },
            error: function(err){
                alert('Đã xảy ra lỗi.');
                console.log(err);
            }
        });
    });

    // Kiểm tra trước khi gửi form thanh toán và gỡ lỗi
    $('#cart-form').submit(function(e) {
        let selectedItems = $('.select-item:checked').map(function() {
            return $(this).val();
        }).get();
        
        if (selectedItems.length === 0) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Thông báo',
                text: 'Vui lòng chọn ít nhất một sản phẩm để thanh toán.',
                confirmButtonText: 'OK'
            });
        } else {
            console.log('Selected items:', selectedItems); // Gỡ lỗi
        }
    });
    // Xác nhận khi bấm nút xoá từng sản phẩm
$('.product-remove .remove').click(function(e) {
    e.preventDefault();
    let url = $(this).attr('href');

    Swal.fire({
        title: 'Bạn có chắc chắn muốn xoá sản phẩm này?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Xoá',
        cancelButtonText: 'Huỷ'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = url;
        }
    });
});
// Xoá nhiều sản phẩm được chọn
$('#delete-selected').click(function() {
    let selectedIds = $('.select-item:checked').map(function() {
        return $(this).val();
    }).get();

    if (selectedIds.length === 0) {
        Swal.fire({
            icon: 'warning',
            title: 'Thông báo',
            text: 'Vui lòng chọn ít nhất một sản phẩm để xoá.',
            confirmButtonText: 'OK'
        });
        return;
    }

    Swal.fire({
        title: 'Bạn có chắc chắn muốn xoá các sản phẩm đã chọn?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Xoá tất cả',
        cancelButtonText: 'Huỷ'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '{{ route("cart.deleteSelected") }}', // bạn sẽ tạo route này
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    ids: selectedIds
                },
                success: function(res) {
                    if (res.success) {
                        location.reload();
                    } else {
                        Swal.fire('Lỗi', res.message || 'Không xoá được sản phẩm', 'error');
                    }
                },
                error: function(err) {
                    console.log(err);
                    Swal.fire('Lỗi', 'Đã xảy ra lỗi khi xoá sản phẩm.', 'error');
                }
            });
        }
    });
});

});
</script>