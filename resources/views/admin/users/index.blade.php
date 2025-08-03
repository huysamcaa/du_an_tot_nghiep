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
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-md-12">
                {{-- Nút thêm tài khoản --}}
                <div class="mb-3 d-flex justify-content-between align-items-center">
                    <a href="{{ route('admin.users.create') }}" class="btn btn-outline-primary">
                        <i class="fa fa-plus mr-1"></i> Thêm tài khoản
                    </a>
                </div>

                {{-- Flash messages --}}
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                    </div>
                @endif

                <div class="card">
                    <div class="card-header">
                        <strong class="card-title">Danh sách tài khoản</strong>
                    </div>
                    <div class="card-body">
                        <table  id="bootstrap-data-table" class="table table-striped table-bordered text-center">
                            <thead class="thead-light">
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
                                @forelse($users as $key => $user)
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
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
