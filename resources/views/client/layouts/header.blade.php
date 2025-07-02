<header class="header01 isSticky">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="headerInner01">
                            <div class="logo">
                                <a href="index.html">
                                    <img src="{{ asset('assets/Client/images/logo.png') }}" alt="Ulina"/>
                                </a>
                            </div>
                            <div class="mainMenu">
    <ul>
        <li><a href="{{ route('client.home') }}">Home</a></li>
        <li><a href="{{ route('client.categories.index') }}">Category</a></li>
        <li><a href="{{ route('client.coupons.index') }}">Promotion</a></li>
    
    </ul>
</div>

                            <div class="accessNav">
                                <a href="javascript:void(0);" class="menuToggler"><i class="fa-solid fa-bars"></i> <span>Menu</span></a>
                                <div class="anSocial">
                                    <div class="ansWrap">
                                        <a class="fac" href="javascript:void(0);"><i class="fa-brands fa-facebook-f"></i></a>
                                        <a class="twi" href="javascript:void(0);"><i class="fa-brands fa-twitter"></i></a>
                                        <a class="lin" href="javascript:void(0);"><i class="fa-brands fa-linkedin-in"></i></a>
                                        <a class="ins" href="javascript:void(0);"><i class="fa-brands fa-instagram"></i></a>
                                    </div>
                                    <a class="tog" href="javascript:void(0);"><i class="fa-solid fa-share-alt"></i></a>
                                </div>
                                <div class="anSelects">
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
                                </div>
                                <div class="anItems">
<div class="anSearch"><a href="javascript:void(0);"><i class="fa-solid fa-search"></i></a></div>


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
                                                        @if(Auth::user()->role === 'admin')
                                                            <a href="{{ route('admin.dashboard') }}">
                                                                <i class="fa-solid fa-dashboard"></i> Dashboard Admin
                                                            </a>
                                                        @else
                                                            <a href="{{ route('client.profile.show') }}">
                                                                <i class="fa-solid fa-user-circle"></i> Tài khoản của tôi
                                                            </a>
                                                              <a href="{{route('user.addresses.index')}}">
                                                                <i class="fa-solid fa-map-location-dot"></i> Địa chỉ của tôi
                                                            </a>
                                                        @endif
                                                    </div>
                                                    <div class="userDropdownFooter">
                                                        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                                            <i class="fa-solid fa-sign-out-alt"></i> Đăng xuất
                                                        </a>
                                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
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
                                        <a href="javascript:void(0);" class="anCartToggle">
    <i class="fa-solid fa-shopping-cart"></i>
    <span class="cart-count">{{ $totalProduct }}</span>
</a>

                                        
                                        <div class="cartWidgetArea">
                                            @foreach($cartItems as $item)
                                            <div class="cartWidgetProduct">
                                                <img src="{{ asset($item->product->thumbnail) }}" alt="Marine Design">
                                                <a href="shop_details1.html">{{ $item->product->name }}</a>
                                                <div class="cartProductPrice clearfix">
                                                    <span class="price">{{ number_format($item->product->price) }}đ</span>
                                                </div>
                                                <a href="{{ route('cart.destroy', $item->id) }}" class="cartRemoveProducts"><i class="fa-solid fa-xmark"></i></a>
                                            </div>
                                            @endforeach
                                            <div class="totalPrice" id="cart-total">Subtotal: <span class="price">{{ number_format($total) }}đ</span></div>
                                            <div class="cartWidgetBTN clearfix">
                                                <a class="cart" href="{{ route('cart.index') }}">View Cart</a>
                                                <a class="checkout" href="{{ route('checkout') }}">Checkout</a>
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
        </header>
