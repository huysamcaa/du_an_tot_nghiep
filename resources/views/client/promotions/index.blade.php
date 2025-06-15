@extends('client.layouts.app')

@section('title', 'Khuyến mãi')

@section('content')
<body>
<div class="container">
    <h1 class="text-2xl font-bold mb-6">Chương trình khuyến mãi đang diễn ra</h1>

    @if ($promotions->count())
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($promotions as $promotion)
                <div class="bg-white rounded shadow p-4 border">
                    <h2 class="text-xl font-semibold">
                        <a href="{{ route('client.promotions.show', $promotion->id) }}" class="text-blue-600 hover:underline">
                            {{ $promotion->title }}
                        </a>
                    </h2>

                    <p class="text-sm text-gray-500 mt-1">
                        <strong>Mã khuyến mãi:</strong>
                        <span class="text-indigo-600 font-mono">
                            {{ $promotion->code ?? 'Không có' }}
                        </span>
                    </p>

                    <p class="text-gray-600 mt-2 line-clamp-3">{{ $promotion->description }}</p>
                <p class="mt-2 text-green-600 font-bold"> Giảm: {{ rtrim(rtrim($promotion->discount_percent, '0'), '.') }}%</p>


                    <p class="text-sm text-gray-500">
                        Từ {{ \Carbon\Carbon::parse($promotion->start_date)->format('d/m/Y') }}
                        đến {{ \Carbon\Carbon::parse($promotion->end_date)->format('d/m/Y') }}
                    </p>
                </div>
            @endforeach
        </div>
    @else
        <p>Hiện tại không có khuyến mãi nào.</p>
    @endif
</div>
@endsection
</body>

