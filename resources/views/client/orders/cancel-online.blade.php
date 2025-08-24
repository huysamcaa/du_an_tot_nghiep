@extends('client.layouts.app')

@section('title', 'Hủy đơn hàng')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-danger text-white">
                    <h4 class="mb-0 text-light">Hủy đơn hàng #{{ $order->code }}</h4>
                </div>

                <div class="card-body">
                    @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                    @endif

                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Bạn đang yêu cầu hủy đơn hàng này. Vui lòng điền đầy đủ thông tin bên dưới.
                    </div>

                    <form action="{{ route('client.orders.cancel2', $order->id) }}" method="POST">
                        @csrf

                        <!-- Phần lý do hủy -->
                        <div class="mb-4">
                            <h5 class="fw-bold border-bottom pb-2 mb-3">1. Lý do hủy đơn</h5>
                            <div class="mb-3">
                                <label for="cancel_reason" class="form-label fw-bold">Lý do hủy <span class="text-danger">*</span></label>
                                <select class="form-select @error('cancel_reason') is-invalid @enderror" id="cancel_reason" name="cancel_reason" required>
                                    <option value="" selected disabled>-- Chọn lý do --</option>
                                    <option value="Đổi ý, không muốn mua nữa" {{ old('cancel_reason') == 'Đổi ý, không muốn mua nữa' ? 'selected' : '' }}>Đổi ý, không muốn mua nữa</option>
                                    <option value="Đặt nhầm sản phẩm" {{ old('cancel_reason') == 'Đặt nhầm sản phẩm' ? 'selected' : '' }}>Đặt nhầm sản phẩm</option>
                                    <option value="Giá sản phẩm không phù hợp" {{ old('cancel_reason') == 'Giá sản phẩm không phù hợp' ? 'selected' : '' }}>Giá sản phẩm không phù hợp</option>
                                    <option value="Quên áp dụng mã giảm giá" {{ old('cancel_reason') == 'Quên áp dụng mã giảm giá' ? 'selected' : '' }}>Quên áp dụng mã giảm giá</option>
                                    <option value="other" {{ old('cancel_reason') == 'other' ? 'selected' : '' }}>Lý do khác</option>
                                </select>
                                @error('cancel_reason')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3" id="other_reason_container" style="display: none;">
                                <label for="other_reason" class="form-label fw-bold">Vui lòng nêu rõ lý do <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('other_reason') is-invalid @enderror" id="other_reason" name="other_reason" rows="2">{{ old('other_reason') }}</textarea>
                                @error('other_reason')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="cancel_feedback" class="form-label fw-bold">Ý kiến đóng góp</label>
                                <textarea class="form-control @error('cancel_feedback') is-invalid @enderror" id="cancel_feedback" name="cancel_feedback" rows="2" placeholder="Chúng tôi rất tiếc vì điều này. Bạn có góp ý gì để chúng tôi cải thiện không?">{{ old('cancel_feedback') }}</textarea>
                                @error('cancel_feedback')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Phần thông tin hoàn tiền -->
                        @if($order->is_paid)
                        <div class="mb-4">
                            <h5 class="fw-bold border-bottom pb-2 mb-3">2. Thông tin hoàn tiền</h5>

                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                Vui lòng cung cấp thông tin tài khoản ngân hàng để nhận hoàn tiền
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
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

                                <div class="col-md-6">
                                    <label for="account_name" class="form-label">Tên tài khoản <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('account_name') is-invalid @enderror" id="account_name" name="account_name"
                                        placeholder="Nguyen Van A" value="{{ old('account_name') }}" required>
                                    @error('account_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="account_number" class="form-label">Số tài khoản <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('account_number') is-invalid @enderror" id="account_number" name="account_number"
                                        placeholder="1234567890" value="{{ old('account_number') }}" required>
                                    @error('account_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="phone_number" class="form-label">Số điện thoại <span class="text-danger">*</span></label>
                                    <input type="tel" class="form-control @error('phone_number') is-invalid @enderror" id="phone_number" name="phone_number"
                                        placeholder="0987654321" value="{{ old('phone_number') }}" required>
                                    @error('phone_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        @endif

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                            <a href="{{ route('client.orders.purchase.history') }}" class="btn btn-outline-secondary me-md-2 rounded-5">
                                <i class="fas fa-arrow-left me-1"></i> Quay lại
                            </a>
                            <button type="submit" class="btn btn-danger rounded-5">
                                <i class="fas fa-times-circle me-1"></i> Xác nhận hủy
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Xử lý lý do hủy
    const reasonSelect = document.getElementById('cancel_reason');
    const otherReasonContainer = document.getElementById('other_reason_container');
    const otherReasonInput = document.getElementById('other_reason');

    function handleReasonChange() {
        if (reasonSelect.value === 'other') {
            otherReasonContainer.style.display = 'block';
            otherReasonInput.setAttribute('required', 'required');
        } else {
            otherReasonContainer.style.display = 'none';
            otherReasonInput.removeAttribute('required');
        }
    }

    // Gắn sự kiện
    reasonSelect.addEventListener('change', handleReasonChange);

    // Khởi tạo trạng thái ban đầu
    handleReasonChange();
});
</script>

<style>
    .card {
        border-radius: 10px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        border: none;
    }

    .card-header {
        border-radius: 10px 10px 0 0 !important;
        padding: 1.5rem;
    }

    .form-select, .form-control {
        padding: 0.8rem 1rem;
        font-size: 1rem;
    }

    textarea.form-control {
        min-height: 100px;
    }

    .btn {
        padding: 0.8rem 1.5rem;
        font-size: 1rem;
    }

    .border-bottom {
        border-bottom: 2px solid #dee2e6 !important;
    }
</style>
@endsection
