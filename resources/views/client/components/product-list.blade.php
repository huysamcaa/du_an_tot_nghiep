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
                        <div class="pi01Thumb" style="height: auto; overflow: hidden;"   >
                             <img src="{{ asset('storage/' . $product->thumbnail) }}" alt="{{ $product->name }}"
                                style="width: 100%; height: auto; object-fit: cover;" /></a>
                            <div class="pi01Actions" data-product-id="{{ $product->id }}">
                                <a href="javascript:void(0);" class="pi01QuickView"><i class="fa-solid fa-arrows-up-down-left-right"></i></a>
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
#sliderRange {
    margin-top: 10px;
    margin-bottom: 10px;
}
/* Thu nhỏ nút kéo */
.noUi-handle {
    width: 14px !important;
    height: 14px !important;
    border-radius: 50%;
    top: -5px; /* canh giữa thanh trượt */
    background: #fff;
    border: 2px solid #007bff;
    box-shadow: none;
    cursor: pointer;
}

/* Ẩn icon gạch gạch bên trong */
.noUi-handle::before,
.noUi-handle::after {
    display: none;
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
.color-circle{
    margin-top: 5px;
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
</script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.7.0/nouislider.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.7.0/nouislider.min.js"></script>

