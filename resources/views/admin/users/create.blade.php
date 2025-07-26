@extends('admin.layouts.app')

@section('content')
    <div class="container">
        <h2>Thêm tài khoản người dùng</h2>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label>Họ tên</label>
                <input type="text" name="name" class="form-control" required>
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>

            <div class="form-group">
                <label>Mật khẩu</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <div class="form-group">
                <label>Số điện thoại</label>
                <input type="text" name="phone_number" class="form-control">
            </div>

            <div class="form-group">
                <label>Avatar</label>
                <input type="file" name="avatar" class="form-control-file">
            </div>

            <div class="form-group">
                <label>Giới tính</label>
                <select name="gender" class="form-control">
                    <option value="">-- Chọn --</option>
                    <option value="male">Nam</option>
                    <option value="female">Nữ</option>
                </select>
            </div>

            <div class="form-group">
                <label>Ngày sinh</label>
                <input type="date" name="birthday" class="form-control">
            </div>

            <div class="form-group">
                <label>Vai trò</label>
                <select name="role" class="form-control" required>
                    <option value="user">User</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <div class="form-group">
                <label for="user_group">Nhóm người dùng</label>
                <select name="user_group" id="user_group" class="form-control">
                    <option value="member" selected>Thành viên</option>
                    <option value="vip">VIP</option>
                    <option value="guest">Khách</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Thêm mới</button>
        </form>
    </div>
@endsection
