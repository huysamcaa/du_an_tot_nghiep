<footer class="footer">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-md-6">
                <aside class="widget aboutWidget">
                    <div class="footerLogo">
                        <a href="{{ route('client.home') }}">
                            <img src="{{ asset('assets/images/logo1.png') }}" alt="FreshFit" class="logo-img" />
                        </a>
                    </div>
                    <div class="aboutWidContent">
                        Chúng tôi cam kết mang đến những sản phẩm chất lượng với dịch vụ tốt nhất dành cho bạn.
                    </div>
                    <div class="subscribForm">
                        <form method="post" action="#">
                            <input type="email" name="subsEmail" placeholder="Nhập email của bạn"/>
                            <button type="submit"><i class="fa-solid fa-envelope"></i></button>
                        </form>
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
                    <h3 class="widgetTitle">Liên kết hữu ích</h3>
                    <ul>
                        <li><a href="{{ route('client.coupons.index') }}">Mã giảm giá</a></li>
                        <li><a href="{{ route('client.blogs.index') }}">Về chúng tôi</a></li>
                        <li><a href="javascript:void(0);">Tuyển dụng</a></li>
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
                
            </div>
            <div class="col-md-6">
                
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="footerBar"></div>
            </div>
        </div>
    </div>
</footer>
