@extends('client.layouts.app')

@section('title', $promotion->title)

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white p-6 rounded shadow border">

        <h1 class="text-3xl font-bold mb-4">{{ $promotion->title }}</h1>


        <div class="mb-4">
            <label class="block text-sm text-gray-500 mb-1">Mã khuyến mãi:</label>
            <div class="bg-gray-100 text-indigo-700 font-mono px-3 py-2 rounded border border-indigo-300 text-base inline-block">
                {{ $promotion->code ?? 'Không có mã' }}
            </div>
        </div>

        @if($promotion->description)
            <p class="text-gray-700 mb-4">{{ $promotion->description }}</p>
        @endif

        <p class="text-green-600 font-semibold mb-2">Ưu đãi: {{ rtrim(rtrim($promotion->discount_percent, '0'), '.') }}%</p>

        <p class="text-gray-500 text-sm">
            Thời gian áp dụng:
            {{ \Carbon\Carbon::parse($promotion->start_date)->format('d/m/Y') }}
            – {{ \Carbon\Carbon::parse($promotion->end_date)->format('d/m/Y') }}
        </p>


        <a href="{{ route('client.promotions.index') }}" class="inline-block mt-6 text-blue-500 hover:underline">← Quay lại danh sách khuyến mãi</a>
    </div>
</div>
@endsection
