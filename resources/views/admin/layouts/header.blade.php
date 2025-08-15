<div class="header">

    <div class="header-left active">
        <a href="index.html" class="logo">
            <img src="{{ asset('assets/images/logo.png') }}" alt="FreshFit" class="logo-img" />
        </a>
        <a href="index.html" class="logo-small">
           <img src="{{ asset('assets/images/logo.png') }}" alt="FreshFit" class="logo-img" />
        </a>
        <a id="toggle_btn" href="javascript:void(0);">
        </a>
    </div>

    <a id="mobile_btn" class="mobile_btn" href="#sidebar">
        <span class="bar-icon">
            <span></span>
            <span></span>
            <span></span>
        </span>
    </a>

    <ul class="nav user-menu">

        <li class="nav-item">
            <div class="top-nav-search">
                <a href="javascript:void(0);" class="responsive-search">
                    <i class="fa fa-search"></i>
                </a>
                <form action="#">
                    <div class="searchinputs">
                        <input type="text" placeholder="Tìm kiếm...">
                        <div class="search-addon">
                            <span><img src="{{ asset('assets/admin/img/icons/closes.svg') }}" alt="img"></span>
                        </div>
                    </div>
                    <a class="btn" id="searchdiv"><img src="{{ asset('assets/admin/img/icons/search.svg') }}" alt="img"></a>
                </form>
            </div>
        </li>

        <li class="nav-item dropdown has-arrow flag-nav">
            <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="javascript:void(0);" role="button">
                <img src="{{ asset('assets/admin/img/flags/us1.png') }}" alt="" height="20">
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                <a href="javascript:void(0);" class="dropdown-item">
                    <img src="{{ asset('assets/admin/img/flags/us.png') }}" alt="" height="16"> Tiếng Anh
                </a>
                <a href="javascript:void(0);" class="dropdown-item">
                    <img src="{{ asset('assets/admin/img/flags/fr.png') }}" alt="" height="16"> Tiếng Pháp
                </a>
                <a href="javascript:void(0);" class="dropdown-item">
                    <img src="{{ asset('assets/admin/img/flags/es.png') }}" alt="" height="16"> Tiếng Tây Ban Nha
                </a>
                <a href="javascript:void(0);" class="dropdown-item">
                    <img src="{{ asset('assets/admin/img/flags/de.png') }}" alt="" height="16"> Tiếng Đức
                </a>
            </div>
        </li>

        <li class="nav-item dropdown">
            <a href="javascript:void(0);" class="dropdown-toggle nav-link" data-bs-toggle="dropdown">
                <img src="{{ asset('assets/admin/img/icons/notification-bing.svg') }}" alt="img"> <span class="badge rounded-pill">4</span>
            </a>
            <div class="dropdown-menu notifications">
                <div class="topnav-dropdown-header">
                    <span class="notification-title">Thông báo</span>
                    <a href="javascript:void(0)" class="clear-noti"> Xóa tất cả </a>
                </div>
                <div class="noti-content">
                    <ul class="notification-list">
                        <li class="notification-message">
                            <a href="activities.html">
                                <div class="media d-flex">
                                    <span class="avatar flex-shrink-0">
                                        <img alt="" src="{{ asset('assets/admin/img/profiles/avatar-02.jpg') }}">
                                    </span>
                                    <div class="media-body flex-grow-1">
                                        <p class="noti-details"><span class="noti-title">John Doe</span> đã thêm nhiệm vụ mới <span class="noti-title">Đặt lịch hẹn bệnh nhân</span></p>
                                        <p class="noti-time"><span class="notification-time">4 phút trước</span></p>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li class="notification-message">
                            <a href="activities.html">
                                <div class="media d-flex">
                                    <span class="avatar flex-shrink-0">
                                        <img alt="" src="{{ asset('assets/admin/img/profiles/avatar-03.jpg') }}">
                                    </span>
                                    <div class="media-body flex-grow-1">
                                        <p class="noti-details"><span class="noti-title">Tarah Shropshire</span> đã thay đổi tên nhiệm vụ <span class="noti-title">Đặt lịch hẹn với cổng thanh toán</span></p>
                                        <p class="noti-time"><span class="notification-time">6 phút trước</span></p>
                                    </div>
                                </div>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="topnav-dropdown-footer">
                    <a href="activities.html">Xem tất cả thông báo</a>
                </div>
            </div>
        </li>

        @php
        use Illuminate\Support\Facades\Auth;
        $currentUser = Auth::user();
        @endphp

        <li class="nav-item dropdown has-arrow main-drop">
            <a href="javascript:void(0);" class="dropdown-toggle nav-link userset" data-bs-toggle="dropdown">
                <span class="user-img">
                    <img src="{{ $currentUser && $currentUser->avatar ? asset('storage/' . $currentUser->avatar) : asset('images/default-avatar.png') }}" alt="">
                    <span class="status online"></span>
                </span>
            </a>
            <div class="dropdown-menu menu-drop-user">
                <div class="profilename">
                    <div class="profileset">
                        <span class="user-img">
                            <img src="{{ $currentUser && $currentUser->avatar ? asset('storage/' . $currentUser->avatar) : asset('images/default-avatar.png') }}" alt="">
                            <span class="status online"></span>
                        </span>
                        <div class="profilesets">
                            <h6>{{ $currentUser->name }}</h6>
                            <h5>{{ $currentUser->role === 'admin' ? 'Quản trị viên' : 'Người dùng' }}</h5>
                        </div>
                    </div>
                    <hr class="m-0">
                    <a class="dropdown-item" href="#">
                        <i class="me-2" data-feather="user"></i> Hồ sơ của tôi
                    </a>
                    <a class="dropdown-item" href="#">
                        <i class="me-2" data-feather="settings"></i> Cài đặt
                    </a>
                    <hr class="m-0">
                    <a class="dropdown-item logout pb-0" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <img src="{{ asset('assets/admin/img/icons/log-out.svg') }}" class="me-2" alt="img"> Đăng xuất
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </div>
        </li>

        <!-- Menu Mobile -->
        <div class="dropdown mobile-user-menu">
            <a href="javascript:void(0);" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fa fa-ellipsis-v"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                <a class="dropdown-item" href="#">Hồ sơ của tôi</a>
                <a class="dropdown-item" href="#">Cài đặt</a>
                <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form-mobile').submit();">Đăng xuất</a>
                <form id="logout-form-mobile" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
        </div>

</div>
