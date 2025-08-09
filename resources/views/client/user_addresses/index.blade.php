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
                                <span class="badge bg-primary text-white mt-2">Mặc định</span>
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
                        <div class="col-md-6">
                            <input type="text" name="fullname" class="form-control" placeholder="Họ và tên" value="{{ old('fullname') }}" required>
                            @error('fullname')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <input type="tel" name="phone_number" class="form-control" placeholder="Số điện thoại" value="{{ old('phone_number') }}" pattern="^0[0-9]{9}$" required>
                            @error('phone_number')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <select id="province-add" name="province" class="form-select" required>
                                <option value="">Chọn Tỉnh/Thành phố</option>
                                @foreach($vnLocationsData as $province)
                                    <option value="{{ $province['Name'] }}" {{ old('province') == $province['Name'] ? 'selected' : '' }}>
                                        {{ $province['Name'] }}
                                    </option>
                                @endforeach
                            </select>
                            @error('province')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <select id="ward-add" name="ward" class="form-select" required>
                                <option value="">Chọn Phường/Xã</option>
                                @if(old('province') && $selectedProvince = collect($vnLocationsData)->firstWhere('Name', old('province')))
                                    @foreach(collect($selectedProvince['Districts'])->flatMap(fn($d) => $d['Wards']) as $ward)
                                        <option value="{{ $ward['Name'] }}" {{ old('ward') == $ward['Name'] ? 'selected' : '' }}>
                                            {{ $ward['Name'] }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                            @error('ward')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <input type="text" name="address" id="addressInput-add" class="form-control" placeholder="Địa chỉ cụ thể (Số nhà, tên đường...)" value="{{ old('address') }}" required>
                            @error('address')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <div class="form-check">
                                <input type="checkbox" name="id_default" id="id_default" value="1" class="form-check-input" {{ old('id_default') ? 'checked' : '' }}>
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
<div class="modal fade" id="editAddressModal-{{ $address->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('user.addresses.update', $address->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Cập nhật địa chỉ</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body row g-3">
                    <div class="col-md-6">
                        <input type="text" name="fullname" class="form-control" placeholder="Họ và tên" value="{{ old('fullname', $address->fullname) }}" required>
                    </div>
                    <div class="col-md-6">
                        <input type="tel" name="phone_number" class="form-control" placeholder="Số điện thoại" value="{{ old('phone_number', $address->phone_number) }}" required>
                    </div>

                    <div class="col-md-6">
                        <select name="province" class="form-select province-edit" data-id="{{ $address->id }}" required>
                            <option value="">Chọn Tỉnh/Thành phố</option>
                            @foreach($vnLocationsData as $province)
                                <option value="{{ $province['Name'] }}" {{ old('province', $address->province) == $province['Name'] ? 'selected' : '' }}>
                                    {{ $province['Name'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <select name="ward" class="form-select ward-edit" data-id="{{ $address->id }}" required>
                            <option value="">Chọn Phường/Xã</option>
                            @if(old('province', $address->province) && $selectedProvince = collect($vnLocationsData)->firstWhere('Name', old('province', $address->province)))
                                @foreach(collect($selectedProvince['Districts'])->flatMap(fn($d) => $d['Wards']) as $ward)
                                    <option value="{{ $ward['Name'] }}" {{ old('ward', $address->ward) == $ward['Name'] ? 'selected' : '' }}>
                                        {{ $ward['Name'] }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <div class="col-12 mt-3">
                        <label class="form-label">Địa chỉ cụ thể:</label>
                        <input type="text" name="address" class="form-control"
                               placeholder="Số nhà, tên đường..." value="{{ old('address', Str::before($address->address, ', ' . $address->ward . ', ' . $address->province)) }}" required>
                    </div>

                    <div class="col-12">
                        <div class="form-check">
                            <input type="checkbox" name="id_default" id="id_default_{{ $address->id }}" value="1" class="form-check-input"
                                   {{ old('id_default', $address->id_default) ? 'checked' : '' }}>
                            <label class="form-check-label" for="id_default_{{ $address->id }}">Đặt làm mặc định</label>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Huỷ</button>
                    <button type="submit" class="btn btn-danger">Cập nhật</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

{{-- Script để tự động mở modal khi có lỗi (giữ lại) --}}
@if ($errors->any())
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if(session('edit_address_id'))
                let id = {{ session('edit_address_id') }};
                let editEl = document.getElementById('editAddressModal-' + id);
                if (editEl) {
                    let editModal = new bootstrap.Modal(editEl);
                    editModal.show();
                }
            @else
                let addModalEl = document.getElementById('addAddressModal');
                let addModal = new bootstrap.Modal(addModalEl);
                addModal.show();
            @endif
        });
    </script>
@endif

{{-- Script để tự động tải phường/xã khi thay đổi tỉnh/thành phố (sử dụng JS) --}}
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const vnLocationsData = @json($vnLocationsData);

        // Logic cho modal Thêm Địa chỉ
        const provinceSelectAdd = document.getElementById("province-add");
        const wardSelectAdd = document.getElementById("ward-add");

        provinceSelectAdd.addEventListener("change", function () {
            const selectedProvinceName = this.value;
            wardSelectAdd.innerHTML = '<option value="">Chọn Phường/Xã</option>';
            if (selectedProvinceName) {
                const selectedProvince = vnLocationsData.find(p => p.Name === selectedProvinceName);
                if (selectedProvince) {
                    const allWards = selectedProvince.Districts.flatMap(d => d.Wards);
                    allWards.forEach(ward => {
                        wardSelectAdd.add(new Option(ward.Name, ward.Name));
                    });
                }
            }
        });

        // Logic cho các modal Sửa Địa chỉ
        document.querySelectorAll(".province-edit").forEach(provinceSelect => {
            const id = provinceSelect.dataset.id;
            const wardSelect = document.querySelector(`.ward-edit[data-id='${id}']`);

            provinceSelect.addEventListener("change", function () {
                const selectedProvinceName = this.value;
                wardSelect.innerHTML = '<option value="">Chọn Phường/Xã</option>';
                if (selectedProvinceName) {
                    const selectedProvince = vnLocationsData.find(p => p.Name === selectedProvinceName);
                    if (selectedProvince) {
                        const allWards = selectedProvince.Districts.flatMap(d => d.Wards);
                        allWards.forEach(ward => {
                            wardSelect.add(new Option(ward.Name, ward.Name));
                        });
                    }
                }
            });
        });
    });
</script>
@endsection
