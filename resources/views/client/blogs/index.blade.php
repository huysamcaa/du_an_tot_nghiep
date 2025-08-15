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
</style>
<div class="container pt-5 mt-5" style="min-height: 80vh;">

   

    <div class="row justify-content-center">
        @foreach($blogs as $blog)
        <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
            <div class="card h-100 border-0 shadow-sm blog-card">
                <a href="{{ route('client.blogs.show', $blog->slug) }}">
                    <img src="{{ $blog->thumbnail ?? asset('images/default-thumbnail.jpg') }}" 
     class="card-img-top blog-card-img" 
     alt="{{ $blog->title }}">

                </a>
                <div class="card-body">
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
        @endforeach
    </div>
</div>
@endsection
