@extends('client.layouts.app')

@section('content')
<style>
.ulinaTable table th, .ulinaTable table td {
    text-align: center;
    vertical-align: middle;
}
.ulinaTable .table {
    background-color: #fff;
    border-radius: 6px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}
.sectionGap {
    padding: 100px 0;
}
</style>

<section class="sectionGap">
    <div class="container">
        <div class="ulinaTitle text-center mb-5">
            <h2 class="h2Title">Mã Giảm Giá Của Bạn</h2>
            <p class="secDesc">Tất cả mã giảm giá bạn đang sở hữu</p>
        </div>

        @if($coupons->isEmpty())
            <p class="text-center">Bạn chưa có mã giảm giá nào.</p>
        @else
            <div class="ulinaTable table-responsive">
                <table class="table table-bordered">
                    <thead class="thead-dark">
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
            </div>
        @endif
    </div>
</section>
@endsection
