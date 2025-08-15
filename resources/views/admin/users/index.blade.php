@extends('admin.layouts.app')

@section('title', 'Danh sách tài khoản')

@section('content')

<div class="breadcrumbs">
    <div class="breadcrumbs-inner">
        <div class="row m-0">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1>Danh sách tài khoản</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
                            <li><a href="{{ route('admin.dashboard') }}">Trang chủ</a></li>
                            <li class="active">Tài khoản</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="col-md-12">

        {{-- Flash messages --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Đóng">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Đóng">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        {{-- Nút thêm tài khoản --}}
        <div class="mb-3 d-flex justify-content-start align-items-center">
            <a href="{{ route('admin.users.create') }}" class="btn btn-success">
                <i class="fa fa-plus mr-1"></i> Thêm tài khoản
            </a>
        </div>

        <div class="card">
            <div class="card-header">
                <strong class="card-title">Danh sách tài khoản</strong>
            </div>

            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center mb-3">
                    {{-- Form chọn số dòng hiển thị --}}
                    <form method="GET" action="{{ route('admin.users.index') }}" class="d-flex align-items-center" style="gap: 12px;">
                        <div>
                            <label for="perPage" style="font-weight:600;">Hiển thị:</label>
                            <select name="perPage" id="perPage" class="form-control d-inline-block" style="width:auto;" onchange="this.form.submit()">
                                <option value="10" {{ request('perPage') == '10' ? 'selected' : '' }}>10</option>
                                <option value="25" {{ request('perPage') == '25' ? 'selected' : '' }}>25</option>
                                <option value="50" {{ request('perPage') == '50' ? 'selected' : '' }}>50</option>
                                <option value="100" {{ request('perPage') == '100' ? 'selected' : '' }}>100</option>
                            </select>
                        </div>
                    </form>

                    {{-- Form tìm kiếm --}}
                    <form method="GET" action="{{ route('admin.users.index') }}" class="w-50">
                        <div class="d-flex">
                            <input type="text" name="search" class="form-control" placeholder="Tìm tên, email, số điện thoại..." value="{{ request('search') }}">
                            <button class="btn btn-primary ml-1" type="submit">Tìm</button>
                            @if (request('search'))
                                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary ml-1">Xóa</a>
                            @endif
                        </div>
                    </form>
                </div>

                <table id="users-table" class="table table-striped table-bordered text-center align-middle">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Avatar</th>
                            <th>Họ tên</th>
                            <th>Email</th>
                            <th>SĐT</th>
                            <th>Giới tính</th>
                            <th>Vai trò</th>
                            <th>Nhóm</th>
                            <th>Trạng thái</th>
                            <th>Ngày sinh</th>
                            <th>Ngày tạo</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $key => $user)
                            <tr>
                                <td>{{ $users->firstItem() + $key }}</td>
                                <td>
                                    <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : asset('assets/images/default.png') }}"
                                         alt="Avatar" width="40" height="40" class="rounded-circle border">
                                </td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->phone_number }}</td>
                                <td>{{ ucfirst($user->gender) }}</td>
                                <td><span class="badge badge-info">{{ ucfirst($user->role) }}</span></td>
                                <td><span class="badge badge-secondary">{{ ucfirst($user->user_group ?? 'Không rõ') }}</span></td>
                                <td>
                                    @if($user->status === 'active')
                                        <span class="badge badge-success">Hoạt động</span>
                                    @else
                                        <span class="badge badge-danger">Bị khóa</span>
                                    @endif
                                </td>
                                <td>{{ optional($user->birthday)->format('d/m/Y') }}</td>
                                <td>{{ $user->created_at->format('d/m/Y') }}</td>
                                <td>
                                    @if($user->status === 'active')
                                        <form action="{{ route('admin.users.lock', $user->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button class="btn btn-sm btn-warning" onclick="return confirm('Khóa tài khoản này?')">
                                                <i class="fa fa-lock"></i>
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="12" class="text-center text-muted py-4">Không có tài khoản nào.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div class="text-muted">
                        Hiển thị từ {{ $users->firstItem() ?? 0 }} đến {{ $users->lastItem() ?? 0 }} trên tổng số {{ $users->total() }} tài khoản
                    </div>
                    <div>
                        {!! $users->appends(request()->query())->onEachSide(1)->links('pagination::bootstrap-4') !!}
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>

@endsection

@section('scripts')
    {{-- DataTables CSS --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">

    {{-- jQuery and DataTables JS --}}
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#users-table').DataTable({
                "order": [[ 10, "desc" ]],  // Sắp xếp theo cột ngày tạo giảm dần
                "paging": false,            // Tắt phân trang DataTables, dùng phân trang Laravel
                "searching": false,         // Tắt tìm kiếm DataTables, dùng form tìm kiếm bên trên
                "info": false,              // Tắt thông tin DataTables
                "columnDefs": [
                    { "orderable": false, "targets": [1, 11] } // Không cho sắp xếp cột Avatar và Hành động
                ],
                "language": {
                    "emptyTable": "Không có tài khoản nào trong bảng",
                    "zeroRecords": "Không tìm thấy tài khoản nào phù hợp"
                }
            });
        });
    </script>
@endsection
