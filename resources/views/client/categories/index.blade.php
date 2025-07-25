@extends('client.layouts.app')

@section('title', 'Danh mục sản phẩm')

@section('content')
<!-- BEGIN: Page Banner Section -->
<section class="pageBannerSection">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="pageBannerContent text-center">
                    <h2>Mua sắn với FreshFit</h2>
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
                                    @forelse($products as $product)
                                    <div class="col-sm-6 col-xl-4 mb-4">
                                        <div class="productItem01">
                                            <div class="pi01Thumb">
                                                <a href="{{ route('product.detail', $product->id) }}">
                                                    <img src="{{ asset('storage/' . $product->thumbnail) }}" alt="{{ $product->name }}" />
                                                    @if($product->galleries->count() > 0)
                                                    <img src="{{ asset('storage/' . $product->galleries->first()->path) }}" alt="{{ $product->name }}" />
                                                    @endif
                                                </a>
                                                <div class="pi01Actions">
                                                    <a href="javascript:void(0);" class="pi01Cart"><i class="fa-solid fa-shopping-cart"></i></a>
                                                    <a href="javascript:void(0);" class="pi01QuickView"><i class="fa-solid fa-arrows-up-down-left-right"></i></a>
                                                    <a href="javascript:void(0);" class="pi01Wishlist"><i class="fa-solid fa-heart"></i></a>
                                                    <a href="{{ route('product.detail', $product->id) }}"><i class="fa-solid fa-arrows-up-down-left-right"></i></a>
                                                </div>
                                                @if($product->is_sale)
                                                <div class="productLabels clearfix">
                                                    <span class="plDis">-{{ round((1 - $product->sale_price/$product->price)*100) }}%</span>
                                                    <span class="plSale">Sale</span>
                                                </div>
                                                @endif
                                            </div>
                                            <div class="pi01Details">
                                                <div class="productRatings">
                                                    <div class="productRatingWrap">
                                                        <div class="star-rating"><span></span></div>
                                                    </div>
                                                    <div class="ratingCounts">{{ $product->reviews->count() }} Reviews</div>
                                                </div>
                                                <h3>
                                                    <a href="{{ route('product.detail', $product->id) }}">
                                                        {{ Str::limit($product->name, 40) }}
                                                    </a>
                                                </h3>
                                                <div class="pi01Price">
                                                    @if($product->is_sale)
                                                    <ins>{{ number_format($product->sale_price,0,',','.') }}₫</ins>
                                                    <del>{{ number_format($product->price,0,',','.') }}₫</del>
                                                    @else
                                                    <ins>{{ number_format($product->price,0,',','.') }}₫</ins>
                                                    @endif
                                                </div>
                                                {{-- hiển thị color variants --}}
                                                @if(optional($product->variantsWithAttributes())->count())
                                                @php
                                                // Lấy danh sách màu
                                                $colors = collect();
                                                foreach($product->variantsWithAttributes() as $variant) {
                                                foreach($variant->attributeValues as $attrVal) {
                                                if($attrVal->attribute->slug === 'color') {
                                                $colors->push($attrVal);
                                                }
                                                }
                                                }
                                                $colors = $colors->unique('id');

                                                // Lấy danh sách size
                                                $sizes = $product->variantsWithAttributes()
                                                ->flatMap(fn($v) => $v->attributeValues->filter(fn($val) => $val->attribute->slug === 'size'))
                                                ->unique('id');
                                                @endphp

                                                <div class="pi01Variations">
                                                    @if($colors->isNotEmpty())
                                                    <div class="pi01VColor">
                                                        @foreach($colors as $color)
                                                        <div class="colorOptionWrapper">
                                                            <input
                                                                type="radio"
                                                                name="color_{{ $product->id }}"
                                                                id="color_{{ $product->id }}_{{ $color->id }}"
                                                                value="{{ $color->value }}"
                                                                hidden>
                                                            <label
                                                                for="color_{{ $product->id }}_{{ $color->id }}"
                                                                class="customColorCircle"
                                                                style="background-color: {{ Str::start($color->hex, '#') }};"
                                                                title="{{ ucfirst($color->value) }}"></label>
                                                        </div>
                                                        @endforeach
                                                    </div>
                                                    @endif

                                                    @if($sizes->count())
                                                    <div class="pi01VSize">
                                                        @foreach($sizes as $size)
                                                        <div class="pi01VSItem">
                                                            <input
                                                                type="radio"
                                                                name="size_{{ $product->id }}"
                                                                id="size_{{ $product->id }}_{{ $size->id }}"
                                                                value="{{ $size->value }}">
                                                            <label for="size_{{ $product->id }}_{{ $size->id }}">
                                                                {{ strtoupper($size->value) }}
                                                            </label>
                                                        </div>
                                                        @endforeach
                                                    </div>
                                                    @endif
                                                </div>
                                                @endif

                                            </div>
                                        </div>
                                    </div>
                                    @empty
                                    <p class="text-center">Chưa có sản phẩm nào.</p>
                                    @endforelse
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
</style>

@push('scripts')
<script>
    $(window).on('load', function() {
        // Hủy slider mặc định
        if ($("#sliderRange").hasClass("ui-slider")) {
            $("#sliderRange").slider("destroy");
        }
        // Đọc giá từ Blade vào JS
        var minPrice = {
            {
                $min
            }
        };
        var maxPrice = {
            {
                $max
            }
        };
        var globalMin = 0;
        var globalMax = 5000000;

        function formatVND(n) {
            return n.toLocaleString('vi-VN') + ' đ';
        }

        $("#sliderRange").slider({
            range: true,
            min: globalMin,
            max: globalMax,
            step: 10000,
            values: [minPrice, maxPrice],
            slide: function(event, ui) {
                $("#amount").text(formatVND(ui.values[0]) + ' – ' + formatVND(ui.values[1]));
                $("#price_min").val(ui.values[0]);
                $("#price_max").val(ui.values[1]);
            }
        });

        // Khởi tạo hiển thị ban đầu
        var init = $("#sliderRange").slider("values");
        $("#amount").text(formatVND(init[0]) + ' – ' + formatVND(init[1]));
    });
</script>
@endpush

@endsection
