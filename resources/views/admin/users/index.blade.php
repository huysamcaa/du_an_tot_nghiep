@extends('admin.layouts.app')

@section('title', 'Danh sách tài khoản')

@section('content')
    <div class="content">

        <div class="page-header">
            <div class="page-title">
                <h4>Danh sách tài khoản</h4>
                <h6>Xem / Tìm kiếm / Lọc tài khoản</h6>
            </div>
            <div class="page-btn d-flex" style="gap:10px;">
                @if (Route::has('admin.users.trashed'))
                    <a href="{{ route('admin.users.trashed') }}" class="btn btn-secondary">Tài khoản đã xóa</a>
                @endif
            </div>
        </div>

        {{-- Thông báo --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card">
            <div class="card-body">

                {{-- Form tìm kiếm & lọc (giống style product) --}}
                <form method="GET" action="{{ route('admin.users.index') }}" class="row g-2 align-items-center mb-3">

                    {{-- Tìm kiếm --}}
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control" placeholder="Tìm tên, email, SĐT..."
                            value="{{ request('search') }}">
                    </div>
                    {{-- Trạng thái --}}
                    <div class="col-md-2">
                        <select name="status" class="form-control" onchange="this.form.submit()">
                            <option value="">Tất cả trạng thái</option>
                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Hoạt động</option>
                            <option value="locked" {{ request('status') === 'locked' ? 'selected' : '' }}>Bị khóa</option>
                        </select>
                    </div>

                    {{-- Vai trò --}}
                    <div class="col-md-2">
                        <select name="role" class="form-control" onchange="this.form.submit()">
                            <option value="">Tất cả vai trò</option>
                            <option value="user" {{ request('role') === 'user' ? 'selected' : '' }}>User</option>
                            <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                    </div>
                    {{-- Hàng lọc ngày + nhóm + sắp xếp (gọn) --}}
                    <div class="col-md-2">
                        <select name="sort" class="form-control" onchange="this.form.submit()">
                            <option value="created_desc" {{ request('sort') === 'created_desc' ? 'selected' : '' }}>Mới nhất
                            </option>
                            <option value="created_asc" {{ request('sort') === 'created_asc' ? 'selected' : '' }}>Cũ nhất
                            </option>
                            <option value="name_asc" {{ request('sort') === 'name_asc' ? 'selected' : '' }}>Tên A→Z
                            </option>
                            <option value="name_desc" {{ request('sort') === 'name_desc' ? 'selected' : '' }}>Tên Z→A
                            </option>
                        </select>
                    </div>
                    {{-- Nút --}}
                    <div class="col-md-1 d-flex" style="gap:6px;">
                        <button class="btn btn-primary w-100" type="submit">Tìm</button>
                        @if (request()->hasAny([
                                'search',
                                'status',
                                'role',
                                'gender',
                                'perPage',
                                'created_from',
                                'created_to',
                                'birthday_from',
                                'birthday_to',
                                'sort',
                                'user_group',
                            ]))
                            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary w-100">Xóa</a>
                        @endif
                    </div>
                </form>

                {{-- Bảng tài khoản (style giống product) --}}
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle text-center">
                        <thead class="table-light">
                            <tr>
                                <th>STT</th>
                                <th>Avatar</th>
                                <th>Họ tên</th>
                                <th>Email</th>
                                <th>SĐT</th>
                                <th>Giới tính</th>
                                <th>Vai trò</th>
                                <th>Trạng thái</th>
                                <th>Ngày sinh</th>
                                <th>Ngày tạo</th>
                                <th class="text-center">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($users as $key => $user)
                                <tr>
                                    <td>{{ $users->firstItem() + $key }}</td>
                                    <td>
                                        <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : asset('assets/images/default.png') }}"
                                            width="60" height="60" class="rounded-circle border" alt="Avatar">
                                    </td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->phone_number }}</td>
                                    <td>{{ ucfirst($user->gender) }}</td>
                                    <td><span>{{ ucfirst($user->role) }}</span></td>
                                    <td>
                                        @if ($user->status === 'active')
                                            <span>Hoạt động</span>
                                        @else
                                            <span>Bị khóa</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge {{ $user->status === 'active' ? 'bg-success' : 'bg-danger' }}">
                                            {{ $user->status === 'active' ? 'Hoạt động' : 'Bị khóa' }}
                                        </span>
                                    </td>
                                    <td>{{ optional($user->birthday)->format('d/m/Y') ?? '—' }}</td>
                                    <td>{{ optional($user->created_at)->format('d/m/Y') }}</td>
                                    <td>
                                        <div class="d-flex align-items-center justify-content-center" style="gap:10px;">
                                            @if (Route::has('admin.users.show'))
                                                <a href="{{ route('admin.users.show', $user->id) }}" title="Xem">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                            @endif
                                            @if (Route::has('admin.users.lock') && $user->status === 'active')
                                                <form action="{{ route('admin.users.lock', $user->id) }}" method="POST"
                                                    style="display:inline-block;"
                                                    onsubmit="return confirm('Khóa tài khoản này?')">
                                                    @csrf @method('PATCH')
                                                    <button  type="submit" class="btn btn-link p-0" title="Khóa">
                                                        <i class="fa fa-lock"></i>
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="12" class="text-center text-muted">Không có tài khoản nào.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <form method="GET" action="{{ route('admin.users.index') }}" class="d-flex align-items-center"
                    style="gap: 12px;">
                    <div>
                        <label for="perPage" style="font-weight:600;">Hiển thị:</label>
                        <select name="perPage" id="perPage" class="form-control d-inline-block" style="width:auto;"
                            onchange="this.form.submit()">
                            <option value="10" {{ request('perPage') == '10' ? 'selected' : '' }}>10</option>
                            <option value="25" {{ request('perPage') == '25' ? 'selected' : '' }}>25</option>
                            <option value="50" {{ request('perPage') == '50' ? 'selected' : '' }}>50</option>
                            <option value="100" {{ request('perPage') == '100' ? 'selected' : '' }}>100</option>
                        </select>
                    </div>
                </form>
                {{-- Phân trang --}}
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div class="text-muted">
                        Hiển thị từ {{ $users->firstItem() ?? 0 }} đến {{ $users->lastItem() ?? 0 }} trên tổng số
                        {{ $users->total() }} tài khoản
                    </div>
                    <div>
                        {!! $users->appends(request()->query())->onEachSide(1)->links('pagination::bootstrap-4') !!}
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
