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
                    <th>Trạng thái</th>
                    <th>Ngày sinh</th>
                    <th>Ngày tạo</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>
                        @if($user->avatar)
                            <img src="{{ asset('storage/' . $user->avatar) }}" width="40" class="rounded-circle">
                        @else
                            <span>--</span>
                        @endif
                    </td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->phone_number }}</td>
                    <td>{{ ucfirst($user->gender) }}</td>
                    <td><span class="badge badge-info">{{ $user->role }}</span></td>
                    <td>
                        @if($user->status === 'active')
                            <span class="badge badge-success">Hoạt động</span>
                        @else
                            <span class="badge badge-danger">Bị khóa</span>
                        @endif
                    </td>
                    <td>{{ optional($user->birthday)->format('d/m/Y') }}</td>
                    <td>{{ $user->created_at->format('d/m/Y') }}</td>
                  <td class="d-flex" style="gap: 5px;">
    <td class="d-flex" style="gap: 5px;">
    {{-- Nút Sửa --}}
    <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-warning" title="Sửa tài khoản">
        <i class="fa fa-pencil"></i>
    </a>

    {{-- Nút Khóa hoặc Mở khóa --}}
    @if($user->status === 'active')
        {{-- Nút Khóa --}}
        <form action="{{ route('admin.users.lock', $user->id) }}" method="POST" style="display: inline;">
            @csrf @method('PATCH')
            <button type="submit" class="btn btn-sm btn-secondary" title="Khóa tài khoản" onclick="return confirm('Khóa tài khoản này?')">
                <i class="fa fa-lock"></i>
            </button>
        </form>
    @else
        {{-- Nút Mở khóa --}}
        <form action="{{ route('admin.users.unlock', $user->id) }}" method="POST" style="display: inline;">
            @csrf @method('PATCH')
            <button type="submit" class="btn btn-sm btn-success" title="Mở khóa tài khoản" onclick="return confirm('Mở khóa tài khoản này?')">
                <i class="fa fa-unlock"></i>
            </button>
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
