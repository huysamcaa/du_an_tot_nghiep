@extends('client.layouts.app')
@section('title', 'Địa chỉ của bạn')

@section('content')
<div class="checkoutPage">
    <div class="container">
        <div class="section-header mb-4 d-flex justify-content-between align-items-center">
            <h4 class="section-title fs-4"> Địa chỉ của tôi</h4>
            <a href="#" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#addAddressModal">+ Thêm địa chỉ mới</a>
        </div>

        <div class="row">
            @forelse ($addresses as $address)
            <div class="col-12 mb-4">
                <div class="ulina-address-box p-4 border rounded shadow-sm" style="font-size: 16px; line-height: 1.8;">
                    <div class="d-flex justify-content-between flex-wrap">
                        <div class="mb-2">
                            <strong class="d-block mb-1 fs-5">{{ $address->fullname }}</strong>
                            <div class="text-muted mb-1">Số điện thoại: {{ $address->phone_number }}</div>
                            <div>Địa chỉ:{{ $address->address }}</div>

                            @if ($address->id_default)
                            <span class="badge bg-danger text-white mt-2">Mặc định</span>
                            @endif
                        </div>

                        <div class="text-end">
                            <button type="button"
                                class="btn btn-link text-primary px-0 me-2 fs-6"
                                data-bs-toggle="modal"
                                data-bs-target="#editAddressModal-{{ $address->id }}">
                                Cập nhật
                            </button>

                            @if(! $address->id_default)
                            <!-- Chỉ hiện nút Xoá khi không phải mặc định -->
                            <form action="{{ route('user.addresses.destroy', $address->id) }}"
                                method="POST"
                                class="d-inline-block"
                                onsubmit="return confirm('Bạn có chắc muốn xoá địa chỉ này?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-link text-danger px-0 fs-6">
                                    Xoá
                                </button>
                            </form>
                            @endif

                            @if (! $address->id_default)

                            <br>
                            <!-- Thiết lập mặc định -->
                            <form action="{{ route('user.addresses.set_default', $address->id) }}"
                                method="POST"
                                class="mt-2 d-inline-block">
                                @csrf
                                <button class="btn btn-outline-secondary btn-sm">
                                    Thiết lập mặc định
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <p class="text-muted fs-6">Hiện tại bạn chưa có địa chỉ nào.</p>
            @endforelse
        </div>
    </div>
</div>

<!-- Modal thêm địa chỉ khách hàng -->
<div class="modal fade" id="addAddressModal" tabindex="-1" aria-labelledby="addAddressModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Địa chỉ mới</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
            </div>
            <div class="modal-body">
                {{-- Lỗi chung --}}
                @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                
                @endif

                <form action="{{ route('user.addresses.store') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        {{-- Họ tên --}}
                        <div class="col-md-6">
                            <input type="text"
                                name="fullname"
                                class="form-control"
                                placeholder="Họ và tên"
                                value="{{ old('fullname') }}"
                                required>
                            @error('fullname')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                            
                        </div>

                        {{-- Số điện thoại --}}
                        <div class="col-md-6">
                            <input type="tel"
                                name="phone_number"
                                class="form-control"
                                placeholder="Số điện thoại"
                                value="{{ old('phone_number') }}"
                                pattern="[0-9]{10,11}"
                                required>
                            @error('phone_number')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Khu vực --}}
                        <div class="col-12">
                            <input type="text"
                                name="area"
                                class="form-control"
                                placeholder="Tỉnh/Thành phố, Quận/Huyện, Phường/Xã"
                                value="{{ old('area') }}"
                                required>
                            @error('area')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Địa chỉ cụ thể --}}
                        <div class="col-12">
                            <input type="text"
                                name="address"
                                class="form-control"
                                placeholder="Địa chỉ cụ thể (Số nhà, tên đường...)"
                                value="{{ old('address') }}"
                                required>
                            @error('address')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Đặt mặc định --}}
                        <div class="col-12">
                            <div class="form-check">
                                <input type="checkbox"
                                    name="id_default"
                                    id="id_default"
                                    value="1"
                                    class="form-check-input"
                                    {{ old('id_default') ? 'checked' : '' }}>
                                <label class="form-check-label" for="id_default">Đặt làm địa chỉ mặc định</label>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Huỷ</button>
                        <button type="submit" class="btn btn-danger">Hoàn thành</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Mở lại modal nếu có lỗi --}}
@if ($errors->any())
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let modal = new bootstrap.Modal(document.getElementById('addAddressModal'));
        modal.show();
    });
</script>
@endif



@foreach ($addresses as $address)

<!-- Modal sửa địa chỉ khách hàng -->
<div class="modal fade" id="editAddressModal-{{ $address->id }}" tabindex="-1" aria-labelledby="editAddressModalLabel-{{ $address->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cập nhật địa chỉ</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
            </div>
            <div class="modal-body">
                {{-- Lỗi chung --}}
                @if (session('edit_errors') && session('edit_errors.id') == $address->id)
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach (session('edit_errors')->errors()->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <form action="{{ route('user.addresses.update', $address->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        {{-- Họ tên --}}
                        <div class="col-md-6">
                            <input type="text"
                                name="fullname"
                                class="form-control"
                                placeholder="Họ và tên"
                                value="{{ old('fullname', $address->fullname) }}"
                                required>
                            @error('fullname')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Số điện thoại --}}
                        <div class="col-md-6">
                            <input type="tel"
                                name="phone_number"
                                class="form-control"
                                placeholder="Số điện thoại"
                                value="{{ old('phone_number', $address->phone_number) }}"
                                pattern="[0-9]{10,11}"
                                required>
                            @error('phone_number')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Khu vực --}}
                        <div class="col-12">
                            <input type="text"
                                name="area"
                                class="form-control"
                                placeholder="Tỉnh/Thành phố, Quận/Huyện, Phường/Xã"
                                value="{{ old('area', explode(',', $address->address)[1] ?? '') }}"
                                required>
                            @error('area')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Địa chỉ cụ thể --}}
                        <div class="col-12">
                            <input type="text"
                                name="address"
                                class="form-control"
                                placeholder="Địa chỉ cụ thể (Số nhà, tên đường...)"
                                value="{{ old('address', explode(',', $address->address)[0] ?? '') }}"
                                required>
                            @error('address')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Đặt mặc định --}}
                        <div class="col-12">
                            <div class="form-check">
                                <input type="checkbox"
                                    name="id_default"
                                    id="id_default_{{ $address->id }}"
                                    value="1"
                                    class="form-check-input"
                                    {{ old('id_default', $address->id_default) ? 'checked' : '' }}
                                    {{ $address->id_default ? 'disabled' : '' }}>
                                <label class="form-check-label" for="id_default_{{ $address->id }}">
                                    Đặt làm địa chỉ mặc định
                                    @if ($address->id_default)
                                    <span class="text-muted small">(Đây là địa chỉ mặc định hiện tại)</span>
                                    @endif
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Huỷ</button>
                        <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endforeach




@endsection
