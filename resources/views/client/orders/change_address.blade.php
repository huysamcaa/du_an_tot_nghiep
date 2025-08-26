@extends('client.layouts.app')
@section('title', 'Cập nhật địa chỉ đơn hàng')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Cập nhật địa chỉ đơn hàng #{{ $order->code }}</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('client.orders.change-address', $order->id) }}">
                        @csrf
                        <div class="mb-3">
                            <label for="fullname" class="form-label">Họ và tên</label>
                            <input type="text" name="fullname" id="fullname" class="form-control" value="{{ old('fullname', $order->fullname) }}" required>
                            @error('fullname') <div class="text-danger small">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label for="phone_number" class="form-label">Số điện thoại</label>
                            <input type="text" name="phone_number" id="phone_number" class="form-control" value="{{ old('phone_number', $order->phone_number) }}" required>
                            @error('phone_number') <div class="text-danger small">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $order->email) }}">
                            @error('email') <div class="text-danger small">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label for="address" class="form-label">Địa chỉ nhận hàng</label>
                            <input type="text" name="address" id="address" class="form-control" value="{{ old('address', $order->address) }}" required>
                            @error('address') <div class="text-danger small">{{ $message }}</div> @enderror
                        </div>

                        <div class="d-flex justify-content-end">
                            <a href="{{ url()->previous() }}" class="btn btn-secondary me-2">Quay lại</a>
                            <button type="submit" class="btn btn-success">Cập nhật</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection