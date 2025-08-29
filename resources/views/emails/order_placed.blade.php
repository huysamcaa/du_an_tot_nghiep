<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Xác nhận đơn hàng</title>
</head>
<body>
    <h2>Xin chào {{ $order->fullname }}!</h2>
    <p>Cảm ơn bạn đã đặt hàng tại cửa hàng chúng tôi.</p>

    <p><strong>Mã đơn hàng:</strong> {{ $order->code }}</p>
    <p><strong>Tổng tiền:</strong> {{ number_format($order->total_amount, 0, ',', '.') }}đ</p>
    <p><strong>Địa chỉ giao hàng:</strong> {{ $order->address }}</p>

    <h3>Chi tiết sản phẩm:</h3>
    <ul>
        @foreach($order->items as $item)
            <li>{{ $item->name }} (x{{ $item->quantity }}) - {{ number_format($item->price, 0, ',', '.') }}đ</li>
        @endforeach
    </ul>

    <p>Chúng tôi sẽ giao hàng trong thời gian sớm nhất.</p>

    <p>Trân trọng,<br>Đội ngũ cửa hàng</p>
</body>
</html>
