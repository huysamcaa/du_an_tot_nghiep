<header class="header01 isSticky">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="headerInner01">
                    <div class="logo">
                        <a href="index.html">
                            <img src="{{ asset('assets/Client/images/logo.png') }}" alt="Ulina" />
                        </a>
                    </div>
                    <div class="mainMenu">
                        <ul>
                            <li class="menu-item">
                                <a href="{{ route('client.home') }}">Trang Chủ</a>
                            </li>
                            <li class="menu-item">
                                <a href="{{ route('client.categories.index') }}">Danh mục</a>
                            </li>
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

                            <li><a href="{{ route('client.blogs.index') }}">Bài viết</a></li>
                            
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
                            <div class="anSearch"><a href="javascript:void(0);"><i class="fa-solid fa-search"></i></a>

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
                            <div class="anCart">

                                <a href="javascript:void(0);"><i
                                        class="fa-solid fa-shopping-cart"></i><span>{{ $totalProduct }}</span></a>
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
