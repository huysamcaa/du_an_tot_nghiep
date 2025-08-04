@extends('client.layouts.app')

@section('title', 'Chi tiết hoàn đơn #' . $refund->order->code)

@section('content')
<section class="pageBannerSection">
  <div class="container">
    <div class="row">
      <div class="col-lg-12">
        <div class="pageBannerContent text-center">
          <h2>Chi tiết hoàn đơn</h2>
          <div class="pageBannerPath">
            <a href="{{ route('client.home') }}">Trang chủ</a> &nbsp;&gt;&nbsp;
            <span>Hoàn đơn #{{ $refund->order->code }}</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="py-5">
  <div class="container">

    {{-- 2 cột: Thông tin đơn hoàn + Tài khoản --}}
    <div class="row mb-5 g-4">
      {{-- Thông tin hoàn đơn --}}
      <div class="col-md-6">
        <div class="p-4 border rounded bg-white shadow-sm h-100">
          <h5 class="mb-3"><i class="fas fa-file-invoice me-2 text-primary"></i>Thông tin hoàn đơn</h5>
          <ul class="list-group list-group-flush">
            <li class="list-group-item"><strong>Mã đơn hàng:</strong> #{{ $refund->order->code }}</li>
            <li class="list-group-item"><strong>Tổng tiền hoàn:</strong> {{ number_format($refund->total_amount) }}₫</li>
            <li class="list-group-item">
              <strong>Trạng thái:</strong>
              @switch($refund->status)
                @case('pending') <span class="badge bg-warning">Chờ xử lý</span> @break
                @case('receiving') <span class="badge bg-info">Đang tiếp nhận</span> @break
                @case('completed') <span class="badge bg-success">Hoàn thành</span> @break
                @case('rejected') <span class="badge bg-danger">Đã từ chối</span> @break
                @case('failed') <span class="badge bg-dark">Thất bại</span> @break
                @case('cancel') <span class="badge bg-secondary">Đã hủy</span> @break
                @default <span class="badge bg-light text-dark">{{ $refund->status }}</span>
              @endswitch
            </li>
            <li class="list-group-item"><strong>Lý do hoàn:</strong> {{ $refund->reason }}</li>
            @if($refund->reason_image)
              <li class="list-group-item">
                <strong>Hình ảnh/Video minh chứng:</strong><br>
                @if(Str::endsWith($refund->reason_image, ['.mp4', '.webm']))
                  <video controls style="max-width:100%; height:auto;" class="mt-2 rounded">
                    <source src="{{ asset('storage/' . $refund->reason_image) }}">
                  </video>
                @else
                  <img src="{{ asset('storage/' . $refund->reason_image) }}" class="img-fluid mt-2 rounded" style="max-width:300px;">
                @endif
              </li>
            @endif
          </ul>
        </div>
      </div>

      {{-- Thông tin tài khoản --}}
      <div class="col-md-6">
        <div class="p-4 border rounded bg-white shadow-sm h-100">
          <h5 class="mb-3"><i class="fas fa-university me-2 text-success"></i>Thông tin tài khoản nhận tiền</h5>
          <ul class="list-group list-group-flush">
            <li class="list-group-item"><strong>Ngân hàng:</strong> {{ $refund->bank_name }}</li>
            <li class="list-group-item"><strong>Số tài khoản:</strong> {{ $refund->bank_account }}</li>
            <li class="list-group-item"><strong>Chủ tài khoản:</strong> {{ $refund->user_bank_name }}</li>
            <li class="list-group-item"><strong>SĐT liên hệ:</strong> {{ $refund->phone_number }}</li>
          </ul>
        </div>
      </div>
    </div>

    {{-- Danh sách sản phẩm hoàn --}}
    <div class="p-4 border rounded bg-white shadow-sm">
      <h5 class="mb-4"><i class="fas fa-box-open me-2 text-danger"></i>Sản phẩm hoàn</h5>
      <div class="table-responsive">
        <table class="table table-bordered align-middle text-center">
          <thead class="table-light">
            <tr>
              <th class="text-start">Sản phẩm</th>
              <th>Phân loại</th>
              <th>Số lượng</th>
              <th>Giá</th>
            </tr>
          </thead>
          <tbody>
            @foreach($refund->items as $item)
              <tr>
                <td class="text-start d-flex align-items-center">
                  <img src="{{ asset('storage/' . ($item->product->thumbnail ?? 'default.jpg')) }}"
                       alt="{{ $item->product->name ?? 'Sản phẩm' }}"
                       class="me-3 rounded"
                       style="width:50px; height:50px; object-fit:cover;">
                  <div>{{ $item->name }}</div>
                </td>
                <td>
                  @if($item->variant && $item->variant->attributeValues->count())
                    {{ $item->variant->attributeValues->pluck('value')->implode(' - ') }}
                  @else
                    <span class="text-muted">-</span>
                  @endif
                </td>
                <td>{{ $item->quantity }}</td>
                <td>{{ number_format($item->price) }}₫</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>

    <div class="mt-4 text-end">
      <a href="{{ route('refunds.index') }}" class="ulinaBTN">
        <span>← Quay lại danh sách hoàn đơn</span>
      </a>
    </div>

  </div>
</section>
@endsection
