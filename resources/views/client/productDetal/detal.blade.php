@extends('client.layouts.app')

@section('content')
    <!-- BEGIN: Shop Details Section -->
    <section class="shopDetailsPageSection">
        <div class="container">
            <div class="row">

                <!-- 1. Import Slick CSS/JS -->


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

                <script>
                    document.querySelectorAll('.productGalleryThumb .pgtImage img')
                        .forEach(img => img.addEventListener('click', () => {
                            document.querySelector('.productGallery .pgImage img').src = img.src;
                        }));
                </script>


                <div class="col-lg-6">
                    <div class="productContent">
                        <div class="pcCategory">
                            <a href="shop_right_sidebar.html">Fashion</a>, <a href="shop_left_sidebar.html">Sports</a>
                        </div>
                        <h2>{{ $product->name }}</h2>
                        <div class="pi01Price">
                            @if ($product->sale_price > 0 && $product->sale_price < $product->price)
                                <ins>{{ number_format($product->sale_price, 0, ',', '.') }} đ</ins>
                                <del>{{ number_format($product->price, 0, ',', '.') }} đ</del>
                            @else
                                <ins>{{ number_format($product->price, 0, ',', '.') }} đ</ins>
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
                                <span>Available :</span>

                                <!-- 12 chưa có thông tin /////////////////////////////////// -->
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
                                                <p>{{ $color->value }}</p>
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
                                <div class="quantity clearfix">
                                    <button type="button" name="btnMinus" class="qtyBtn btnMinus">_</button>
                                    <input type="number" class="carqty input-text qty text" name="quantity" value="1">
                                    <button type="button" name="btnPlus" class="qtyBtn btnPlus">+</button>
                                </div>
                                <button type="submit" class="ulinaBTN"><span>Add to Cart</span></button>
                                <a href="wishlist.html" class="pcWishlist"><i class="fa-solid fa-heart"></i></a>
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
                                <!-- đg chưa có thông tin -->
                                <!-- <a href="javascript:void(0);"></a>, <a href="javascript:void(0);">Bags</a>, <a href="javascript:void(0);">Girls</a> -->
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
            <!--  sản phẩm liên quan  -->
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

                                            {{-- Nếu có đánh giá --}}
                                            @if ($reviews->count())
                                                <p class="mb-4 text-muted">Điểm trung bình: <strong
                                                        class="text-warning">{{ number_format($reviews->avg('rating'), 1) }}/5</strong>
                                                    từ {{ $reviews->count() }} đánh giá</p>

                                                @foreach ($reviews as $review)
                                                    @if ($review->product && $review->product->is_active)
                                                        <div class="border rounded p-3 mb-4 shadow-sm bg-white">
                                                            <div
                                                                class="d-flex justify-content-between align-items-center mb-2">
                                                                <strong>{{ $review->reviewer_name }}</strong>
                                                                <span class="badge bg-warning text-dark">⭐
                                                                    {{ $review->rating }}/5</span>
                                                            </div>
                                                            <p class="mb-2">{{ $review->review_text }}</p>

                                                            @if ($review->multimedia->count())
                                                                <div class="d-flex flex-wrap gap-2 mt-2">
                                                                    @foreach ($review->multimedia as $media)
                                                                        @if (Str::contains($media->mime_type, 'image'))
                                                                            <img src="{{ asset('storage/' . $media->file) }}"
                                                                                width="100" class="rounded border">
                                                                        @elseif(Str::contains($media->mime_type, 'video'))
                                                                            <video width="180" controls
                                                                                class="border rounded">
                                                                                <source
                                                                                    src="{{ asset('storage/' . $media->file) }}"
                                                                                    type="{{ $media->mime_type }}">
                                                                            </video>
                                                                        @endif
                                                                    @endforeach
                                                                </div>
                                                            @endif
                                                        </div>
                                                    @endif
                                                @endforeach
                                            @else
                                                <div class="alert alert-info">Chưa có đánh giá nào cho sản phẩm này.</div>
                                            @endif

                                            {{-- Form đánh giá --}}
                                            @auth
                                                <hr class="my-4">
                                                <h5 class="mb-3">Gửi đánh giá của bạn</h5>

                                                <form method="POST" action="{{ route('client.reviews.store') }}"
                                                    enctype="multipart/form-data">
                                                    @csrf
                                                    <input type="hidden" name="product_id" value="{{ $product->id }}">

                                                    <div class="mb-3">
                                                        <label for="rating" class="form-label">Số sao</label>
                                                        <select name="rating" id="rating" class="form-select w-auto"
                                                            required>
                                                            <option value="5">5 sao</option>
                                                            <option value="4">4 sao</option>
                                                            <option value="3">3 sao</option>
                                                            <option value="2">2 sao</option>
                                                            <option value="1">1 sao</option>
                                                        </select>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="review_text" class="form-label">Nội dung</label>
                                                        <textarea name="review_text" id="review_text" class="form-control" rows="4" required>{{ old('review_text') }}</textarea>
                                                        @error('review_text')
                                                            <div class="text-danger small">{{ $message }}</div>
                                                        @enderror
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="media" class="form-label">Hình ảnh/Video (tuỳ
                                                            chọn)</label>
                                                        <input type="file" name="media[]" class="form-control" multiple>
                                                    </div>

                                                    <button type="submit" class="btn btn-primary px-4">Gửi đánh giá</button>
                                                </form>
                                            @else
                                                <div class="alert alert-warning mt-3">
                                                    Vui lòng <a href="{{ route('login') }}">đăng nhập</a> để gửi đánh giá.
                                                </div>
                                            @endauth
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

                                <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                                <script>
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
                        message = errors.content[0]; // lấy lỗi đầu tiên của 'content'
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

        $(document).on('click', '.pagination a', function (e) {
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

        loadComments();
    });

    // Toggle form trả lời
    $(document).on('click', '.toggle-reply', function () {
        let id = $(this).data('id');
        $('.reply-form').addClass('d-none');
        $('#reply-form-' + id).toggleClass('d-none');
    });
