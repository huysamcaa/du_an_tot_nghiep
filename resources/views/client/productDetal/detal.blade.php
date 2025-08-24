@extends('client.layouts.app')

@section('content')
<style>
    /* Container mỗi thuộc tính
.pcVariation {
    margin-bottom: 15px;
} */

/* Container các option */
/* .pcvContainer {
    display: flex;
    gap: 8px;
} */

/* Ẩn radio input */
.attributeOptionWrapper input[type="radio"] {
    opacity: 0;
    visibility: hidden;
    width: 0;
    height: 0;
    position: absolute;
}

/* Vòng màu */
.attributeOptionWrapper label.color {
    display: inline-block;
    margin: 5px 0 0 15px;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    border: 1px solid #ccc;
    cursor: pointer;
    transition: all 0.2s;
}

.attributeOptionWrapper input[type="radio"]:checked + label.color {
    border: 2px solid #333; /* viền khi chọn */
}

/* Button chữ */
.attributeOptionWrapper label.text {
    border: 1px solid #e5e5e5;
    width: auto;
    height: 36px;
    text-align: center;
    border-radius: 3px;
    font-size: 16px;
    font-weight: 400;
    color: #7f8495;
    background: #FFF;
    line-height: 36px;
    cursor: pointer;
    padding: 0 10px;
    margin-left: 15px;
    transition: all ease 350ms;
    -moz-transition: all ease 350ms;
    -webkit-transition: all ease 350ms;
}

.attributeOptionWrapper input[type="radio"]:checked + label.text {
    color: #FFF;
    border-color: #7b9496;
    background: #7b9496;
}

/* Hover hiệu ứng */
.attributeOptionWrapper label:hover {
    border-color: #232a27;
}

