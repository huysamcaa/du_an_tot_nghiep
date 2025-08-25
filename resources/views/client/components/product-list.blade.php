@php
    use App\Models\Admin\AttributeValue;
    use Illuminate\Support\Str;

    $selectedCategory = $selectedCategory ?? null;
    $selectedBrand    = $selectedBrand ?? null;
    $selectedSize     = $selectedSize ?? null;
    $selectedColor    = $selectedColor ?? null;

    $categories       = $categories ?? collect();
    $availableBrands  = $availableBrands ?? [];
    $availableSizes   = $availableSizes ?? [];
    $availableColors  = $availableColors ?? [];

    $min = $min ?? 0;
    $max = $max ?? 0;

    $activeFilters = [];

    if ($selectedCategory) {
        $activeFilters['category_id'] = $categories->firstWhere('id', $selectedCategory)?->name;
    }
    if ($selectedBrand) {
        $activeFilters['brand'] = $availableBrands[$selectedBrand] ?? $selectedBrand;
    }
    if ($selectedSize) {
        $activeFilters['size'] = $selectedSize;
    }
    if ($selectedColor) {
        $colorValue = AttributeValue::where('hex', $selectedColor)
            ->whereHas('attribute', fn($q) => $q->where('slug', 'color'))
            ->value('value');
        $activeFilters['color'] = $colorValue ?? $selectedColor;
    }
    if (request('price_min') || request('price_max')) {
        $activeFilters['price'] = number_format(request('price_min', $min), 0, ',', '.') . '₫ - ' . number_format(request('price_max', $max), 0, ',', '.') . '₫';
    }
@endphp

