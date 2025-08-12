@extends('client.layouts.app')

@section('content')
<!-- BEGIN: Page Banner Section -->
<section class="pageBannerSection">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="pageBannerContent text-center">
                    <h2>Sản Phẩm Yêu Thích</h2>
                    <div class="pageBannerPath">
                        <a href="{{route('client.home')}}">Trang chủ</a>&nbsp;&nbsp;>&nbsp;&nbsp;<span>Sản Phẩm Yêu Thích</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- END: Page Banner Section -->
<section class="cartPageSection woocommerce">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="cartHeader">
                    <h3>Sản phẩm yêu thích</h3>
                </div>
            </div>
            <div class="col-lg-12">
                @if($wishlists->count() > 0)
                <table class="shop_table cart_table wisthlist_table">
                    <thead>
                        <tr>
                            <th class="product-thumbnail">Tên sản phẩm</th>
                            <th class="product-name">&nbsp;</th>
                            <th class="product-price">Giá</th>
                            <th class="product-availability">Trạng thái</th>
                            <th class="product-addtocart">&nbsp;</th>
                            <th class="product-remove">&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($wishlists as $wishlist)
                            <tr>
                                <td class="product-thumbnail">
                                    <a href="{{ route('product.detail', $wishlist->product->id) }}"><img src="{{ asset('storage/' . $wishlist->product->thumbnail) }}" alt="img"/></a>
                                </td>
                                <td class="product-name">
                                    <a href="{{ route('product.detail', $wishlist->product->id) }}">{{$wishlist->product->name}}</a>
                                </td>
                                <td class="product-price">
                                    <div class="pi01Price">
                                        @if($wishlist->product->sale_price > 0 && $wishlist->product->sale_price < $wishlist->product->price)
                                            <ins>{{ number_format($wishlist->product->sale_price, 0, ',', '.') }}₫</ins>
                                            <del>{{ number_format($wishlist->product->price, 0, ',', '.') }}₫</del>
                                        @else
                                            <ins>{{ number_format($wishlist->product->price, 0, ',', '.') }}₫</ins>
                                        @endif
                                    </div>
                                </td>
                                <td class="product-availability">
                                    @php
                                        $variantStock = $wishlist->product->variants->sum('stock');
                                    @endphp
                                    {{ $variantStock > 0 ? 'Còn hàng' : 'Hết hàng' }}
                                </td>

                                <td class="product-addtocart">
                                    <a href="{{route('product.detail', $wishlist->product->id)}}" class="ulinaBTN"><span>Xem chi tiết</span></a>
                                </td>
                                <td class="product-remove">
                                    <a href="javascript:void(0);" class="remove remove-wishlist" data-url="{{ route('wishlist.destroy', $wishlist->id) }}"><span></span></a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                    <div class="text-center">
                        <h4>Danh sách yêu thích trống</h4>
                        <p class="m-3">Bạn chưa có sản phẩm nào trong danh sách yêu thích.</p>
                        <a href="{{ route('client.categories.index') }}" class="ulinaBTN px-4"><span>Tiếp tục mua sắm</span></a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function(){
        const removeBtn = document.querySelectorAll('.remove-wishlist');

        removeBtn.forEach(button => {
            button.addEventListener('click', function(e){
                e.preventDefault();
                const url = this.dataset.url;
                Swal.fire({
                    title: "Xoá sản phẩm yêu thích",
                    text: "Bạn chắc chắn muốn xoá sản phẩm yêu thích này!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText : "Xoá",
                    cancelButtonText: "Huỷ"
                }).then((result)=>{
                    if(result.isConfirmed){
                        window.location.href = url;
                    }
                })
            })
        });
    })
</script>
@endsection