</style>
<!-- BEGIN: Shop Details Section -->
<section class="shopDetailsPageSection">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <div class="productGalleryWrap">
                    <div class="productGallery">
                        {{-- Hiển thị ảnh chính của sản phẩm --}}
                        <div class="pgImage">
                            <img id="main-product-image" src="{{ asset('storage/' . $product->thumbnail) }}" alt="{{ $product->name }}" />
                        </div>

                        {{-- Hiển thị các ảnh chi tiết của sản phẩm (nếu có) --}}
                        @if ($product->galleries)
                        @foreach ($product->galleries as $image)
                        <div class="pgImage">
                            {{-- Sửa lỗi ở đây --}}
                            <img src="{{ asset('storage/' . $image->image) }}" alt="{{ $product->name }} - Ảnh chi tiết" />
                        </div>
                        @endforeach
                        @endif

                        {{-- Hiển thị ảnh của các biến thể --}}
                        @foreach ($product->variants as $variant)
                        @if ($variant->thumbnail)
                        <div class="pgImage">
                            <img src="{{ asset('storage/' . $variant->thumbnail) }}" alt="{{ $product->name }} - Biến thể" />
                        </div>
                        @endif
                        @endforeach
                    </div>

                    <div class="productGalleryThumbWrap">
                        <div class="productGalleryThumb">
                            {{-- Hiển thị ảnh chính của sản phẩm --}}
                            <div class="pgtImage">
                                <img src="{{ asset('storage/' . $product->thumbnail) }}" alt="{{ $product->name }}" />
                            </div>

                            {{-- Hiển thị các ảnh chi tiết của sản phẩm (nếu có) --}}
                            @if ($product->galleries)
                            @foreach ($product->galleries as $image)
                            <div class="pgtImage">
                                {{-- Sửa lỗi ở đây --}}
                                <img src="{{ asset('storage/' . $image->image) }}" alt="{{ $product->name }} - Ảnh chi tiết" />
                            </div>
                            @endforeach
                            @endif

                            {{-- Hiển thị ảnh của các biến thể --}}
                            @foreach ($product->variants as $variant)
                            @if ($variant->thumbnail)
                            <div class="pgtImage">
                                <img src="{{ asset('storage/' . $variant->thumbnail) }}" alt="{{ $product->name }} - Biến thể" />
                            </div>
                            @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="productContent">
                    <div class="pcCategory">
                        {{-- Kiểm tra xem danh mục của sản phẩm có danh mục cha hay không --}}
                        @if($product->category->parent)
                            {{-- Nếu có, đây là danh mục con. Hiển thị cả danh mục cha và danh mục con. --}}
                            <a href="{{ route('client.categories.index', ['category_id' => $product->category->parent->id]) }}">
                                {{ $product->category->parent->name }}
                            </a>,
                            <a href="{{ route('client.categories.index', ['category_id' => $product->category->id]) }}">
                                {{ $product->category->name }}
                            </a>
                        @else
                            {{-- Nếu không có danh mục cha, đây là danh mục cấp cao nhất. Chỉ hiển thị tên danh mục. --}}
                            <a href="{{ route('client.categories.index', ['category_id' => $product->category->id]) }}">
                                {{ $product->category->name }}
                            </a>
                        @endif
                    </div>
                    <h2>{{ $product->name }}</h2>

                    {{-- Logic hiển thị giá sản phẩm theo khoảng giá của biến thể --}}
                    <div class="pi01Price">
                        @php
                        $minPrice = $product->variants->min(function($variant) {
                        return $variant->sale_price > 0 ? $variant->sale_price : $variant->price;
                        });
                        $maxPrice = $product->variants->max(function($variant) {
                        return $variant->sale_price > 0 ? $variant->sale_price : $variant->price;
                        });

                        $minOriginalPrice = $product->variants->min('price');
                        $maxOriginalPrice = $product->variants->max('price');
                        @endphp

                        <ins id="sale-price">
                            @if ($minPrice == $maxPrice)
                            {{ number_format($minPrice, 0, ',', '.') }} đ
                            @else
                            {{ number_format($minPrice, 0, ',', '.') }} - {{ number_format($maxPrice, 0, ',', '.') }} đ
                            @endif
                        </ins>

                        @if ($minOriginalPrice > $minPrice || $maxOriginalPrice > $maxPrice)
                        <del id="original-price">
                            @if ($minOriginalPrice == $maxOriginalPrice)
                            {{ number_format($minOriginalPrice, 0, ',', '.') }} đ
                            @else
                            {{ number_format($minOriginalPrice, 0, ',', '.') }} - {{ number_format($maxOriginalPrice, 0, ',', '.') }} đ
                            @endif
                        </del>
                        @else
                        <del id="original-price" style="display:none;"></del>
                        @endif
                    </div>


                    @php
                    $avg = round($allReviews->avg('rating') ?? 0, 1);
                    $count = $allReviews->count();
                    $full = floor($avg);
                    $half = $avg - $full >= 0.5 ? 1 : 0;
                    $empty = 5 - $full - $half;
                    @endphp

                    <div class="d-flex align-items-center gap-2">
                        <div>
                            @for ($i = 0; $i < $full; $i++)
                                <i class="fa-solid fa-star text-warning"></i>
                                @endfor
                                @if ($half)
                                <i class="fa-solid fa-star-half-stroke text-warning"></i>
                                @endif
                                @for ($i = 0; $i < $empty; $i++)
                                    <i class="fa-regular fa-star text-warning"></i>
                                    @endfor
                        </div>

                        <strong class="ms-2">{{ $avg }}/5 </strong>
                        <span class="text-muted ms-1">({{ $count }} đánh giá)</span>
                    </div>


                    <div class="pcExcerpt">
                        <p>{{ $product->short_description }}</p>
                    </div>
                    <form id="addToCartForm" method="POST" action="{{ route('cart.add') }}">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}" />
                        
                        @foreach($productAttributes as $attrSlug => $attr)
                            <div class="pcVariation">
                                <span class="mt-2">{{ $attr['label'] }}</span>
                                <div class="pcvContainer">
                                    @foreach($attr['values'] as $value)
                                        <div class="attributeOptionWrapper">
                                            <input type="radio"
                                                name="attribute_values[{{ $attrSlug }}]"
                                                value="{{ $value->id }}"
                                                id="attr_{{ $value->id }}"
                                                hidden>
                                                @if($attrSlug === 'color')
                                                    <label for="attr_{{ $value->id }}" class="color" style="background:{{ $value->hex }};"></label>
                                                @else
                                                    <label for="attr_{{ $value->id }}" class="text">{{ $value->value }}</label>
                                                @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                        <div class="productStock mb-3">
                            <span>Số Lượng: </span>
                            <span id="stock-quantity">--</span>
                        </div>
                        <div class="pcBtns">
                            <div class="quantity-product">
                                <button type="button" name="btnMinus" class="qtyBtn btnMinus">-</button>
                                <input type="number" class="carqty input-text qty text" name="quantity" value="1"
                                    min="1">
                                <button type="button" name="btnPlus" class="qtyBtn btnPlus">+</button>
                            </div>
                            <br>
                            <button type="submit" id="add-to-cart" class="ulinaBTN" disabled><span>Vui lòng chọn thuộc tính</span></button>
                            <a href="javascript:void(0);" data-product-id="{{ $product->id }}" class="pcWishlist">
                                <i class="fa-solid fa-heart {{ $isFavorite ? 'text-danger' : '' }}"></i></a>
                            <a href="javascript:void(0);" class="pcCompare"><i class="fa-solid fa-right-left"></i></a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Tab sản phẩm -->
        <div class="row productTabRow">
            <div class="col-lg-12">
                <ul class="nav productDetailsTab" id="productDetailsTab" role="tablist">
                    <li role="presentation">
                        <button class="active" id="description-tab" data-bs-toggle="tab" data-bs-target="#description"
                            type="button" role="tab" aria-controls="description" aria-selected="true">Chi tiết
                            sản phẩm</button>
                    </li>
                    <li role="presentation">
                        <button id="additionalinfo-tab" data-bs-toggle="tab" data-bs-target="#additionalinfo"
                            type="button" role="tab" aria-controls="additionalinfo" aria-selected="false"
                            tabindex="-1">Additional Information</button>
                    </li>
                    <li role="presentation">
                        <button id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews" type="button"
                            role="tab" aria-controls="reviews" aria-selected="false" tabindex="-1">Bình
                            luận</button>
                    </li>
                </ul>
                <div class="tab-content" id="desInfoRev_content">
                    <div class="tab-pane fade show active" id="description" role="tabpanel"
                        aria-labelledby="description-tab" tabindex="0">
                        <div class="productDescContentArea">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="descriptionContent">
                                        <h3>Mô tả chi tiết</h3>
                                        <div class="description-scrollable">
                                            <p>{{ $product->short_description }}</p>
                                            {!! $product->description !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="additionalinfo" role="tabpanel"
                        aria-labelledby="additionalinfo-tab" tabindex="0">
                        <div class="additionalContentArea">
                            <h3>Additional Information</h3>
                            <table>
                                <tbody>
                                    <tr>
                                        <th>Item Code</th>
                                        <td>AB42 - 2394 - DS023</td>
                                    </tr>
                                    <tr>
                                        <th>Brand</th>
                                        <td>Ulina</td>
                                    </tr>
                                    <tr>
                                        <th>Dimention</th>
                                        <td>12 Cm x 42 Cm x 20 Cm</td>
                                    </tr>
                                    <tr>
                                        <th>Specification</th>
                                        <td>1pc dress, 1 pc soap, 1 cleaner</td>
                                    </tr>
                                    <tr>
                                        <th>Weight</th>
                                        <td>2 kg</td>
                                    </tr>
                                    <tr>
                                        <th>Warranty</th>
                                        <td>1 year</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="reviews" role="tabpanel" aria-labelledby="reviews-tab"
                        tabindex="0">
                        <div class="productReviewArea">
                            <div class="row">
                                <div class="col-lg-6">
                                    @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul class="mb-0">
                                            @foreach ($errors->all() as $err)
                                            <li>{{ $err }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    @endif

                                    <div class="review-section mt-4">

                                        <h4 class="mt-4" id="reviews">Đánh giá sản phẩm</h4><br>

                                        {{-- BỘ LỌC ĐÁNH GIÁ + SẮP XẾP --}}
                                        <div
                                            class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-4 px-3 py-3 border rounded bg-light shadow-sm">

                                            {{-- Bộ lọc theo số sao --}}
                                            <div class="d-flex flex-wrap align-items-center gap-2">
                                                <strong class="me-2">Lọc theo sao:</strong>
                                                <a href="{{ request()->url() }}#reviews"
                                                    class="btn btn-sm {{ request('rating') == null ? 'btn-warning text-white' : 'btn-outline-secondary' }}">
                                                    Tất cả
                                                </a>
                                                @for ($i = 5; $i >= 1; $i--)
                                                <a href="{{ request()->fullUrlWithQuery(['rating' => $i]) }}#reviews"
                                                    class="btn btn-sm {{ request('rating') == $i ? 'btn-warning text-white' : 'btn-outline-secondary' }}">
                                                    {{ $i }} <i
                                                        class="fa-solid fa-star text-warning"></i>
                                                </a>
                                                @endfor



                                            </div>

                                            {{-- Bộ lọc sắp xếp --}}
                                            <form method="GET" class="d-flex align-items-center">
                                                <div class="input-group input-group-sm" style="min-width: 200px;">
                                                    <label class="input-group-text bg-light"><i
                                                            class="fa-solid fa-sort"></i></label>
                                                    <select name="sort" class="form-select"
                                                        onchange="this.form.submit()">
                                                        <option value="">Sắp xếp theo</option>
                                                        <option value="latest"
                                                            {{ request('sort') == 'latest' ? 'selected' : '' }}>Mới
                                                            nhất</option>
                                                        <option value="highest"
                                                            {{ request('sort') == 'highest' ? 'selected' : '' }}>Sao
                                                            cao nhất</option>
                                                        <option value="lowest"
                                                            {{ request('sort') == 'lowest' ? 'selected' : '' }}>Sao
                                                            thấp nhất</option>
                                                    </select>
                                                </div>
                                            </form>

                                        </div>

                                        {{-- Trung bình đánh giá --}}
                                        @if ($allReviews->count())
                                        <div class="p-3 mb-4 border rounded bg-light shadow-sm">
                                            <h5 class="mb-2">Đánh giá trung bình</h5>
                                            <div class="d-flex align-items-center">
                                                <span class="fs-4 fw-semibold text-warning me-2">
                                                    {{ number_format($allReviews->avg('rating'), 1) }}
                                                    <i class="fa-solid fa-star text-warning"></i>
                                                </span>
                                                <span class="text-muted">
                                                    từ {{ $allReviews->count() }} đánh giá
                                                </span>
                                            </div>
                                        </div>
                                        @endif

                                        <hr class="my-4">

                                        {{-- Danh sách đánh giá --}}
                                        @forelse ($reviews as $review)
                                        <div class="review-box">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <div>
                                                    <strong>{{ $review->reviewer_name }}</strong>
                                                    <small class="text-muted ms-2">
                                                        <i class="fa-regular fa-clock me-1"></i>
                                                        {{ $review->created_at->format('H:i d/m/Y') }}
                                                    </small>
                                                </div>
                                                <div class="rating-stars">
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        <i
                                                        class="fa{{ $i <= $review->rating ? 's' : 'r' }} fa-star text-warning"></i>
                                                        @endfor
                                                </div>
                                            </div>

                                            <p class="mb-2">{{ $review->review_text }}</p>


                                            {{-- Hình ảnh hoặc video --}}
                                            @if ($review->multimedia->count())
                                            <div class="d-flex flex-wrap gap-3 mt-3">
                                                @foreach ($review->multimedia as $media)
                                                @if (Str::contains($media->mime_type, 'image'))
                                                <div
                                                    style="width: 120px; height: 120px; overflow: hidden; border-radius: 6px; border: 1px solid #ddd; cursor: pointer;">
                                                    <img src="{{ asset('storage/' . $media->file) }}"
                                                        alt="review image"
                                                        style="width: 100%; height: 100%; object-fit: cover;"
                                                        onclick="window.open(this.src)">
                                                </div>
                                                @elseif (Str::contains($media->mime_type, 'video'))
                                                <div
                                                    style="width: 200px; height: 120px; overflow: hidden; border-radius: 6px; border: 1px solid #ddd;">
                                                    <video
                                                        style="width: 100%; height: 100%; object-fit: cover;"
                                                        controls>
                                                        <source
                                                            src="{{ asset('storage/' . $media->file) }}"
                                                            type="{{ $media->mime_type }}">
                                                    </video>
                                                </div>
                                                @endif
                                                @endforeach
                                            </div>
                                            @endif

                                        </div>

                                        {{-- Ngăn cách giữa các đánh giá --}}
                                        <hr>
                                        @empty
                                        <div class="alert alert-info">Chưa có đánh giá nào.</div>
                                        @endforelse
                                        <div class="pagination-wrapper">
                                            {{ $reviews->links() }}
                                        </div>
                                    </div><br>

                                    <h3>Bình Luận</h3>
                                    <div id="comment-list"></div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="commentFormArea">
                                        <h3>Thêm bình luận</h3>
                                        <div class="reviewFrom">
                                            <form id="comment-form" method="POST">
                                                @csrf
                                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                <textarea name="content" class="form-control" placeholder="Nhập bình luận..." required></textarea>
                                                <button type="submit" class="ulinaBTN mt-2"><span>Gửi bình
                                                        luận</span></button>
                                            </form>
                                            <div id="comment-message" class="text-success mt-2"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Sản phẩm liên quan -->
        <div class="row relatedProductRow">
            <div class="col-lg-12">
                <h2 class="secTitle">Sản phẩm liên quan</h2>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="productCarousel owl-carousel">
                            @foreach ($relatedProducts as $prod)
                            <div class="productItem01 {{ $prod->comments_count ? '' : 'pi01NoRating' }}">
                                <div class="pi01Thumb">
                                    {{-- Ảnh chính + ảnh biến thể --}}
                                    <a href="{{ route('product.detail', $prod->id) }}">
                                        <img src="{{ asset('storage/' . $prod->thumbnail) }}"
                                            alt="{{ $prod->name }}" />
                                        @if ($firstVar = $prod->variants->first())
                                        <img src="{{ asset('storage/' . $firstVar->thumbnail) }}"
                                            alt="{{ $prod->name }} - Biến thể" />
                                        @endif
                                    </a>

                                            {{-- Sale label --}}
                                            <div class="productLabels clearfix">
                                                @if ($prod->is_sale && now()->between($prod->sale_price_start_at, $prod->sale_price_end_at))
                                                    <span
                                                        class="plDis">-{{ round((1 - $prod->sale_price / $prod->price) * 100) }}%</span>
                                                    <span class="plSale">Sale</span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="pi01Details">
                                            {{-- Star rating + Reviews --}}
                                            @php
                                                $avg = $prod->avg_rating ?? 0;
                                                $avgPercent = round($avg * 20); // 5 sao = 100%
                                            @endphp

                                            @if (($prod->reviews_count ?? 0) > 0)
                                                <div class="productRatings">
                                                    <div class="productRatingWrap">
                                                        <div class="star-rating"
                                                            aria-label="{{ number_format($avg, 1) }}/5">
                                                            <span style="width: {{ $avgPercent }}%"></span>
                                                        </div>
                                                    </div>
                                                    <div class="ratingCounts">{{ $prod->reviews_count }} đánh giá</div>
                                                </div>
                                            @endif


                                            {{-- Tên sản phẩm --}}
                                            <h3>
                                                <a href="{{ route('product.detail', $prod->id) }}">
                                                    {{ Str::limit($prod->name, 40) }}
                                                </a>
                                            </h3>

                                            {{-- Giá --}}
                                            <div class="pi01Price">
                                                @if ($prod->is_sale && now()->between($prod->sale_price_start_at, $prod->sale_price_end_at))
                                                    <ins>{{ number_format($prod->sale_price, 0, ',', '.') }}₫</ins>
                                                    <del>{{ number_format($prod->price, 0, ',', '.') }}₫</del>
                                                @else
                                                    <ins>{{ number_format($prod->price, 0, ',', '.') }}₫</ins>
                                                @endif
                                            </div>

                                            {{-- Màu & Size từ attributeValues --}}
                                            @if (optional($prod->variantsWithAttributes())->count())
                                                @php
                                                    // Lấy danh sách màu
                                                    $colors = collect();
                                                    foreach ($prod->variantsWithAttributes() as $variant) {
                                                        foreach ($variant->attributeValues as $attrVal) {
                                                            if ($attrVal->attribute->slug === 'color') {
                                                                $colors->push($attrVal);
                                                            }
                                                        }
                                                    }
                                                    $colors = $colors->unique('id');

                                                    // Lấy danh sách size
                                                    $sizes = $prod
                                                        ->variantsWithAttributes()
                                                        ->flatMap(
                                                            fn($v) => $v->attributeValues->filter(
                                                                fn($val) => $val->attribute->slug === 'size',
                                                            ),
                                                        )
                                                        ->unique('id');
                                                @endphp

                                                <div class="pi01Variations">
                                                    @if ($colors->isNotEmpty())
                                                        <div class="pi01VColor">
                                                            @foreach ($colors as $color)
                                                                <div class="colorOptionWrapper">
                                                                    <input type="radio"
                                                                        name="color_{{ $prod->id }}"
                                                                        id="color_{{ $prod->id }}_{{ $color->id }}"
                                                                        hidden>
                                                                    <label
                                                                        for="color_{{ $prod->id }}_{{ $color->id }}"
                                                                        class="customColorCircle"
                                                                        style="background-color: {{ Str::start($color->hex, '#') }};"
                                                                        title="{{ ucfirst($color->value) }}"></label>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    @endif

                                                    @if ($sizes->count())
                                                        <div class="pi01VSize">
                                                            @foreach ($sizes as $size)
                                                                <div class="pi01VSItem">
                                                                    <input type="radio" name="size_{{ $prod->id }}"
                                                                        id="size_{{ $prod->id }}_{{ $size->id }}">
                                                                    <label
                                                                        for="size_{{ $prod->id }}_{{ $size->id }}">
                                                                        {{ strtoupper($size->value) }}
                                                                    </label>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                </div>
                                            @endif

                                        </div>
                                    {{-- Actions --}}
                                    <div class="pi01Actions">
                                        <a href="javascript:void(0);" class="pi01QuickView"><i
                                                class="fa-solid fa-arrows-up-down-left-right"></i></a>
                                        <a href="{{ route('product.detail', $product->id) }}"><i
                                                class="fa-solid fa-arrows-up-down-left-right"></i></a>
                                    </div>

                                    {{-- Sale label --}}
                                    <div class="productLabels clearfix">
                                        @if ($prod->is_sale && $prod->sale_price_start_at && $prod->sale_price_end_at && now()->between($prod->sale_price_start_at, $prod->sale_price_end_at))
                                        <span
                                            class="plDis">-{{ round((1 - $prod->sale_price / $prod->price) * 100) }}%</span>
                                        <span class="plSale">Sale</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="pi01Details">
                                    {{-- Star rating + Reviews --}}
                                    @php
                                    $avg = $prod->avg_rating ?? 0;
                                    $avgPercent = round($avg * 20); // 5 sao = 100%
                                    @endphp

                                    @if (($prod->reviews_count ?? 0) > 0)
                                    <div class="productRatings">
                                        <div class="productRatingWrap">
                                            <div class="star-rating"
                                                aria-label="{{ number_format($avg, 1) }}/5">
                                                <span style="width: {{ $avgPercent }}%"></span>
                                            </div>
                                        </div>
                                        <div class="ratingCounts">{{ $prod->reviews_count }} đánh giá</div>
                                    </div>
                                    @endif


                                    {{-- Tên sản phẩm --}}
                                    <h3>
                                        <a href="{{ route('product.detail', $prod->id) }}">
                                            {{ Str::limit($prod->name, 40) }}
                                        </a>
                                    </h3>

                                    {{-- Giá --}}
                                    <div class="pi01Price">
                                        @if ($prod->is_sale && $prod->sale_price_start_at && $prod->sale_price_end_at && now()->between($prod->sale_price_start_at, $prod->sale_price_end_at))
                                        <ins>{{ number_format($prod->sale_price, 0, ',', '.') }}₫</ins>
                                        <del>{{ number_format($prod->price, 0, ',', '.') }}₫</del>
                                        @else
                                        <ins>{{ number_format($prod->price, 0, ',', '.') }}₫</ins>
                                        @endif
                                    </div>

                                     {{-- Màu & Size từ attributeValues --}}
                                    @if (optional($prod->variantsWithAttributes())->count())
                                    @php
                                    // Lấy danh sách màu - ĐÃ SỬA: Thêm kiểm tra null
                                    $colors = collect();
                                    foreach ($prod->variantsWithAttributes() as $variant) {
                                    foreach ($variant->attributeValues as $attrVal) {
                                    if ($attrVal->attribute && $attrVal->attribute->slug === 'color') {
                                    $colors->push($attrVal);
                                    }
                                    }
                                    }
                                    $colors = $colors->unique('id');

                                    // Lấy danh sách size - ĐÃ SỬA: Thêm kiểm tra null
                                    $sizes = $prod
                                    ->variantsWithAttributes()
                                    ->flatMap(
                                    fn($v) => $v->attributeValues->filter(
                                    fn($val) => $val->attribute && $val->attribute->slug === 'size',
                                    ),
                                    )
                                    ->unique('id');
                                    @endphp

                                    <div class="pi01Variations">
                                        @if ($colors->isNotEmpty())
                                        <div class="pi01VColor">
                                            @foreach ($colors as $color)
                                            <div class="colorOptionWrapper">
                                                <input type="radio"
                                                    name="color_{{ $prod->id }}"
                                                    id="color_{{ $prod->id }}_{{ $color->id }}"
                                                    hidden>
                                                <label
                                                    for="color_{{ $prod->id }}_{{ $color->id }}"
                                                    class="customColorCircle"
                                                    style="background-color: {{ Str::start($color->hex, '#') }};"
                                                    title="{{ ucfirst($color->value) }}"></label>
                                            </div>
                                            @endforeach
                                        </div>
                                        @endif

                                        @if ($sizes->count())
                                        <div class="pi01VSize">
                                            @foreach ($sizes as $size)
                                            <div class="pi01VSItem">
                                                <input type="radio" name="size_{{ $prod->id }}"
                                                    id="size_{{ $prod->id }}_{{ $size->id }}">
                                                <label
                                                    for="size_{{ $prod->id }}_{{ $size->id }}">
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
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    </div>
</section>
<!-- END: Shop Details Section -->

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Lấy dữ liệu variants từ backend
        const variants = @json($variants);
        const product = @json($product);

        // Các phần tử DOM quan trọng
        const storagePath = "{{ asset('storage') }}";
        const form = document.getElementById('addToCartForm');
        const qtyInput = form.querySelector('[name="quantity"]');
        const addToCartBtn = document.getElementById('add-to-cart');
        const saleEl = document.getElementById('sale-price');
        const priceEl = document.getElementById('original-price');
        const stockEl = document.getElementById('stock-quantity');

        // Hàm định dạng giá tiền
        const formatPrice = price => {
            return new Intl.NumberFormat('vi-VN', {
                style: 'currency',
                currency: 'VND'
            }).format(price);
        };

        // Hàm tìm biến thể được chọn
        const getSelectedVariant = () => {
            // Lấy tất cả thuộc tính đã chọn
            const selectedAttributes = {};
            document.querySelectorAll('[name^="attribute_values"]').forEach(input => {
                if (input.checked) {
                    // input name = attribute_values[Color], attribute_values[Size],...
                    const attrName = input.name.match(/\[(.*)\]/)[1]; // lấy phần trong []
                    selectedAttributes[attrName] = parseInt(input.value);
                }
            });

            const variant = variants.find(v => {
                return Object.values(selectedAttributes).every(val => v.attribute_values.includes(val)) &&
                    v.attribute_values.length === Object.values(selectedAttributes).length;
            });

            console.log('Found variant:', variant);
            return variant;
        };

        // Hàm cập nhật giá và số lượng
        const updatePriceAndStock = () => {
            const variant = getSelectedVariant();
            const mainImgEl = document.getElementById('main-product-image');

            if (!variant) {
                addToCartBtn.disabled = true;
                addToCartBtn.innerHTML = '<span>Vui lòng chọn thuộc tính</span>';
                stockEl.textContent = '--';
                if (mainImgEl) {
                    mainImgEl.src = "{{ asset('storage/' . $product->thumbnail) }}";
                }
                return;
            }

            // Có variant => cập nhật giá
            if (variant.sale_price > 0 && variant.sale_price < variant.price) {
                saleEl.textContent = formatPrice(variant.sale_price);
                saleEl.style.display = 'inline';
                priceEl.textContent = formatPrice(variant.price);
                priceEl.style.display = 'inline';
            } else {
                saleEl.textContent = formatPrice(variant.price);
                priceEl.style.display = 'none';
            }

            // Số lượng tồn
            stockEl.textContent = variant.stock || 0;

            // Trạng thái nút
            addToCartBtn.disabled = variant.stock <= 0;
            addToCartBtn.innerHTML = variant.stock > 0
                ? '<span>Thêm vào giỏ</span>'
                : '<span>Hết hàng</span>';

            // Ảnh
            if (mainImgEl) {
                mainImgEl.src = variant.thumbnail || "{{ asset('storage/' . $product->thumbnail) }}";
            }
        };

        // Xử lý tăng/giảm số lượng
        form.querySelector('.btnMinus').addEventListener('click', () => {
            const currentQty = parseInt(qtyInput.value) || 1;
            qtyInput.value = Math.max(1, currentQty - 1);
        });

        form.querySelector('.btnPlus').addEventListener('click', () => {
            const currentQty = parseInt(qtyInput.value) || 1;
            qtyInput.value = currentQty + 1;
        });

        // Gán sự kiện khi chọn thuộc tính
        document.querySelectorAll('[name^="attribute_values"]').forEach(input => {
            input.addEventListener('change', updatePriceAndStock);
        });


        // Khởi tạo lần đầu
        updatePriceAndStock();

        // Xử lý thêm vào giỏ hàng
        form.addEventListener('submit', async (e) => {
            e.preventDefault();

            const variant = getSelectedVariant();
            const quantity = parseInt(qtyInput.value) || 1;

            if (!variant || variant.stock <= 0) {
                return Swal.fire('Hết hàng', 'Sản phẩm đã hết hàng', 'error');
            }

            if (quantity > variant.stock) {
                return Swal.fire('Thông báo', `Chỉ còn ${variant.stock} sản phẩm trong kho`,
                    'warning');
            }

            try {
                const res = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': form.querySelector('[name="_token"]').value,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: new FormData(form)
                });

                const data = await res.json();

                if (res.status === 401 || data.unauthenticated) {
                    return Swal.fire({
                        icon: 'warning',
                        title: 'Chưa đăng nhập',
                        text: 'Vui lòng đăng nhập.',
                        showConfirmButton: true
                    }).then(() => location.href = '/login');
                }

                if (data.success) {
                    // Cập nhật số lượng trong giỏ hàng
                    const cartCountEl = document.querySelector('.anCart span');
                    if (cartCountEl) {
                        cartCountEl.innerText = data.totalProduct;
                    }

                    const cartWidgetArea = document.querySelector('.cartWidgetArea');
                    if (cartWidgetArea && data.cartWidget) {
                        cartWidgetArea.innerHTML = data.cartWidget;
                    }
                    return Swal.fire({
                        icon: 'success',
                        title: 'Thành công!',
                        text: 'Đã thêm vào giỏ hàng.',
                        timer: 1500,
                        showConfirmButton: false
                    });
                }

                Swal.fire('Thông báo', data.message || 'Có lỗi xảy ra', 'error');
            } catch (error) {
                console.error(error);
                Swal.fire('Lỗi hệ thống', 'Vui lòng thử lại sau.', 'error');
            }
        });

        // Xử lý bình luận
        $(document).ready(function() {
            function loadComments(page = 1) {
                $.get(`{{ url('comments/list') }}?product_id={{ $product->id }}&page=${page}`,
                    function(data) {
                        $('#comment-list').html(data);
                    });
            }

            $('#comment-form').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    type: 'POST',
                    url: "{{ route('comments.store') }}",
                    data: $(this).serialize(),
                    success: function(res) {
                        $('#comment-form textarea').val('');
                        $('#comment-message')
                            .removeClass('text-danger')
                            .addClass('text-success')
                            .text(res.message);
                        loadComments();
                    },
                    error: function(xhr) {
                        let errors = xhr.responseJSON?.errors;
                        let message = 'Đã xảy ra lỗi.';

                        if (errors && errors.content) {
                            message = errors.content[0];
                        } else if (xhr.responseJSON?.message) {
                            message = xhr.responseJSON.message;
                        }

                        $('#comment-message')
                            .removeClass('text-success')
                            .addClass('text-danger')
                            .text(message);
                    }
                });
            });

            $('#comment-list').on('click', '.pagination a', function(e) {

                e.preventDefault();
                const page = $(this).attr('href').split('page=')[1];
                loadComments(page);
            });

            // Gửi trả lời
            $(document).on('submit', '.reply-form', function(e) {
                e.preventDefault();
                const form = $(this);
                $.ajax({
                    type: 'POST',
                    url: "{{ route('comments.reply') }}",
                    data: form.serialize(),
                    success: function(res) {
                        loadComments();
                    },
                    error: function() {
                        alert('Lỗi khi gửi trả lời');
                    }
                });
            });

            // Toggle form trả lời
            $(document).on('click', '.toggle-reply', function() {
                let id = $(this).data('id');
                $('.reply-form').addClass('d-none');
                $('#reply-form-' + id).toggleClass('d-none');
            });

            loadComments();
        });
        // Thêm sản phẩm yêu thích
        const wishlistBtn = document.querySelector('.pcWishlist');
        wishlistBtn.addEventListener('click', async () => {
            const productId = wishlistBtn.dataset.productId;
            const heartIcon = wishlistBtn.querySelector('i.fa-heart');

            try {
                const response = await fetch("{{ route('wishlist.add') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        product_id: productId
                    })
                });

                const data = await response.json();

                if (response.status === 401) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Chưa đăng nhập',
                        text: 'Vui lòng đăng nhập để thêm vào danh sách yêu thích.',
                        showConfirmButton: true
                    }).then(() => window.location.href = '/login');
                    return;
                }

                if (data.success) {
                    if (data.action === 'added') {
                        heartIcon.classList.add('text-danger');
                        Swal.fire({
                            icon: 'success',
                            title: 'Thành công!',
                            text: data.message,
                            timer: 1500,
                            showConfirmButton: false
                        });
                    } else {
                        heartIcon.classList.remove('text-danger');
                        Swal.fire({
                            icon: 'success',
                            title: 'Thành công!',
                            text: data.message,
                            timer: 1500,
                            showConfirmButton: false
                        });
                    }
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi!',
                        text: data.message,
                        showConfirmButton: true
                    });
                }
            } catch (error) {
                console.error('Error adding to wishlist:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi!',
                    text: 'Đã xảy ra lỗi khi thêm vào danh sách yêu thích.',
                    showConfirmButton: true
                });
            }
        });
    });
</script>
<style>
    .description-scrollable {
        max-height: 400px;
        /* Đặt chiều cao tối đa cho nội dung */
        overflow-y: auto;
        /* Thêm thanh cuộn dọc khi nội dung vượt quá chiều cao */
        padding-right: 15px;
        /* Tùy chọn: Thêm khoảng đệm để văn bản không dính vào thanh cuộn */
    }
</style>
@endsection
