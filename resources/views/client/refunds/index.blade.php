@extends('client.layouts.app')

@section('title', 'Danh sách yêu cầu hoàn tiền')

@section('content')
<section class="pageBannerSection">
  <div class="container">
    <div class="row">
      <div class="col-lg-12">
        <div class="pageBannerContent text-center">
          <h2>Danh sách hoàn tiền</h2>
          <div class="pageBannerPath">
            <a href="{{ route('client.home') }}">Trang chủ</a>&nbsp;&nbsp;>&nbsp;&nbsp;<span>Hoàn tiền</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="cartPageSection woocommerce py-5">
  <div class="container">
    @if(session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
      <div class="alert alert-danger">
        <ul class="mb-0">
          @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    @if($refunds->count())
    <div class="table-responsive">
      <table class="shop_table cart_table">
        <thead>
          <tr>
            <th>Đơn hàng</th>
            <th>Tổng tiền</th>
            <th>Trạng thái</th>
            <th>Ngày tạo</th>
            <th>Hành động</th>
          </tr>
        </thead>
        <tbody>
          @foreach($refunds as $refund)
          <tr>
            <td>
              <a href="{{ route('client.orders.show', $refund->order->code) }}">
                #{{ $refund->order->code }}
              </a>
            </td>
            <td>
              <strong>{{ number_format($refund->total_amount) }}₫</strong>
            </td>
            <td>
              @switch($refund->status)
                @case('pending') <span class="badge bg-warning">Chờ xử lý</span> @break
                @case('receiving') <span class="badge bg-info">Đang tiếp nhận</span> @break
                @case('completed') <span class="badge bg-success">Hoàn thành</span> @break
                @case('rejected') <span class="badge bg-danger">Đã từ chối</span> @break
                @case('failed') <span class="badge bg-dark">Thất bại</span> @break
                @case('cancel') <span class="badge bg-secondary">Đã hủy</span> @break
                @default <span class="badge bg-light text-dark">{{ $refund->status }}</span>
              @endswitch
            </td>
            <td>{{ $refund->created_at->format('d/m/Y H:i') }}</td>
            <td style="display: flex; gap: 8px; align-items: center;">
              <a href="{{ route('refunds.show', $refund->id) }}" class="ulinaBTN small me-1">
                <span>Xem</span>
              </a>

              @if($refund->status === 'pending')
              <form action="{{ route('refunds.cancel', $refund->id) }}"
                    method="POST"
                    style="display:inline-block"
                    onsubmit="return confirm('Bạn có chắc muốn hủy yêu cầu này?');">
                @csrf
                <button type="submit" class="ulinaBTN ulinaBTN--outline small">
                  <span>Hủy</span>
                </button>
              </form>
              @endif
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    <div class="mt-4">
      {{ $refunds->links('pagination::bootstrap-4') }}
    </div>

    @else
      <p class="text-center">Chưa có yêu cầu hoàn tiền nào.</p>
    @endif
  </div>
</section>
@endsection
