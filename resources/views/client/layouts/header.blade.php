<header class="header01 isSticky">




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


                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="anSupport">
                        <i class="fa-solid fa-headset"></i>
                        <h3>Số Điện Thoại</h3>
                        <h3>+ 84 867 857 597</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</header>