</script>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row relatedProductRow">
                <div class="col-lg-12">
                    <h2 class="secTitle">Sản phẩm liên quan</h2>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="productCarousel owl-carousel">

                                @foreach ($relatedProducts as $prod)
                                    <div class="productItem01 {{ $prod->comments_count ? '' : 'pi01NoRating' }}">
                                        <div class="pi01Thumb">
                                            {{-- 2 ảnh hover: chính + biến thể đầu --}}
                                            <img src="{{ asset('storage/' . $prod->thumbnail) }}"
                                                alt="{{ $prod->name }}" />
                                            @if ($firstVar = $prod->variants->first())
                                                <img src="{{ asset('storage/' . $firstVar->thumbnail) }}"
                                                    alt="{{ $prod->name }} - Biến thể" />
                                            @else
                                                <img src="{{ asset('storage/' . $prod->thumbnail) }}"
                                                    alt="{{ $prod->name }}" />
                                            @endif

                                            {{-- Actions --}}
                                            <div class="pi01Actions">
                                                <a href="javascript:void(0);" class="pi01Cart"><i
                                                        class="fa-solid fa-shopping-cart"></i></a>
                                                <a href="javascript:void(0);" class="pi01QuickView"><i
                                                        class="fa-solid fa-arrows-up-down-left-right"></i></a>
                                                <a href="javascript:void(0);" class="pi01Wishlist"><i
                                                        class="fa-solid fa-heart"></i></a>
                                            </div>

                                            {{-- Sale label --}}
                                            <div class="productLabels clearfix">
                                                @if ($prod->is_sale && now()->between($prod->sale_price_start_at, $prod->sale_price_end_at))
                                                    <span
                                                        class="plDis">-{{ round((100 * ($prod->price - $prod->sale_price)) / $prod->price) }}%</span>
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
                                            <h3><a
                                                    href="{{ route('product.detail', $prod->id) }}">{{ Str::limit($prod->name, 40) }}</a>
                                            </h3>

                                            {{-- Giá --}}
                                            <div class="pi01Price">
                                                @if ($prod->is_sale && now()->between($prod->sale_price_start_at, $prod->sale_price_end_at))
                                                    <ins>{{ number_format($prod->sale_price) }}</ins>
                                                    <del>{{ number_format($prod->price) }}</del>
                                                @else
                                                    <ins>{{ number_format($prod->price) }}</ins>
                                                @endif
                                            </div>

                                            {{-- Màu & Size --}}
                                            <div class="pi01Variations">
                                                <div class="pi01VColor">
                                                    @foreach ($prod->variants->pluck('sku')->take(3) as $i => $sku)
                                                        <div class="pi01VCItem">
                                                            <input {{ $i === 0 ? 'checked' : '' }} type="radio"
                                                                name="color{{ $prod->id }}"
                                                                id="color{{ $prod->id }}_{{ $i }}" />
                                                            <label
                                                                for="color{{ $prod->id }}_{{ $i }}"></label>
                                                        </div>
                                                    @endforeach
                                                </div>
                                                <div class="pi01VSize">
                                                    @foreach (['S', 'M', 'L'] as $j => $size)
                                                        <div class="pi01VSItem">
                                                            <input {{ $j === 0 ? 'checked' : '' }} type="radio"
                                                                name="size{{ $prod->id }}"
                                                                id="size{{ $prod->id }}_{{ $size }}" />
                                                            <label
                                                                for="size{{ $prod->id }}_{{ $size }}">{{ $size }}</label>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>

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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
const variants = @json($variants);

