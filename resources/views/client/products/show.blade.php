@extends('client.layouts.app')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-md-6">
            <img src="{{ asset('storage/' . $product->thumbnail) }}" alt="{{ $product->name }}" class="img-fluid">
        </div>
        <div class="col-md-6">
            <h2>{{ $product->name }}</h2>
            <p class="text-danger fw-bold">${{ $product->sale_price }}</p>
            <p>{{ $product->short_description }}</p>
            <p>{!! $product->description !!}</p>
        </div>
    </div>
</div>
@endsection
