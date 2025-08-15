<div class="row">
    @foreach ($products as $product)
        <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
            <div class="productItem01">
                <div class="pi01Thumb" style="height: auto; overflow: hidden;">
                    <img src="{{ asset('storage/' . $product->thumbnail) }}"
                         alt="{{ $product->name }}"
                         style="width: 100%; height: auto; object-fit: cover;" />
                    <img src="{{ asset('storage/' . $product->thumbnail) }}"
                         alt="{{ $product->name }}"
                         style="width: 100%; height: auto; object-fit: cover;" />

                    <!-- Nút hành động -->
                    <div class="pi01Actions" data-product-id="{{ $product->id }}">
    <a href="javascript:void(0)" class="piAddToCart"
       data-id="{{ $product->id }}">
        <i class="fa-solid fa-shopping-cart"></i>
    </a>
    <form id="add-to-cart-form-{{ $product->id }}" style="display:none;">
    @csrf
    <input type="hidden" name="product_id" value="{{ $product->id }}">
    <input type="hidden" name="quantity" value="1">
</form>
                        <a href="{{ route('product.detail', $product->id) }}" title="Xem chi tiết">
                            <i class="fa-solid fa-eye"></i>
                        </a>
                    </div>

                    @if ($product->sale_price && $product->price > $product->sale_price)
                        <div class="productLabels clearfix">
                            <span class="plDis">
                                -{{ number_format($product->price - $product->sale_price, 0, ',', '.') }}đ
                            </span>
                            <span class="plSale">SALE</span>
                        </div>
                    @endif
                </div>

                <div class="pi01Details">
                    <h3 style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ $product->name }}">
                        <a href="{{ route('product.detail', $product->id) }}" style="color: inherit; text-decoration: none;">
                            {{ $product->name }}
                        </a>
                    </h3>

                    <div class="pi01Price">
                        <ins>{{ number_format($product->sale_price ?? $product->price, 0, ',', '.') }}đ</ins>
                        @if ($product->sale_price && $product->price > $product->sale_price)
                            <del>{{ number_format($product->price, 0, ',', '.') }}đ</del>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>

<div class="d-flex justify-content-center mt-4">
    {{ $products->links() }}
</div>

<style>
/* ===== PAGINATION ===== */
.pagination {
    display: flex;
    justify-content: center;
    margin-top: 20px;
}
.pagination .page-link {
    color: #7b9494; 
    border: 1px solid #c5d0d0; 
    background-color: transparent;
    border-radius: 50% !important; 
    width: 60px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 500;
    transition: all 0.3s ease;
    padding: 0;
    font-size: 22px;
}
.pagination .page-link:hover {
    background-color: #7b9494;
    color: white;
    border-color: #7b9494;
}
.pagination .active .page-link {
    background-color: #7b9494;
    border-color: #7b9494;
    color: white;
}
.pagination .page-item {
    margin: 0 4px;
}
.pagination .disabled .page-link {
    opacity: 0.5;
    pointer-events: none;
}

/* ===== PRODUCT ITEM ===== */
.pi01Thumb {
   position: relative;
    overflow: hidden;
}

.pi01Thumb img {
    display: block;
    width: 100%;
    height: auto;
    border-radius: 8px;
    transition: transform 0.3s ease; /* Cho hiệu ứng mượt */
    position: relative;
    z-index: 0;
}
/* Nút hành động */
.pi01Actions {
  position: absolute;
  bottom: 10px;
  left: 50%;
  transform: translateX(-50%);
  display: flex;
  gap: 8px;
  opacity: 0;
  transition: opacity 0.25s ease;
  z-index: 2;
}

.pi01Actions a {
    background: #7b9494;
    color: #fff;
    padding: 8px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background 0.3s ease;
}

.pi01Actions a:hover {
    background: #5d7373;
}

/* Hover hiện nút và phóng ảnh */
.pi01Thumb:hover .pi01Actions {
    opacity: 1;
    
}
.productItem01:hover .pi01Thumb img {
    opacity: 0.8; /* Mờ nhẹ thôi */
    transform: scale(1.05);
}

.productItem01 {
    background: #f9f9f9;
    border-radius: 10px;
    overflow: hidden;
    transition: transform 0.25s ease, box-shadow 0.25s ease;
    border: 1px solid #eee;
}
.productItem01:hover {
    transform: translateY(-4px);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
    border-color: #7b9494;
}

.pi01Details {
    padding-left: 15px;
}
.pi01Thumb::after {
    content: "";
    position: absolute;
    top: 0; left: 0;
    width: 100%; height: 100%;
    background: rgba(255,255,255,0.1);
    opacity: 0;
    transition: opacity 0.3s ease;
    z-index: 1;
}
.pi01Thumb:hover::after {
    opacity: 1;
}
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.piAddToCart').forEach(function (btn) {
        btn.addEventListener('click', function () {
            let productId = this.getAttribute('data-id');
            let form = document.getElementById('add-to-cart-form-' + productId);
            let formData = new FormData(form);


            fetch("{{ route('cart.add') }}", {
    method: 'POST',
    body: formData,
    headers: {
        'X-CSRF-TOKEN': formData.get('_token'),
        'X-Requested-With': 'XMLHttpRequest' // <--- thêm dòng này
    }
})
.then(res => res.json())
.then(data => {
    if (data.success) {
        alert('Đã thêm vào giỏ hàng!');
    } else {
        alert(data.message || 'Có lỗi xảy ra!');
    }
})
.catch(err => {
    console.error(err);
    alert('Không thể thêm sản phẩm vào giỏ!');
});
        });
    });
});

</script>