<div class="row">
    <!-- Sidebar lọc sản phẩm -->
    <div class="col-lg-3 col-md-4">
        <div class="shopSidebar">
            @if(count($activeFilters))
                <aside class="widget">
                    <h3 class="widgetTitle">Đang lọc</h3>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($activeFilters as $key => $value)
                            @php $query = request()->except([$key, 'page']); @endphp
                            <a href="{{ url()->current() . '?' . http_build_query($query) }}" class="btn btn-outline-secondary btn-sm">
                                {{ $value }} &times;
                            </a>
                        @endforeach
                        <a href="{{ url()->current() }}" class="btn btn-secondary btn-sm">
                            Xóa tất cả
                        </a>
                    </div>
                </aside>
            @endif

            <!-- Danh mục -->
            <aside class="widget">
                <h3 class="widgetTitle">Danh mục</h3>
                <ul class="categoryFilterList">
                    <li>
                        <a href="{{ url()->current() }}" class="{{ is_null($selectedCategory) ? 'active' : '' }}">Tất cả</a>
                    </li>
                    @foreach($categories as $cat)
                        <li>
                            <a href="{{ url()->current() . '?' . http_build_query(array_merge(request()->except(['category_id','page']), ['category_id' => $cat->id])) }}"
                                class="{{ (string)$selectedCategory === (string)$cat->id ? 'active' : '' }}">
                                {{ $cat->name }}
                                @if(isset($cat->products_count)) ({{ $cat->products_count }}) @endif
                            </a>
                        </li>
                    @endforeach
                </ul>
            </aside>

            <!-- Giá -->
            <aside class="widget priceFilter">
                <h3 class="widgetTitle">Lọc theo giá</h3>
                <form action="{{ url()->current() }}" method="get" id="price-filter-form">
                    @foreach(request()->except(['price_min','price_max','page']) as $key => $val)
                        <input type="hidden" name="{{ $key }}" value="{{ $val }}">
                    @endforeach
                    <div id="sliderRange"></div>
                    <div class="pfsWrap d-flex align-items-center mt-2">
                        <label class="me-2">Giá:</label>
                        <span id="amount">{{ number_format($min, 0, ',', '.') }}₫ - {{ number_format($max, 0, ',', '.') }}₫</span>
                    </div>
                    <input type="hidden" name="price_min" id="price_min" value="{{ $min }}">
                    <input type="hidden" name="price_max" id="price_max" value="{{ $max }}">
                    <button type="submit" class="btn btn-sm btn-outline-secondary mt-2">Áp dụng</button>
                </form>
            </aside>

            <!-- Kích cỡ -->
            <aside class="widget sizeFilter">
                <h3 class="widgetTitle">Kích cỡ</h3>
                <form action="{{ url()->current() }}" method="get" id="sizeFilterForm">
                    @foreach(request()->except(['size','page']) as $key => $val)
                        <input type="hidden" name="{{ $key }}" value="{{ $val }}">
                    @endforeach
                    <div class="productSizeWrap">
                        @foreach($availableSizes as $size)
                            @php $id = "size_{$size}"; @endphp
                            <div class="pswItem">
                                <input type="radio" name="size" id="{{ $id }}" value="{{ $size }}"
                                    {{ $selectedSize === $size ? 'checked' : '' }}
                                    onchange="document.getElementById('sizeFilterForm').submit();" />
                                <label for="{{ $id }}">{{ $size }}</label>
                            </div>
                        @endforeach
                    </div>
                </form>
            </aside>

            <!-- Màu sắc -->
            <aside class="widget colorFilter">
                <h3 class="widgetTitle">Màu sắc</h3>
                <form action="{{ url()->current() }}" method="get">
                    @foreach(request()->except(['color','page']) as $k => $v)
                        <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                    @endforeach
                    <div class="productColorWrap d-flex flex-wrap gap-2 mt-2">
                        @foreach($availableColors as $hex)
                            @php $id = 'color_' . md5($hex); @endphp
                            <div class="colorOptionWrapper text-center">
                                <input type="radio" name="color" id="{{ $id }}" value="{{ $hex }}"
                                    {{ $selectedColor === $hex ? 'checked' : '' }}
                                    onchange="this.form.submit();" hidden>
                                <label for="{{ $id }}" class="customColorCircle" style="background-color: {{ Str::start($hex, '#') }};"></label>
                            </div>
                        @endforeach
                    </div>
                </form>
            </aside>

            <!-- Thương hiệu -->
            <aside class="widget">
                <h3 class="widgetTitle">Thương hiệu</h3>
                <ul class="brandFilterList">
                    @foreach($availableBrands as $brandId => $brandName)
                        <li>
                            <a href="{{ url()->current() }}?{{ http_build_query(array_merge(request()->except(['brand','page']),['brand' => $brandId])) }}"
                                class="{{ (string)$selectedBrand === (string)$brandId ? 'active' : '' }}">
                                {{ $brandName }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </aside>
        </div>
    </div>

    <!-- Danh sách sản phẩm -->
    <div class="col-lg-9 col-md-8">
<div class="row">
    @foreach ($products as $product)
        @php
            // Chuẩn bị toàn bộ variants dưới dạng JSON cho JS
            $variantMap = $product->variants
                ->map(function ($v) {
                    return [
                        'id' => $v->id,
                        'price' => (float) $v->price,
                        'sale_price' =>
                            $v->sale_price > 0 && $v->sale_price < $v->price ? (float) $v->sale_price : null,
                        'attrs' => $v->attributeValues->mapWithKeys(function ($av) {
                            return [$av->attribute->slug => $av->id];
                        }),
                    ];
                })
                ->values();
        @endphp

        <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
            <div class="productItem01" data-product-id="{{ $product->id }}" data-variants='@json($variantMap)'>
                <div class="pi01Thumb">
                    <img src="{{ asset('storage/' . $product->thumbnail) }}" alt="{{ $product->name }}"
                         style="width: 100%; height: auto; object-fit: cover;" />

                    <div class="pi01Actions" data-product-id="{{ $product->id }}">
                        <a href="javascript:void(0)" class="piAddToCart" data-id="{{ $product->id }}">
                            <i class="fa-solid fa-shopping-cart"></i>
                        </a>
                        <form id="add-to-cart-form-{{ $product->id }}" style="display:none;">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <input type="hidden" name="quantity" value="1">
                            <input type="hidden" name="variant_id" value="">
                        </form>
                        <a href="{{ route('product.detail', $product->id) }}"><i class="fa-solid fa-eye"></i></a>
                    </div>
                </div>

                <div class="pi01Details">
                    <h3 title="{{ $product->name }}">
                        <a href="{{ route('product.detail', $product->id) }}">{{ $product->name }}</a>
                    </h3>

                    {{-- Hiển thị khoảng giá ban đầu --}}
                    @php
                        $prices = $product->variants->map(fn($v) =>
                            $v->sale_price > 0 && $v->sale_price < $v->price ? $v->sale_price : $v->price
                        );
                        $minPrice = $prices->min();
                        $maxPrice = $prices->max();
                    @endphp
                    <div class="pi01Price">
                        @if ($minPrice != $maxPrice)
                            <ins>{{ number_format($minPrice, 0, ',', '.') }}đ - {{ number_format($maxPrice, 0, ',', '.') }}đ</ins>
                        @else
                            <ins>{{ number_format($minPrice, 0, ',', '.') }}đ</ins>
                        @endif
                    </div>

@php
    $attributesGrouped = $product->variants
        ->flatMap(fn($v) => $v->attributeValues)
        ->groupBy(fn($av) => $av->attribute->slug);
@endphp

@foreach($attributesGrouped as $slug => $values)
<div class="product-attribute mt-1 d-flex align-items-center gap-1 flex-wrap">
    <strong class="me-1">{{ ucfirst($slug) }}:</strong>
    @foreach($values->unique('id') as $av)
        @if($slug === 'color')
            <span class="attr-option color-option"
                  data-attr="{{ $slug }}"
                  data-id="{{ $av->id }}"
                  style="width:16px;height:16px;border-radius:50%;background:{{ \Illuminate\Support\Str::start($av->hex, '#') }};border:1px solid #ccc;cursor:pointer;">
            </span>
        @else
            <span class="badge bg-light text-dark border attr-option"
                  data-attr="{{ $slug }}"
                  data-id="{{ $av->id }}">
                {{ $av->value }}
            </span>
        @endif
    @endforeach
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
    </div>
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
        opacity: 1;
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
/* Size, material (badge) */
.attr-option.selected:not(.color-option) {
    background: #7b9494 !important;
    color: #fff !important;
    border: 1px solid #7b9494 !important;
}

/* Circle (color) */
.color-option {
    display: inline-block;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    cursor: pointer;
    border: 2px solid #ccc;
    transition: all 0.2s ease;
}

/* Hover */
.color-option:hover {
    border-color: #7b9494;
}

/* Khi chọn màu: không đổi background, chỉ thêm viền nổi bật */
.color-option.selected {
    border: 3px solid #000 !important;
}

</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
    function formatVND(n){ return new Intl.NumberFormat('vi-VN').format(Math.round(n)) + 'đ'; }

    function updatePriceAndForm(card){
        const variants = JSON.parse(card.dataset.variants || '[]');
        const selected = {};

        // Thu thập attr đã chọn
        card.querySelectorAll('.attr-option.selected').forEach(el=>{
            selected[el.dataset.attr] = el.dataset.id;
        });

        const form = card.querySelector('form');
        form.variant_id.value = '';

        // 🔥 Xóa hết hidden cũ
        form.querySelectorAll('input[name="attribute_values[]"]').forEach(e=>e.remove());

        // Tạo lại hidden cho mỗi attr đã chọn
        Object.values(selected).forEach(id=>{
            let hidden = document.createElement('input');
            hidden.type = 'hidden';
            hidden.name = 'attribute_values[]';
            hidden.value = id;
            form.appendChild(hidden);
        });

        // Tính tổng attr cần chọn
        const allAttrs = [...new Set(variants.flatMap(v => Object.keys(v.attrs)))];
        const totalAttrs = allAttrs.length;

        if(Object.keys(selected).length < totalAttrs){
            return; // chưa chọn đủ thì thoát
        }

        // Tìm variant phù hợp
        const matched = variants.find(v=>{
            return Object.entries(selected).every(([attr,id]) =>
                String(v.attrs[attr]||'') === String(id)
            );
        });

        if(matched){
            const priceBox = card.querySelector('.pi01Price');
            const html = matched.sale_price
                ? `<del>${formatVND(matched.price)}</del> <ins>${formatVND(matched.sale_price)}</ins>`
                : `<ins>${formatVND(matched.price)}</ins>`;
            priceBox.innerHTML = html;

            form.variant_id.value = matched.id; // quan trọng: gán variant_id
        }
    }


    // Chọn thuộc tính (dùng chung cho mọi loại attr)
    document.querySelectorAll('.attr-option').forEach(el=>{
        el.addEventListener('click',()=>{
            const group = el.closest('.product-attribute');
            group.querySelectorAll('.attr-option').forEach(x=>x.classList.remove('selected'));
            el.classList.add('selected');
            updatePriceAndForm(el.closest('.productItem01'));
        });
    });

    // Thêm vào giỏ
    document.querySelectorAll('.piAddToCart').forEach(btn=>{
        btn.addEventListener('click',()=>{
            const form = document.getElementById('add-to-cart-form-'+btn.dataset.id);
            if(!form.variant_id.value){
                alert('Vui lòng chọn đầy đủ thuộc tính trước khi thêm vào giỏ hàng!');
                return;
            }
            fetch("{{ route('cart.add') }}",{
                method:'POST',body:new FormData(form),
                headers:{'X-CSRF-TOKEN':form._token.value,'X-Requested-With':'XMLHttpRequest'}
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    // cập nhật số lượng trên icon giỏ
                    document.querySelector(".cart-count").innerText = data.totalProduct;

                    // cập nhật lại dropdown giỏ hàng
                    document.querySelector(".cartWidgetArea").innerHTML = data.cartWidget;

                    alert("Đã thêm sản phẩm vào giỏ hàng");
                } else {
                    alert(data.message || 'Có lỗi xảy ra!');
                }
            })
            .catch(err => {
                console.error(err);
                alert('Có lỗi kết nối server!');
            });
        });
    });
});
</script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.7.0/nouislider.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.7.0/nouislider.min.js"></script>
