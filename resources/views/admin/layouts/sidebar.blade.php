<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
                <li class="menu-title">Quản lý</li>

                <!-- Dashboard -->
                <li class="active">
                    <a href="{{ route('admin.dashboard') }}">
                        <img src="{{ asset('assets/admin/img/icons/dashboard.svg') }}" alt="img">
                        <span> Thống kê</span>
                    </a>
                </li>

                <!-- Thương hiệu -->
                <li class="submenu">
                    <a href="javascript:void(0);">
                        <img src="{{ asset('assets/admin/img/icons/purchase1.svg') }}" alt="img">
                        <span> Thương hiệu</span> <span class="menu-arrow"></span>
                    </a>
                    <ul>
                        <li><a href="{{ route('admin.brands.index') }}">Danh sách thương hiệu</a></li>
                        <li><a href="{{ route('admin.brands.create') }}">Thêm thương hiệu</a></li>
                    </ul>
                </li>

                <!-- Danh mục -->
                <li class="submenu">
                    <a href="javascript:void(0);">
                        <img src="{{ asset('assets/admin/img/icons/purchase1.svg') }}" alt="img">
                        <span> Danh mục</span> <span class="menu-arrow"></span>
                    </a>
                    <ul>
                        <li><a href="{{ route('admin.categories.index') }}">Xem danh mục</a></li>
                        <li><a href="{{ route('admin.categories.create') }}">Thêm danh mục</a></li>
                    </ul>
                </li>

                <!-- Sản phẩm -->
                <li class="submenu">
                    <a href="javascript:void(0);">
                        <img src="{{ asset('assets/admin/img/icons/product.svg') }}" alt="img">
                        <span> Sản phẩm</span> <span class="menu-arrow"></span>
                    </a>
                    <ul>
                        <li><a href="{{ route('admin.products.index') }}">Danh sách sản phẩm</a></li>
                        <li><a href="{{ route('admin.products.create') }}">Thêm sản phẩm</a></li>
                    </ul>
                </li>

                <!-- Biến thể -->
                <li class="submenu">
                    <a href="javascript:void(0);">
                        <img src="{{ asset('assets/admin/img/icons/transcation.svg') }}" alt="img">
                        <span> Biến thể</span> <span class="menu-arrow"></span>
                    </a>
                    <ul>
                        <li><a href="{{ route('admin.attributes.index') }}">Danh sách biến thể</a></li>
                        <li><a href="{{ route('admin.attributes.create') }}">Thêm biến thể</a></li>
                    </ul>
                </li>

                <!-- Đơn hàng -->
                <li class="submenu">
                    <a href="javascript:void(0);">
                         <img src="{{ asset('assets/admin/img/icons/wallet1.svg') }}" alt="img">
                        <span> Thanh toán</span> <span class="menu-arrow"></span>
                    </a>
                    <ul>
                        <li><a href="{{ route('admin.orders.index') }}">Quản lý đơn hàng</a></li>
                    </ul>
                </li>

                <!-- Trạng thái đơn hàng -->
                <li class="submenu">
                    <a href="javascript:void(0);">
                        <img src="{{ asset('assets/admin/img/icons/transcation.svg') }}" alt="img">
                        <span> Trạng thái đơn hàng</span> <span class="menu-arrow"></span>
                    </a>
                    <ul>
                        <li><a href="{{ route('admin.order_statuses.index') }}">Danh sách trạng thái</a></li>
                        <li><a href="{{ route('admin.order_statuses.create') }}">Thêm trạng thái</a></li>
                    </ul>
                </li>

                <!-- Khuyến mãi -->
                <li>
                    <a href="{{ route('admin.coupon.index') }}">
                        <img src="{{ asset('assets/admin/img/icons/transcation.svg') }}" alt="img">
                        <span> Khuyến mãi</span>
                    </a>
                </li>

                <!-- Hoàn hàng -->
                <li>
                    <a href="{{ route('admin.refunds.index') }}">
                        <img src="{{ asset('assets/admin/img/icons/transcation.svg') }}" alt="img">
                        <span> Hoàn hàng</span>
                    </a>
                </li>

                <!-- Đánh giá -->
                <li>
                    <a href="{{ route('admin.reviews.index') }}">
                        <img src="{{ asset('assets/admin/img/icons/transcation.svg') }}" alt="img">
                        <span> Đánh giá</span>
                    </a>
                </li>

                <!-- Bình luận -->
                <li class="submenu">
                    <a href="javascript:void(0);">
                        <img width="50" height="50" src="https://img.icons8.com/ios/50/chat-message.png" alt="chat-message"/>
                        <span> Bình luận</span> <span class="menu-arrow"></span>
                    </a>
                    <ul>
                        <li><a href="{{ route('admin.comments.index') }}">Danh sách bình luận</a></li>
                        <li><a href="{{ route('admin.replies.index') }}">Danh sách phản hồi</a></li>
                    </ul>
                </li>

                <!-- Người dùng -->
                <li class="submenu">
                    <a href="javascript:void(0);">
                        <img src="{{ asset('assets/admin/img/icons/users1.svg') }}" alt="img">
                        <span> Người dùng</span> <span class="menu-arrow"></span>
                    </a>
                    <ul>
                        <li><a href="{{ route('admin.users.index') }}">Tài khoản</a></li>
                        <li><a href="{{ route('admin.users.locked') }}">Tài khoản bị khóa</a></li>
                    </ul>
                </li>

                <!-- Bài viết -->
                 <li class="submenu">
                    <a href="javascript:void(0);">
                        <img src="{{ asset('assets/admin/img/icons/transcation.svg') }}" alt="img">
                        <span> Bài viết</span> <span class="menu-arrow"></span>
                    </a>
                    <ul>
                        <li><a href="{{ route('admin.blogs.index') }}">Danh sách bài viết</a></li>
                        <li><a href="{{ route('admin.blogs.create') }}">Thêm bài viết</a></li>
                        <li><a href="{{ route('admin.blog_categories.index') }}">Danh mục bài viết</a></li>
                        <li><a href="{{ route('admin.blog_categories.create') }}">Thêm danh mục</a></li>
                    </ul>
                </li>
                <!-- Liên hệ -->
                        <li class="submenu">
                            <a href="javascript:void(0);">
                                <img src="{{ asset('assets/admin/img/icons/contact.jpg') }}" alt="img">
                                <span> Liên hệ</span> <span class="menu-arrow"></span>
                            </a>
                            <ul>
                                <li><a href="{{ route('admin.contact.index') }}">Trang liên hệ</a></li>
                            </ul>
                        </li>

            </ul>
        </div>
    </div>
</div>
