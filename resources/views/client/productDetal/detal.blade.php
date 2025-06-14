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

                    <!-- Check biến thể -->
                    <div class="pcVariations">
                        <div class="pcVariation">
                            <span>Màu</span>
                            <div class="pcvContainer">
                                <div class="pi01VCItem">
                                    <input checked type="radio" name="color_4_6" value="Blue" id="color_4_6251_1_blue" />
                                    <label for="color_4_6251_1_blue"></label>
                                </div>
                                <div class="pi01VCItem yellows">
                                    <input type="radio" name="color_4_6" value="Yellow" id="color_4_6502_2_blue" />
                                    <label for="color_4_6502_2_blue"></label>
                                </div>
                                <div class="pi01VCItem reds">
                                    <input type="radio" name="color_4_6" value="Red" id="color_4_603rt_3_blue" />
                                    <label for="color_4_603rt_3_blue"></label>
                                </div>
                            </div>
                        </div>
                        <div class="pcVariation pcv2">
                            <span>Size</span>
                            <div class="pcvContainer">
                                <div class="pswItem">
                                    <input checked="" type="radio" name="ws_1" value="S" id="ws_145gb_s">
                                    <label for="ws_145gb_s">S</label>
                                </div>
                                <div class="pswItem">
                                    <input type="radio" name="ws_1" value="M" id="ws_1780fg_m">
                                    <label for="ws_1780fg_m">M</label>
                                </div>
                                <div class="pswItem">
                                    <input type="radio" name="ws_1" value="L" id="ws_14rghb_l">
                                    <label for="ws_14rghb_l">L</label>
                                </div>
                                <div class="pswItem">
                                    <input type="radio" name="ws_1" value="XL" id="ws_1t67u_xl">
                                    <label for="ws_1t67u_xl">XL</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- //////// -->
                    <div class="pcBtns">
                        <div class="quantity clearfix">
                            <button type="button" name="btnMinus" class="qtyBtn btnMinus">_</button>
                            <input type="number" class="carqty input-text qty text" name="quantity" value="01">
                            <button type="button" name="btnPlus" class="qtyBtn btnPlus">+</button>
                        </div>
                        <button type="submit" class="ulinaBTN"><span>Add to Cart</span></button>
                        <a href="wishlist.html" class="pcWishlist"><i class="fa-solid fa-heart"></i></a>
                        <a href="javascript:void(0);" class="pcCompare"><i class="fa-solid fa-right-left"></i></a>
                    </div>
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
                                    <div class="reviewList">
                                        <ol>
                                            @foreach ($comments as $comment)
                                            <li>
                                                <div class="postReview">
                                                    <img src="images/author/7.jpg" alt="Post Review">
                                                    <h2>{{ $comment->user->fullname }}</h2>
                                                    <div class="postReviewContent">
                                                        {{$comment->content}}
                                                    </div>
                                                    <div class="productRatingWrap">
                                                        <div class="star-rating"><span></span></div>
                                                    </div>
                                                    <div class="reviewMeta">

                                                        <span> {{$comment->created_at}}</span>
                                                    </div>
                                                </div>
                                            </li>
                                            @endforeach
                                        </ol>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="commentFormArea">
                                        <h3>Add A Review</h3>
                                        <div class="reviewFrom">
                                            <form method="post" action="#" class="row">
                                                <div class="col-lg-12">
                                                    <div class="reviewStar">
                                                        <label>Your Rating</label>
                                                        <div class="rsStars"><i class="fa-regular fa-star"></i><i class="fa-regular fa-star"></i><i class="fa-regular fa-star"></i><i class="fa-regular fa-star"></i><i class="fa-regular fa-star"></i></div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12">
                                                    <input type="text" name="comTitle" placeholder="Review title">
                                                </div>
                                                <div class="col-lg-12">
                                                    <textarea name="comComment" placeholder="Write your review here"></textarea>
                                                </div>
                                                <div class="col-lg-6">
                                                    <input type="text" name="comName" placeholder="Your name">
                                                </div>
                                                <div class="col-lg-6">
                                                    <input type="email" name="comEmail" placeholder="Your email">
                                                </div>
                                                <div class="col-lg-12">
                                                    <button type="submit" name="reviewtSubmit" class="ulinaBTN"><span>Submit Now</span></button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
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

@endsection
