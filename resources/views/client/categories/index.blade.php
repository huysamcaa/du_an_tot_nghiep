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
                            <a href="{{ route('client.home') }}">Trang chủ</a>&nbsp;&nbsp;>&nbsp;&nbsp;<span>Danh mục</span>
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
                                $activeFilters['price'] =
                                    number_format(request('price_min', $min), 0, ',', '.') .
                                    '₫ - ' .
                                    number_format(request('price_max', $max), 0, ',', '.') .
                                    '₫';
                            }
                        @endphp

                        @if (count($activeFilters))
                            <aside class="widget">
                                <h3 class="widgetTitle">Đang lọc</h3>
                                <div class="d-flex flex-wrap gap-2">
                                    @foreach ($activeFilters as $key => $value)
                                        @php
                                            $query = request()->except([$key, 'page']);
                                        @endphp
                                        <a href="{{ url()->current() . '?' . http_build_query($query) }}"
                                            class="btn btn-outline-secondary btn-sm">
                                            @if (!empty($value))
                                                {{ $value }}
                                            @endif
                                            &times;
                                        </a>
                                    @endforeach

                                    <a href="{{ url()->current() }}" class="btn btn-secondary btn-sm">
                                        Xóa tất cả
                                    </a>
                                </div>
                            </aside>
                        @endif

                        <aside class="widget">
                            <h3 class="widgetTitle">Danh mục</h3>
                            <ul class="categoryFilterList">
                                <li>
                                    <a href="{{ url()->current() }}"
                                        class="{{ is_null($selectedCategory) ? 'active' : '' }}">
                                        Tất cả
                                    </a>
                                </li>
                                @foreach ($categories as $category)
                                    @php
                                        // Kiểm tra xem danh mục cha có đang được chọn hay không
                                        $parentIsActive = (string) $selectedCategory === (string) $category->id;
                                        // Kiểm tra xem có bất kỳ danh mục con nào đang được chọn hay không
                                        $childIsSelected = $category->children->contains('id', $selectedCategory);
                                    @endphp

                                    {{-- Kiểm tra nếu danh mục cha có con --}}
                                    @if ($category->children->isNotEmpty())
                                        <li
                                            class="menu-item-has-children {{ $parentIsActive || $childIsSelected ? 'active' : '' }}">
                                            <div
                                                class="category-parent-link d-flex align-items-center justify-content-between">
                                                {{-- Link lọc sản phẩm cho danh mục cha --}}
                                                <a
                                                    href="{{ url()->current() . '?' . http_build_query(array_merge(request()->except(['category_id', 'page']), ['category_id' => $category->id])) }}">
                                                    {{ $category->name }}
                                                </a>
                                                {{-- Nút + để mở/đóng danh mục con --}}
                                                <span class="toggle-children-btn">
                                                    <i class="fa-solid fa-plus"></i>
                                                </span>
                                            </div>
                                            <ul class="sub-category-list">
                                                @foreach ($category->children as $child)
                                                    @php
                                                        $childIsActive =
                                                            (string) $selectedCategory === (string) $child->id;
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
                                            $isActive = (string) $selectedCategory === (string) $category->id;
                                        @endphp
                                        <li>
                                            <a href="{{ url()->current() . '?' . http_build_query(array_merge(request()->except(['category_id', 'page']), ['category_id' => $category->id])) }}"
                                                class="{{ $isActive ? 'active' : '' }}">
                                                {{ $category->name }}
                                                @if (isset($category->products_count))
                                                    ({{ $category->products_count }})
                                                @endif
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
                                    @foreach (request()->except(['price_min', 'price_max', 'page']) as $key => $val)
                                        <input type="hidden" name="{{ $key }}" value="{{ $val }}">
                                    @endforeach

                                    <div id="sliderRange"></div>

                                    <div class="pfsWrap d-flex align-items-center mt-2">
                                        <label class="me-2">Giá:</label>
                                        <span id="amount">{{ number_format($min, 0, ',', '.') }}₫ -
                                            {{ number_format($max, 0, ',', '.') }}₫</span>
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
                                @foreach (request()->except(['size', 'page']) as $key => $val)
                                    <input type="hidden" name="{{ $key }}" value="{{ $val }}">
                                @endforeach

                                <div class="productSizeWrap">
                                    @foreach ($availableSizes as $size)
                                        @php $id = "size_{$size}"; @endphp
                                        <div class="pswItem">
                                            <input type="radio" name="size" id="{{ $id }}"
                                                value="{{ $size }}" {{ $selectedSize === $size ? 'checked' : '' }}
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
                                @foreach (request()->except(['color', 'page']) as $k => $v)
                                    <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                                @endforeach

                                <div class="productColorWrap d-flex flex-wrap gap-2 mt-2">
                                    @foreach ($availableColors as $hex)
                                        @php $id = 'color_' . md5($hex); @endphp
                                        <div class="colorOptionWrapper text-center">
                                            <input type="radio" name="color" id="{{ $id }}"
                                                value="{{ $hex }}" {{ $selectedColor === $hex ? 'checked' : '' }}
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
                                @foreach ($availableBrands as $brandId => $brandName)
                                    <li>
                                        <a href="{{ url()->current() }}?{{ http_build_query(array_merge(request()->except(['brand', 'page']), ['brand' => $brandId])) }}"
                                            class="{{ (string) $selectedBrand === (string) $brandId ? 'active' : '' }}">
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
                                        @foreach (request()->except(['sort', 'page']) as $k => $v)
                                            <input type="hidden" name="{{ $k }}"
                                                value="{{ $v }}">
                                        @endforeach
                                        <label for="sortSelect">Sắp xếp theo</label>
                                        <select id="sortSelect" name="sort" onchange="this.form.submit()">
                                            <option value="" {{ is_null($sort) ? 'selected' : '' }}>Mặc định
                                            </option>
                                            <option value="price_desc" {{ $sort === 'price_desc' ? 'selected' : '' }}>Cao
                                                đến thấp</option>
                                            <option value="price_asc" {{ $sort === 'price_asc' ? 'selected' : '' }}>Thấp
                                                đến cao</option>
                                            <option value="newest" {{ $sort === 'newest' ? 'selected' : '' }}>Mới nhất
                                            </option>
                                        </select>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row shopProductRow">
                        <div class="col-lg-12">
                            <div class="tab-content productViewTabContent" id="productViewTabContent">
                                <div class="tab-pane show active" id="grid-tab-pane" role="tabpanel"
                                    aria-labelledby="grid-tab" tabindex="0">
                                    <div class="row">
                                        @foreach ($products as $product)
                                            @php
                                            // Lấy tất cả biến thể đang sale
                                            $active_sale_variants = $product->variants->filter(function ($variant) {
                                                return $variant->is_sale == 1 &&
                                                    $variant->sale_price &&
                                                    $variant->sale_price_start_at &&
                                                    $variant->sale_price_end_at &&
                                                    now()->between($variant->sale_price_start_at, $variant->sale_price_end_at);
                                            });

                                            // Lấy giá thấp nhất và cao nhất của các biến thể đang sale
                                            $min_sale_price = $active_sale_variants->min('sale_price');
                                            $max_sale_price = $active_sale_variants->max('sale_price');

                                            // Lấy giá thấp nhất và cao nhất của tất cả các biến thể (để so sánh)
                                            $min_price = $product->variants->min('price');
                                            $max_price = $product->variants->max('price');

                                            // Xác định giá hiển thị trên frontend
                                            $is_on_sale = $active_sale_variants->isNotEmpty();

                                            $displayed_min_price = $is_on_sale ? $min_sale_price : $min_price;
                                            $displayed_max_price = $is_on_sale ? $max_sale_price : $max_price;
                                        @endphp
                                            <div class="col-lg-4 col-md-6 col-sm-6 mb-4">
                                                @php
                                                    // Lập ma trận biến thể: id, price, sale_price + tất cả attribute động
                                                    $variantMatrix = $product->variants->map(function ($v) {
                                                        $variantData = [
                                                            'id' => (int) $v->id,
                                                            'price' => (int) $v->price,
                                                            'sale_price' => $v->sale_price ? (int) $v->sale_price : null,
                                                        ];

                                                        // Thêm tất cả attribute vào mảng variantData
                                                        foreach($v->attributeValues as $attrVal){
                                                            $slug = $attrVal->attribute->slug;
                                                            $variantData[$slug] = $attrVal->id; // luôn dùng id
                                                            if($slug==='color'){
                                                                $variantData['color_hex'] = $attrVal->hex; // chỉ để render UI
                                                            }
                                                        }
                                                        return $variantData;
                                                    })->values();
                                                @endphp

                                                <div class="productItem01" data-product-id="{{ $product->id }}"
                                                    data-variants='@json($variantMatrix)'>

                                                    <div class="pi01Thumb" style="height: auto; overflow: hidden;">
                                                        <img src="{{ asset('storage/' . $product->thumbnail) }}"
                                                            alt="{{ $product->name }}"
                                                            style="width: 100%; height: auto; object-fit: cover;" />
                                                        <img src="{{ asset('storage/' . $product->thumbnail) }}"
                                                            alt="{{ $product->name }}"
                                                            style="width: 100%; height: auto; object-fit: cover;" />
                                                        <div class="pi01Actions" data-product-id="{{ $product->id }}">
                                                            <a href="javascript:void(0)" class="piAddToCart"
                                                                data-id="{{ $product->id }}">
                                                                <i class="fa-solid fa-shopping-cart"></i>
                                                            </a>
                                                            <form id="add-to-cart-form-{{ $product->id }}"
                                                                style="display:none;">
                                                                @csrf
                                                                <input type="hidden" name="product_id"
                                                                    value="{{ $product->id }}">
                                                                <input type="hidden" name="quantity" value="1">
                                                                <input type="hidden" name="product_variant_id"
                                                                    id="variant_input_{{ $product->id }}">

                                                            </form>
                                                            <a href="{{ route('product.detail', $product->id) }}"><i
                                                                    class="fa-solid fa-eye"></i></a>
                                                        </div>
                                                       {{-- Hiển thị nhãn SALE nếu có biến thể đang sale --}}
                                                    @if ($is_on_sale)
                                                        <div class="productLabels clearfix">
                                                            @if ($min_sale_price && $min_sale_price < $min_price)
                                                                <span class="plDis">-{{ number_format($min_price - $min_sale_price, 0, ',', '.') }}₫</span>
                                                            @endif
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


                                                        @php
                                                            $displayed_min_price = $min_sale_price ?? $min_price;
                                                            $displayed_max_price = $max_sale_price ?? $max_price;
                                                        @endphp
                                                        <div class="pi01Price">
                                                            @if ($displayed_min_price !== $displayed_max_price)
                                                                <ins class="js-price">{{ number_format($displayed_min_price, 0, ',', '.') }}đ
                                                                    -
                                                                    {{ number_format($displayed_max_price, 0, ',', '.') }}đ</ins>
                                                                <del class="js-compare d-none"></del>
                                                            @else
                                                                <ins
                                                                    class="js-price">{{ number_format($displayed_min_price, 0, ',', '.') }}đ</ins>
                                                                <del
                                                                    class="js-compare @if (!($min_sale_price && $min_sale_price < $min_price)) d-none @endif">
                                                                    @if ($min_sale_price && $min_sale_price < $min_price)
                                                                        {{ number_format($min_price, 0, ',', '.') }}đ
                                                                    @endif
                                                                </del>
                                                            @endif
                                                        </div>
                                                        {{-- Hiển thị tất cả các thuộc tính động --}}
                                                        @if($variantMatrix->count())
                                                            <div class="variant-selectors mt-2">
                                                                @php
                                                                    // Lấy danh sách attribute của sản phẩm
                                                                    $attributesForUI = $variantMatrix->flatMap(function($v) {
                                                                        return collect($v)->except(['id','price','sale_price']);
                                                                    })->keys()->unique();
                                                                @endphp

                                                                @foreach($attributesForUI as $attr)
                                                                    <div class="variant-group variant-{{ $attr }} d-flex align-items-center gap-1 mb-2">
                                                                        <strong class="me-2">{{ ucfirst($attr) }}:</strong>
                                                                        @php
                                                                            $values = $variantMatrix->pluck($attr)->filter()->unique()->values();
                                                                        @endphp

                                                                        @foreach($values as $val)
                                                                            @php
                                                                                $inputId = "{$attr}-{$product->id}-".\Illuminate\Support\Str::slug($val);

                                                                                // Lấy thông tin attributeValue để render (chỉ color cần HEX)
                                                                                $attrValData = $product->variants->flatMap(function($v) use($attr, $val){
                                                                                    return $v->attributeValues->filter(fn($av)=>$av->attribute->slug==$attr && $av->id==$val);
                                                                                })->first();
                                                                            @endphp

                                                                            <input type="radio" id="{{ $inputId }}"
                                                                                name="{{ $attr }}_{{ $product->id }}"
                                                                                class="visually-hidden js-attr-{{ $attr }}"
                                                                                value="{{ $val }}">

                                                                            @if($attr === 'color' && $attrValData)
                                                                                <label for="{{ $inputId }}" class="color-swatch"
                                                                                    style="background-color: {{ $attrValData->hex }}"
                                                                                    title="{{ $attrValData->value }}"></label>
                                                                            @else
                                                                                <label for="{{ $inputId }}" class="size-pill">
                                                                                    {{ $attrValData?->value ?? $val }}
                                                                                </label>
                                                                            @endif
                                                                        @endforeach

                                                                    </div>
                                                                @endforeach
                                                                {{-- Hidden input để gửi attribute_values --}}
