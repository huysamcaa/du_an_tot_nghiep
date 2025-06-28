@extends('client.layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Mã Giảm Giá Của Bạn</h2>

    @if($coupons->isEmpty())
        <p>Bạn chưa có mã giảm giá nào.</p>
    @else
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Mã</th>
                    <th>Tiêu đề</th>
                    <th>Giá trị</th>
                    <th>Số lượng</th>
                    <th>Ngày nhận</th>
                </tr>
            </thead>
            <tbody>
                @foreach($coupons as $coupon)
                    <tr>
                        <td>{{ $coupon->code }}</td>
                        <td>{{ $coupon->title }}</td>
                        <td>{{ $coupon->discount_value }} {{ $coupon->discount_type == 'percent' ? '%' : 'VND' }}</td>
                        <td>{{ $coupon->pivot->amount }}</td>
                        <td>{{ $coupon->pivot->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
