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
    @php
        // Lấy toàn bộ giá (ưu tiên sale_price nếu có)
        $prices = $product->variants->map(function($variant) {
            return ($variant->is_sale  && $variant->sale_price > 0)
                ? $variant->sale_price
                : $variant->price;
        });

        $minPrice = $prices->min();
        $maxPrice = $prices->max();
    @endphp

    @if ($minPrice != $maxPrice)
        <ins>{{ number_format($minPrice, 0, ',', '.') }}đ - {{ number_format($maxPrice, 0, ',', '.') }}đ</ins>
    @else
        <ins>{{ number_format($minPrice, 0, ',', '.') }}đ</ins>
    @endif
</div>


                    {{-- Size options --}}
                    @php
                    // Lấy tất cả attribute values theo từng attribute
                    $attributes = $product->variants
                        ->flatMap(fn($v) => $v->attributeValues)
                        ->groupBy('attribute_id');
// Map mỗi attribute_value_id -> ảnh đại diện của 1 variant chứa nó
    $avImageMap = [];
    foreach ($product->variants as $v) {
        // tuỳ cấu trúc, dùng $v->thumbnail hoặc $v->image
        $img = $v->thumbnail
            ? asset('storage/' . $v->thumbnail)
            : asset('storage/' . $product->thumbnail);

        foreach ($v->attributeValues as $av) {
            // chỉ gán nếu chưa có (tránh overwrite)
            if (!isset($avImageMap[$av->id])) {
                $avImageMap[$av->id] = $img;
            }
        }
    }
                        // $sizeValues = $product->variants
                        //     ->flatMap(fn($v) => $v->attributeValues->filter(fn($av) => $av->attribute->slug === 'size'))
                        //     ->unique('id')
                        //     ->values();
                    @endphp
                    @foreach ($attributes as $attributeId => $values)
                        @php
                            $attribute = $values->first()->attribute; // vì cùng 1 attribute nên lấy cái đầu tiên
                        @endphp
                        <div class="product-attribute mt-1 d-flex gap-1">
                            <strong class="mt-2 me-2">{{ $attribute->name }}</strong>
                            {{-- Nếu là màu --}}
                            @if ($attribute->slug === 'color')
                                <div class="d-flex gap-1">
                                    @foreach ($values->unique('id') as $av)
                                    <span class="color-option color-circle attribute-option"
                                        data-id="{{$av->id}}"
                                        data-attribute="{{$attribute->slug}}"
                                        data-color="{{ \Illuminate\Support\Str::start($av->hex, '#') }}"
                                        data-image="{{ $avImageMap[$av->id] ?? asset('storage/' . $variant->thumbnail) }}"
                                        style="display:inline-block; width:16px; height:16px; border-radius:50%; background-color: {{ \Illuminate\Support\Str::start($av->hex, '#') }}; border:1px solid #ccc; cursor:pointer;">
                                    </span>
                                    @endforeach
                                </div>
                            @else
                                {{-- Các thuộc tính khác --}}
                                @foreach ($values->unique('id') as $av)
                                    <span class="attribute-option"
                                        data-id="{{ $av->id }}"
                                        data-attribute="{{ $attribute->slug }}"
                                        data-image="{{ $avImageMap[$av->id] ?? asset('storage/' . $variant->thumbnail) }}">
                                        {{ $av->value }}
                                    </span>
                                @endforeach
                            @endif
                        </div>
                    @endforeach

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
/* ====== SIZE / CHẤT LIỆU ====== */
.attribute-option:not(.color-option) {
    display: inline-block;
    padding: 3px 10px;
    margin: 4px 0;
    border: 1px solid #ccc;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.2s ease;
    font-size: 14px;
    user-select: none;
}


