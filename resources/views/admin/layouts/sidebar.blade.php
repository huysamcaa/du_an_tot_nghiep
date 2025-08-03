<aside id="left-panel" class="left-panel">
    <nav class="navbar navbar-expand-sm navbar-default">
        <div id="main-menu" class="main-menu collapse navbar-collapse">
            <ul class="nav navbar-nav">

                <li class="menu-title">UI elements</li>
                <li class="menu-item-has-children dropdown">
                <li>
                    <a href="{{ route('admin.brands.index') }}">
                        <i class="menu-icon fa fa-tag"></i>
                        Thương Hiệu
                    </a>
                </li>


                </li>
                <li class="menu-item-has-children dropdown">
                <li>
                    <a href="http://127.0.0.1:8000/admin">
                        <i class="menu-icon fa fa-comments"></i> Thống Kê
                    </a>
                </li>

                </li>

                <!-- Các menu item khác -->
                <li class="menu-item-has-children dropdown">
                    <a href="admin.categories.index" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false">
                        <i class="menu-icon fa fa-cogs"></i>Danh mục
                    </a>
                    <ul class="sub-menu children dropdown-menu">
                        <li><i class="fa fa-puzzle-piece"></i><a href="{{ route('admin.categories.index') }}">Xem Danh
                                Mục</a></li>
                        <!-- Các mục con khác -->
                    </ul>
                </li>


                <li class="menu-title"></li><!-- /.menu-title -->
                <li class="menu-item-has-children dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false"> <i class="menu-icon fa fa-archive"></i>Sản Phẩm</a>
                    <ul class="sub-menu children dropdown-menu">
                        <li><i class="fa fa-calendar"></i><a href="{{ route('admin.products.index') }}">Danh sách sản
                                phẩm</a></li>
                        <li><i class="fa fa-bars"></i><a href="{{ route('admin.products.create') }}">Thêm sản phẩm</a>
                        </li>
                    </ul>
                </li>


                <li class="menu-item-has-children dropdown">
                <li>
                    <a href="{{ route('admin.coupon.index') }}">
                        <i class="menu-icon fa fa-industry"></i>Khuyến mãi
                    </a>

                </li>
                </li>
                <li class="menu-item-has-children dropdown">
                <li>
                    <a href="{{ route('admin.refunds.index') }}">
                        <i class="menu-icon fa fa-industry"></i>Hoàn Hàng 
                    </a>

                </li>
                </li>
                <li class="menu-item-has-children dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false">
                        <i class="menu-icon fa fa-industry"></i>Biến thể
                    </a>
                    <ul class="sub-menu children dropdown-menu">
                        <li>
                            <i class="fa fa-puzzle-piece"></i>
                            <a href="{{ route('admin.attributes.index') }}">
                                Quản lý Biến thể
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="menu-item-has-children dropdown">
                    <a href="admin.users.index" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false">
                        <i class="menu-icon fa fa-cogs"></i>Người dùng
                    </a>
                    <ul class="sub-menu children dropdown-menu">
                        <li><i class="fa fa-puzzle-piece"></i><a href="http://127.0.0.1:8000/admin/users">Xem tài
                                khoản</a></li>
                        <li><i class="fa fa-lock"></i><a href="{{ route('admin.users.locked') }}">Tài khoản bị khóa</a>
                        </li>
                        <!-- Các mục con khác -->
                    </ul>
                </li>

                <li class="menu-item-has-children dropdown">

                    <a href="{{ route('admin.order_statuses.index') }}" class="dropdown-toggle" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                        <i class="menu-icon fa fa-cogs"></i>Trạng thái đơn hàng
                    </a>
                    <ul class="sub-menu children dropdown-menu">
                        <li><i class="fa fa-calendar"></i><a href="{{ route('admin.order_statuses.index') }}">Danh
                                sách trạng thái</a></li>
                        <li><i class="fa fa-bars"></i><a href="{{ route('admin.order_statuses.create') }}">Thêm trạng
                                thái</a></li>

                        <!-- Các mục con khác -->
                    </ul>
                </li>
                <li class="menu-item-has-children dropdown">
                    <a href="{{ route('admin.comments.index') }}" class="dropdown-toggle" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                        <i class="menu-icon fa fa-cogs"></i>Bình luận
                    </a>
                    <ul class="sub-menu children dropdown-menu">
                        <li><i class="fa fa-puzzle-piece"></i><a href="{{ route('admin.comments.index') }}">Danh sách
                                bình luận</a></li>
                        <li><i class="fa fa-puzzle-piece"></i><a href="{{ route('admin.replies.index') }}">Danh sách
                                phản hồi bình luận</a></li>
                    </ul>
                </li>

                <li class="menu-item-has-children dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false">
                        <i class="menu-icon fa fa-tag"></i> Thanh Toán
                    </a>
                    <ul class="sub-menu children dropdown-menu">
                        <li>
                            <i class="fa fa-trademark"></i>
                            <a href="{{ route('admin.orders.index') }}">
                                Quản lý đơn hàng
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="menu-item-has-children dropdown">
                <li>
                    <a href="{{ route('admin.reviews.index') }}">
                        <i class="menu-icon fa fa-comments"></i> Đánh Giá
                    </a>
                </li>

                </li>
                <li class="menu-item-has-children dropdown">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="menu-icon fa fa-book"></i> Bài Viết
    </a>
    <ul class="sub-menu children dropdown-menu">
        <li><i class="fa fa-list"></i>
            <a href="{{ route('admin.blogs.index') }}">Danh sách bài viết</a>
        </li>
        <li><i class="fa fa-plus"></i>
            <a href="{{ route('admin.blogs.create') }}">Thêm bài viết</a>
        </li>
    </ul>
</li>

        </div>
    </nav>
</aside>