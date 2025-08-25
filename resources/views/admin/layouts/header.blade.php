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
