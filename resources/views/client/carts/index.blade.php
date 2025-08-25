@extends('client.layouts.app')

@section('content')
<style>
    .pageBannerSection {
        background:#ECF5F4;
        padding: 10px 0;
    }
    .pageBannerContent h2 {

        font-size: 72px;
        color:#52586D;
        font-family: 'Jost', sans-serif;
    }
    .pageBannerPath a {
        color: #007bff;
        text-decoration: none;
    }

    .pageBannerSection {
        background:#ECF5F4;
        padding: 10px 0;
    }
    .pageBannerContent h2 {

        font-size: 72px;
        color:#52586D;
        font-family: 'Jost', sans-serif;
    }
    .pageBannerPath a {
        color: #007bff;
        text-decoration: none;
    }
    .checkoutPage {
    margin-top: 0 !important;
    padding-top: 0 !important;

}
.pageBannerSection {
    padding: 20px 0;
    min-height: 10px;
}

.pageBannerSection .pageBannerContent h2 {
    font-size: 38px;
    margin-bottom: 10px;
}
.pageBannerPath {
    font-size: 14px;
}

</style>
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
                                <th class="product-select" style="width:10%"><input type="checkbox" id="select-all" > Chọn</th>
                                <th class="product-name">Ảnh</th>
                                <th class="product-thumbnail">Tên sản phẩm</th>
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
                                if ($item->variant) {
                                    // Nếu có biến thể
                                    $price = $item->variant->is_sale
                                        ? $item->variant->sale_price
                                        : $item->variant->price;

                                    $isOutOfStock = $item->variant->stock == 0;
                                } else {
                                    // Nếu không có biến thể
                                    $price = $item->product->is_sale
                                        ? $item->product->sale_price
                                        : $item->product->price;

                                    // Nếu bảng products có cột stock thì dùng, không thì để false
                                    $isOutOfStock = property_exists($item->product, 'stock')
                                        ? $item->product->stock == 0
                                        : false;
                                }

                                $total += $price * $item->quantity;
                                $totalQuantity += $item->quantity;

                                $isNotAvailable = !$item->product || !$item->product->is_active;
                                $disableItem = $isOutOfStock || $isNotAvailable;
                            @endphp
                            <tr data-id="{{ $item->product_id }}" data-price="{{ $price }}" data-quantity="{{ $item->quantity }}" class="{{$disableItem ? 'out-of-stock' : ''}}">

                                <td class="product-select">
                                    <input type="checkbox" name="selected_items[]" value="{{ $item->id }}" class="select-item"{{$disableItem ? 'disabled' : ''}}>
                                </td>
                                <td class="product-thumbnail">
                                    <a href="{{ route('product.detail', $item->product->id) }}"><img src="{{ asset('storage/' . ($item->variant->thumbnail ?? $item->product->thumbnail)) }}" style="height:100%; width:auto" alt="Cart Item"></a>
                                </td>
                                <td class="product-name">
                                    <a href="{{ route('product.detail', $item->product->id) }}">{{ $item->product->name }}</a>
                                    @if (!$item->product || !$item->product->is_active)
                                    <div class="cart-item disabled text-danger">
                                        Sản phẩm không tồn tại
                                    </div>
                                    @elseif($isOutOfStock)
                                        <span class="badge bg-danger small">Hết hàng</span>
                                    @endif
                                </td>
                                <td class="product-variant">
                                    @if($item->variant )
                                        @if($item->variant && $item->variant->attributeValues->count() > 0)
                                            <p>{{$item->variant->attributeValues->pluck('value')->implode(' / ')}}</p>
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
            @if(count($cartItems) > 0)
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
                            <button type="submit" class="ulinaBTN" id="checkout-btn">
                                <span>TIẾN HÀNH THANH TOÁN</span>
                            </button>

                    </div>
                </div>
            </div>
            @endif
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
$(document).ready(function () {
    const $selectAll = $('#select-all');
    const $selectItems = $('.select-item');
    const $checkoutBtn = $('#checkout-btn');

    // Hàm cập nhật tổng tiền đơn hàng
    function updateCartTotal() {
        let total = 0;
        let selectedCount = 0;
        let hasValidItem = false;
        // Duyệt qua từng sản phẩm được chọn
        $selectItems.each(function () {
            if (this.checked && !$(this).is(':disabled')) {
                const row = $(this).closest('tr');
                const price = parseFloat(row.data('price'));
                const qty = parseInt(row.find('.quantity-num').text());
                total += price * qty;
                selectedCount++;
                hasValidItem = true;
            }
        });
        $('#checkout-btn').prop('disabled', !hasValidItem);
        // Cập nhật hiển thị tổng tiền và tổng đơn
        $('#cart-total ins').text(new Intl.NumberFormat('vi-VN').format(total) + 'đ');
        $('#grand-total ins').text(new Intl.NumberFormat('vi-VN').format(total + 30000) + 'đ');
    }

    // Gọi hàm tính tổng khi mới tải trang
    updateCartTotal();

    // Khi bấm "Chọn tất cả", thay đổi trạng thái của từng checkbox sản phẩm
    $selectAll.on('click', function () {
        $selectItems.prop('checked', this.checked);
        updateCartTotal();
    });

    // Khi thay đổi checkbox của từng sản phẩm
    $selectItems.on('change', function () {
        // Nếu tất cả đều được chọn thì đánh dấu lại "Chọn tất cả"
        $selectAll.prop('checked', $selectItems.length === $('.select-item:checked').length);
        updateCartTotal();
    });

    // Xử lý khi bấm nút tăng/giảm số lượng
    $('.change-qty').on('click', function (e) {
        e.preventDefault();

        const $btn = $(this);
        const $form = $btn.closest('form');
        const action = $btn.data('action'); // "increase" hoặc "decrease"
        const row = $btn.closest('tr');
        const $quantitySpan = row.find('.quantity-num');
        const cartItemId = $form.find('input[name="cart_item_id"]').val();

        // Gửi AJAX đến server để cập nhật số lượng
        $.post('{{ route("cart.update") }}', {
            _token: '{{ csrf_token() }}',
            cart_item_id: cartItemId,
            quantity: action
        }).done(function (res) {
            if (res.success) {
                // Cập nhật lại số lượng và tổng tiền dòng đó
                $quantitySpan.text(res.new_quantity);
                row.find('.product-subtotal ins').text(res.item_total + 'đ');
                row.data('quantity', res.new_quantity);
                updateCartTotal();
            } else {
                // Hiện thông báo nếu có lỗi
                Swal.fire('Thông báo', res.message || 'Không cập nhật được sản phẩm', 'warning');
            }
        }).fail(function () {
            Swal.fire('Lỗi', 'Đã xảy ra lỗi khi cập nhật số lượng.', 'error');
        });
    });

    // Khi bấm nút "Tiến hành thanh toán"
    $('#cart-form').on('submit', function (e) {
        const selected = $('.select-item:checked');
        if (selected.length === 0) {
            e.preventDefault(); // Chặn submit nếu không chọn sản phẩm nào
            Swal.fire('Thông báo', 'Vui lòng chọn ít nhất một sản phẩm để thanh toán.', 'warning');
        }
    });

    // Khi bấm nút xoá 1 sản phẩm
    $('.product-remove .remove').on('click', function (e) {
        e.preventDefault();
        const url = $(this).attr('href');

        // Hiện hộp thoại xác nhận xoá
        Swal.fire({
            title: 'Bạn có chắc chắn muốn xoá sản phẩm này?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Xoá',
            cancelButtonText: 'Huỷ'
        }).then(result => {
            if (result.isConfirmed) {
                window.location.href = url; // Nếu xác nhận thì chuyển sang route xoá
            }
        });
    });

    // Khi bấm nút "Xoá tất cả" các sản phẩm đã chọn
    $('#delete-selected').on('click', function () {
        const selectedIds = $('.select-item:checked').map(function () {
            return this.value;
        }).get();

        if (selectedIds.length === 0) {
            Swal.fire('Thông báo', 'Vui lòng chọn ít nhất một sản phẩm để xoá.', 'warning');
            return;
        }

        // Hiện xác nhận xoá nhiều
        Swal.fire({
            title: 'Bạn có chắc chắn muốn xoá các sản phẩm đã chọn?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Xoá tất cả',
            cancelButtonText: 'Huỷ'
        }).then(result => {
            if (result.isConfirmed) {
                // Gửi AJAX để xoá nhiều
                $.post('{{ route("cart.deleteSelected") }}', {
                    _token: '{{ csrf_token() }}',
                    ids: selectedIds
                }).done(res => {
                    if (res.success) {
                        location.reload(); // Reload lại trang nếu xoá thành công
                    } else {
                        Swal.fire('Lỗi', res.message || 'Không xoá được sản phẩm', 'error');
                    }
                }).fail(() => {
                    Swal.fire('Lỗi', 'Đã xảy ra lỗi khi xoá sản phẩm.', 'error');
                });
            }
        });
    });
});
</script>
