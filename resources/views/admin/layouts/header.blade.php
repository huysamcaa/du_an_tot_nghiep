<header id="header" class="header">
    <div class="top-left">
                            <div class="logo">
                        <a href="index.html">
                            <img src="{{ asset('assets/images/logo.png') }}" alt="FreshFit" class="logo-img" height="150px"  />
                        </a>
                    </div>

    </div>
    <div class="top-right">
        <div class="header-menu">
            <div class="header-left">
                <button class="search-trigger"><i class="fa fa-search"></i></button>
                <div class="form-inline">
                    <form class="search-form">
                        <input class="form-control mr-sm-2" type="text" placeholder="Search ..." aria-label="Search">
                        <button class="search-close" type="submit"><i class="fa fa-close"></i></button>
                    </form>
                </div>
                <div class="dropdown for-notification">
                    <button class="btn btn-secondary dropdown-toggle" type="button" id="notification"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-bell"></i>
                        {{-- <span class="count bg-danger">3</span> --}}
                    </button>
                </div>

                <div class="dropdown for-message">
                    <button class="btn btn-secondary dropdown-toggle" type="button" id="message"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-envelope"></i>
                        {{-- <span class="count bg-primary">4</span> --}}
                    </button>
                </div>
            </div>

            <div class="user-area dropdown float-right">
                <a href="#" class="dropdown-toggle active" data-toggle="dropdown" aria-haspopup="true"
                    aria-expanded="false">
                    @php
                        $currentUser = Auth::user();
                    @endphp

                    <img class="user-avatar rounded-circle"
                        src="{{ $currentUser && $currentUser->avatar ? asset('storage/' . $currentUser->avatar) : asset('assets/images/default.png') }}"
                        alt="User Avatar">
                </a>

                <div class="user-menu dropdown-menu">
                    <a class="nav-link" href="#"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fa fa-power-off"></i>Logout
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>
<style>
    .logo-img{
        margin-top: -45px;
    }
</style>
