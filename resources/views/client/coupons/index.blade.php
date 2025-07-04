@extends('client.layouts.app')

@section('content')
<section class="pageBannerSection">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="pageBannerContent text-center">
                            <h2>Mã giảm giá</h2>
                            <div class="pageBannerPath">
                                <a href="{{ route('client.home') }}">Trang chủ</a>&nbsp;&nbsp;&gt;&nbsp;&nbsp;<span>Mã giảm giá</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
</section>
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