.color-option.selected {
    border: 3px solid #000 !important; /* Viền đen đậm hơn */

/* Khi được chọn thì đổi màu nền + chữ trắng */
.attribute-option:not(.color-option).selected {
    background: #7b9496 !important;
    color: #fff !important;
    border-color: #7b9496 !important;
}

/* ====== MÀU SẮC (COLOR CIRCLE) ====== */
.color-option {
    display: inline-block;
    margin-top: 9px;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    border: 2px solid #ddd;
    cursor: pointer;
    transition: all 0.2s ease;

}

/* Khi hover thì viền xám đậm hơn */
.color-option:hover {
    border-color: #7b9496;
}

/* Khi chọn thì chỉ viền đen nổi bật */
.color-option.selected {
    border: 3px solid #000 !important;
}

</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Xử lý chọn thuộc tính
    document.querySelectorAll('.attribute-option').forEach(el => {
        el.addEventListener('click', e => {
            let parent = e.target.closest(".product-attribute");
            parent.querySelectorAll(".attribute-option").forEach(opt => opt.classList.remove("selected"));
            e.target.classList.add("selected");

            let productCard = e.target.closest(".productItem01");
            let productId = productCard.querySelector(".piAddToCart").dataset.id;

            // Thu thập các thuộc tính đã chọn
            let selectedIds = [];
            productCard.querySelectorAll(".attribute-option.selected").forEach(sel => {
                selectedIds.push(sel.dataset.id);
            });

            let requiredCount = productCard.querySelectorAll(".product-attribute").length;
            if (selectedIds.length === requiredCount) {
                fetch("{{ route('check.variant') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        product_id: productId,
                        attribute_values: selectedIds
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.found) {
                        // Cập nhật giá
                        let priceEl = productCard.querySelector(".pi01Price ins");
                        if (priceEl) priceEl.innerText = data.price;

                        // Cập nhật ảnh
                        if (data.image) {
                            let imgEls = productCard.querySelectorAll(".pi01Thumb img");
                            imgEls.forEach(img => img.src = data.image);
                        }
                    }
                })
                .catch(err => console.error("checkVariant error:", err));
            }
        });
    });

    // Xử lý thêm vào giỏ hàng
    document.querySelectorAll('.piAddToCart').forEach(btn => {
        if (btn.dataset.bound) return;
        btn.dataset.bound = "true";

        btn.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();

            let productCard = this.closest(".productItem01");
            let productId = this.dataset.id;

            // Thu thập thuộc tính đã chọn
            let selectedAttributes = {};
            productCard.querySelectorAll(".attribute-option.selected").forEach(el => {
                selectedAttributes[el.dataset.attribute] = el.dataset.id;
            });

            let requiredCount = productCard.querySelectorAll(".product-attribute").length;
            if (Object.keys(selectedAttributes).length < requiredCount) {
                alert("Vui lòng chọn đầy đủ thuộc tính!");
                return;
            }

            let form = document.getElementById("add-to-cart-form-" + productId);
            let formData = new FormData(form);
            for (let key in selectedAttributes) {
                formData.append("attribute_values[]", selectedAttributes[key]);
            }

            this.classList.add("disabled");

            fetch("{{ route('cart.add') }}", {
                method: "POST",
                body: formData,
                headers: {
                    "X-CSRF-TOKEN": formData.get("_token"),
                    "X-Requested-With": "XMLHttpRequest"
                }
            })
            .then(res => {
                if (res.status === 401) {
                    alert("Vui lòng đăng nhập để thêm sản phẩm vào giỏ!");
                    return Promise.reject(); // Ngắt luôn, không chạy xuống
                }
                return res.json();
            })
            .then(data => {
                if (data.success) {
                //Cập nhật số lượng sản phẩm trên icon giỏ
                document.querySelector(".cart-count").innerText = data.totalProduct;

                // Cập nhật lại dropdown / widget giỏ hàng
                document.querySelector(".cartWidgetArea").innerHTML = data.cartWidget;
                    alert("Đã thêm sản phẩm vào giỏ hàng");
                } else {
                    alert(data.message || "Có lỗi xảy ra!");
                }
            })
            .catch(err => {
                console.error("Thêm giỏ hàng lỗi:", err);
                alert("Không thể thêm sản phẩm vào giỏ");
            })
            .finally(() => {
                this.classList.remove("disabled");
            });
        });
    });
});
</script>


</script>
