@extends('client.layouts.app') 

@section('content')
<div class="container mt-5">
    <h2>Danh mục sản phẩm</h2>
    <ul>
        @foreach ($categories as $category)
            <li>
                {{ $category->name }}
                @if ($category->children->count())
                    <ul>
                        @foreach ($category->children as $child)
                            <li>{{ $child->name }}</li>
                        @endforeach
                    </ul>
                @endif
            </li>
        @endforeach
    </ul>
</div>
@endsection
