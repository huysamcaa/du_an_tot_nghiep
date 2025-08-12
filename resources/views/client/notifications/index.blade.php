@extends('client.layouts.app')

@section('content')
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
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-eye me-1"></i> Đánh dấu đã đọc
                    </button>
                </form>

                {{-- FORM: Xoá --}}
                <form id="bulkDeleteForm" action="{{ route('client.notifications.bulkDelete') }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa những thông báo đã chọn?')">
                    @csrf
                    @method('DELETE')
                    <div id="bulkDeleteInputs"></div>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash-alt me-1"></i> Xoá đã chọn (<span id="selectedCount">0</span>)
                    </button>
                </form>
            </div>
        </div>

        {{-- DANH SÁCH THÔNG BÁO --}}
        <div class="row g-4">
            @foreach ($notifications as $notification)
                <div class="col-md-6 col-lg-4">
                    <div class="card shadow-sm h-100 border-0 rounded-4 {{ $notification->read == 0 ? 'bg-light border-primary' : 'bg-white border' }}">
                        <div class="card-body position-relative">
                            {{-- Checkbox --}}
                            <div class="form-check position-absolute top-0 end-0 m-2">
                                <input class="form-check-input checkbox-item" type="checkbox" value="{{ $notification->id }}">
                            </div>

                            {{-- Nội dung --}}
                            <div class="d-flex align-items-start gap-2 mb-3">
                                <i class="fas fa-bell text-warning fa-lg"></i>
                                <div>
                                    <h6 class="fw-semibold mb-1 text-wrap text-dark" style="min-height: 3rem;">
                                        {{ \Illuminate\Support\Str::limit($notification->message, 100) }}
                                    </h6>
                                    <div class="text-muted small">
                                        <i class="far fa-clock me-1"></i>
                                        {{ \Carbon\Carbon::parse($notification->created_at)->format('d/m/Y H:i') }}
                                    </div>
                                </div>
                            </div>

                            {{-- Các nút --}}
                            <div class="d-flex gap-2">
                                @if ($notification->read == 0)
                                    <a href="{{ route('client.notifications.markAsRead', $notification->id) }}" class="btn btn-outline-primary btn-sm flex-fill">
                                        <i class="fas fa-eye me-1"></i> Đánh dấu đã đọc
                                    </a>
                                @else
                                    <button type="button" class="btn btn-success btn-sm flex-fill" disabled>
                                        <i class="fas fa-check-circle me-1"></i> Đã đọc
                                    </button>
                                @endif

                                <a href="{{ route('client.notifications.show', $notification->id) }}" class="btn btn-outline-info btn-sm flex-fill">
                                    <i class="fas fa-info-circle me-1"></i> Xem chi tiết
                                </a>

                                <form action="{{ route('client.notifications.destroy', $notification->id) }}" method="POST" class="flex-fill">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm w-100" onclick="return confirm('Bạn có chắc muốn xóa thông báo này?')">
                                        <i class="fas fa-trash me-1"></i> Xóa
                                    </button>
                                </form>
                            </div>
                        </div>
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