<input type="hidden" name="attribute_values[]" class="js-attribute-values" value="">
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
                        @if ($p->onFirstPage())
                            <span class="disabled"><i class="fa-solid fa-angle-left"></i></span>
                        @else
                            <a href="{{ $p->previousPageUrl() }}"><i class="fa-solid fa-angle-left"></i></a>
                        @endif

                        {{-- Pages --}}
                        @for ($i = 1; $i <= $p->lastPage(); $i++)
                            @if ($i === $p->currentPage())
                                <span class="current">{{ $i }}</span>
                            @else
                                <a href="{{ $p->url($i) }}">{{ $i }}</a>
                            @endif
                        @endfor

                        {{-- Next --}}
                        @if ($p->hasMorePages())
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

        /* CSS cho phần danh mục */
        .category-parent-link {
            cursor: pointer;
            padding-right: 15px;
            /* Thêm khoảng trống cho nút toggle */
        }

        .toggle-children-btn {
            cursor: pointer;
            font-size: 14px;
            padding: 5px;
        }

        /* Đảm bảo danh mục con luôn ẩn khi không có class show */
        .categoryFilterList .sub-category-list {
            display: none;
            list-style: none;
            padding-left: 20px;
            margin-top: 5px;
        }

        /* Tăng độ ưu tiên để buộc hiển thị danh mục con khi có class show */
        .categoryFilterList .sub-category-list.show {
            display: block !important;
        }

        .color-swatch {
            width: 18px;
            height: 18px;
            border-radius: 50%;
            display: inline-block;
            border: 1px solid #ccc;
            cursor: pointer;
        }

        .size-pill {
            padding: 2px 8px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 12px;
            cursor: pointer;
            background: #fff;
        }

        .color-swatch.is-disabled,
        .size-pill.is-disabled {
            opacity: .4;
            cursor: not-allowed;
            text-decoration: line-through;
        }

        input.visually-hidden {
            position: absolute;
            left: -9999px;
        }

        .piAddToCart.disabled {
            opacity: .6;
            pointer-events: none;
        }
        /* Màu nhấn chung */
