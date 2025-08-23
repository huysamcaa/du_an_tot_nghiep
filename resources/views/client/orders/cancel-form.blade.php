@extends('client.layouts.app')

@section('title', 'Hủy đơn hàng')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-danger">
                    <h4 class="mb-0 text-light">Hủy đơn hàng #{{ $order->code }}</h4>
                </div>

                <div class="card-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Bạn đang yêu cầu hủy đơn hàng này. Vui lòng chọn lý do bên dưới.
                    </div>

                    <form action="{{ route('client.orders.cancel', $order->id) }}" method="POST">
                        @csrf

                        <div class="mb-4">
                            <label for="cancel_reason" class="form-label fw-bold">Lý do hủy <span class="text-danger">*</span></label>
                            <select class="form-select form-select-lg" id="cancel_reason" name="cancel_reason" required>
                                <option value="" selected disabled>-- Chọn lý do --</option>
                                <option value="Đổi ý, không muốn mua nữa">Đổi ý, không muốn mua nữa</option>
                                <option value="Đặt nhầm sản phẩm">Đặt nhầm sản phẩm</option>
                                <option value="Giá sản phẩm không phù hợp">Giá sản phẩm không phù hợp</option>
                                <option value="Quên áp dụng mã giảm giá / khuyến mãi">Quên áp dụng mã giảm giá / khuyến mãi</option>
                                <option value="Đặt lại đơn mới với thông tin chính xác hơn">Đặt lại đơn mới với thông tin chính xác hơn</option>
                                <option value="Không còn nhu cầu">Không còn nhu cầu</option>
                                <option value="Muốn thay đổi sản phẩm/màu sắc/kích cỡ">Muốn thay đổi sản phẩm/màu sắc/kích cỡ</option>
                                <option value="other">Lý do khác</option>
                            </select>
                        </div>


                        <div class="mb-4" id="other_reason_container" style="display: none;">
                            <label for="other_reason" class="form-label fw-bold">Vui lòng nêu rõ lý do <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="other_reason" name="other_reason" rows="3"></textarea>
                        </div>

                        <div class="mb-4">
                            <label for="cancel_feedback" class="form-label fw-bold">Ý kiến đóng góp (nếu có)</label>
                            <textarea class="form-control" id="cancel_feedback" name="cancel_feedback" rows="3" placeholder="Chúng tôi rất tiếc vì điều này. Bạn có góp ý gì để chúng tôi cải thiện không?"></textarea>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
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
        const reasonSelect = document.getElementById('cancel_reason');
        const otherReasonContainer = document.getElementById('other_reason_container');
        const otherReasonInput = document.getElementById('other_reason');

        reasonSelect.addEventListener('change', function() {
            if (this.value === 'other') {
                otherReasonContainer.style.display = 'block';
                otherReasonInput.setAttribute('required', 'required');
            } else {
                otherReasonContainer.style.display = 'none';
                otherReasonInput.removeAttribute('required');
            }
        });

        // Validate form khi submit
        document.querySelector('form').addEventListener('submit', function(e) {
            if (!reasonSelect.value) {
                e.preventDefault();
                alert('Vui lòng chọn lý do hủy đơn hàng');
                return false;
            }

            if (reasonSelect.value === 'other' && !otherReasonInput.value.trim()) {
                e.preventDefault();
                alert('Vui lòng nhập lý do hủy đơn hàng');
                return false;
            }

            return true;
        });
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

    .form-select-lg {
        padding: 0.8rem 1rem;
        font-size: 1.1rem;
    }

    textarea.form-control {
        min-height: 120px;
    }

    .btn-lg {
        padding: 0.8rem 1.5rem;
        font-size: 1.1rem;
    }
</style>
@endsection
