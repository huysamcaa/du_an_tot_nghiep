@extends('client.layouts.app')

@section('content')
<style>
    .pageBannerSection { background:#ECF5F4; padding: 10px 0; }
    .pageBannerContent h2 { font-size: 38px; color:#52586D; font-family:'Jost',sans-serif; margin-bottom:10px; }
    .pageBannerPath { font-size:14px; }
    .custom-btn {
        background-color:#94B7B9; color:#fff; border:none; border-radius:20px;
        padding:8px 16px; display:inline-flex; align-items:center; justify-content:center; gap:6px;
        font-weight:600; transition:.2s; white-space:nowrap; min-width:120px; height:40px; text-align:center;
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
.list-group-item {
    border-width: 1px !important;     /* ép viền đủ 4 phía */
    border-style: solid !important;
    margin-bottom: 12px;              /* tạo khoảng cách giữa các item */
    border-radius: 12px !important;   /* bo góc */
}

.list-group-item.bg-light.border-primary {
    border-color: #0d6efd !important; /* viền xanh khi chưa đọc */
}

.list-group-item.bg-white.border {
    border-color: #dee2e6 !important; /* viền xám khi đã đọc */
}

.list-group {
    border: none !important; /* xoá border mặc định list-group */
}

    .custom-btn:hover { background-color:#7fa1a3; }
    .custom-btn:disabled { opacity:.6; cursor:not-allowed; }
</style>

<section class="pageBannerSection">
    <div class="container">
        <div class="row"><div class="col-lg-12">
            <div class="pageBannerContent text-center">
                <h2>Thông báo</h2>
                <div class="pageBannerPath">
                    <a href="{{route('client.home')}}">Trang chủ</a>&nbsp;&nbsp;>&nbsp;&nbsp;<span>Thông báo</span>
                </div>
            </div>
        </div></div>
    </div>
</section>

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
                {{-- Đánh dấu đã đọc hàng loạt --}}
                <form id="bulkReadForm" action="{{ route('client.notifications.bulkMarkAsRead') }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn đánh dấu là đã đọc?')">
                    @csrf @method('PATCH')
                    <div id="bulkReadInputs"></div>
                    <button type="submit" class="custom-btn">
                        <i class="fas fa-eye me-1"></i> Đánh dấu đã đọc
                    </button>
                </form>

                {{-- Xóa hàng loạt --}}
                <form id="bulkDeleteForm" action="{{ route('client.notifications.bulkDelete') }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa những thông báo đã chọn?')">
                    @csrf @method('DELETE')
                    <div id="bulkDeleteInputs"></div>
                    <button type="submit" class="custom-btn">
                        <i class="fas fa-trash-alt me-1"></i> Xoá đã chọn (<span id="selectedCount">0</span>)
                    </button>
                </form>
            </div>
        </div>

        {{-- DANH SÁCH --}}
        <div class="list-group">
            @foreach ($notifications as $notification)
                <div id="notif-{{ $notification->id }}"
                     class="list-group-item d-flex align-items-center justify-content-between gap-3 rounded-4 shadow-sm mb-2
                     {{ $notification->read == 0 ? 'bg-light border-primary' : 'bg-white border' }}">

                    {{-- Checkbox --}}
                    <div class="form-check me-2">
                        <input class="form-check-input checkbox-item" type="checkbox" value="{{ $notification->id }}">
                    </div>

                    {{-- Icon --}}
                    <i class="fas fa-bell text-warning fa-lg"></i>

                    {{-- Nội dung --}}
                    <div class="flex-grow-1">
                        <h6 id="notif-title-{{ $notification->id }}"
                            class="{{ $notification->read ? 'fw-normal' : 'fw-semibold' }} mb-1 text-dark">
                            {{ \Illuminate\Support\Str::limit($notification->message, 100) }}
                        </h6>
                        <div class="text-muted small">
                            <i class="far fa-clock me-1"></i>
                            {{ \Carbon\Carbon::parse($notification->created_at)->format('d/m/Y H:i') }}
                        </div>
                    </div>

                    {{-- Chỉ còn nút Chi tiết (JS sẽ mark-as-read trước khi chuyển trang) --}}
                    <div class="d-flex gap-2" style="flex-shrink:0;">
                        <a href="{{ route('client.notifications.show', $notification->id) }}"
                           class="custom-btn btn-detail"
                           data-id="{{ $notification->id }}"
                           data-mark-url="{{ route('client.notifications.markAsRead', $notification->id) }}">
                            <i class="fas fa-info-circle me-1"></i> Chi tiết
                        </a>
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
        if (selectedCount) selectedCount.textContent = selected.length;
        bulkReadInputs.innerHTML = ''; bulkDeleteInputs.innerHTML = '';
        selected.forEach(id => {
            const hidden = (name, val) => {
                const el = document.createElement('input');
                el.type = 'hidden'; el.name = name; el.value = val; return el;
            };
            bulkReadInputs.appendChild(hidden('selected[]', id));
            bulkDeleteInputs.appendChild(hidden('selected[]', id));
        });
    }

    if (checkAll) {
        checkAll.addEventListener('change', () => {
            checkboxes.forEach(cb => cb.checked = checkAll.checked);
            updateFormInputs();
        });
    }
    checkboxes.forEach(cb => cb.addEventListener('change', updateFormInputs));
    updateFormInputs();

    // === Shopee-like: mark-as-read ngay khi bấm "Chi tiết" rồi mới điều hướng ===
    document.querySelectorAll('.btn-detail').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const id = this.dataset.id;
            const markUrl = this.dataset.markUrl;
            const detailUrl = this.getAttribute('href');

            // Cập nhật UI tức thì
            const item  = document.getElementById('notif-' + id);
            const title = document.getElementById('notif-title-' + id);
            if (item) { item.classList.remove('bg-light','border-primary'); item.classList.add('bg-white','border'); }
            if (title) { title.classList.remove('fw-semibold'); title.classList.add('fw-normal'); }

            // Gọi API mark-as-read; xong thì chuyển trang (dù lỗi vẫn điều hướng)
            fetch(markUrl, { method: 'GET', headers: { 'X-Requested-With': 'XMLHttpRequest' } })
              .catch(()=>{})
              .finally(()=>{ window.location.href = detailUrl; });
        });
    });

    // Nếu quay lại bằng back/forward cache, reload để đồng bộ 100%
    window.addEventListener('pageshow', function(e) {
        if (e.persisted) location.reload();
    });
});
</script>
@endpush
