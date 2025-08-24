<div class="row">
    @foreach ($products as $product)
        <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
            @php
                $variantMap = $product->variants
                    ->map(function ($v) {
                        return [
                            'id' => $v->id,
                            'price' => (float) $v->price,
                            'sale_price' =>
                                $v->sale_price > 0 && $v->sale_price < $v->price ? (float) $v->sale_price : null,
                            'attrs' => $v->attributeValues->mapWithKeys(function ($av) {
                                return [$av->attribute->slug => $av->id]; // ví dụ: ['size' => 12, 'color' => 34]
                            }),
                        ];
                    })
                    ->values();
            @endphp

            <div class="productItem01" data-product-id="{{ $product->id }}" data-variants='@json($variantMap)'>
                <div class="pi01Thumb" style="height: auto; overflow: hidden;">

                    <img src="{{ asset('storage/' . $product->thumbnail) }}" alt="{{ $product->name }}"
                        style="width: 100%; height: auto; object-fit: cover;" />
                    <img src="{{ asset('storage/' . $product->thumbnail) }}" alt="{{ $product->name }}"
                        style="width: 100%; height: auto; object-fit: cover;" />

                    <!-- Nút hành động -->


                    <div class="pi01Actions" data-product-id="{{ $product->id }}">
                        <a href="javascript:void(0)" class="piAddToCart" data-id="{{ $product->id }}">
                            <i class="fa-solid fa-shopping-cart"></i>
                        </a>
                        <form id="add-to-cart-form-{{ $product->id }}" style="display:none;">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <input type="hidden" name="quantity" value="1">
                            <input type="hidden" name="variant_id" value="">
                            <input type="hidden" name="size" value="">
                            <input type="hidden" name="color" value="">

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
                    <h3 style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"
                        title="{{ $product->name }}">
                        <a href="{{ route('product.detail', $product->id) }}"
                            style="color: inherit; text-decoration: none;">
                            {{ $product->name }}
                        </a>
                    </h3>


                    <div class="pi01Price" data-product-id="{{ $product->id }}">
                        @php
                            // Lấy toàn bộ giá (ưu tiên sale_price nếu có)
                            $prices = $product->variants->map(function ($variant) {
                                return $variant->sale_price > 0 && $variant->sale_price < $variant->price
                                    ? $variant->sale_price
                                    : $variant->price;
                            });

                            $minPrice = $prices->min();
                            $maxPrice = $prices->max();
                        @endphp

                        @if ($minPrice != $maxPrice)
                            <ins class="price-text">{{ number_format($minPrice, 0, ',', '.') }}đ -
                                {{ number_format($maxPrice, 0, ',', '.') }}đ</ins>
                        @else
                            <ins class="price-text">{{ number_format($minPrice, 0, ',', '.') }}đ</ins>
                        @endif
                    </div>

                    {{-- Size options --}}
                    @php
                        $sizeValues = $product->variants
                            ->flatMap(fn($v) => $v->attributeValues->filter(fn($av) => $av->attribute->slug === 'size'))
                            ->unique('id')
                            ->values();
                    @endphp
                    @if ($sizeValues->count())
                        <div class="product-sizes mt-1">
                            <strong>Size:</strong>
                            @foreach ($sizeValues as $av)
                                <span class="badge bg-light text-dark border size-option" data-id="{{ $av->id }}"
                                    data-size="{{ $av->value }}">
                                    {{ $av->value }}
                                </span>
                            @endforeach
                        </div>
                    @endif

                    {{-- Color options --}}
                    @php
                        $colorValues = $product->variants
                            ->flatMap(
                                fn($v) => $v->attributeValues->filter(fn($av) => $av->attribute->slug === 'color'),
                            )
                            ->unique('id')
                            ->values();
                    @endphp
                    @if ($colorValues->count())
                        <div class="product-colors mt-1 d-flex gap-1">
                            <strong class="me-1">Màu:</strong>
                            @foreach ($colorValues as $av)
                                <span class="color-circle color-option" data-id="{{ $av->id }}"
                                    data-color="{{ \Illuminate\Support\Str::start($av->hex, '#') }}"
                                    style="display:inline-block; width:16px; height:16px; border-radius:50%; background-color: {{ \Illuminate\Support\Str::start($av->hex, '#') }}; border:1px solid #ccc; cursor:pointer;">
                                </span>
                            @endforeach
                        </div>
                    @endif

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
        transition: transform 0.3s ease;
        /* Cho hiệu ứng mượt */
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
    .productItem01:hover .pi01Actions {
    opacity: 1;
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
        opacity: 0.8;
        /* Mờ nhẹ thôi */
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
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.1);
        opacity: 0;
        transition: opacity 0.3s ease;
        z-index: 1;
    }

    .pi01Thumb:hover::after {
        opacity: 1;
    }

    .size-option.selected {
        background: #7b9494 !important;
        color: #fff !important;
    }

    .color-option.selected {
        border: 2px solid #7b9494 !important;
    }

    .color-circle {
        margin-top: 5px;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        function formatCurrency(n) {
            return new Intl.NumberFormat('vi-VN').format(Math.round(n)) + 'đ';
        }

        function updatePriceAndForm(productCard) {
            const variants = JSON.parse(productCard.dataset.variants || '[]');
            const selectedSizeId = productCard.querySelector('.size-option.selected')?.dataset.id;
            const selectedColorId = productCard.querySelector('.color-option.selected')?.dataset.id;

            const productId = productCard.dataset.productId;
            const form = document.getElementById('add-to-cart-form-' + productId);

            // Chưa chọn đủ thì giữ khoảng giá & clear variant_id
            if (!selectedSizeId || !selectedColorId) {
                form.querySelector('input[name="variant_id"]').value = '';
                form.querySelector('input[name="size"]').value = selectedSizeId || '';
                form.querySelector('input[name="color"]').value = selectedColorId || '';
                return;
            }

            // Tìm variant khớp
            const matched = variants.find(v =>
                String(v.attrs?.size || '') === String(selectedSizeId) &&
                String(v.attrs?.color || '') === String(selectedColorId)
            );

            if (matched) {
                const usePrice = matched.sale_price ?? matched.price;
                let html = '';
                if (matched.sale_price && matched.sale_price < matched.price) {
                    html = `<del style="margin-right:8px;opacity:.7">${formatCurrency(matched.price)}</del>` +
                        `<ins>${formatCurrency(matched.sale_price)}</ins>`;
                } else {
                    html = `<ins>${formatCurrency(usePrice)}</ins>`;
                }

                // Cập nhật vùng giá (ghi đè cả .pi01Price để chắc chắn)
                const priceParent = productCard.querySelector('.pi01Price');
                if (priceParent) priceParent.innerHTML = html;

                // Set vào form
                form.querySelector('input[name="variant_id"]').value = matched.id;
                form.querySelector('input[name="size"]').value = selectedSizeId;
                form.querySelector('input[name="color"]').value = selectedColorId;
            } else {
                alert('Biến thể với size/màu này hiện không khả dụng.');
                form.querySelector('input[name="variant_id"]').value = '';
            }
        }

        // ===== Chọn size =====
        document.querySelectorAll('.size-option').forEach(function(el) {
            el.addEventListener('click', function() {
                const parent = this.closest('.product-sizes');
                parent.querySelectorAll('.size-option').forEach(e => e.classList.remove(
                    'selected'));
                this.classList.add('selected');

                const card = this.closest('.productItem01');
                updatePriceAndForm(card);
            });
        });

        // ===== Chọn màu =====
        document.querySelectorAll('.color-option').forEach(function(el) {
            el.addEventListener('click', function() {
                const parent = this.closest('.product-colors');
                parent.querySelectorAll('.color-option').forEach(e => e.classList.remove(
                    'selected'));
                this.classList.add('selected');

                const card = this.closest('.productItem01');
                updatePriceAndForm(card);
            });
        });

        // ===== Thêm vào giỏ =====
        document.querySelectorAll('.piAddToCart').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const productId = this.getAttribute('data-id');
                const form = document.getElementById('add-to-cart-form-' + productId);

                const variantId = form.querySelector('input[name="variant_id"]').value;
                const selectedSize = form.querySelector('input[name="size"]').value;
                const selectedColor = form.querySelector('input[name="color"]').value;

                if (!selectedSize || !selectedColor) {
                    alert('Vui lòng chọn size và màu trước khi thêm vào giỏ hàng!');
                    return;
                }
                if (!variantId) {
                    alert('Không tìm thấy biến thể phù hợp với lựa chọn của bạn!');
                    return;
                }

                const formData = new FormData(form);

                fetch("{{ route('cart.add') }}", {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': formData.get('_token'),
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            const cartDropdown = document.querySelector(
                                '#cart-widget .dropdown-menu');
                            if (cartDropdown) cartDropdown.innerHTML = data.cartWidget;
                            alert('Đã thêm sản phẩm vào giỏ hàng!');
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
