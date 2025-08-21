@extends('client.layouts.app')

@section('title', 'Danh mục sản phẩm')

@section('content')
<!-- BEGIN: Page Banner Section -->
<section class="pageBannerSection">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="pageBannerContent text-center">
                    <h2>Mua sắm với FreshFit</h2>
                    <div class="pageBannerPath">
                        <a href="{{route('client.home')}}">Trang chủ</a>&nbsp;&nbsp;>&nbsp;&nbsp;<span>Danh mục</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- END: Page Banner Section -->

<!-- BEGIN: Shop Page Section -->
<section class="shopPageSection shopPageHasSidebar">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-xl-3">
                <div class="shopSidebar">

                    @php
                    use App\Models\Admin\AttributeValue;

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

                    @if(count($activeFilters))
                    <aside class="widget">
                        <h3 class="widgetTitle">Đang lọc</h3>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($activeFilters as $key => $value)
                            @php
                            $query = request()->except([$key, 'page']);
                            @endphp
                            <a href="{{ url()->current() . '?' . http_build_query($query) }}"
                                class="btn btn-outline-secondary btn-sm">
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
                                <a href="{{ url()->current() }}"
                                    class="{{ is_null($selectedCategory) ? 'active' : '' }}">
                                    Tất cả
                                </a>
                            </li>
                            @foreach($categories as $category)
                            {{-- Kiểm tra nếu danh mục cha có con --}}
                            @if($category->children->isNotEmpty())
                            <li class="menu-item-has-children">
                                <a href="javascript:void(0);">{{ $category->name }}</a>
                                <ul>
                                    @foreach($category->children as $child)
                                    @php
                                    $childIsActive = (string)$selectedCategory === (string)$child->id;
                                    @endphp
                                    <li>
                                        <a href="{{ url()->current() . '?' . http_build_query(array_merge(request()->except(['category_id', 'page']), ['category_id' => $child->id])) }}"
                                            class="{{ $childIsActive ? 'active' : '' }}">
                                            {{ $child->name }}
                                        </a>
                                    </li>
                                    @endforeach
                                </ul>
                            </li>
                            @else
                            {{-- Nếu danh mục cha không có con, nó là danh mục lá --}}
                            @php
                            $isActive = (string)$selectedCategory === (string)$category->id;
                            @endphp
                            <li>
                                <a href="{{ url()->current() . '?' . http_build_query(array_merge(request()->except(['category_id','page']), ['category_id' => $category->id])) }}"
                                    class="{{ $isActive ? 'active' : '' }}">
                                    {{ $category->name }}
                                    @if(isset($category->products_count)) ({{ $category->products_count }}) @endif
                                </a>
                            </li>
                            @endif
                            @endforeach
                        </ul>
                    </aside>

                    <!-- Giá -->
                    <aside class="widget priceFilter">
                        <h3 class="widgetTitle">Lọc theo giá</h3>
                        <div class="shopWidgetWraper">
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
                        </div>
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
                                    <label for="{{ $id }}" class="customColorCircle"
                                        style="background-color: {{ Str::start($hex, '#') }};"></label>
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


            <div class="col-lg-8 col-xl-9">
                <div class="row shopAccessRow">
                    <div class="col-sm-6">
                        <div class="productCount">
                            Hiển thị
                            <strong>{{ $products->firstItem() }}</strong> -
                            <strong>{{ $products->lastItem() }}</strong>
                            trong tổng số
                            <strong>{{ $products->total() }}</strong>
                            sản phẩm
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="shopAccessBar">
                            <div class="sortNav">
                                <form method="get" action="{{ url()->current() }}">
                                    @foreach(request()->except(['sort','page']) as $k => $v)
                                    <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                                    @endforeach
                                    <label for="sortSelect">Sắp xếp theo</label>
                                    <select id="sortSelect" name="sort" onchange="this.form.submit()">
                                        <option value="" {{ is_null($sort) ? 'selected' : '' }}>Mặc định</option>
                                        <option value="price_desc" {{ $sort==='price_desc' ? 'selected' : '' }}>Cao đến thấp</option>
                                        <option value="price_asc" {{ $sort==='price_asc'  ? 'selected' : '' }}>Thấp đến cao</option>
                                        <option value="newest" {{ $sort==='newest'     ? 'selected' : '' }}>Mới nhất</option>
                                    </select>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row shopProductRow">
                    <div class="col-lg-12">
                        <div class="tab-content productViewTabContent" id="productViewTabContent">
                            <div class="tab-pane show active" id="grid-tab-pane" role="tabpanel" aria-labelledby="grid-tab" tabindex="0">
                                <div class="row">
                                    @foreach ($products as $product)
                                    @php
                                    // Lấy giá thấp nhất và giá sale thấp nhất từ tất cả các biến thể của sản phẩm
                                    $min_price = $product->variants->min('price');
                                    $min_sale_price = $product->variants->whereNotNull('sale_price')->min('sale_price');

                                    // Lấy giá cao nhất và giá sale cao nhất từ tất cả các biến thể
                                    $max_price = $product->variants->max('price');
                                    $max_sale_price = $product->variants->whereNotNull('sale_price')->max('sale_price');
                                    @endphp
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
                                                @if ($min_sale_price && $min_sale_price < $min_price)
                                                    <div class="productLabels clearfix">
                                                    <span class="plDis">-{{ number_format($min_price - $min_sale_price, 0, ',', '.') }}đ</span>
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
                                                $displayed_min_price = $min_sale_price ?? $min_price;
                                                $displayed_max_price = $max_sale_price ?? $max_price;
                                                @endphp
                                                @if($displayed_min_price !== $displayed_max_price)
                                                {{-- Hiển thị dải giá nếu giá min và max khác nhau --}}
                                                <ins>{{ number_format($displayed_min_price, 0, ',', '.') }}đ - {{ number_format($displayed_max_price, 0, ',', '.') }}đ</ins>
                                                @else
                                                {{-- Hiển thị một giá duy nhất nếu tất cả biến thể có cùng giá --}}
                                                <ins>{{ number_format($displayed_min_price, 0, ',', '.') }}đ</ins>
                                                @endif

                                                {{-- Hiển thị giá gốc bị gạch ngang nếu có giá sale --}}
                                                @if ($min_sale_price && $min_sale_price < $min_price)
                                                    <del>{{ number_format($min_price, 0, ',', '.') }}đ</del>
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
                        </div>
                    </div>
                </div>
            </div>
            @php $p = $products; @endphp
            <div class="shopPagination">
                {{-- Prev --}}
                @if($p->onFirstPage())
                <span class="disabled"><i class="fa-solid fa-angle-left"></i></span>
                @else
                <a href="{{ $p->previousPageUrl() }}"><i class="fa-solid fa-angle-left"></i></a>
                @endif

                {{-- Pages --}}
                @for($i = 1; $i <= $p->lastPage(); $i++)
                    @if($i === $p->currentPage())
                    <span class="current">{{ $i }}</span>
                    @else
                    <a href="{{ $p->url($i) }}">{{ $i }}</a>
                    @endif
                    @endfor

                    {{-- Next --}}
                    @if($p->hasMorePages())
                    <a href="{{ $p->nextPageUrl() }}"><i class="fa-solid fa-angle-right"></i></a>
                    @else
                    <span class="disabled"><i class="fa-solid fa-angle-right"></i></span>
                    @endif
            </div>

        </div>
    </div>
    </div>
