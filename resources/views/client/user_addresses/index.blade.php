
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
                        {{-- Họ tên --}}
                        <div class="col-md-6">
                            <input type="text" name="fullname" class="form-control" placeholder="Họ và tên"
                                value="{{ session('edit_address_id') ? '' : old('fullname') }}" required
                                oninvalid="this.setCustomValidity('Vui lòng nhập họ tên')"
                                oninput="this.setCustomValidity('')">
                            @error('fullname')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                            
                        </div>

                        {{-- Số điện thoại --}}
                        <div class="col-md-6">
                            <input type="tel" name="phone_number" class="form-control" placeholder="Số điện thoại"
                                value="{{ session('edit_address_id') ? '' : old('phone_number') }}"
                                pattern="^(09|03)[0-9]{8}$" required
                                oninvalid="this.setCustomValidity('Vui lòng nhập số điện thoại hợp lệ')"
                                oninput="this.setCustomValidity('')">
                            @error('phone_number')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Khu vực: Tỉnh/TP, Quận/Huyện, Phường/Xã --}}
                        <div class="col-md-4">
                            <select id="province" class="form-select" required>
                                <option value="">Chọn Tỉnh/Thành phố</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <select id="district" class="form-select" required>
                                <option value="">Chọn Quận/Huyện</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <select id="ward" class="form-select" required>
                                <option value="">Chọn Phường/Xã</option>
                            </select>
                        </div>

                        {{-- Hidden để gửi area --}}
                        <input type="hidden" name="area" id="areaInput" value="{{ old('area') }}">
                        @error('area')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror

                        {{-- Địa chỉ cụ thể --}}
                        <div class="col-12">
                            <input type="text" name="address" id="addressInput" class="form-control"
                                placeholder="Địa chỉ cụ thể (Số nhà, tên đường...)"
                                value="{{ session('edit_address_id') ? '' : old('address') }}" required disabled
                                oninvalid="this.setCustomValidity('Vui lòng nhập địa chỉ cụ thể')"
                                oninput="this.setCustomValidity('')">
                            @error('address')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Mặc định --}}
                        <div class="col-12">
                            <div class="form-check">
                                <input type="checkbox" name="id_default" id="id_default" value="1"
                                    class="form-check-input" {{ old('id_default') ? 'checked' : '' }}>
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

{{-- Gắn Script xử lý địa chỉ --}}
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const provinceSelect = document.getElementById("province");
        const districtSelect = document.getElementById("district");
        const wardSelect = document.getElementById("ward");
        const areaInput = document.getElementById("areaInput");
        const addressInput = document.getElementById("addressInput");

        fetch("{{ asset('assets/Client/js/vn-location.json') }}")
            .then(res => res.json())
            .then(data => {
                data.forEach(province => {
                    const option = new Option(province.Name, province.Name);
                    option.dataset.code = province.Code;
                    provinceSelect.add(option);
                });

                provinceSelect.addEventListener("change", function () {
                    districtSelect.length = 1;
                    wardSelect.length = 1;
                    const selected = data.find(p => p.Name === this.value);
                    if (!selected) return;

                    selected.Districts.forEach(district => {
                        const option = new Option(district.Name, district.Name);
                        option.dataset.code = district.Code;
                        districtSelect.add(option);
                    });
                    updateAreaInput();
                });

                districtSelect.addEventListener("change", function () {
                    wardSelect.length = 1;
                    const province = data.find(p => p.Name === provinceSelect.value);
                    if (!province) return;

                    const district = province.Districts.find(d => d.Name === this.value);
                    if (!district) return;

                    district.Wards.forEach(ward => {
                        wardSelect.add(new Option(ward.Name, ward.Name));
                    });
                    updateAreaInput();
                });

                wardSelect.addEventListener("change", updateAreaInput);

                function updateAreaInput() {
                    const p = provinceSelect.value;
                    const d = districtSelect.value;
                    const w = wardSelect.value;
                    if (p && d && w) {
                        areaInput.value = `${p}, ${d}, ${w}`;
                        addressInput.disabled = false;
                    } else {
                        areaInput.value = "";
                        addressInput.disabled = true;
                    }
                }
            })
            .catch(err => {
                console.error("Lỗi khi tải địa chỉ VN:", err);
            });
    });
</script>

{{-- Modal Sửa Địa chỉ --}}
@foreach ($addresses as $address)
<div class="modal fade" id="editAddressModal-{{ $address->id }}" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <form action="{{ route('user.addresses.update', $address->id) }}" method="POST">
        @csrf
        @method('PUT')
        <input type="hidden" name="area" class="area-input" value="{{ $address->area }}">
        <div class="modal-header">
          <h5 class="modal-title">Cập nhật địa chỉ</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body row g-3">
          {{-- Họ tên & SĐT --}}
          <div class="col-md-6">
            <input type="text" name="fullname" class="form-control" placeholder="Họ và tên" value="{{ $address->fullname }}" required>
          </div>
          <div class="col-md-6">
            <input type="tel" name="phone_number" class="form-control" placeholder="Số điện thoại" value="{{ $address->phone_number }}" required>
          </div>

          {{-- Khu vực đang lưu --}}
