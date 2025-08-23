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
        @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        <div class="row">
            {{-- Cột trái: danh sách sản phẩm lỗi --}}
            <div class="col-lg-6">
                <div class="orderReviewWrap mb-4 p-4 shadow-sm rounded border bg-white">
                    <h4 class="mb-4"><i class="fas fa-exclamation-circle me-2 text-danger"></i>Sản phẩm lỗi đã chọn</h4>
                    <div class="table-responsive">
                        <table class="table align-middle text-center">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-start">Sản phẩm</th>
                                    <th>Phân loại</th>
                                    <th>Giá</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $totalRefund = 0; @endphp
                                @foreach($selectedItems as $item)
                                @php $totalRefund += $item->price; @endphp
                                <tr>
                                    <td class="text-start d-flex align-items-center">
                                        {{-- Kiểm tra và hiển thị ảnh --}}
                                        @if(optional($item->variant)->thumbnail)
                                        <img src="{{ asset('storage/' . $item->variant->thumbnail) }}"
                                            alt="{{ $item->name }}"
                                            class="me-3"
                                            style="width:50px; height:50px; object-fit:cover; border-radius:6px;">
                                        @else
                                        <img src="{{ asset('storage/default-product.png') }}"
                                            alt="{{ $item->name }}"
                                            class="me-3"
                                            style="width:50px; height:50px; object-fit:cover; border-radius:6px;">
                                        @endif
                                        <div>
                                            {{-- Hiển thị tên sản phẩm, có thể dùng tên từ OrderItem để an toàn hơn --}}
                                            <div class="fw-semibold">{{ $item->name }}</div>
                                            {{-- Hiển thị phân loại sản phẩm --}}
                                            @if($item->variant && $item->variant->attributeValues->count())
                                            <small class="text-muted">
                                                {{ $item->variant->attributeValues->pluck('value')->implode(' - ') }}
                                            </small>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        @if($item->variant && $item->variant->attributeValues->count())
                                        {{ $item->variant->attributeValues->pluck('value')->implode(' - ') }}
                                        @else
                                        <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="text-danger fw-semibold">
                                            {{ number_format($item->price) }}đ
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <td colspan="2" class="text-end fw-bold">Tổng hoàn tiền:</td>
                                    <td class="fw-bold text-danger">{{ number_format($totalRefund) }}đ</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
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
                                <label for="bank_name" class="form-label">Ngân hàng <span class="text-danger">*</span></label>
                                <select class="form-select @error('bank_name') is-invalid @enderror" id="bank_name" name="bank_name" required>
                                    <option value="" selected disabled>-- Chọn ngân hàng --</option>
                                    <option value="Vietcombank" {{ old('bank_name') == 'Vietcombank' ? 'selected' : '' }}>Vietcombank</option>
                                    <option value="VietinBank" {{ old('bank_name') == 'VietinBank' ? 'selected' : '' }}>VietinBank</option>
                                    <option value="BIDV" {{ old('bank_name') == 'BIDV' ? 'selected' : '' }}>BIDV</option>
                                    <option value="Agribank" {{ old('bank_name') == 'Agribank' ? 'selected' : '' }}>Agribank</option>
                                    <option value="Techcombank" {{ old('bank_name') == 'Techcombank' ? 'selected' : '' }}>Techcombank</option>
                                    <option value="MB Bank" {{ old('bank_name') == 'MB Bank' ? 'selected' : '' }}>MB Bank</option>
                                    <option value="ACB" {{ old('bank_name') == 'ACB' ? 'selected' : '' }}>ACB</option>
                                    <option value="Sacombank" {{ old('bank_name') == 'Sacombank' ? 'selected' : '' }}>Sacombank</option>
                                    <option value="VPBank" {{ old('bank_name') == 'VPBank' ? 'selected' : '' }}>VPBank</option>
                                    <option value="SHB" {{ old('bank_name') == 'SHB' ? 'selected' : '' }}>SHB</option>
                                </select>
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
                                <div class="custom-file-upload">
                                    <input type="file" name="reason_image" id="reason_image"
                                        class="form-control @error('reason_image') is-invalid @enderror"
                                        accept="image/*,video/*" style="display: none;"> {{-- Ẩn input gốc --}}

                                    <label for="reason_image" class="custom-file-label">
                                        <i class="fas fa-camera me-2"></i>Chọn Ảnh/Video
                                    </label>
                                    <span id="file-name" class="ms-3 text-muted">Không có tệp nào được chọn</span>
                                </div>
                                <small class="text-muted d-block mt-1">
                                    Hỗ trợ ảnh hoặc video định dạng JPEG, PNG, MP4, MOV, AVI. Dung lượng tối đa: <strong>10MB</strong>. Thời lượng video khuyến nghị: <strong>dưới 1 phút</strong>.
                                </small>
                                @error('reason_image')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror

                                <div class="mt-3" id="preview_area"></div>
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
@push('scripts')
<script>
    document.getElementById('reason_image').addEventListener('change', function(event) {
        const file = event.target.files[0];
        const preview = document.getElementById('preview_area');
        const fileNameSpan = document.getElementById('file-name');
        preview.innerHTML = '';

        if (!file) {
            fileNameSpan.textContent = 'Không có tệp nào được chọn';
            return;
        }

        fileNameSpan.textContent = file.name;

        const fileType = file.type;
        const fileURL = URL.createObjectURL(file);

        if (fileType.startsWith('image/')) {
            const img = document.createElement('img');
            img.src = fileURL;
            img.alt = 'Preview';
            img.classList.add('img-fluid', 'rounded');
            img.style.maxHeight = '300px';
            preview.appendChild(img);
        } else if (fileType.startsWith('video/')) {
            const video = document.createElement('video');
            video.src = fileURL;
            video.controls = true;
            video.style.maxHeight = '300px';
            video.classList.add('w-100', 'rounded');
            preview.appendChild(video);
        }
    });
</script>
<style>
    .custom-file-upload {
        display: flex;
        align-items: center;
    }

    .custom-file-label {
        background-color: #f8f9fa;
        border: 1px solid #ced4da;
        border-radius: .25rem;
        padding: .375rem .75rem;
        cursor: pointer;
        transition: background-color .2s, border-color .2s;
        font-weight: 500;
    }

    .custom-file-label:hover {
        background-color: #e9ecef;
        border-color: #adb5bd;
    }

    .custom-file-label:active {
        background-color: #dee2e6;
        border-color: #adb5bd;
    }

    .custom-file-label i {
        margin-right: .5rem;
    }
</style>
@endpush