:root { --accent: #5d7373; }

/* Tô viền/bo tròn khi CHỌN MÀU */
.productItem01 input.js-color:checked + .color-swatch {
  outline: 2px solid #fff;               /* viền trong */
  box-shadow: 0 0 0 3px var(--accent);   /* vòng sáng ngoài */
  transform: scale(1.08);
  border-color: var(--accent);
}

/* Hover/focus cho MÀU */
.productItem01 .color-swatch:hover { transform: scale(1.06); }
.productItem01 input.js-color:focus-visible + .color-swatch {
  outline: 2px dashed var(--accent);
  outline-offset: 2px;
}

/* Tô nền khi CHỌN SIZE */
.productItem01 input.js-size:checked + .size-pill {
  background: var(--accent);
  color: #fff;
  border-color: var(--accent);
  box-shadow: 0 2px 8px rgba(0,0,0,.12);
  transform: translateY(-1px);
}

/* Hover/focus cho SIZE */
.productItem01 .size-pill:hover { border-color: var(--accent); }
.productItem01 input.js-size:focus-visible + .size-pill {
  outline: 2px dashed var(--accent);
  outline-offset: 2px;
}

/* Trạng thái bị vô hiệu hoá (đã có class is-disabled trong JS) */
.productItem01 .color-swatch.is-disabled,
.productItem01 .size-pill.is-disabled {
  opacity: .35;
  filter: grayscale(.2);
  cursor: not-allowed;
  text-decoration: line-through;
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

    // document.addEventListener('DOMContentLoaded', function() {
    //     document.querySelectorAll('.piAddToCart').forEach(function(btn) {
    //         btn.addEventListener('click', function() {
    //             let productId = this.getAttribute('data-id');
    //             let form = document.getElementById('add-to-cart-form-' + productId);
    //             let formData = new FormData(form);


    //             fetch("{{ route('cart.add') }}", {
    //                     method: 'POST',
    //                     body: formData,
    //                     headers: {
    //                         'X-CSRF-TOKEN': formData.get('_token'),
    //                         'X-Requested-With': 'XMLHttpRequest' // <--- thêm dòng này
    //                     }
    //                 })
    //                 .then(res => res.json())
    //                 .then(data => {
    //                     if (data.success) {
    //                         alert('Đã thêm vào giỏ hàng!');
    //                     } else {
    //                         alert(data.message || 'Có lỗi xảy ra!');
    //                     }
    //                 })
    //                 .catch(err => {
    //                     console.error(err);
    //                     alert('Không thể thêm sản phẩm vào giỏ!');
    //                 });
    //         });
    //     });
    // });

   (function() {
  function openActiveSubmenu() {
    const activeParentLi = document.querySelector('.menu-item-has-children.active');
    if (activeParentLi) {
      const subMenu = activeParentLi.querySelector('.sub-category-list');
      const icon = activeParentLi.querySelector('.toggle-children-btn i');
      if (subMenu && icon) {
        subMenu.classList.add('show');
        icon.classList.remove('fa-plus');
        icon.classList.add('fa-minus');
      }
    }
  }

  function setupToggleListeners() {
    const toggleButtons = document.querySelectorAll('.toggle-children-btn');
    toggleButtons.forEach(button => {
      button.addEventListener('click', function(e) {
        e.preventDefault();
        const parentLi = this.closest('li');
        const subMenu = parentLi.querySelector('.sub-category-list');
        const icon = this.querySelector('i');

        subMenu.classList.toggle('show');

        if (subMenu.classList.contains('show')) {
          icon.classList.remove('fa-plus');
          icon.classList.add('fa-minus');
        } else {
          icon.classList.remove('fa-minus');
          icon.classList.add('fa-plus');
        }
      });
    });
  }

  document.addEventListener('DOMContentLoaded', function() {
    openActiveSubmenu();
    setupToggleListeners();
  });
})();


// ================== VARIANT UI + ADD TO CART (mới, gộp 1 chỗ) ==================
(function(){
    // ===== Helpers =====
    function formatVND(n){
        try { return new Intl.NumberFormat('vi-VN').format(n) + 'đ'; }
        catch(e){ return (n||0).toLocaleString('vi-VN') + 'đ'; }
    }

    function safeParse(s){ try { return JSON.parse(s||'[]'); } catch(e){ return []; } }

    const q = (sel, root=document)=> root.querySelector(sel);
    const qa = (sel, root=document)=> Array.from(root.querySelectorAll(sel));

    const norm = (s)=> (s||'').toString().trim().toLowerCase().replace(/^#/,'');

    document.addEventListener('DOMContentLoaded', function(){
        // Khởi tạo cho tất cả card sản phẩm có variants
        qa('.productItem01[data-variants]').forEach(initVariantUI);

        // Gắn listener add-to-cart
        qa('.piAddToCart').forEach(btn=>{
            if(btn.dataset.bound==='1') return;
            btn.dataset.bound='1';
            btn.addEventListener('click', onAddToCartClick);
        });
    });

    // Cho phép click lại label để bỏ chọn
    document.addEventListener('click', function(e){
        const lbl = e.target.closest('label[class^="color-swatch"], label[class^="size-pill"], label[class^="js-attr-"]');
        if(!lbl) return;
        const inp = document.getElementById(lbl.getAttribute('for'));
        if(inp && inp.checked){
            inp.checked = false;
            inp.dispatchEvent(new Event('change', {bubbles:true}));
        }
    });

    // ===== Add to cart =====
    function onAddToCartClick(e){
        e.preventDefault();
        const btn = e.currentTarget;
        const card = btn.closest('.productItem01');
        const pid = card?.dataset.productId;
        const variants = safeParse(card?.dataset.variants).map(v=> {
            const newV = {...v};
            if(v.color) newV.color = norm(v.color);
            return newV;
        });
        const needVariant = variants.length>0;
        const form = document.getElementById('add-to-cart-form-'+pid);
        const fd = new FormData(form);

        // Lấy variant id từ hidden
        let variantId = (document.getElementById('variant_input_'+pid)?.value||'').toString().trim();

        if(needVariant && !variantId){
            const selected = {};
            qa('input[class^="js-attr-"]:checked', card).forEach(i=>{
                const attr = i.className.match(/js-attr-(\w+)/)?.[1];
                if(attr) selected[attr] = i.value;
            });
            const match = variants.find(v => Object.keys(selected).every(k => (v[k]+''===selected[k]+'')));
            if(!match){
                alert('Vui lòng chọn đầy đủ thuộc tính trước khi thêm vào giỏ.');
                return;
            }
            variantId = match.id;
        }

        if(variantId) fd.set('product_variant_id', variantId);

        fetch("{{ route('cart.add') }}", {
            method:'POST',
            body: fd,
            headers: {'X-CSRF-TOKEN': fd.get('_token'), 'X-Requested-With':'XMLHttpRequest'}
        })
        .then(res=>res.json())
        .then(data=>{
            if(data.unauthenticated){
                if(confirm('Bạn cần đăng nhập. Chuyển đến trang đăng nhập?')) location.href='/login';
                return;
            }
            if(data.success){
                const cartCountEl = document.querySelector('.anCart span');
                if(cartCountEl && typeof data.totalProduct!=='undefined') cartCountEl.textContent = data.totalProduct;
                const cartWidgetArea = document.querySelector('.cartWidgetArea');
                if(cartWidgetArea && data.cartIcon) cartWidgetArea.innerHTML = data.cartIcon;
                alert('Đã thêm vào giỏ hàng!');
            } else {
                alert(data.message||'Có lỗi xảy ra!');
            }
        })
        .catch(err=>{ console.error(err); alert('Không thể thêm sản phẩm vào giỏ!'); });
    }

    // ===== Variant UI =====
    function initVariantUI(card){
        const pid = card.dataset.productId;
        const variants = safeParse(card.dataset.variants).map(v=>{
            if(v.color) v.color = norm(v.color);
            return v;
        });

        const priceIns = q('.pi01Price .js-price', card);
        const priceDel = q('.pi01Price .js-compare', card);
        const addBtn   = q('.pi01Actions .piAddToCart', card);
        const hidden   = document.getElementById('variant_input_'+pid);

        const attrInputs = qa('input[class^="js-attr-"]', card);

        if(variants.length && addBtn){
            addBtn.classList.add('disabled');
            addBtn.setAttribute('aria-disabled','true');
        }

        function getSelectedAttrs(){
            const selected={};
            attrInputs.forEach(i=>{
                if(i.checked){
                    const attr = i.className.match(/js-attr-(\w+)/)?.[1];
                    if(attr) selected[attr]=i.value;
                }
            });
            return selected;
        }

        function refreshOptions(){
            const selected = getSelectedAttrs();
            attrInputs.forEach(inp=>{
                const attr = inp.className.match(/js-attr-(\w+)/)?.[1];
                const otherSelected = {...selected}; delete otherSelected[attr];

                const allowed = variants.filter(v=> Object.keys(otherSelected).every(k=> !otherSelected[k]|| (v[k]+''===otherSelected[k]+'')))
                                        .map(v=>v[attr]);
                const allowedSet = new Set(allowed.filter(Boolean));
                const lb = card.querySelector('label[for="'+inp.id+'"]');
                if(allowedSet.size && !allowedSet.has(inp.value)){
                    inp.checked=false;
                    inp.disabled=true;
                    lb && lb.classList.add('is-disabled');
                } else {
                    inp.disabled=false;
                    lb && lb.classList.remove('is-disabled');
                }
            });
        }

        function refreshPriceAndHidden(){
            const selected = getSelectedAttrs();
            const match = variants.find(v => Object.keys(selected).every(k=> (v[k]+''===selected[k]+'')));
            if(match){
                if(hidden) hidden.value = match.id;
                if(addBtn){
                    addBtn.classList.remove('disabled');
                    addBtn.removeAttribute('aria-disabled');
                }
                if(priceIns) priceIns.textContent = formatVND(match.sale_price??match.price);
                if(priceDel){
                    if(match.sale_price && match.sale_price<match.price){
                        priceDel.textContent = formatVND(match.price);
                        priceDel.classList.remove('d-none');
                    } else {
                        priceDel.textContent='';
                        priceDel.classList.add('d-none');
                    }
                }
            } else {
                if(hidden) hidden.value='';
                if(addBtn){
                    addBtn.classList.add('disabled');
                    addBtn.setAttribute('aria-disabled','true');
                }
                if(priceIns) priceIns.textContent='';
                if(priceDel){ priceDel.textContent=''; priceDel.classList.add('d-none'); }
            }
        }

function updateAttributeValuesHidden(card) {
    const hiddenInputs = qa('.js-attribute-values', card);
    const selectedValues = qa('input[class^="js-attr-"]:checked', card)
        .map(i => i.value);
    hiddenInputs.forEach(inp => inp.value = selectedValues.join(',')); // hoặc lưu từng input nếu controller xử lý mảng
}

// Trong event change:
card.addEventListener('change', function(e){
    if(e.target.className.match(/js-attr-/)){
        refreshOptions();
        refreshPriceAndHidden();
        updateAttributeValuesHidden(card); // cập nhật hidden
    }
});


        // Khởi tạo lần đầu
        refreshOptions();
        refreshPriceAndHidden();
    }
})();
</script>
    @endpush

@endsection
