@extends('client.layouts.app')
@section('title','Xác nhận hoàn đơn #'.$order->code)

@section('content')
<section class="pageBannerSection">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="pageBannerContent text-center">
                    <h2>Xác nhận hoàn đơn</h2>
                    <div class="pageBannerPath">
                        <a href="{{ route('client.home') }}">Trang chủ</a>&nbsp;&nbsp;>&nbsp;&nbsp;<span>Hoàn đơn #{{ $order->code }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="checkoutPage py-5">
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

        <div class="row">
            {{-- Cột trái: danh sách sản phẩm lỗi --}}
            <div class="col-lg-6">
                <div class="orderReviewWrap mb-4">
                    <h3>Sản phẩm lỗi đã chọn</h3>
                    <table class="shop_table cart_table">
                        <thead>
                            <tr>
                                <th>Sản phẩm</th>
                                <th>Phân loại</th>
                                <th>Giá</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $totalRefund = 0; @endphp
                            @foreach($selectedItems as $item)
                            @php $totalRefund += $item->price; @endphp
                            <tr>
                                <td class="d-flex align-items-center">
                                    <img src="{{ asset('storage/' . $item->product->thumbnail) }}"
                                        alt="{{ $item->product->name }}"
                                        style="width:50px; height:50px; object-fit:cover; border-radius:6px; margin-right:10px;">
                                    <div>
                                        <strong>{{ $item->product->name }}</strong><br>
                                        @if($item->variant)
                                        <small class="text-muted">{{ $item->variant->name }}</small>
                                        @endif
                                    </div>
                                </td>
                                <td>{{ $item->variant->name ?? '-' }}</td>
                                <td>
                                    <div class="pi01Price">
                                        <ins>{{ number_format($item->price) }}đ</ins>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="2" class="text-end">Tổng hoàn tiền:</th>
                                <th>
                                    <div class="pi01Price">
                                        <ins>{{ number_format($totalRefund) }}đ</ins>
                                    </div>
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            {{-- Cột phải: form hoàn tiền --}}
            <div class="col-lg-6">
                <div class="checkoutForm">
                    <h3>Thông tin hoàn tiền</h3>

                    <form method="POST" action="{{ route('refunds.store') }}" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="order_id" value="{{ $order->id }}">
                        @foreach ($selectedItems as $index => $item)
                        <input type="hidden" name="item_ids[{{ $index }}]" value="{{ $item->id }}">
                        @endforeach

                        <div class="row">
                            <div class="col-lg-12 mb-3">
                                <label for="reason" class="form-label">Lý do hoàn tiền</label>
                                <textarea name="reason" id="reason"
                                    class="form-control @error('reason') is-invalid @enderror"
                                    rows="4" placeholder="Nhập lý do hoàn tiền">{{ old('reason') }}</textarea>
                                @error('reason')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="bank_account" class="form-label">Số tài khoản</label>
                                <input type="text" name="bank_account" id="bank_account"
                                    class="form-control @error('bank_account') is-invalid @enderror"
                                    value="{{ old('bank_account') }}" placeholder="Nhập số tài khoản">
                                @error('bank_account')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="user_bank_name" class="form-label">Chủ tài khoản</label>
                                <input type="text" name="user_bank_name" id="user_bank_name"
                                    class="form-control @error('user_bank_name') is-invalid @enderror"
                                    value="{{ old('user_bank_name') }}" placeholder="Nhập tên chủ tài khoản">
                                @error('user_bank_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="bank_name" class="form-label">Ngân hàng</label>
                                <input type="text" name="bank_name" id="bank_name"
                                    class="form-control @error('bank_name') is-invalid @enderror"
                                    value="{{ old('bank_name') }}" placeholder="Nhập ngân hàng">
                                @error('bank_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="phone_number" class="form-label">Số điện thoại</label>
                                <input type="text" name="phone_number" id="phone_number"
                                    class="form-control @error('phone_number') is-invalid @enderror"
                                    value="{{ old('phone_number') }}" placeholder="Nhập số điện thoại">
                                @error('phone_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-lg-12 mb-3">
                                <label for="reason_image" class="form-label">Ảnh/Video sản phẩm lỗi</label>
                                <input type="file" name="reason_image" id="reason_image"
                                    class="form-control @error('reason_image') is-invalid @enderror"
                                    accept="image/*,video/*">
                                @error('reason_image')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-lg-12 text-end">
                                <button type="submit" class="placeOrderBTN ulinaBTN">
                                    <span>Gửi yêu cầu hoàn tiền</span>
                                </button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</section>
@endsection
