<div class="row">
    @foreach ($products as $product)
        <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
            <div class="productItem01">

                    <div class="pi01Actions" data-product-id="{{ $product->id }}">
                        <a href="javascript:void(0);" class="pi01QuickView"><i class="fa-solid fa-arrows-up-down-left-right"></i></a>
                        <a href="{{ route('product.detail', $product->id) }}"><i class="fa-solid fa-eye"></i></a>
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
.pi01Thumb {
    overflow: hidden;
    position: relative;
        background: #fff; /* nền trắng trong khung ảnh */
    padding: 10px;
}

.pi01Thumb img {
    position: static !important; /* Không dịch chuyển */
    left: auto !important;
    top: auto !important;
    transform: none !important;
    transition: none !important;
        border-radius: 8px;
    transition: transform 0.3s ease;
}

/* Nếu theme dùng pseudo-element hoặc ảnh thứ 2 */
.pi01Thumb:hover img,
.pi01Thumb::before,
.pi01Thumb::after {
    transform: none !important;
    opacity: 1 !important;
    visibility: visible !important;
}
.pi01Actions {
    display: none !important;
}
.productItem01:hover img {
    filter: none !important;
    opacity: 1 !important;
    transform: none !important;
}

.productItem01 a:hover img {
    filter: none !important;
    opacity: 1 !important;
    transform: none !important;
}
.productItem01 {
    background: #f9f9f9; /* nền sáng cho từng sản phẩm */
    border-radius: 10px;
    overflow: hidden; /* bo góc ảnh */
    transition: transform 0.25s ease, box-shadow 0.25s ease;
    border: 1px solid #eee;
}

.productItem01:hover {
    transform: translateY(-4px); /* nâng nhẹ sản phẩm khi hover */
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
    border-color: #7b9494;
}
.productItem01:hover .pi01Thumb img {
    transform: scale(1.02); /* phóng nhẹ ảnh khi hover */
}
.pi01Details {
    padding-left: 15px; /* dịch sang phải 10px */
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.pi01Thumb').forEach(function(wrapper) {
        let img = wrapper.querySelector('img');
        if (!img) return;

        // Hover chỉ phóng to/hiệu ứng, KHÔNG đổi ảnh
        wrapper.addEventListener('mouseenter', function() {
            img.style.transform = 'scale(1.05)'; // hoặc bỏ nếu không muốn hiệu ứng
        });

        wrapper.addEventListener('mouseleave', function() {
            img.style.transform = 'scale(1)';
        });
    });
});
</script>








