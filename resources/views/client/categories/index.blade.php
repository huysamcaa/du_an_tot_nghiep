 {{-- <ul>
@foreach($categories as $category)
<li>
    <a href="{{ route('client.category.show', $category->slug) }}">{{$category->name}}</a>
    @if($category->children->count())
        <ul>
            @foreach($category->children as $child)
                <li>
                    <a href="{{ route('client.category.show', $category->slug) }}">{{ $child->name }}</a>
                </li>
            @endforeach
        </ul>
    @endif
</li>
@endforeach
</ul> 
 --}}
 @extends('client.layouts.app')

@section('content')
    <div class="container">
        <h2>Sản phẩm thuộc danh mục: {{ $category->name }}</h2>

        <div class="row">
            @forelse ($products as $product)
                <div class="col-md-4">
                    <div class="card mb-3">
                        <img src="{{ $product->thumbnail }}" class="card-img-top" alt="{{ $product->name }}">
                        <div class="card-body">
                            <h5 class="card-title">{{ $product->name }}</h5>
                            <p class="card-text">{{ $product->short_description }}</p>
                            <p class="card-text">Giá: {{ number_format($product->price) }} đ</p>
                        </div>
                    </div>
                </div>
            @empty
                <p>Không có sản phẩm nào trong danh mục này.</p>
            @endforelse
        </div>
    </div>
@endsection

