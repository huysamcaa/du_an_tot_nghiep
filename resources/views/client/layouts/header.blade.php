<header class="header01 isSticky">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="headerInner01">
                    <div class="logo">
                        <a href="{{ route('client.home') }}">
                            <img src="{{ asset('assets/images/logo.png') }}" alt="FreshFit" class="logo-img" />
                        </a>
                    </div>
                    <div class="mainMenu">
                        <ul>
                            <li class="menu-item">
                                <a href="{{ route('client.home') }}">Trang Chủ</a>
                            </li>

                          <li class="menu-item-has-children">
                                <a href="javascript:void(0)">Danh Mục </a>
                                <div class="megaMenu">
                                    <div class="row">
                                        {{-- Lặp qua từng nhóm danh mục (chunks) để tạo các cột --}}
                                        @foreach($chunks as $chunk)
                                            <div class="col-lg-4">
                                                <ul>
                                                    {{-- Lặp qua các danh mục trong từng nhóm để hiển thị --}}
                                                    @foreach($chunk as $category)
                                                        <li>
                                                           <a href="{{ route('client.categories.index', ['category_id' => $category->id]) }}">{{ $category->name }}</a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endforeach
                                        <div class="col-lg-4 hideOnMobile">
                                            <div class="lookBook01 lb01M2">
                                                <div class="lbContent">
                                                    <h3>Hãy sành điệu</h3>
                                                    <h2>Thời trang & phong cách</h2>
                                                    <a href="{{ route('client.categories.index') }}" class="ulinaLink"><i class="fa-solid fa-angle-right"></i>Mua ngay</a>
                                                </div>
                                                <img src="{{ asset('assets/Client/images/home1/3.png') }}" alt="Mans Latest Collection">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>

                            <style>
                                /* CSS để xử lý văn bản quá dài */
                                .megaMenu ul li a {
                                    white-space: nowrap;
                                    overflow: hidden;
                                    text-overflow: ellipsis;
                                    display: block;
                                    max-width: 100%;
                                }
                            </style>

                            {{-- <li class="menu-item">
                                <a href="{{ route('client.coupons.index') }}">Khuyến Mãi</a>
                            </li> --}}

                            {{-- <li class="menu-item-has-children">
                                <a href="javascript:void(0);">Shop</a>
                                <div class="megaMenu">
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <h3>List Pages</h3>
                                            <ul>
                                                <li><a href="shop_left_sidebar.html">Shop Left Sidebar</a></li>
                                                <li><a href="shop_full_width.html">Shop Full Width</a></li>
                                                <li><a href="shop_right_sidebar.html">Shop Right Sidebar</a></li>
                                                <li><a href="collections.html">Collections</a></li>
                                                <li><a href="collection_list.html">Collection List</a></li>
                                            </ul>
                                        </div>
                                        <div class="col-lg-4">
                                            <h3>Details & Utility</h3>
                                            <ul>
                                                <li><a href="shop_details1.html">Shop Details 01</a></li>
                                                <li><a href="shop_details2.html">Shop Details 02</a></li>
                                                <li><a href="cart.html">Shopping Cart</a></li>
                                                <li><a href="checkout.html">Checkout</a></li>
                                                <li><a href="wishlist.html">Wishlist</a></li>
                                            </ul>
                                        </div>
                                        <div class="col-lg-4 hideOnMobile">
                                            <div class="lookBook01 lb01M2">
                                                <div class="lbContent">
                                                    <h3>Be Stylish</h3>
                                                    <h2>Girl’s Latest Fashion</h2>
                                                    <a href="shop_left_sidebar.html" class="ulinaLink"><i
                                                            class="fa-solid fa-angle-right"></i>Shop Now</a>
                                                </div>
                                                <img src="images/home1/3.png" alt="Mans Latest Collection">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li> --}}


                             <li class="menu-item">
                                <a href="{{ route('client.coupons.index') }}">Khuyến mãi</a>
                            </li>
                             <li class="menu-item">
                                <a href="{{ route('client.notifications.index') }}">Thông báo</a>
                            </li>
                            {{-- <li class="menu-item-has-children">
                                <a href="javascript:void(0);">Blog</a>
                                <ul>
                                    <li class="menu-item-has-children">
                                        <a href="javascript:void(0);">Blog Standard</a>
                                        <ul>
                                            <li><a href="blog_standard_lsb.html">Left Sidebar</a></li>
                                            <li><a href="blog_standard_nsb.html">No Sidebar</a></li>
                                            <li><a href="blog_standard_rsb.html">Right Sidebar</a></li>
                                        </ul>
                                    </li>
                                    <li class="menu-item-has-children">
                                        <a href="javascript:void(0);">Blog Grid</a>
                                        <ul>
                                            <li><a href="blog_grid_lsb.html">Left Sidebar</a></li>
                                            <li><a href="blog_grid_nsb.html">No Sidebar</a></li>
                                            <li><a href="blog_grid_rsb.html">Right Sidebar</a></li>
                                        </ul>
                                    </li>
                                    <li class="menu-item-has-children">
                                        <a href="javascript:void(0);">Blog Details</a>
                                        <ul>
                                            <li><a href="blog_details_lsb.html">Left Sidebar</a></li>
                                            <li><a href="blog_details_nsb.html">No Sidebar</a></li>
                                            <li><a href="blog_details_rsb.html">Right Sidebar</a></li>
                                        </ul>
                                    </li>
                                </ul>
                            </li> --}}

                            <li class="menu-item-has-children menu-blog">
    <a href="javascript:void(0);">Bài viết</a>
    <div class="megaMenu">
        <div class="row">
            <div class="col-lg-12">
                <ul>
                    @foreach($blogCategories as $blogCategory)
                        <li>
                            <a href="{{ route('client.blogs.index', ['category' => $blogCategory->id]) }}">
    {{ $blogCategory->name }}