document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('addToCartForm');
    const qtyInput = form.querySelector('[name="quantity"]');
    const addToCartBtn = form.querySelector('button[type="submit"]');
    const saleEl = document.querySelector('.pi01Price ins');
    const priceEl = document.querySelector('.pi01Price del');

    const formatPrice = price =>
        new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(price);

    const getSelectedVariant = () => {
        const colorId = form.querySelector('[name="color"]:checked')?.value;
        const sizeId = form.querySelector('[name="size"]:checked')?.value;
        return variants.find(v => v.color_id == colorId && v.size_id == sizeId);
    };

    const updatePriceAndStock = () => {
        const variant = getSelectedVariant();
        console.log(variant);

        if (variant) {
            // nếu không có giá sale lấy giá gốc
            if (variant.sale_price > 0 && variant.sale_price < variant.price) {
                saleEl.textContent = formatPrice(variant.sale_price);
                saleEl.style.display = 'inline';
                priceEl.textContent = formatPrice(variant.price);
                priceEl.style.display = 'inline';
            } else {
                saleEl.textContent = formatPrice(variant.price);
                priceEl.style.display = 'none';
            }
            if (variant.stock > 0) {
                addToCartBtn.disabled = false;
                addToCartBtn.innerHTML = '<span>Add to Cart</span>';
            } else {
                addToCartBtn.disabled = true;
                addToCartBtn.innerHTML = '<span>SOLDOUT</span>';
            }
        } else {
            saleEl.textContent = formatPrice({{ $product->sale_price }});
            priceEl.textContent = formatPrice({{ $product->price }});
            addToCartBtn.disabled = true;
            addToCartBtn.innerHTML = '<span>SOLDOUT</span>';
        }
    };

    // Xử lý tăng/giảm số lượng
    form.querySelector('.btnMinus').onclick = () => {
        const currentQty = parseInt(qtyInput.value) || 1;
        qtyInput.value = Math.max(1, currentQty - 1);
    };

    form.querySelector('.btnPlus').onclick = () => {
        const currentQty = parseInt(qtyInput.value) || 1;
        qtyInput.value = currentQty + 1;
    };

    form.querySelectorAll('[name="color"], [name="size"]').forEach(input =>
        input.addEventListener('change', updatePriceAndStock)
    );

    updatePriceAndStock();

    // Xử lý thêm vào giỏ hàng
    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        const variant = getSelectedVariant();
        const quantity = parseInt(qtyInput.value) || 1;

        if (!variant || variant.stock === 0) {
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
                document.querySelector('.anCart span').innerText = data.totalProduct;
                return Swal.fire({
                    icon: 'success',
                    title: 'Thành công!',
                    text: 'Đã thêm vào giỏ hàng.',
                    timer: 1500,
                    showConfirmButton: false
                });
            }

            Swal.fire('Thông báo', data.message || 'Sản phẩm đã hết hàng', 'error');
            console.log(data);
        } catch (error) {
            console.error(error);
            Swal.fire('Lỗi hệ thống', 'Vui lòng thử lại sau.', 'error');
        }
    });
});
</script>
@endsection
