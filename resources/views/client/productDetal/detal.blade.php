@extends('client.layouts.app')

@section('content')
<!-- BEGIN: Shop Details Section -->
<section class="shopDetailsPageSection">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <div class="productGalleryWrap">
                    <!-- Slider ảnh lớn -->
                    <div class="productGallery">
                        <div class="pgImage">
                            <img src="{{ asset('storage/' . $product->thumbnail) }}" alt="{{ $product->name }}" />
                        </div>
                        @foreach ($product->variants as $variant)
                            @if ($variant->thumbnail)
                                <div class="pgImage">
                                    <img src="{{ asset('storage/' . $variant->thumbnail) }}"
                                        alt="{{ $product->name }} - Biến thể" />
                                </div>
                            @endif
                        @endforeach
                    </div>

                    <!-- Slider thumbnail -->
                    <div class="productGalleryThumbWrap">
                        <div class="productGalleryThumb">
                            <div class="pgtImage">
                                <img src="{{ asset('storage/' . $product->thumbnail) }}" alt="{{ $product->name }}" />
                            </div>
                            @foreach ($product->variants as $variant)
                                @if ($variant->thumbnail)
                                    <div class="pgtImage">
                                        <img src="{{ asset('storage/' . $variant->thumbnail) }}"
                                            alt="{{ $product->name }} - Biến thể" />
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
                        <a href="shop_right_sidebar.html">Fashion</a>, <a href="shop_left_sidebar.html">Sports</a>
                    </div>
                    <h2>{{ $product->name }}</h2>
                    <div class="pi01Price">
                        @if ($product->sale_price > 0 && $product->sale_price < $product->price)
                            <ins id="sale-price">{{ number_format($product->sale_price, 0, ',', '.') }} đ</ins>
                            <del id="original-price">{{ number_format($product->price, 0, ',', '.') }} đ</del>
                        @else
                            <ins id="sale-price">{{ number_format($product->price, 0, ',', '.') }} đ</ins>
                        @endif
                    </div>
                    <div class="productRadingsStock clearfix">
                        <div class="productRatings float-start">
                            <div class="productRatingWrap">
                                <div class="star-rating"><span></span></div>
                            </div>
                            <div class="ratingCounts">{{ $product->views }}</div>
                        </div>
                        <div class="productStock float-end">
                            <span>Số Lượng: </span>
                            <span id="stock-quantity">--</span>
                        </div>
                    </div>
                    <div class="pcExcerpt">
                        <p>{{ $product->short_description }}</p>
                    </div>
                    <form id="addToCartForm" method="POST" action="{{ route('cart.add') }}">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}" />
                        <!-- Check biến thể -->
                        <div class="pcVariations">
                            <div class="pcVariation">
                                <span>Màu</span>
                                <div class="pcvContainer">
                                    @foreach ($colors as $color)
                                        <div class="colorOptionWrapper">
                                            <input type="radio" name="color" value="{{ $color->id }}"
                                                id="color_{{ $color->id }}"
                                                @if (old('color') == $color->id || $loop->first) checked @endif hidden>
                                            <label for="color_{{ $color->id }}" class="customColorCircle"
                                                style="background-color: {{ $color->hex }};"></label>
                                            {{-- <p>{{ $color->value }}</p> --}}
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="pcVariation pcv2">
                                <span>Size</span>
                                <div class="pcvContainer">
                                    @foreach ($sizes as $size)
                                        <div class="pswItem">
                                            <input type="radio" name="size" value="{{ $size->id }}"
                                                id="size_{{ $size->id }}"
                                                @if (old('size') == $size->id || $loop->first) checked @endif>
                                            <label for="size_{{ $size->id }}">{{ $size->value }}</label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <!-- //////// -->
                        <div class="pcBtns">
                            <div class="quantity-product">
                                <button type="button" name="btnMinus" class="qtyBtn btnMinus">-</button>
                                <input type="number" class="carqty input-text qty text" name="quantity" value="1" min="1">
                                <button type="button" name="btnPlus" class="qtyBtn btnPlus">+</button>
                            </div>
                            <br>
                            <button type="submit" id="add-to-cart" class="ulinaBTN"><span>Thêm vào giỏ</span></button>
                            <a href="javascript:void(0);" data-product-id = "{{ $product->id }}" class="pcWishlist">
                                <i class="fa-solid fa-heart {{ $isFavorite ? 'text-danger' : '' }}"></i></a>
                            <a href="javascript:void(0);" class="pcCompare"><i class="fa-solid fa-right-left"></i></a>
                        </div>
                    </form>
                    <div class="pcMeta">
                        <p>
                            <span>Sku</span>
                            <a href="javascript:void(0);">{{ $product->sku }}</a>
                        </p>
                        <p class="pcmTags">
                            <span>Tags:</span>
                        </p>
                        <p class="pcmSocial">
                            <span>Share</span>
                            <a class="fac" href="javascript:void(0);"><i class="fa-brands fa-facebook-f"></i></a>
                            <a class="twi" href="javascript:void(0);"><i class="fa-brands fa-twitter"></i></a>
                            <a class="lin" href="javascript:void(0);"><i class="fa-brands fa-linkedin-in"></i></a>
                            <a class="ins" href="javascript:void(0);"><i class="fa-brands fa-instagram"></i></a>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tab sản phẩm -->
        <div class="row productTabRow">
            <div class="col-lg-12">
                <ul class="nav productDetailsTab" id="productDetailsTab" role="tablist">
                    <li role="presentation">
                        <button class="active" id="description-tab" data-bs-toggle="tab"
                            data-bs-target="#description" type="button" role="tab" aria-controls="description"
                            aria-selected="true">Chi tiết sản phẩm</button>
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
                                <div class="col-lg-6">
                                    <div class="descriptionContent">
                                        <h3>Mô tả chi tiết</h3>
                                        <p>{{ $product->short_description }}</p>
                                        <p>{{ $product->description }}</p>
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
                                        <h3 class="mb-3">Đánh giá sản phẩm</h3>