</a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</li>

                             <li class="menu-item">
                                <a href="{{ route('client.contact.index') }}">Liên hệ</a>
                            </li>

                        </ul>
                    </div>
                    <div class="accessNav">

                        <a href="javascript:void(0);" class="menuToggler"><i class="fa-solid fa-bars"></i>
                            <span>Menu</span></a>

                        {{-- <div class="anSocial">
                            <div class="ansWrap">
                                <a class="fac" href="javascript:void(0);"><i class="fa-brands fa-facebook-f"></i></a>
                                <a class="twi" href="javascript:void(0);"><i class="fa-brands fa-twitter"></i></a>
                                <a class="lin" href="javascript:void(0);"><i
                                        class="fa-brands fa-linkedin-in"></i></a>
                                <a class="ins" href="javascript:void(0);"><i class="fa-brands fa-instagram"></i></a>
                            </div>
                            <a class="tog" href="javascript:void(0);"><i class="fa-solid fa-share-alt"></i></a>
                        </div> --}}

                        {{-- <div class="anSelects">
                            <div class="anSelect">
                                <select name="languages">
                                    <option value="ENG">EN</option>
                                    <option value="ARA">AR</option>
                                    <option value="GER">GR</option>
                                    <option value="SPA">SP</option>
                                </select>
                            </div>
                            <div class="anSelect">
                                <select name="currency">
                                    <option value="USD">USD</option>
                                    <option value="GBP">GBP</option>
                                    <option value="EUR">EUR</option>
                                    <option value="OMR">OMR</option>
                                </select>
                            </div>
                        </div> --}}
                        <div class="anItems">
                            <div class="searchToggle">
    <a href="javascript:void(0);" id="toggleSearch"><i class="fa fa-search"></i></a>

    <div class="searchWrapper" id="searchWrapper" style="display: none;">
        <form action="{{ route('search') }}" method="GET" class="searchForm">
            <input type="text" name="keyword" placeholder="Tìm kiếm sản phẩm..." required />
            <button type="submit"><i class="fa fa-search"></i></button>
        </form>
    </div>
