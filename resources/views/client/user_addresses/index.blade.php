
@extends('client.layouts.app')
@section('title', 'Địa chỉ của bạn')

@section('content')
<section class="pageBannerSection">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="pageBannerContent text-center">
                    <h2>Địa chỉ của tôi</h2>
                    <div class="pageBannerPath">
                        <a href="{{ route('client.home') }}">Trang chủ</a>&nbsp;&nbsp;>&nbsp;&nbsp;<span>Địa chỉ của tôi</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<br>

<div class="container">
    <div class="section-header mb-4 d-flex justify-content-between align-items-center">
        <h4 class="section-title fs-4">Địa chỉ của tôi</h4>
        <a href="#" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#addAddressModal">+ Thêm địa chỉ mới</a>
    </div>

    <div class="row">
        @forelse ($addresses as $address)
            <div class="col-12 mb-4" id="address-{{ $address->id }}">
                <div class="ulina-address-box p-4 border rounded shadow-sm" style="font-size:16px; line-height:1.8;">
                    <div class="d-flex justify-content-between flex-wrap">
                        <div class="mb-2">
                            <strong class="d-block mb-1 fs-5">{{ $address->fullname }}</strong>
                            <div class="text-muted mb-1">Số điện thoại: {{ $address->phone_number }}</div>
                            <div>Địa chỉ: {{ $address->address }}</div>
                            @if($address->id_default)
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
                                <form action="{{ route('user.addresses.destroy', $address->id) }}"
                                      method="POST"
                                      class="d-inline-block"
                                      onsubmit="return confirm('Bạn có chắc muốn xoá địa chỉ này?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-link text-danger px-0 fs-6">Xoá</button>
                                </form>
                                <br>
                                <form action="{{ route('user.addresses.set_default', $address->id) }}"
                                      method="POST"
                                      class="mt-2 d-inline-block">
                                    @csrf
                                    <button class="btn btn-outline-secondary btn-sm">Thiết lập mặc định</button>
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

{{-- Modal Thêm Địa chỉ --}}
<div class="modal fade" id="addAddressModal" tabindex="-1" aria-labelledby="addAddressModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Địa chỉ mới</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">

                <form id="addAddressForm" action="{{ route('user.addresses.store') }}" method="POST" novalidate>
                    @csrf
                    <div class="row g-3">
                        {{-- Họ tên --}}
                        <div class="col-md-6">
                            <input type="text"
                                   name="fullname"
                                   class="form-control"
                                   placeholder="Họ và tên"
                                   value="{{ session('edit_address_id') ? '' : old('fullname') }}"
                                   required
                                   oninvalid="this.setCustomValidity('Vui lòng nhập họ tên')"
                                   oninput="this.setCustomValidity('')">
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
                                   value="{{ session('edit_address_id') ? '' : old('phone_number') }}"
                                   pattern="^(09|03)[0-9]{8}$"
                                   required
                                   oninvalid="this.setCustomValidity('Vui lòng nhập số điện thoại bắt đầu bằng 09 hoặc 03 và gồm 10 chữ số')"
                                   oninput="this.setCustomValidity('')">
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
                                   value="{{ session('edit_address_id') ? '' : old('area') }}"
                                   required
                                   oninvalid="this.setCustomValidity('Vui lòng nhập khu vực')"
                                   oninput="this.setCustomValidity('')">
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
                                   value="{{ session('edit_address_id') ? '' : old('address') }}"
                                   required
                                   oninvalid="this.setCustomValidity('Vui lòng nhập địa chỉ cụ thể')"
                                   oninput="this.setCustomValidity('')">
                            @error('address')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        {{-- Mặc định --}}
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

{{-- Modal Sửa Địa chỉ --}}
@foreach ($addresses as $address)
    <div class="modal fade" id="editAddressModal-{{ $address->id }}" tabindex="-1" aria-labelledby="editAddressModalLabel-{{ $address->id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Cập nhật địa chỉ</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="editAddressForm-{{ $address->id }}"
                          action="{{ route('user.addresses.update', $address->id) }}"
                          method="POST"
                          novalidate>
                        @csrf
                        @method('PUT')
                        <div class="row g-3">
                            {{-- Họ tên --}}
                            <div class="col-md-6">
                                <input type="text"
                                       name="fullname"
                                       class="form-control"
                                       placeholder="Họ và tên"
                                       value="{{ session('edit_address_id') == $address->id ? old('fullname', $address->fullname) : $address->fullname }}"
                                       required
                                       oninvalid="this.setCustomValidity('Vui lòng nhập họ tên')"
                                       oninput="this.setCustomValidity('')">
                                @error('fullname', 'edit')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            {{-- Số điện thoại --}}
                            <div class="col-md-6">
                                <input type="tel"
                                       name="phone_number"
                                       class="form-control"
                                       placeholder="Số điện thoại"
                                       value="{{ session('edit_address_id') == $address->id ? old('phone_number', $address->phone_number) : $address->phone_number }}"
                                       pattern="^(09|03)[0-9]{8}$"
                                       required
                                       oninvalid="this.setCustomValidity('Vui lòng nhập số điện thoại bắt đầu bằng 09 hoặc 03 và gồm 10 chữ số')"
                                       oninput="this.setCustomValidity('')">
                                @error('phone_number', 'edit')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            {{-- Khu vực --}}
                            <div class="col-12">
                                <input type="text"
                                       name="area"
                                       class="form-control"
                                       placeholder="Tỉnh/Thành phố, Quận/Huyện, Phường/Xã"
                                       value="{{ session('edit_address_id') == $address->id ? old('area', trim(explode(',', $address->address)[1] ?? '')) : trim(explode(',', $address->address)[1] ?? '') }}"
                                       required
                                       oninvalid="this.setCustomValidity('Vui lòng nhập khu vực')"
                                       oninput="this.setCustomValidity('')">
                                @error('area', 'edit')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            {{-- Địa chỉ cụ thể --}}
                            <div class="col-12">
                                <input type="text"
                                       name="address"
                                       class="form-control"
                                       placeholder="Địa chỉ cụ thể (Số nhà, tên đường...)"
                                       value="{{ session('edit_address_id') == $address->id ? old('address', trim(explode(',', $address->address)[0] ?? '')) : trim(explode(',', $address->address)[0] ?? '') }}"
                                       required
                                       oninvalid="this.setCustomValidity('Vui lòng nhập địa chỉ cụ thể')"
                                       oninput="this.setCustomValidity('')">
                                @error('address', 'edit')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            {{-- Mặc định --}}
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

{{-- Tự động mở modal khi có lỗi --}}
@if ($errors->any() && ! session('edit_address_id'))
    <script>
    document.addEventListener('DOMContentLoaded', function() {
      let addModalEl = document.getElementById('addAddressModal');
      let addModal   = new bootstrap.Modal(addModalEl);
      addModal.show();
    });
    </script>
@endif

@if (session('edit_address_id'))
    <script>
    document.addEventListener('DOMContentLoaded', function() {
      let id      = {{ session('edit_address_id') }};
      let editEl  = document.getElementById('editAddressModal-' + id);
      if (editEl) {
        let editModal = new bootstrap.Modal(editEl);
        editModal.show();
      }
    });
    </script>
@endif
@endsection
