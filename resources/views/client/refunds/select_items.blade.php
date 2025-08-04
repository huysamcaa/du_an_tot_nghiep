@extends('client.layouts.app')

@section('title', 'Chọn sản phẩm lỗi — Đơn #' . $order->code)

@section('content')
<section class="pageBannerSection">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="pageBannerContent text-center">
                    <h2>Chọn sản phẩm lỗi</h2>
                    <div class="pageBannerPath">
                        <a href="{{ route('client.home') }}">Trang chủ</a>&nbsp;&nbsp;>&nbsp;&nbsp;<span>Hoàn đơn #{{ $order->code }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="cartPageSection woocommerce py-5">
    <div class="container">
        <form action="{{ route('refunds.confirm_items', $order->id) }}" method="POST">
            @csrf

            @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            @if($order->items->count())
            <table class="shop_table cart_table">
                <thead>
                    <tr>
                        <th class="product-select">
                            <input type="checkbox" id="select-all"> Chọn
                        </th>
                        <th class="product-thumbnail">Ảnh</th>
                        <th class="product-name">Tên sản phẩm</th>
                        <th class="product-variation">Phân loại</th>
                        <th class="product-category">Danh mục</th>
                        <th class="product-status">Trạng thái</th>
                        <th class="product-quantity">Số lượng</th>
                        <th class="product-price">Giá</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                    @for($i = 0; $i < $item->quantity; $i++)
                        <tr>
                            <td class="product-select">
                                <input
                                    type="checkbox"
                                    name="items[]"
                                    class="select-item"
                                    value="{{ $item->id }}_{{ $i }}">
                            </td>
                            <td class="product-thumbnail">
                                <img src="{{ asset('storage/' . $item->product->thumbnail) }}"
                                    alt="{{ $item->product->name }}"
                                    style="width:60px; height:60px; object-fit:cover; border-radius:6px;">
                            </td>
                            <td class="product-name">
                                {{ $item->product->name }}
                            </td>
                            <td class="product-variation">
                                @if($item->variant && $item->variant->attributeValues->count())
                                {{ $item->variant->attributeValues->pluck('value')->implode(' - ') }}
                                @else
                                <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="product-category">
                                {{ $item->product->category->name ?? 'Không có' }}
                            </td>
                            <td class="product-status">
                                {{ $order->status }}
                            </td>
                            <td class="product-quantity">
                                x1
                            </td>
                            <td class="product-price">
                                <div class="pi01Price">
                                    <ins>{{ number_format($item->price) }}đ</ins>
                                </div>
                            </td>
                        </tr>
                        @endfor
                        @endforeach
                </tbody>
            </table>

            @error('items')
            <div class="text-danger mt-2">{{ $message }}</div>
            @enderror

            <div class="text-end mt-4">
                <button type="submit" class="ulinaBTN"><span>TIẾP TỤC</span></button>
            </div>

            @else
            <p>Không có sản phẩm nào trong đơn hàng này.</p>
            @endif
        </form>
    </div>
</section>
@endsection

@push('scripts')
<script>
    // Chọn tất cả
    document.getElementById('select-all').addEventListener('change', function() {
        document.querySelectorAll('.select-item').forEach(cb => cb.checked = this.checked);
    });
</script>
@endpush
