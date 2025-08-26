@extends('admin.layouts.app')

@section('content')
<div class="content">
  {{-- Header --}}
  <div class="d-flex justify-content-between align-items-center mb-3">
    <div>
      <h4 class="mb-0">Danh sách đánh giá</h4>
      <small class="text-muted">Quản lý đánh giá</small>
    </div>
  </div>

  {{-- Alerts --}}
  <div class="col-md-12">
    @if (session('success'))
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
      </div>
    @endif
    @if (session('error'))
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
      </div>
    @endif
    @if (session('warning'))
      <div class="alert alert-warning alert-dismissible fade show" role="alert">
        {{ session('warning') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
      </div>
    @endif
    @if ($errors->any())
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <ul class="mb-0 ps-3">
          @foreach ($errors->all() as $err)
            <li>{{ $err }}</li>
          @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
      </div>
    @endif
  </div>

  {{-- ONE CARD: Filters + Table --}}
  <div class="card">
    <div class="card-body">

      {{-- FILTERS (GET) --}}
      <form method="GET" action="{{ route('admin.reviews.index') }}" class="row g-2 align-items-end mb-3">
        {{-- Giữ params khác (ngoài page) --}}
        @foreach (request()->except(['page']) as $k => $v)
          @continue(in_array($k, ['search','status','rating','has_media','date_from','date_to']))
          <input type="hidden" name="{{ $k }}" value="{{ $v }}">
        @endforeach

        {{-- 1 dòng trên desktop: tổng 12 cột --}}
        <div class="col-12 col-lg-3">
          <label class="form-label mb-1">Tìm kiếm</label>
          <input type="text" name="search" class="form-control" placeholder="Tên sp / người dùng / nội dung…"
                 value="{{ request('search') }}">
        </div>

        <div class="col-6 col-lg-2">
          <label class="form-label mb-1">Trạng thái</label>
          <select name="status" class="form-control">
            <option value="">-- Tất cả --</option>
            <option value="pending"  {{ request('status')==='pending'  ? 'selected' : '' }}>Chờ duyệt</option>
            <option value="approved" {{ request('status')==='approved' ? 'selected' : '' }}>Đã duyệt</option>
            <option value="rejected" {{ request('status')==='rejected' ? 'selected' : '' }}>Từ chối</option>
          </select>
        </div>

        <div class="col-6 col-lg-1">
          <label class="form-label mb-1">Sao</label>
          <select name="rating" class="form-control">
            <option value="">-- --</option>
            @for ($i=5; $i>=1; $i--)
              <option value="{{ $i }}" {{ request('rating')==(string)$i ? 'selected' : '' }}>{{ $i }} ⭐</option>
            @endfor
          </select>
        </div>

        <div class="col-6 col-lg-1">
          <label class="form-label mb-1">Media</label>
          <select name="has_media" class="form-control">
            <option value="">-- --</option>
            <option value="yes" {{ request('has_media')==='yes' ? 'selected' : '' }}>Có</option>
            <option value="no"  {{ request('has_media')==='no'  ? 'selected' : '' }}>Không</option>
          </select>
        </div>

        <div class="col-6 col-lg-2">
          <label class="form-label mb-1">Từ ngày</label>
          <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
        </div>

        <div class="col-6 col-lg-2">
          <label class="form-label mb-1">Đến ngày</label>
          <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
        </div>

        {{-- Nút (auto-width, cùng 1 dòng) --}}
        <div class="col-12 col-lg-1 d-flex align-items-end">
          <div class="d-flex" style="gap:8px;">
            <button type="submit"
                    class="btn"
                    style="background:#ffa200;color:#fff;font-weight:600;border:none;border-radius:4px;flex:0 0 auto;white-space:nowrap;">
              Tìm kiếm
            </button>

            @if (request()->hasAny(['search','status','rating','has_media','date_from','date_to','perPage']))
              <a href="{{ route('admin.reviews.index') }}"
                 class="btn btn-outline-secondary"
                 style="flex:0 0 auto;white-space:nowrap;">
                Xóa lọc
              </a>
            @endif
          </div>
        </div>
      </form>

      {{-- TABLE --}}
      <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle text-center mb-0">
          <thead class="table-light">
            <tr>
              <th>#</th>
              <th>Media</th>
              <th>Người dùng</th>
              <th>Sản phẩm</th>
              <th>Đánh giá</th>
              <th>Nội dung</th>
              <th>Trạng thái</th>
              <th>Hành động</th>
            </tr>
          </thead>
          <tbody>
          @forelse($reviews as $index => $review)
            <tr>
              <td>{{ $reviews->firstItem() + $index }}</td>

              <td>
                <div class="d-flex flex-wrap justify-content-center gap-1">
                  @foreach ($review->multimedia as $media)
                    @php $src = asset('storage/' . $media->file); @endphp
                    @if (str_starts_with($media->mime_type, 'image/'))
                      <img src="{{ $src }}" width="60" height="60"
                           class="rounded border review-media-item"
                           data-type="{{ $media->mime_type }}" data-src="{{ $src }}"
                           style="object-fit:cover;cursor:pointer;">
                    @elseif (str_starts_with($media->mime_type, 'video/'))
                      <video width="60" height="60" muted
                             class="rounded border review-media-item"
                             data-type="{{ $media->mime_type }}" data-src="{{ $src }}"
                             style="object-fit:cover;cursor:pointer;">
                        <source src="{{ $src }}" type="{{ $media->mime_type }}">
                      </video>
                    @endif
                  @endforeach
                </div>
              </td>

              <td>{{ $review->reviewer_name }}</td>

              <td>
                <span class="d-inline-block text-truncate" style="max-width:180px;">
                  {{ $review->product->name ?? '—' }}
                </span>
              </td>

              <td>{{ $review->rating }} ⭐</td>

              <td>
                <span class="d-inline-block text-truncate" style="max-width:260px;">
                  {{ Str::limit($review->review_text, 120) }}
                </span>
              </td>

              <td>
                @if (is_null($review->is_active))
                  <span class="badge bg-warning text-dark">Chờ duyệt</span>
                @elseif ($review->is_active === 1)
                  <span class="badge bg-success">Đã duyệt</span>
                @elseif ($review->is_active === 0)
                  <span class="badge bg-danger">Từ chối</span>
                  @if (!empty($review->reason))
                    <br><small class="text-muted">Lý do: {{ $review->reason }}</small>
                  @endif
                @else
                  <span class="badge bg-secondary">Không xác định</span>
                @endif
              </td>

              <td>
                @if (is_null($review->is_active))
                  <form action="{{ route('admin.reviews.approve', $review->id) }}" method="POST" class="d-inline-block mb-1">
                    @csrf @method('PATCH')
                    <button type="submit" class="btn btn-sm btn-success" title="Duyệt đánh giá">
                      <i class="fa fa-check"></i>
                    </button>
                  </form>

                  <form action="{{ route('admin.reviews.reject', $review->id) }}" method="POST" class="d-inline-block">
                    @csrf @method('PATCH')
                    <div class="input-group input-group-sm mb-1" style="max-width:260px;">
                      <input type="text" name="reason" class="form-control" placeholder="Lý do từ chối">
                      <button type="submit" class="btn btn-danger" title="Từ chối đánh giá">
                        <i class="fa fa-times"></i>
                      </button>
                    </div>
                    @if (session('reject_id') == $review->id)
                      @error('reason')
                        <small class="text-danger d-block mt-1">{{ $message }}</small>
                      @enderror
                    @endif
                  </form>
                @else
                  <span class="text-muted">—</span>
                @endif
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="8" class="text-muted text-center py-4">Không có đánh giá nào.</td>
            </tr>
          @endforelse
          </tbody>
        </table>
      </div>

      {{-- Footer (trong card) --}}
      <div class="d-flex justify-content-between align-items-center mt-4">
        <div class="text-muted">
          Hiển thị từ {{ $reviews->firstItem() ?? 0 }} đến {{ $reviews->lastItem() ?? 0 }} trên tổng số {{ $reviews->total() }} mục
        </div>
        <div>
          {!! $reviews->appends(request()->query())->onEachSide(1)->links('pagination::bootstrap-4') !!}
        </div>
      </div>

    </div>
  </div>
</div>

{{-- Sticky controls: Hiển thị / trang --}}
<div id="reviews-footer-controls"
     class="d-flex justify-content-between align-items-center px-3 py-3"
     style="position:sticky; bottom:0; background:#fff; border-top:1px solid #eef0f2; z-index:5; gap:12px; flex-wrap:wrap;">
  <form method="GET" action="{{ route('admin.reviews.index') }}" class="d-flex align-items-center" style="gap:8px; margin:0;">
    @foreach (request()->except(['perPage','page']) as $k => $v)
      <input type="hidden" name="{{ $k }}" value="{{ $v }}">
    @endforeach
    <label for="perPage" class="mb-0" style="font-weight:600;">Hiển thị:</label>
    <select name="perPage" id="perPage" class="form-control"
            style="width:90px;border:1px solid #cfd4da;border-radius:8px;padding:6px 10px;background:#f9fafb;"
            onchange="this.form.submit()">
      @foreach ([10,25,50,100] as $n)
        <option value="{{ $n }}" {{ request('perPage')==(string)$n ? 'selected' : '' }}>{{ $n }}</option>
      @endforeach
    </select>
  </form>

  <div class="d-flex align-items-center flex-wrap" style="gap:10px; margin-left:auto;">
    <small class="text-muted me-2">
      Hiển thị từ {{ $reviews->firstItem() ?? 0 }} đến {{ $reviews->lastItem() ?? 0 }} / {{ $reviews->total() }} mục
    </small>
    <nav aria-label="Pagination">
      <div class="pagination pagination-sm mb-0">
        {!! $reviews->appends(request()->query())->onEachSide(1)->links('pagination::bootstrap-4') !!}
      </div>
    </nav>
  </div>
</div>

{{-- Modal xem media --}}
<div id="mediaModal" class="modal fade" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content bg-dark text-white position-relative">
      <button type="button" class="btn-close position-absolute top-0 end-0 m-2" data-bs-dismiss="modal" aria-label="Close"></button>
      <div class="modal-body text-center" id="mediaModalBody"></div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<style>
/* Nút tìm kiếm auto-width, không kéo giãn */
#couponFilters button[type="submit"] { flex:0 0 auto; white-space:nowrap; }
/* Ảnh/video preview trong ô media */
.review-media-item { transition: transform .1s; }
.review-media-item:hover { transform: scale(1.03); }
</style>
<script>
document.addEventListener('DOMContentLoaded', function () {
  const modal     = new bootstrap.Modal(document.getElementById('mediaModal'));
  const modalBody = document.getElementById('mediaModalBody');

  document.querySelectorAll('.review-media-item').forEach(item => {
    item.addEventListener('click', function() {
      const type = this.dataset.type;
      const src  = this.dataset.src;
      modalBody.innerHTML = type.startsWith('image/')
        ? `<img src="${src}" class="img-fluid rounded" style="max-height:80vh;">`
        : `<video controls autoplay style="max-width:100%;max-height:80vh;"><source src="${src}" type="${type}"></video>`;
      modal.show();
    });
  });
});
</script>
@endpush