<h4 class="mt-4" id="reviews">Đánh giá sản phẩm</h4><br>

{{-- BỘ LỌC ĐÁNH GIÁ + SẮP XẾP --}}
<div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-4 px-3 py-3 border rounded bg-light shadow-sm">

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
                {{ $i }} <i class="fa-solid fa-star text-warning"></i>
            </a>
        @endfor



    </div>

    {{-- Bộ lọc sắp xếp --}}
    <form method="GET" class="d-flex align-items-center">
        <div class="input-group input-group-sm" style="min-width: 200px;">
            <label class="input-group-text bg-light"><i class="fa-solid fa-sort"></i></label>
            <select name="sort" class="form-select" onchange="this.form.submit()">
                <option value="">Sắp xếp theo</option>
                <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Mới nhất</option>
                <option value="highest" {{ request('sort') == 'highest' ? 'selected' : '' }}>Sao cao nhất</option>
                <option value="lowest" {{ request('sort') == 'lowest' ? 'selected' : '' }}>Sao thấp nhất</option>
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
                    <i class="fa{{ $i <= $review->rating ? 's' : 'r' }} fa-star text-warning"></i>
                @endfor
            </div>
        </div>

        <p class="mb-2">{{ $review->review_text }}</p>


       {{-- Hình ảnh hoặc video --}}