</section>
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
        transition: transform 0.3s ease;
        /* Cho hiệu ứng mượt */
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
        opacity: 0.8;
        /* Mờ nhẹ thôi */
        transform: scale(1.05);
    }

    .pi01Details {
        padding-left: 15px;
        /* dịch sang phải 10px */
    }

    .color-circle {
        margin-top: 5px;
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


    .pageBannerSection {
        padding: 20px 0;
        min-height: 10px;
    }

    .pageBannerSection .pageBannerContent h2 {
        font-size: 28px;
        margin-bottom: 10px;
    }

    .pageBannerPath {
        font-size: 14px;
    }
</style>

@push('scripts')
<script>
   // Hàm định dạng tiền tệ Việt Nam
    function formatVND(n) {
        return new Intl.NumberFormat('vi-VN', {
            style: 'currency',
            currency: 'VND'
        }).format(n).replace('₫', '') + '₫';
    }

    $(document).ready(function() {
        // Đọc giá từ Blade vào JS
        var minPrice = {{ $min }};
        var maxPrice = {{ $max }};
        var globalMin = {{ $globalMin ?? 0 }}; // Giá trị min toàn cục, bạn có thể truyền từ controller
        var globalMax = {{ $globalMax ?? 5000000 }}; // Giá trị max toàn cục

        // Tạo slider
        $("#sliderRange").slider({
            range: true,
            min: globalMin,
            max: globalMax,
            step: 10000,
            values: [minPrice, maxPrice],
            slide: function(event, ui) {
                // Cập nhật hiển thị giá
                $("#amount").text(formatVND(ui.values[0]) + ' - ' + formatVND(ui.values[1]));
                // Cập nhật giá trị vào input ẩn
                $("#price_min").val(ui.values[0]);
                $("#price_max").val(ui.values[1]);
            }
        });

        // Cập nhật hiển thị giá trị ban đầu cho thanh slider
        var initialValues = $("#sliderRange").slider("values");
        $("#amount").text(formatVND(initialValues[0]) + ' - ' + formatVND(initialValues[1]));
    });

    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.piAddToCart').forEach(function(btn) {
            btn.addEventListener('click', function() {
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
@endpush

@endsection
