 <ul>
@foreach($categories as $category)
<li>
    <a href="{{ route('category.products', $category->id) }}">{{$category->name}}</a>
    @if($category->children->count())
        <ul>
            @foreach($category->children as $child)
                <li>
                    <a href="{{ route('category.products', $child->id) }}">{{ $child->name }}</a>
                </li>
            @endforeach
        </ul>
    @endif
</li>
@endforeach
</ul> 
