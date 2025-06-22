 <ul>
    
@foreach($categories as $category)
<li>
    <a href="#">{{$category->name}}</a>
    @if($category->children->count())
        <ul>
            @foreach($category->children as $child)
                <li>
                    <a href="{{ $child->name }}">{{ $child->name }}</a>
                </li>
            @endforeach
        </ul>
    @endif
</li>
@endforeach
</ul> 



