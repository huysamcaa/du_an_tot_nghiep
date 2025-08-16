@extends('client.layouts.app')

@section('content')
@php use Illuminate\Support\Str; @endphp

{{-- CSS để căn giữa ảnh trong nội dung bài viết --}}
<style>
    .blog-content img {
        display: block;
        margin-left: auto;
        margin-right: auto;
        max-width: 100%;
        height: auto;
    }
</style>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">

            {{-- Tiêu đề bài viết theo style Ulina --}}
            <div class="section-header mb-5 text-center">
                <h2 class="section-title">{{ $blog->title }}</h2>
            </div>

            {{-- Ngày tạo --}}
            <p class="text-muted">
                <i class="fa fa-calendar"></i>
                {{ $blog->created_at->format('d/m/Y H:i') }}
            </p>

            {{-- Ảnh đại diện bài viết --}}
            @if($blog->image)
                <img src="{{ asset('storage/' . $blog->image) }}"
                     class="img-fluid mb-4 rounded shadow-sm mx-auto d-block"
                     style="max-width: 800px;"
                     alt="{{ $blog->title }}">
            @endif

            {{-- Nội dung bài viết --}}
            <div class="blog-content" style="line-height: 1.8; color: #444; font-size: 16px; overflow-x: auto;">
                {!! $blog->content !!}
            </div>
        </div>
    </div>

    {{-- BÀI VIẾT NỔI BẬT --}}
    <div class="section-header text-center mt-5 mb-4">
        <h3 class="section-title">Bài viết nổi bật</h3>
    </div>
    <div class="row">
        @foreach($featuredBlogs as $item)
            <div class="col-md-4">
                <div class="card mb-4 shadow-sm h-100">
                    @php
                        preg_match('/<img.+src=[\'"](?P<src>.+?)[\'"].*>/i', $item->content, $imageMatch);
                        $firstImage = $imageMatch['src'] ?? asset('images/default-thumbnail.jpg');
                    @endphp
                    <img src="{{ $firstImage }}"
                         class="card-img-top blog-card-img mx-auto d-block"
                         style="max-height: 200px; object-fit: cover;"
                         alt="{{ $item->title }}">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">{{ Str::limit($item->title, 50) }}</h5>
                        <p class="text-muted mb-2">
                            <i class="fa fa-calendar"></i>
                            {{ $item->created_at->format('d/m/Y') }}
                        </p>
                        <a href="{{ route('client.blogs.show', $item->slug) }}" class="btn btn-sm btn-outline-primary mt-auto">Xem chi tiết</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
<style>
        button,
.btn {
    background-color: #96b6b8;
    color: white;
    border: none;
    border-radius: 50px;
    padding: 6px 18px; 
    font-weight: bold;
    font-size: 14px; 
    text-transform: uppercase;
    min-width: 110px; 
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px; 
    transition: all 0.3s ease;
}

button:hover,
.btn:hover {
    background-color: #82a1a3;
    color: white;
    transform: translateY(-1px);
}

.btn-outline-primary,
.btn-outline-danger,
.btn-outline-secondary {
    background-color: #96b6b8;
    color: white;
    border: none;
}

.btn-outline-primary:hover,
.btn-outline-danger:hover,
.btn-outline-secondary:hover {
    background-color: #82a1a3;
    color: white;
}

    .text-end {
    text-align: center !important; 
}

.text-end form,
.text-end button {
    display: inline-block;
}

.text-end .btn {
    margin: 4px; 
}
</style>
@endsection