</div>
                            <div class="wishlist">
                            <a href="{{route('wishlist.index')}}" class="pi01Wishlist"><i class="fa-solid fa-heart"></i></a>

                            </div>

                            <div class="anUser" id="userMenuWrapper">
                                <a href="#" id="userMenuToggle">
                                    <i class="fa-solid fa-user"></i>
                                </a>

                                <div class="userDropdownMenu" id="userDropdown" style="display: none;">
                                    <div class="userDropdownInner">
                                        @auth
                                            <div class="userDropdownHeader">
                                                <h3>Chào mừng, {{ Auth::user()->name }}!</h3>
                                            </div>
                                            <div class="userDropdownItem">
                                                @if (Auth::user()->role === 'admin')
                                                    <a href="{{ route('admin.dashboard') }}">
                                                        <i class="fa-solid fa-gauge"></i> Dashboard Admin
                                                    </a>
                                                @endif

                                                <a href="{{ route('client.profile.show') }}">
                                                    <i class="fa-solid fa-user-circle"></i> Tài khoản của tôi
                                                </a>
                                                <a href="{{ route('client.orders.purchase.history') }}">
                                                    <i class="fa-solid fa-user-circle"></i> Đơn Hàng
                                                </a>


                                                    <a href="{{ route('user.addresses.index') }}">
                                                        <i class="fa-solid fa-map-location-dot"></i> Địa chỉ của tôi
                                                    </a>


                                            </div>

                                            <div class="userDropdownFooter">
                                                <a href="#"
                                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                                    <i class="fa-solid fa-sign-out-alt"></i> Đăng xuất
                                                </a>
                                                <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                                    style="display: none;">
                                                    @csrf
                                                </form>
                                            </div>
                                        @else
                                            <div class="userDropdownHeader">
                                                <h3>Chào mừng, Khách!</h3>
                                            </div>
                                            <div class="userDropdownItem">
                                                <a href="{{ route('login') }}">
                                                    <i class="fa-solid fa-sign-in-alt"></i> Đăng nhập
                                                </a>
                                            </div>
                                            <div class="userDropdownItem">
                                                <a href="{{ route('register') }}">
                                                    <i class="fa-solid fa-user-plus"></i> Đăng ký
                                                </a>
                                            </div>
                                        @endauth
                                    </div>
                                </div>
                            </div>

                            {{-- hết user --}}
                            <div class="anCart" id="cart-widget">

                                <a href="javascript:void(0);"><i
                                        class="fa-solid fa-shopping-cart"></i><span class="cart-count">{{ $totalProduct }}</span></a>
                                <div class="cartWidgetArea">
                                    @include('partials.cart_widget')
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="anSupport">
                        <i class="fa-solid fa-headset"></i>
                        <h3>Số Điện Thoại</h3>
                        <h3>086 785 7597 </h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</header>
{{-- Form tìm kiếm ẩn giống Ulina --}}
<div class="searchWrapper" id="searchWrapper" style="display: none;">
    <div class="searchForm">
        <form action="{{ route('search') }}" method="GET">
            <input type="text" name="keyword" placeholder="Tìm kiếm sản phẩm..." required />
            <button type="submit"><i class="fa fa-search"></i></button>
        </form>
        <span class="searchClose" id="closeSearch"><i class="fa fa-times"></i></span>
    </div>
</div>

{{-- CSS --}}
<style>
    .searchWrapper {
    position: absolute;
    top: 40px; /* cách icon search một chút */
    right: 0;
    background: #fff;
    padding: 5px 10px;
    border: 1px solid #ccc;
    border-radius: 30px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    z-index: 9999;
}

.searchForm {
    display: flex;
    align-items: center;
}

.searchForm input {
    border: none;
    outline: none;
    padding: 8px 12px;
    font-size: 14px;
    width: 200px;
}

.searchForm button {
    background: none;
    border: none;
    cursor: pointer;
    font-size: 16px;
}

/* Chỉ áp dụng cho menu Bài viết */
.menu-blog > .megaMenu {
    position: absolute;
    top: 100%; /* ngay dưới menu cha */
    left: 0;
    display: none; /* ẩn mặc định */
    background: #fff;
    border: 1px solid #ddd;
    border-radius: 6px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    padding: 6px 0;
    min-width: 180px;
    z-index: 1000;
}

/* Hover hiện menu */
.menu-blog:hover > .megaMenu {
    display: block;
}

/* Reset ul li */
.menu-blog .megaMenu ul {
    list-style: none;
    margin: 0;
    padding: 0;
}

.menu-blog .megaMenu li {
    white-space: nowrap;
}






</style>

{{-- JavaScript --}}
<script>
    const toggleBtn = document.getElementById('toggleSearch');
    const searchWrapper = document.getElementById('searchWrapper');
    const closeBtn = document.getElementById('closeSearch');

    // Toggle khi click vào icon search
    toggleBtn.addEventListener('click', function (e) {
        e.stopPropagation(); // chặn lan truyền
        searchWrapper.style.display = 'flex';
    });

    // Đóng khi click vào nút X
    closeBtn.addEventListener('click', function () {
        searchWrapper.style.display = 'none';
    });

    // Đóng khi click ra ngoài
    document.addEventListener('click', function (e) {
        if (searchWrapper.style.display === 'flex' && !searchWrapper.contains(e.target) && e.target !== toggleBtn) {
            searchWrapper.style.display = 'none';
        }
    });


</script>
