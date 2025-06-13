<aside id="left-panel" class="left-panel">
    <nav class="navbar navbar-expand-sm navbar-default">
        <div id="main-menu" class="main-menu collapse navbar-collapse">
            <ul class="nav navbar-nav">

                <li class="menu-title">UI elements</li>

                <!-- Các menu item khác -->
                <li class="menu-item-has-children dropdown">
                    <a href="admin.categories.index" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="menu-icon fa fa-cogs"></i>Danh mục
                    </a>
                    <ul class="sub-menu children dropdown-menu">
                        <li><i class="fa fa-puzzle-piece"></i><a href="http://127.0.0.1:8000/admin/categories">Xem Danh Mục</a></li>
                        <!-- Các mục con khác -->
                    </ul>
                </li>

                  <li class="menu-item-has-children dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"
                       aria-haspopup="true" aria-expanded="false">
                        <i class="menu-icon fa fa-industry"></i>Nhà sản xuất
                    </a>
                    <ul class="sub-menu children dropdown-menu">
                        <li>
                            <i class="fa fa-puzzle-piece"></i>
                            <a href="{{ route('admin.manufacturers.index') }}">
                                Quản lý nhà sản xuất
                            </a>
                        </li>
                    </ul>
                     <li class="menu-item-has-children dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"
                       aria-haspopup="true" aria-expanded="false">
                        <i class="menu-icon fa fa-credit-card"></i>Khuyến mãi
                    </a>
                    <ul class="sub-menu children dropdown-menu">
                        <li>
                            <i class="fa fa fa-tag"></i>
                            <a href="{{ route('admin.promotions.index') }}">
                                Quản lý khuyến mãi
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="menu-item-has-children dropdown">
                    <a href="admin.carts.index" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="menu-icon fa fa-cogs"></i>Giỏ Hàng
                    </a>
                    <ul class="sub-menu children dropdown-menu">
                        <li><i class="fa fa-puzzle-piece"></i><a href="{{route('admin.carts.index')}}">Xem Giỏ Hàng</a></li>
                        <!-- Các mục con khác -->
                    </ul>
                </li>
                <!-- Thêm các menu item khác tương tự -->
            </ul>
        </div>
    </nav>
</aside>
