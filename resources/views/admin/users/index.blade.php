@extends('admin.layouts.app')

@section('content')
<h1>Danh sách tài khoản</h1>
<a href="{{ route('admin.users.create') }}" class="btn btn-primary mb-3">Thêm tài khoản</a>

<div class="card">
    <div class="card-header">
        <strong class="card-title">Tài khoản người dùng</strong>
    </div>
    <div class="card-body">
        <table class="table table-bordered table-striped" id="users-table">
            <thead>
                <tr>
                    <th>ID</th>
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
                @foreach($users as $key => $user)
                <tr>
                    <td>{{  $users->firstItem() + $key }}</td>
                    <td>
                        <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : asset('assets/images/default.png') }}" width="40" class="rounded-circle">
                    </td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->phone_number }}</td>
                    <td>{{ ucfirst($user->gender) }}</td>
                    <td><span class="badge badge-info">{{ $user->role }}</span></td>
                    <td><span class="badge badge-secondary">{{ ucfirst($user->user_group ?? 'Không xác định') }}</span></td>

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
                            <form action="{{ route('admin.users.lock', $user->id) }}" method="POST">
                                @csrf @method('PATCH')
                                <button class="btn btn-sm btn-secondary" onclick="return confirm('Khóa tài khoản này?')">Khóa</button>
                            </form>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<script>
    // Kích hoạt datatable
    $(document).ready(function () {
        $('#users-table').DataTable({
            order: [[0, 'desc']]
        });
    });
</script>
@endsection
