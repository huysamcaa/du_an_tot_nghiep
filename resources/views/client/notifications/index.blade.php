@extends('client.layouts.app')

@section('content')
<style>
    .pageBannerSection {
        background:#ECF5F4;
        padding: 10px 0;
    }
    .pageBannerContent h2 {

        font-size: 72px;
        color:#52586D;
        font-family: 'Jost', sans-serif;
    }
    .pageBannerPath a {
        color: #007bff;
        text-decoration: none;
    }
    .checkoutPage {
    margin-top: 0 !important;
    padding-top: 0 !important;

}
.pageBannerSection {
    padding: 20px 0;
    min-height: 10px;
}

.pageBannerSection .pageBannerContent h2 {
    font-size: 38px;
    margin-bottom: 10px;
}
.pageBannerPath {
    font-size: 14px;
}
.custom-btn {
    background-color: #94B7B9;
    color: #fff;
    border: none;
    border-radius: 20px;
    padding: 8px 16px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    font-weight: 600;
    transition: all 0.2s ease-in-out;
    white-space: nowrap;

    min-width: 120px;   /* 👈 đồng bộ chiều rộng */
    height: 40px;       /* 👈 đồng bộ chiều cao */
    text-align: center;
}

.custom-btn i {
    color: #fff;
}

.custom-btn:hover {
    background-color: #7fa1a3;
}

.custom-btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}
</style>
<!-- BEGIN: Page Banner Section -->
<section class="pageBannerSection">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="pageBannerContent text-center">
                    <h2>Thông báo </h2>
                    <div class="pageBannerPath">
                        <a href="{{route('client.home')}}">Trang chủ</a>&nbsp;&nbsp;>&nbsp;&nbsp;<span>Thông báo</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- END: Page Banner Section -->
<div class="container py-5">
    @if (session('success'))
        <div class="alert alert-success text-center">{{ session('success') }}</div>
    @endif

    @if ($notifications->isEmpty())
        <div class="alert alert-info text-center">Bạn chưa nhận được thông báo nào.</div>
    @else
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
            <div>
                <h5 class="fw-bold text-dark mb-2">🔔 Danh sách thông báo</h5>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="checkAll">
                    <label class="form-check-label" for="checkAll">Chọn tất cả / Bỏ chọn tất cả</label>
                </div>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                {{-- FORM: Đánh dấu đã đọc --}}
                <form id="bulkReadForm" action="{{ route('client.notifications.bulkMarkAsRead') }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn đánh dấu là đã đọc?')">
                    @csrf
                    @method('PATCH')
                    <div id="bulkReadInputs"></div>
                    <button type="submit" class="custom-btn">
                        <i class="fas fa-eye me-1"></i> Đánh dấu đã đọc
                    </button>
                </form>

                {{-- FORM: Xoá --}}
                <form id="bulkDeleteForm" action="{{ route('client.notifications.bulkDelete') }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa những thông báo đã chọn?')">
                    @csrf
                    @method('DELETE')
                    <div id="bulkDeleteInputs"></div>
                    <button type="submit" class="custom-btn">
                        <i class="fas fa-trash-alt me-1"></i> Xoá đã chọn (<span id="selectedCount">0</span>)
                    </button>
                </form>
            </div>
        </div>

        {{-- DANH SÁCH THÔNG BÁO --}}
        <div class="list-group">
            @foreach ($notifications as $notification)
                <div class="list-group-item d-flex align-items-center justify-content-between gap-3 rounded-4 shadow-sm mb-2
                    {{ $notification->read == 0 ? 'bg-light border-primary' : 'bg-white border' }}">

                    {{-- Checkbox --}}
                    <div class="form-check me-2">
                        <input class="form-check-input checkbox-item" type="checkbox" value="{{ $notification->id }}">
                    </div>

                    {{-- Icon chuông --}}
                    <i class="fas fa-bell text-warning fa-lg"></i>

                    {{-- Nội dung --}}
                    <div class="flex-grow-1">
                        <h6 class="fw-semibold mb-1 text-dark">
                            {{ \Illuminate\Support\Str::limit($notification->message, 100) }}
                        </h6>
                        <div class="text-muted small">
                            <i class="far fa-clock me-1"></i>
                            {{ \Carbon\Carbon::parse($notification->created_at)->format('d/m/Y H:i') }}
                        </div>
                    </div>

                    {{-- Các nút --}}
                    <div class="d-flex gap-2" style="flex-shrink:0;">
                        @if ($notification->read == 0)
                            <a href="{{ route('client.notifications.markAsRead', $notification->id) }}" class="custom-btn">
                                <i class="fas fa-eye me-1"></i> Đọc
                            </a>
                        @else
                            <button type="button" class="custom-btn" disabled>
                                <i class="fas fa-check-circle me-1"></i> Đã đọc
                            </button>
                        @endif

                        <a href="{{ route('client.notifications.show', $notification->id) }}" class="custom-btn">
                            <i class="fas fa-info-circle me-1"></i> Chi tiết
                        </a>

                        <form action="{{ route('client.notifications.destroy', $notification->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa thông báo này?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="custom-btn">
                                <i class="fas fa-trash me-1"></i> Xóa
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const checkAll = document.getElementById('checkAll');
        const checkboxes = document.querySelectorAll('.checkbox-item');
        const selectedCount = document.getElementById('selectedCount');
        const bulkReadInputs = document.getElementById('bulkReadInputs');
        const bulkDeleteInputs = document.getElementById('bulkDeleteInputs');

        function updateFormInputs() {
            const selected = Array.from(checkboxes).filter(cb => cb.checked).map(cb => cb.value);

            selectedCount.textContent = selected.length;

            bulkReadInputs.innerHTML = '';
            bulkDeleteInputs.innerHTML = '';

            selected.forEach(id => {
                const readInput = document.createElement('input');
                readInput.type = 'hidden';
                readInput.name = 'selected[]';
                readInput.value = id;
                bulkReadInputs.appendChild(readInput);

                const delInput = readInput.cloneNode();
                delInput.value = id;
                bulkDeleteInputs.appendChild(delInput);
            });
        }

        checkAll.addEventListener('change', () => {
            checkboxes.forEach(cb => cb.checked = checkAll.checked);
            updateFormInputs();
        });

        checkboxes.forEach(cb => cb.addEventListener('change', updateFormInputs));

        updateFormInputs();
    });
</script>
@endpush


