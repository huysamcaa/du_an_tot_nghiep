@extends('admin.layouts.app')

@section('title', 'Danh sách tài khoản')

@section('content')


<div class="content">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h4 class="mb-0"> Quản lý tài khoản</h4>
                <small class="text-muted">Danh sách tài khoản</small>
            </div>


        </div>
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
<div class="card">
          <div class="card-body">
<form method="GET" action="{{ route('admin.users.index') }}" class="row g-2 align-items-end mb-3">

  <div class="col-md-3">
    <label class="form-label mb-1">Tìm kiếm</label>
    <input type="text" name="search" class="form-control" placeholder="Tên / email / SĐT" value="{{ request('search') }}">
  </div>

  <div class="col-md-2">
    <label class="form-label mb-1">Trạng thái</label>
    <select name="status" class="form-control">
      <option value="">-- Tất cả --</option>
      <option value="active" {{ request('status')==='active' ? 'selected' : '' }}>Hoạt động</option>
      <option value="locked" {{ request('status')==='locked' ? 'selected' : '' }}>Bị khóa</option>
    </select>
  </div>

  <div class="col-md-2">
    <label class="form-label mb-1">Vai trò</label>
    <select name="role" class="form-control">
      <option value="">-- Tất cả --</option>
      <option value="user"  {{ request('role')==='user'  ? 'selected' : '' }}>User</option>
      <option value="admin" {{ request('role')==='admin' ? 'selected' : '' }}>Admin</option>
    </select>
  </div>

  <div class="col-md-2">
    <label class="form-label mb-1">Sắp xếp</label>
    <select name="sort" class="form-control">
      <option value="created_desc" {{ request('sort')==='created_desc' ? 'selected' : '' }}>Mới nhất</option>
      <option value="created_asc"  {{ request('sort')==='created_asc'  ? 'selected' : '' }}>Cũ nhất</option>
      <option value="name_asc"     {{ request('sort')==='name_asc'     ? 'selected' : '' }}>Tên A→Z</option>
      <option value="name_desc"    {{ request('sort')==='name_desc'    ? 'selected' : '' }}>Tên Z→A</option>
    </select>
  </div>

  <div class="col-md-2 d-flex" style="gap:8px;">
    <button class="btn btn-primary w-100" type="submit">Lọc</button>
    <a class="btn btn-outline-secondary w-100" href="{{ route('admin.users.index') }}">Xóa Lọc</a>
  </div>
</form>
  </div>
</div>
        <div class="card">

            <div class="card-body">


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
                                <td><span>{{ ucfirst($user->role) }}</span></td>
                                <td>
                                    @if($user->status === 'active')
                                        <span >Hoạt động</span>
                                    @else
                                        <span >Bị khóa</span>
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
                </table><br>
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
