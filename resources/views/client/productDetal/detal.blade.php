@extends('client.layouts.app')

@section('content')
<!-- BEGIN: Shop Details Section -->
<section class="shopDetailsPageSection">
    <div class="container">
        <div class="row">

            <div class="col-lg-6">
                <div class="productGalleryWrap">
                    <div class="productGallery">
                        <div class="pgImage">
                            <img src="{{ asset('storage/' . $product->thumbnail) }}" alt="{{ $product->name }}" />
                        </div>
                        <div class="pgImage">
                            <img src="{{ asset('storage/' . $product->thumbnail) }}" alt="{{ $product->name }}" />
                        </div>
                        <div class="pgImage">
                            <img src="{{ asset('storage/' . $product->thumbnail) }}" alt="{{ $product->name }}" />
                        </div>
                        <div class="pgImage">
                            <img src="{{ asset('storage/' . $product->thumbnail) }}" alt="{{ $product->name }}" />
                        </div>
                        <div class="pgImage">
                            <img src="{{ asset('storage/' . $product->thumbnail) }}" alt="{{ $product->name }}" />
                        </div>

                    </div>
                    <div class="productGalleryThumbWrap">
                        <div class="productGalleryThumb">
                            <div class="pgtImage">
                                <img src="{{ asset('storage/' . $product->thumbnail) }}" alt="{{ $product->name }}" />
                            </div>
                            <div class="pgtImage">
                                <img src="{{ asset('storage/' . $product->thumbnail) }}" alt="{{ $product->name }}" />
                            </div>
                            <div class="pgtImage">
                                <img src="{{ asset('storage/' . $product->thumbnail) }}" alt="{{ $product->name }}" />
                            </div>
                            <div class="pgtImage">
                                <img src="{{ asset('storage/' . $product->thumbnail) }}" alt="{{ $product->name }}" />
                            </div>
                            <div class="pgtImage">
                                <img src="{{ asset('storage/' . $product->thumbnail) }}" alt="{{ $product->name }}" />
                            </div>
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
                        <ins>{{ $product->sale_price }}</ins>
                        <del>{{ $product->price }}</del>
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
                                        @foreach($colors as $color)
                                            <div class="colorOptionWrapper">
                                                <input type="radio" name="color" value="{{ $color->id }}" id="color_{{ $color->id }}"
                                                    @if(old('color') == $color->id || $loop->first) checked @endif hidden>
                                                <label for="color_{{ $color->id }}"
                                                    class="customColorCircle"
                                                    style="background-color: {{ $color->hex }};"></label>
                                                <p>{{ $color->value }}</p>
                                            </div>
                                        @endforeach
                                    </div>
                            </div>
                            <div class="pcVariation pcv2">
                                <span>Size</span>
                                <div class="pcvContainer">
                                @foreach($sizes as $size)
                                    <div class="pswItem">
                                        <input type="radio" name="size" value="{{ $size->id }}" id="size_{{ $size->id }}" @if(old('size') == $size->id || $loop->first) checked @endif>
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
                        <button class="active" id="description-tab" data-bs-toggle="tab" data-bs-target="#description" type="button" role="tab" aria-controls="description" aria-selected="true">Chi tiết sản phẩm</button>
                    </li>
                    <li role="presentation">
                        <button id="additionalinfo-tab" data-bs-toggle="tab" data-bs-target="#additionalinfo" type="button" role="tab" aria-controls="additionalinfo" aria-selected="false" tabindex="-1">Additional Information</button>
                    </li>
                    <li role="presentation">
                        <button id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews" type="button" role="tab" aria-controls="reviews" aria-selected="false" tabindex="-1">Bình luận</button>
                    </li>
                </ul>
                <div class="tab-content" id="desInfoRev_content">
                    <div class="tab-pane fade show active" id="description" role="tabpanel" aria-labelledby="description-tab" tabindex="0">
                        <div class="productDescContentArea">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="descriptionContent">
                                        <h3>Mô tả chi tiết</h3>
                                        <p>{{$product->short_description}}</p>
                                        <p>{{$product->description}}</p>

                                    </div>
                                </div>
                                <!-- <div class="col-lg-6">
                                    <div class="descriptionContent featureCols">
                                        <h3>Product Features</h3>
                                        <ul>
                                            <li>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium </li>
                                            <li>Letotam rem aperiam, eaque ipsa quae ab illo inventore veritatis</li>
                                            <li>Vitae dicta sunt explicabo. Nemo enim ipsam volupta aut odit aut fugit </li>
                                            <li>Lesed quia consequuntur magni dolores eos qui ratione voluptate.</li>
                                        </ul>
                                    </div>
                                </div> -->
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="additionalinfo" role="tabpanel" aria-labelledby="additionalinfo-tab" tabindex="0">
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

                    <div class="tab-pane fade" id="reviews" role="tabpanel" aria-labelledby="reviews-tab" tabindex="0">
                        <div class="productReviewArea">
