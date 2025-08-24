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
                <div class="col-lg-4 col-md-6 col-sm-6 mb-4">
                    <div class="productItem01">

                        <div class="pi01Thumb" style="height: auto; overflow: hidden;">
                            <img src="{{ asset('storage/' . $product->thumbnail) }}" alt="{{ $product->name }}"
                                style="width: 100%; height: auto; object-fit: cover;" />
                                  <img src="{{ asset('storage/' . $product->thumbnail) }}" alt="{{ $product->name }}"
                                style="width: 100%; height: auto; object-fit: cover;" />


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
                                <a href="{{ route('product.detail', $product->id) }}"><i class="fa-solid fa-eye"></i></a>
                            </div>
                            @if ($product->sale_price && $product->price > $product->sale_price)
                                <div class="productLabels clearfix">
                                    <span class="plDis">-{{ number_format($product->price - $product->sale_price, 0, ',', '.') }}đ</span>
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

    {{-- Hiển thị Size --}}
    @php
        $sizes = $product->variants
            ->flatMap(fn($v) => $v->attributeValues->filter(fn($av) => $av->attribute->slug === 'size')->pluck('value'))
            ->unique()
            ->values()
            ->toArray();
    @endphp
    @if(count($sizes))
        <div class="product-sizes mt-1">
            <strong>Size:</strong>
            @foreach($sizes as $size)
                <span class="badge bg-light text-dark border">{{ $size }}</span>
            @endforeach
        </div>
    @endif

    {{-- Hiển thị Màu --}}
    @php
        $colors = $product->variants
            ->flatMap(fn($v) => $v->attributeValues->filter(fn($av) => $av->attribute->slug === 'color')->pluck('hex'))
            ->unique()
            ->values()
            ->toArray();
    @endphp
    @if(count($colors))
        <div class="product-colors mt-1 d-flex gap-1">
            <strong class="me-1">Màu:</strong>
            @foreach($colors as $hex)
                <span class="color-circle" style="display:inline-block; width:16px; height:16px; border-radius:50%; background-color: {{ \Illuminate\Support\Str::start($hex, '#') }}; border:1px solid #ccc;"></span>
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
    </div>
</div>

<style>
    /* 1. Cho container wrap xuống hàng */
    .pi01Variations {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        /* khoảng cách giữa color và size */
    }

    /* 2. Cho nhóm color & size tự wrap và cuộn khi quá cao */
    .pi01VColor,
    .pi01VSize {
        display: flex;
        flex-wrap: wrap;
        gap: 0.25rem;
        /* khoảng cách giữa từng item */
        max-height: 4rem;
        /* cao tối đa ~2 hàng */
        overflow-y: auto;
        /* cuộn dọc khi vượt */
    }

    /* 3. Giữ kích thước swatch/mỗi label ổn định */
    .pi01VCItem label {
        width: 24px;
        height: 24px;
        display: block;
        border-radius: 50%;
    }

    .pi01VSItem label {
        padding: 0.1rem 0.5rem;
        border-radius: 4px;
        font-size: 0.75rem;
    }

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

/* Nếu theme dùng pseudo-element hoặc ảnh thứ 2 */
.pi01Thumb:hover img,
.pi01Thumb::before,
.pi01Thumb::after {
    transform: none !important;
    opacity: 1 !important;
    visibility: visible !important;
}
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
.pi01Thumb:hover .pi01Actions {
    opacity: 1;

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
.productItem01:hover .pi01Thumb img {
        opacity: 0.8; /* Mờ nhẹ thôi */
    transform: scale(1.05);
}
.pi01Details {
    padding-left: 15px; /* dịch sang phải 10px */
}
.color-circle{
    margin-top: 5px;
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
    var slider = document.getElementById('sliderRange');

    // Khởi tạo noUiSlider
    noUiSlider.create(slider, {
        start: [{{ $min }}, {{ $max }}],
        connect: true,
        step: 10000, // Bước nhảy 10,000 VNĐ
        range: {
            'min': 0,
            'max': 5000000
        }
    });

    var amount = document.getElementById('amount');
    var priceMinInput = document.getElementById('price_min');
    var priceMaxInput = document.getElementById('price_max');

    // Hàm format tiền Việt
    function formatVND(number) {
        return number.toLocaleString('vi-VN') + '₫';
    }

    // Cập nhật khi kéo slider
    slider.noUiSlider.on('update', function (values) {
        var min = Math.round(values[0]);
        var max = Math.round(values[1]);

        amount.textContent = formatVND(min) + ' - ' + formatVND(max);
        priceMinInput.value = min;
        priceMaxInput.value = max;
    });
});

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
<link href="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.7.0/nouislider.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.7.0/nouislider.min.js"></script>

