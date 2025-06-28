@extends('client.layouts.app')

@section('content')
<div class="container py-5">
    <h2 class="mb-4 text-center">Thông tin cá nhân</h2>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
<div class="text-end mt-2">
    <a href="{{ route('client.profile.edit') }}" class="btn btn-primary btn-sm  mb-3">
        Chỉnh sửa thông tin
    </a>
</div>


    <div class="card p-4 shadow-sm">
        <div class="mb-3">
            <label for="name" class="form-label">Họ tên</label>
            <input type="text" class="form-control" value="{{ $user->name }}" disabled>
        </div>

        <div class="mb-3">
            <label for="phone_number" class="form-label">Số điện thoại</label>
            <input type="text" class="form-control" value="{{ $user->phone_number }}" disabled>
        </div>

        <div class="mb-3">
            <label for="gender" class="form-label">Giới tính</label>
            <input type="text" class="form-control" value="{{ $user->gender }}" disabled>
        </div>

        <div class="mb-3">
            <label for="birthday" class="form-label">Ngày sinh</label>
            <input type="text" class="form-control" value="{{ $user->birthday ? $user->birthday->format('d/m/Y') : '' }}" disabled>
        </div>

        <div class="mb-3">
            <label for="avatar" class="form-label">Ảnh đại diện</label><br>
            @if($user->avatar)
                <img src="{{ asset('storage/' . $user->avatar) }}" width="100" class="rounded">
            @else
                <span>Chưa có ảnh đại diện</span>
            @endif
        </div>


    </div>
</div>
@endsection
