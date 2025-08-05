<aside id="left-panel" class="left-panel">
    <nav class="navbar navbar-expand-sm navbar-default">
        <div id="main-menu" class="main-menu collapse navbar-collapse">
            <ul class="nav navbar-nav">
                <li class="menu-title">Quản lý</li>

                <!-- Dashboard -->
                <li>
                    <a href="{{ route('admin.dashboard') }}">
                        <i class="menu-icon fa fa-area-chart"></i> Thống kê
                    </a>
                </li>

                <!-- Thương hiệu -->
                <li class="menu-item-has-children dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="menu-icon fa fa-tag"></i> Thương hiệu
                    </a>
                    <ul class="sub-menu children dropdown-menu">
                        <li><i class="fa fa-list"></i><a href="{{ route('admin.brands.index') }}">Danh sách thương hiệu</a></li>
                        <li><i class="fa fa-plus"></i><a href="{{ route('admin.brands.create') }}">Thêm thương hiệu</a></li>
                    </ul>
                </li>

                <!-- Danh mục -->
                <li class="menu-item-has-children dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="menu-icon fa fa-book"></i> Danh mục
                    </a>
                    <ul class="sub-menu children dropdown-menu">
                        <li><i class="fa fa-list"></i><a href="{{ route('admin.categories.index') }}">Xem danh mục</a></li>
                        <li><i class="fa fa-plus"></i><a href="{{ route('admin.categories.create') }}">Thêm danh mục</a></li>
                    </ul>
                </li>

                <!-- Sản phẩm -->
                <li class="menu-item-has-children dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="menu-icon fa fa-archive"></i> Sản phẩm
                    </a>
                    <ul class="sub-menu children dropdown-menu">
                        <li><i class="fa fa-list"></i><a href="{{ route('admin.products.index') }}">Danh sách sản phẩm</a></li>
                        <li><i class="fa fa-plus"></i><a href="{{ route('admin.products.create') }}">Thêm sản phẩm</a></li>
                    </ul>
                </li>

                <!-- Biến thể -->
                <li class="menu-item-has-children dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="menu-icon fa fa-sliders"></i> Biến thể
                    </a>
                    <ul class="sub-menu children dropdown-menu">
                        <li><i class="fa fa-list"></i><a href="{{ route('admin.attributes.index') }}">Danh sách biến thể</a></li>
                        <li><i class="fa fa-plus"></i><a href="{{ route('admin.attributes.create') }}">Thêm biến thể</a></li>
                    </ul>
                </li>

                <!-- Đơn hàng -->
                <li class="menu-item-has-children dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="menu-icon fa fa-credit-card"></i> Thanh toán
                    </a>
                    <ul class="sub-menu children dropdown-menu">
                        <li><i class="fa fa-list"></i><a href="{{ route('admin.orders.index') }}">Quản lý đơn hàng</a></li>
                    </ul>
                </li>

                <!-- Trạng thái đơn hàng -->
                <li class="menu-item-has-children dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="menu-icon fa fa-cogs"></i> Trạng thái đơn hàng
                    </a>
                    <ul class="sub-menu children dropdown-menu">
                        <li><i class="fa fa-list"></i><a href="{{ route('admin.order_statuses.index') }}">Danh sách trạng thái</a></li>
                        <li><i class="fa fa-plus"></i><a href="{{ route('admin.order_statuses.create') }}">Thêm trạng thái</a></li>
                    </ul>
                </li>

                <!-- Khuyến mãi -->
                <li>
                    <a href="{{ route('admin.coupon.index') }}">
                        <i class="menu-icon fa fa-gift"></i> Khuyến mãi
                    </a>
                </li>

                <!-- Hoàn hàng -->
                <li>
                    <a href="{{ route('admin.refunds.index') }}">
                        <i class="menu-icon fa fa-undo"></i> Hoàn hàng
                    </a>
                </li>

                <!-- Đánh giá -->
                <li>
                    <a href="{{ route('admin.reviews.index') }}">
                        <i class="menu-icon fa fa-comments"></i> Đánh giá
                    </a>
                </li>

                <!-- Bình luận -->
                <li class="menu-item-has-children dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="menu-icon fa fa-comments"></i> Bình luận
                    </a>
                    <ul class="sub-menu children dropdown-menu">
                        <li><i class="fa fa-comment"></i><a href="{{ route('admin.comments.index') }}">Danh sách bình luận</a></li>
                        <li><i class="fa fa-reply"></i><a href="{{ route('admin.replies.index') }}">Danh sách phản hồi</a></li>
                    </ul>
                </li>

                <!-- Người dùng -->
                <li class="menu-item-has-children dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="menu-icon fa fa-users"></i> Người dùng
                    </a>
                    <ul class="sub-menu children dropdown-menu">
                        <li><i class="fa fa-user"></i><a href="{{ route('admin.users.index') }}">Tài khoản</a></li>
                        <li><i class="fa fa-plus"></i><a href="{{ route('admin.users.create') }}">Thêm tài khoản</a></li>
                        <li><i class="fa fa-lock"></i><a href="{{ route('admin.users.locked') }}">Tài khoản bị khóa</a></li>
                    </ul>
                </li>

                <!-- Bài viết -->
                <li class="menu-item-has-children dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="menu-icon fa fa-book"></i> Bài viết
                    </a>
                    <ul class="sub-menu children dropdown-menu">
                        <li><i class="fa fa-list"></i><a href="{{ route('admin.blogs.index') }}">Danh sách bài viết</a></li>
                        <li><i class="fa fa-plus"></i><a href="{{ route('admin.blogs.create') }}">Thêm bài viết</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</aside>
