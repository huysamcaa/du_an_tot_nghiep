@extends('client.layouts.app')

@section('content')
<section class="pageBannerSection">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="pageBannerContent text-center">
                    <h2>Bài viết</h2>
                    <div class="pageBannerPath">
                        <a href="{{ route('client.home') }}">Trang chủ</a>&nbsp;&nbsp;&gt;&nbsp;&nbsp;<span>Bài viết</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    .blog-card-img { height: 250px; width: 100%; object-fit: cover; border-top-left-radius: 0.25rem; border-top-right-radius: 0.25rem; }
        .pageBannerSection {
        background:#ECF5F4;
        padding: 10px 0;
    }
    .pageBannerContent h2 {

        font-size: 72px;
        color:#52586D;
        font-family: 'Jost', sans-serif;
    }
    .pageBannerPath a {
        color: #007bff;
        text-decoration: none;
    }
    
    .pageBannerSection {
        background:#ECF5F4;
        padding: 10px 0;
    }
    .pageBannerContent h2 {
        
        font-size: 72px;
        color:#52586D;
        font-family: 'Jost', sans-serif;
    }
    .pageBannerPath a {
        color: #007bff;
        text-decoration: none;
    }
    .checkoutPage {
    margin-top: 0 !important;
    padding-top: 0 !important;
    
}
.pageBannerSection {
    padding: 20px 0; 
    min-height: 10px; 
}

.pageBannerSection .pageBannerContent h2 {
    font-size: 38px; 
    margin-bottom: 10px;
}
.pageBannerPath {
    font-size: 14px;
}
form select.form-control {
    border: none;
    background-color: #f2f3f5;   /* nền xám nhạt */
    border-radius: 25px;         /* bo tròn góc */
    padding: 8px 16px;
    font-size: 15px;
    color: #333;
    font-weight: 500;
    appearance: none;            /* ẩn mũi tên mặc định */
    -webkit-appearance: none;
    -moz-appearance: none;
    cursor: pointer;
    transition: all 0.2s ease-in-out;
        background: #f2f3f5 url("data:image/svg+xml;utf8,<svg fill='black' height='16' viewBox='0 0 24 24' width='16' xmlns='http://www.w3.org/2000/svg'><path d='M7 10l5 5 5-5z'/></svg>") 
                no-repeat right 12px center;
    background-size: 16px;
    padding-right: 36px; /* chừa chỗ cho icon */
    margin-left: -440px;
    margin-top: -50px;
}

/* Hover effect */
form select.form-control:hover {
    background-color: #e6e6e6;
}

/* Khi focus */
form select.form-control:focus {
    outline: none;
    box-shadow: 0 0 0 2px #007bff33;
}

/* Tùy chỉnh option */
form select.form-control option {
    padding: 10px;
}

/* Bootstrap pagination override */
.page-item .page-link {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 42px;
    height: 42px;
    border-radius: 50% !important; /* bắt buộc tròn */
    border: 1px solid #e0e0e0;
    background-color: #fff;
    color: #6c757d;
    font-size: 16px;
    transition: all 0.2s ease-in-out;
    padding: 0; /* bỏ padding mặc định */
}

/* Hover */
.page-item .page-link:hover {
    background-color: #f7f7f7;
    color: #333;
    transform: scale(1.05);
}

/* Active */
.page-item.active .page-link {
    background-color: #789795;
    color: #fff;
    border-color: #789795;
}

/* Disabled */
.page-item.disabled .page-link {
    color: #c4c4c4;
    background-color: #fff;
    border-color: #e0e0e0;
    cursor: not-allowed;
}
.page-item {
    margin: 0 4px; /* thêm khoảng cách giữa các nút */
}
</style>

<div class="container pt-5 mt-5" style="min-height: 80vh;">

    {{-- Bộ lọc danh mục --}}
    <div class="row mb-4">
        <div class="col-md-4 offset-md-8">
            <form method="GET" action="{{ route('client.blogs.index') }}">
                <select name="category" class="form-control" onchange="this.form.submit()">
                    <option value="">-- Tất cả danh mục --</option>
                    @foreach($blogCategories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>
    </div>

    <div class="row justify-content-center">
        @forelse($blogs as $blog)
            <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                <div class="card h-100 border-0 shadow-sm blog-card">
                    <a href="{{ route('client.blogs.show', $blog->slug) }}">
                        <img src="{{ $blog->thumbnail ?? asset('images/default-thumbnail.jpg') }}" 
                             class="card-img-top blog-card-img" 
                             alt="{{ $blog->title }}">
                    </a>
                    <div class="card-body">
                        {{-- Hiển thị danh mục --}}
                        <p class="text-muted mb-1" style="font-size: 13px;">
                            {{ $blog->category->name ?? 'Chưa phân loại' }}
                        </p>
                        <h5 class="card-title">
                            <a href="{{ route('client.blogs.show', $blog->slug) }}" class="text-dark text-decoration-none">
                                {{ $blog->title }}
                            </a>
                        </h5>
                        <p class="card-text text-muted" style="font-size: 14px;">
                            {{ \Illuminate\Support\Str::limit(strip_tags($blog->content), 100) }}
                        </p>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center text-muted">
                Không có bài viết nào trong danh mục này.
            </div>
        @endforelse
    </div>

    {{-- Phân trang --}}
    <div class="d-flex justify-content-center mt-4">
        {!! $blogs->appends(request()->query())->links('pagination::bootstrap-4') !!}
    </div>
</div>
@endsection