<div class="col-12">
  <label class="form-label">Khu vực hiện tại:</label>
  <div class="bg-light p-2 border rounded current-area" style="cursor: pointer;" data-id="{{ $address->id }}">
    {{ $address->province }}, {{ $address->district }}, {{ $address->ward }} <i class="ms-2 bi bi-pencil-square"></i>
  </div>
</div>


          {{-- Tỉnh/Huyện/Xã ẩn ban đầu --}}
          <div class="col-12 mt-2 location-group d-none" data-id="{{ $address->id }}">
            <div class="row g-2">
              <div class="col-md-4">
                <select name="province" class="form-select province" data-id="{{ $address->id }}">
                  <option value="">Chọn Tỉnh/Thành phố</option>
                </select>
              </div>
              <div class="col-md-4">
                <select name="district" class="form-select district" data-id="{{ $address->id }}">
                  <option value="">Chọn Quận/Huyện</option>
                </select>
              </div>
              <div class="col-md-4">
                <select name="ward" class="form-select ward" data-id="{{ $address->id }}">
                  <option value="">Chọn Phường/Xã</option>
                </select>
              </div>
            </div>
          </div>

          {{-- Địa chỉ cụ thể --}}
          <div class="col-12 mt-3">
            <label class="form-label">Địa chỉ cụ thể:</label>
            <input type="text" name="address" class="form-control"
              placeholder="Số nhà, tên đường..." value="{{ Str::before($address->address, ', ' . $address->area) }}" required>
          </div>

          {{-- Mặc định --}}
          <div class="col-12">
            <div class="form-check">
              <input type="checkbox" name="id_default" id="id_default_{{ $address->id }}" value="1" class="form-check-input"
                {{ $address->id_default ? 'checked' : '' }}>
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


<script>
document.addEventListener("DOMContentLoaded", function () {
  fetch("{{ asset('assets/Client/js/vn-location.json') }}")
    .then(res => res.json())
    .then(data => {
      document.querySelectorAll(".modal[id^='editAddressModal-']").forEach(modal => {
        const id = modal.id.replace("editAddressModal-", "");
        const provinceSelect = modal.querySelector(`.province[data-id='${id}']`);
        const districtSelect = modal.querySelector(`.district[data-id='${id}']`);
        const wardSelect = modal.querySelector(`.ward[data-id='${id}']`);
        const areaInput = modal.querySelector(".area-input");
        const locationGroup = modal.querySelector(`.location-group[data-id='${id}']`);
        const currentArea = modal.querySelector(`.current-area[data-id='${id}']`);

        const [pSaved, dSaved, wSaved] = (areaInput.value || "").split(',').map(s => s.trim());

        // Load danh sách tỉnh
        data.forEach(province => {
          const opt = new Option(province.Name, province.Name);
          opt.dataset.code = province.Code;
          provinceSelect.add(opt);
        });

        // Mở modal thì gắn sự kiện click vào vùng khu vực để hiện select
        currentArea.addEventListener("click", () => {
          locationGroup.classList.remove("d-none");
          provinceSelect.value = pSaved;

          setTimeout(() => {
            loadDistricts(pSaved, dSaved);
            setTimeout(() => {
              loadWards(pSaved, dSaved, wSaved);
            }, 100);
          }, 100);
        });

        provinceSelect.addEventListener("change", () => {
          loadDistricts(provinceSelect.value);
          areaInput.value = "";
        });

        districtSelect.addEventListener("change", () => {
          loadWards(provinceSelect.value, districtSelect.value);
          areaInput.value = "";
        });

        wardSelect.addEventListener("change", () => {
          const p = provinceSelect.value;
          const d = districtSelect.value;
          const w = wardSelect.value;
          if (p && d && w) {
            areaInput.value = `${p}, ${d}, ${w}`;
          } else {
            areaInput.value = "";
          }
        });

        function loadDistricts(pName, selected = "") {
          districtSelect.innerHTML = `<option value="">Chọn Quận/Huyện</option>`;
          wardSelect.innerHTML = `<option value="">Chọn Phường/Xã</option>`;
          const province = data.find(p => p.Name === pName);
          province?.Districts.forEach(d => {
            const opt = new Option(d.Name, d.Name);
            opt.dataset.code = d.Code;
            districtSelect.add(opt);
          });
          if (selected) districtSelect.value = selected;
        }

        function loadWards(pName, dName, selected = "") {
          wardSelect.innerHTML = `<option value="">Chọn Phường/Xã</option>`;
          const province = data.find(p => p.Name === pName);
          const district = province?.Districts.find(d => d.Name === dName);
          district?.Wards.forEach(w => {
            const opt = new Option(w.Name, w.Name);
            wardSelect.add(opt);
          });
          if (selected) wardSelect.value = selected;
        }
      });
    });
});
</script>





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
