<footer class="footer">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-md-6">
                <aside class="widget aboutWidget">
                    <div class="footerLogo">
                        <a href="{{ route('client.home') }}">
                            <img src="{{ asset('assets/images/logo1.png') }}" alt="FreshFit" class="logo-img"/>
                        </a>
                    </div>
                    <div class="aboutWidContent">
                        Chúng tôi cam kết mang đến những sản phẩm chất lượng với dịch vụ tốt nhất dành cho bạn.
                    </div>

                </aside>
            </div>
            <div class="col-lg-3 col-md-6">
                <aside class="widget">
                    <h3 class="widgetTitle">Địa chỉ</h3>
                    <div class="addressContent">
                        <div class="singleAddress">
                            <i class="fa-solid fa-location-dot"></i>
                            Trịnh Văn Bô, Hà Nội, Việt Nam
                        </div>
                        <div class="singleAddress">
                            <i class="fa-solid fa-phone"></i>
                            03838383838
                        </div>
                        <div class="singleAddress">
                            <i class="fa-solid fa-envelope"></i>
                            <a href="mailto:contact@ulina.vn">FreshFit@gmail.com</a>
                        </div>
                    </div>
                </aside>
            </div>

            <div class="col-lg-2 col-md-6">
                <aside class="widget">
                    <h3 class="widgetTitle">Liên kết</h3>
                    <ul>
                        <li><a href="{{ route('client.coupons.index') }}">Mã giảm giá</a></li>
                        <li><a href="{{ route('client.blogs.index') }}">Về chúng tôi</a></li>
                        <li><a href="{{ route('wishlist.index') }}">Sản phẩm yêu thích</a></li>
                        <li><a href="{{ route('client.notifications.index') }}">Thông Báo</a></li>
                    </ul>
                </aside>
            </div>

            <div class="col-lg-3 col-md-6">
                <aside class="widget twoColMenu">
                    <h3 class="widgetTitle">Danh mục</h3>
                    <ul>
                        @foreach ($footerCategories as $category)
                            <li>
                                <a href="{{ route('category.show', $category->slug) }}">
                                    {{ $category->name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>

                </aside>
            </div>
        </div>
        <div class="row footerAccessRow">
            <div class="col-md-6">
                <div class="footerSocial">
                    <a href="https://www.facebook.com/canifa.fanpage"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="https://www.tiktok.com/@hello.canifa"><i class="fa-brands fa-tiktok"></i></a>
                    <a href="https://www.youtube.com/CANIFAOfficial"><i class="fa-brands fa-youtube"></i></a>
                    <a href="https://www.instagram.com/canifa.fashion/"><i class="fa-brands fa-instagram"></i></a>
                </div>
            </div>
            <div class="col-md-6">
                <div class="footerPayments">
                    <a href="javascript:void(0);"><i class="fa-brands fa-cc-paypal"></i></a>
                    <a href="javascript:void(0);"><i class="fa-brands fa-cc-mastercard"></i></a>
                    <a href="javascript:void(0);"><i class="fa-brands fa-cc-visa"></i></a>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="footerBar"></div>
            </div>
        </div>
    </div>
</footer>
<section class="siteInfoSection">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="siteInfo">
                    All rights reserved &nbsp;<a href="index.html">FreshFit</a>&nbsp;&nbsp;&copy;&nbsp;&nbsp;2025
                </div>
            </div>
        </div>
    </div>
</section>
