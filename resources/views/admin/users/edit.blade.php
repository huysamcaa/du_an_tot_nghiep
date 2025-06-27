@extends('admin.layouts.app')

@section('content')
<div class="container">
    <h2>Sửa tài khoản người dùng</h2>
    <form action="{{ route('admin.users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')

        <div class="form-group">
            <label>Họ tên</label>
            <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
        </div>

        <div class="form-group">
            <label>Số điện thoại</label>
            <input type="text" name="phone_number" class="form-control" value="{{ $user->phone_number }}">
        </div>

        <div class="form-group">
            <label>Ảnh đại diện</label><br>
            @if($user->avatar)
                <img src="{{ asset('storage/' . $user->avatar) }}" width="100"><br>
            @endif
            <input type="file" name="avatar" class="form-control-file">
        </div>

        <div class="form-group">
            <label>Giới tính</label>
            <select name="gender" class="form-control">
                <option value="">-- Chọn --</option>
                <option value="male" {{ $user->gender == 'male' ? 'selected' : '' }}>Nam</option>
                <option value="female" {{ $user->gender == 'female' ? 'selected' : '' }}>Nữ</option>
            </select>
        </div>

        <div class="form-group">
            <label>Ngày sinh</label>
            <input type="date" name="birthday" class="form-control" value="{{ $user->birthday }}">
        </div>

        <div class="form-group">
            <label>Vai trò</label>
            <select name="role" class="form-control" required>
                <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>User</option>
                <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success">Cập nhật</button>
    </form>
</div>
@endsection