@if ($review->multimedia->count())
    <div class="d-flex flex-wrap gap-3 mt-3">
        @foreach ($review->multimedia as $media)
            @if (Str::contains($media->mime_type, 'image'))
                <div style="width: 120px; height: 120px; overflow: hidden; border-radius: 6px; border: 1px solid #ddd; cursor: pointer;">
                    <img src="{{ asset('storage/' . $media->file) }}"
                         alt="review image"
                         style="width: 100%; height: 100%; object-fit: cover;"
                         onclick="window.open(this.src)">
                </div>
            @elseif (Str::contains($media->mime_type, 'video'))
                <div style="width: 200px; height: 120px; overflow: hidden; border-radius: 6px; border: 1px solid #ddd;">
                    <video style="width: 100%; height: 100%; object-fit: cover;" controls>
                        <source src="{{ asset('storage/' . $media->file) }}" type="{{ $media->mime_type }}">
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
                                    <img src="{{ asset('storage/' . $prod->thumbnail) }}" alt="{{ $prod->name }}" />
                                    @if ($firstVar = $prod->variants->first())
                                        <img src="{{ asset('storage/' . $firstVar->thumbnail) }}" alt="{{ $prod->name }} - Biến thể" />
                                    @endif
                                </a>

                                {{-- Actions --}}
                                <div class="pi01Actions">
                                    <a href="javascript:void(0);" class="pi01QuickView"><i class="fa-solid fa-arrows-up-down-left-right"></i></a>
                                     <a href="{{ route('product.detail', $product->id) }}"><i class="fa-solid fa-arrows-up-down-left-right"></i></a>
                                </div>

                                {{-- Sale label --}}
                                <div class="productLabels clearfix">
                                    @if ($prod->is_sale && now()->between($prod->sale_price_start_at, $prod->sale_price_end_at))
                                        <span class="plDis">-{{ round((1 - $prod->sale_price / $prod->price) * 100) }}%</span>
                                        <span class="plSale">Sale</span>
                                    @endif
                                </div>
                            </div>

                            <div class="pi01Details">
                                {{-- Star rating + Reviews --}}
                                @if ($prod->comments_count)
                                    <div class="productRatings">
                                        <div class="productRatingWrap">
                                            <div class="star-rating"><span></span></div>
                                        </div>
                                        <div class="ratingCounts">{{ $prod->comments_count }} Reviews</div>
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
                                @if(optional($prod->variantsWithAttributes())->count())
                                    @php
                                        // Lấy danh sách màu
                                        $colors = collect();
                                        foreach($prod->variantsWithAttributes() as $variant) {
                                            foreach($variant->attributeValues as $attrVal) {
                                                if($attrVal->attribute->slug === 'color') {
                                                    $colors->push($attrVal);
                                                }
                                            }
                                        }
                                        $colors = $colors->unique('id');

                                        // Lấy danh sách size
                                        $sizes = $prod->variantsWithAttributes()
                                            ->flatMap(fn($v) => $v->attributeValues->filter(fn($val) => $val->attribute->slug === 'size'))
                                            ->unique('id');
                                    @endphp

                                    <div class="pi01Variations">
                                        @if($colors->isNotEmpty())
                                            <div class="pi01VColor">
                                                @foreach($colors as $color)
                                                    <div class="colorOptionWrapper">
                                                        <input type="radio"
                                                            name="color_{{ $prod->id }}"
                                                            id="color_{{ $prod->id }}_{{ $color->id }}"
                                                            hidden>
                                                        <label for="color_{{ $prod->id }}_{{ $color->id }}"
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
                                                        <input type="radio"
                                                            name="size_{{ $prod->id }}"
                                                            id="size_{{ $prod->id }}_{{ $size->id }}">
                                                        <label for="size_{{ $prod->id }}_{{ $size->id }}">
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
    const form = document.getElementById('addToCartForm');
    const qtyInput = form.querySelector('[name="quantity"]');
    const addToCartBtn = document.getElementById('add-to-cart');
    const saleEl = document.getElementById('sale-price');
    const priceEl = document.getElementById('original-price');
    const stockEl = document.getElementById('stock-quantity');

    // Hàm định dạng giá tiền
    const formatPrice = price => {
        return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(price);
    };

    // Hàm tìm biến thể được chọn
    const getSelectedVariant = () => {
        const colorId = form.querySelector('[name="color"]:checked')?.value;
        const sizeId = form.querySelector('[name="size"]:checked')?.value;

        // Debug: Log giá trị đang chọn
        console.log('Selected color:', colorId, 'size:', sizeId);

        // Tìm biến thể phù hợp
        const variant = variants.find(v =>
            v.color_id == colorId &&
            v.size_id == sizeId
        );

        console.log('Found variant:', variant);
        return variant;
    };

    // Hàm cập nhật giá và số lượng
    const updatePriceAndStock = () => {
        try {
            const variant = getSelectedVariant();

            // Debug: Kiểm tra các phần tử DOM
            console.log('DOM Elements:', {
                saleEl: saleEl,
                priceEl: priceEl,
                stockEl: stockEl,
                addToCartBtn: addToCartBtn
            });

            if (variant) {
                // Cập nhật giá
                if (variant.sale_price > 0 && variant.sale_price < variant.price) {
                    saleEl.textContent = formatPrice(variant.sale_price);
                    saleEl.style.display = 'inline';
                    if (priceEl) {
                        priceEl.textContent = formatPrice(variant.price);
                        priceEl.style.display = 'inline';
                    }
                } else {
                    saleEl.textContent = formatPrice(variant.price);
                    if (priceEl) priceEl.style.display = 'none';
                }

                // Cập nhật số lượng tồn kho
                if (stockEl) {
                    stockEl.textContent = variant.stock || 0;
                }

                // Cập nhật trạng thái nút thêm vào giỏ
                if (addToCartBtn) {
                    addToCartBtn.disabled = variant.stock <= 0;
                    addToCartBtn.innerHTML = variant.stock > 0
                        ? '<span>Thêm vào giỏ</span>'
                        : '<span>Hết hàng</span>';
                }
            } else {
                // Nếu không tìm thấy biến thể, hiển thị giá mặc định của sản phẩm
                const defaultPrice = product.sale_price > 0 && product.sale_price < product.price
                    ? product.sale_price
                    : product.price;

                saleEl.textContent = formatPrice(defaultPrice);
                if (priceEl) {
                    priceEl.textContent = formatPrice(product.price);
                    priceEl.style.display = product.sale_price > 0 ? 'inline' : 'none';
                }
                if (stockEl) stockEl.textContent = '--';
                if (addToCartBtn) {
                    addToCartBtn.disabled = true;
                    addToCartBtn.innerHTML = '<span>Hết hàng</span>';
                }
            }
        } catch (error) {
            console.error('Error in updatePriceAndStock:', error);
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

    // Gán sự kiện khi chọn màu/size
    document.querySelectorAll('[name="color"], [name="size"]').forEach(input => {
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
            return Swal.fire('Thông báo', `Chỉ còn ${variant.stock} sản phẩm trong kho`, 'warning');
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
                if(cartWidgetArea && data.cartIcon){
                    cartWidgetArea.innerHTML = data.cartIcon;
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
    $(document).ready(function () {
        function loadComments(page = 1) {
            $.get(`{{ url('comments/list') }}?product_id={{ $product->id }}&page=${page}`, function (data) {
                $('#comment-list').html(data);
            });
        }

        $('#comment-form').submit(function (e) {
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: "{{ route('comments.store') }}",
                data: $(this).serialize(),
                success: function (res) {
                    $('#comment-form textarea').val('');
                    $('#comment-message')
                        .removeClass('text-danger')
                        .addClass('text-success')
                        .text(res.message);
                    loadComments();
                },
                error: function (xhr) {
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

    $('#comment-list').on('click', '.pagination a', function (e) {

            e.preventDefault();
            const page = $(this).attr('href').split('page=')[1];
            loadComments(page);
        });

        // Gửi trả lời
        $(document).on('submit', '.reply-form', function (e) {
            e.preventDefault();
            const form = $(this);
            $.ajax({
                type: 'POST',
                url: "{{ route('comments.reply') }}",
                data: form.serialize(),
                success: function (res) {
                    loadComments();
                },
                error: function () {
                    alert('Lỗi khi gửi trả lời');
                }
            });
        });

        // Toggle form trả lời
        $(document).on('click', '.toggle-reply', function () {
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
                body: JSON.stringify({ product_id: productId })
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
@endsection