<div class="row">
    <div class="col-lg-6">
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
                    <button type="submit" class="ulinaBTN mt-2"><span>Gửi bình luận</span></button>
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
            url: '{{ route('comments.store') }}',
            data: $(this).serialize(),
            success: function (res) {
                $('#comment-form textarea').val('');
                $('#comment-message').text(res.message);
                loadComments();
            },
            error: function () {
                alert('Lỗi khi gửi bình luận');
            }
        });
    });

    $(document).on('click', '.pagination a', function(e) {
        e.preventDefault();
        const page = $(this).attr('href').split('page=')[1];
        loadComments(page);
    });

    // Xử lý gửi trả lời bằng AJAX
    $(document).on('submit', '.reply-form', function(e) {
        e.preventDefault();
        const form = $(this);
        $.ajax({
            type: 'POST',
            url: '{{ route('comments.reply') }}',
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
                            <div class="productItem01">
                                <div class="pi01Thumb">
                                    <img src="images/products/1.jpg" alt="Ulina Product" />
                                    <img src="images/products/1.1.jpg" alt="Ulina Product" />
                                    <div class="pi01Actions">
                                        <a href="javascript:void(0);" class="pi01Cart"><i class="fa-solid fa-shopping-cart"></i></a>
                                        <a href="javascript:void(0);" class="pi01QuickView"><i class="fa-solid fa-arrows-up-down-left-right"></i></a>
                                        <a href="javascript:void(0);" class="pi01Wishlist"><i class="fa-solid fa-heart"></i></a>
                                    </div>
                                    <div class="productLabels clearfix">
                                        <span class="plDis">- $49</span>
                                        <span class="plSale">Sale</span>
                                    </div>
                                </div>
                                <div class="pi01Details">
                                    <div class="productRatings">
                                        <div class="productRatingWrap">
                                            <div class="star-rating"><span></span></div>
                                        </div>
                                        <div class="ratingCounts">10 Reviews</div>
                                    </div>
                                    <h3><a href="shop_details1.html">Men’s blue cotton t-shirt</a></h3>
                                    <div class="pi01Price">
                                        <ins>$49</ins>
                                        <del>$60</del>
                                    </div>
                                    <div class="pi01Variations">
                                        <div class="pi01VColor">
                                            <div class="pi01VCItem">
                                                <input checked type="radio" name="color1" value="Blue" id="color1_blue" />
                                                <label for="color1_blue"></label>
                                            </div>
                                            <div class="pi01VCItem yellows">
                                                <input type="radio" name="color1" value="Yellow" id="color1_yellow" />
                                                <label for="color1_yellow"></label>
                                            </div>
                                            <div class="pi01VCItem reds">
                                                <input type="radio" name="color1" value="Red" id="color1_red" />
                                                <label for="color1_red"></label>
                                            </div>
                                        </div>
                                        <div class="pi01VSize">
                                            <div class="pi01VSItem">
                                                <input type="radio" name="size1" value="Blue" id="size1_s" />
                                                <label for="size1_s">S</label>
                                            </div>
                                            <div class="pi01VSItem">
                                                <input type="radio" name="size1" value="Yellow" id="size1_m" />
                                                <label for="size1_m">M</label>
                                            </div>
                                            <div class="pi01VSItem">
                                                <input type="radio" name="size1" value="Red" id="size1_xl" />
                                                <label for="size1_xl">XL</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="productItem01 pi01NoRating">
                                <div class="pi01Thumb">
                                    <img src="images/products/2.jpg" alt="Ulina Product" />
                                    <img src="images/products/2.1.jpg" alt="Ulina Product" />
                                    <div class="pi01Actions">
                                        <a href="javascript:void(0);" class="pi01Cart"><i class="fa-solid fa-shopping-cart"></i></a>
                                        <a href="javascript:void(0);" class="pi01QuickView"><i class="fa-solid fa-arrows-up-down-left-right"></i></a>
                                        <a href="javascript:void(0);" class="pi01Wishlist"><i class="fa-solid fa-heart"></i></a>
                                    </div>
                                    <div class="productLabels clearfix">
                                        <span class="plHot">Hot</span>
                                    </div>
                                </div>
                                <div class="pi01Details">
                                    <h3><a href="shop_details2.html">Ulina black clean t-shirt</a></h3>
                                    <div class="pi01Price">
                                        <ins>$14</ins>
                                        <del>$30</del>
                                    </div>
                                    <div class="pi01Variations">
                                        <div class="pi01VColor">
                                            <div class="pi01VCItem">
                                                <input checked type="radio" name="color2" value="Blue" id="color2_blue" />
                                                <label for="color2_blue"></label>
                                            </div>
                                            <div class="pi01VCItem yellows">
                                                <input type="radio" name="color2" value="Yellow" id="color2_yellow" />
                                                <label for="color2_yellow"></label>
                                            </div>
                                            <div class="pi01VCItem reds">
                                                <input type="radio" name="color2" value="Red" id="color2_red" />
                                                <label for="color2_red"></label>
                                            </div>
                                        </div>
                                        <div class="pi01VSize">
                                            <div class="pi01VSItem">
                                                <input type="radio" name="size2" value="Blue" id="size2_s" />
                                                <label for="size2_s">S</label>
                                            </div>
                                            <div class="pi01VSItem">
                                                <input type="radio" name="size2" value="Yellow" id="size2_m" />
                                                <label for="size2_m">M</label>
                                            </div>
                                            <div class="pi01VSItem">
                                                <input type="radio" name="size2" value="Red" id="size2_xl" />
                                                <label for="size2_xl">XL</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('addToCartForm');
    const qtyInput = form.querySelector('.carqty');
    const addToCartBtn = form.querySelector('button[type="submit"]');

    // Tăng/giảm số lượng
    form.querySelector('.btnMinus').onclick = () => {
        qtyInput.value = Math.max(1, parseInt(qtyInput.value) - 1);
    };
    form.querySelector('.btnPlus').onclick = () => {
        qtyInput.value = parseInt(qtyInput.value) + 1 || 1;
    };

    // Kiểm tra biến thể có tồn tại không
    const checkVariantAvailability = () => {
        const productId = form.querySelector('[name="product_id"]').value;
        const colorId = form.querySelector('[name="color"]:checked')?.value;
        const sizeId = form.querySelector('[name="size"]:checked')?.value;

        if (!colorId || !sizeId) {
            addToCartBtn.disabled = true;
            addToCartBtn.innerText = 'Chọn biến thể';
            return;
        }

        fetch(`{{ route('check.variant') }}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': form.querySelector('[name="_token"]').value
            },
            body: JSON.stringify({ product_id: productId, color: colorId, size: sizeId })
        })
        .then(res => res.json())
        .then(data => {
            addToCartBtn.disabled = !data.found;
            addToCartBtn.innerHTML = data.found ? '<span>Add to card</span>' : '<span>SOLDOUT</span>';
        });
    };

    form.querySelectorAll('[name="color"], [name="size"]').forEach(input =>
        input.addEventListener('change', checkVariantAvailability)
    );
    checkVariantAvailability();

    // Xử lý submit form
    form.addEventListener('submit', async (e) => {
        e.preventDefault();
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
            Swal.fire({ icon: 'warning', title: 'Chưa đăng nhập', text: 'Vui lòng đăng nhập.', showConfirmButton: true })
                .then(() => location.href = '/login');
        } else if (data.success) {
            document.querySelector('.anCart span').innerText = data.totalProduct;
            Swal.fire({ icon: 'success', title: 'Thành công!', text: 'Đã thêm vào giỏ hàng.', timer: 1500, showConfirmButton: false });
        } else {
            Swal.fire('Hết hàng', 'Thêm vào giỏ hàng thất bại', 'error');
        }
    });
});
</script>


@endsection


