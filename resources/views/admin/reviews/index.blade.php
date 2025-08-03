@extends('admin.layouts.app')

@section('content')
    <h1>Danh sách đánh giá người dùng</h1>


    <div class="breadcrumbs">
        <div class="breadcrumbs-inner">
            <div class="row m-0">
                <div class="col-sm-4">
                    <div class="page-header float-left">
                        <div class="page-title">
                            <h1>Admin</h1>
                        </div>
                    </div>
                </div>
                <div class="col-sm-8">
                    <div class="page-header float-right">
                        <div class="page-title">
                            <ol class="breadcrumb text-right">
                                <li><a href="#">Trang chủ</a></li>
                                <li><a href="#">Đánh giá</a></li>
                                <li class="active">Danh sách đánh giá</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="animated fadeIn">
            <div class="row">

                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <strong class="card-title">Danh sách đánh giá</strong>
                        </div>

                        <div class="card-body">
                              <form method="GET" action="{{ route('admin.reviews.index') }}" class="mb-3 d-flex"
                    style="gap: 12px; align-items: center;">
                    <div>
                        <label for="perPage" style="font-weight:600;">Hiển thị:</label>
                        <select name="perPage" id="perPage" class="form-control d-inline-block"
                            style="width:auto;" onchange="this.form.submit()">
                            <option value="10" {{ request('perPage') == '10' ? 'selected' : '' }}>10
                            </option>
                            <option value="25" {{ request('perPage') == '25' ? 'selected' : '' }}>25
                            </option>
                            <option value="50" {{ request('perPage') == '50' ? 'selected' : '' }}>50
                            </option>
                            <option value="100" {{ request('perPage') == '100' ? 'selected' : '' }}>100
                            </option>
                        </select>
                    </div>
                </form>
                            <table id="bootstrap-data" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Ảnh</th>
                                        <th>Người Dùng</th>
                                        <th>Sản Phẩm</th>
                                        <th>Đánh Giá</th>
                                        <th>Nội Dung</th>
                                        <th>Trạng thái</th>
                                        <th>Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($reviews as $review)
                                        <tr>
                                            <td>
                                                @php
                                                    $firstMedia = $review->multimedia->first();
                                                @endphp
                                                @if ($firstMedia && $firstMedia->file_type === 'image')
                                                    <img src="{{ asset('storage/' . $firstMedia->file) }}" width="60"
                                                        alt="Ảnh đánh giá">
                                                @elseif($firstMedia && $firstMedia->file_type === 'video')
                                                    <video width="60" muted>
                                                        <source src="{{ asset('storage/' . $firstMedia->file) }}"
                                                            type="{{ $firstMedia->mime_type }}">
                                                    </video>
                                                @else
                                                    <span>--</span>
                                                @endif
                                            </td>
                                            <td>{{ $review->reviewer_name }}</td>
                                            <td>{{ $review->product->name ?? '---' }}</td>
                                            <td>{{ $review->rating }} ⭐</td>
                                            <td>{{ Str::limit($review->review_text, 60) }}</td>
                                            <td>
                                                @if ($review->is_active === 1)
                                                    <span class="badge bg-success">Đã duyệt</span>
                                                @elseif($review->is_active === 0 && $review->reason)
                                                    <span class="badge bg-danger">Từ chối</span><br>
                                                    <small>Lý do: {{ $review->reason }}</small>
                                                @elseif($review->is_active === 0 && !$review->reason)
                                                    <span class="badge bg-warning text-dark">Chờ duyệt</span>
                                                @else
                                                    <span class="badge bg-secondary">Không xác định</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if (is_null($review->is_active))
                                                    <form action="{{ route('admin.reviews.approve', $review->id) }}"
                                                        method="POST" class="d-inline-block mb-1">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="btn btn-sm btn-success">Duyệt</button>
                                                    </form>

                                                    <form action="{{ route('admin.reviews.reject', $review->id) }}"
                                                        method="POST" class="d-inline-block">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="text" name="reason"
                                                            class="form-control form-control-sm mb-1"
                                                            placeholder="Lý do từ chối" required>
                                                        <button type="submit" class="btn btn-sm btn-danger">Từ
                                                            chối</button>
                                                    </form>
                                                @else
                                                    <span class="text-muted">--</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <!-- Phân trang -->
                            <div class="d-flex justify-content-between align-items-center mt-4">
                                <div class="text-muted">
                                    Hiển thị từ {{ $reviews->firstItem() ?? 0 }} đến {{ $reviews->lastItem() ?? 0 }} trên
                                    tổng số {{ $reviews->total() }} đánh giá
                                </div>
                                <div>
                                    {{ $reviews->appends(request()->query())->links('pagination::bootstrap-4') }}
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="clearfix"></div>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#bootstrap-data').DataTable({
                order: [
                    [1, 'asc']
                ],
                paging: false,
                searching: false
            });
        });
    </script>
@endsection
