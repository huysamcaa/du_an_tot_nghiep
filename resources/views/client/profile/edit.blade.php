@extends('client.layouts.app')

@section('content')
<div class="container py-5">
    <h2 class="mb-4 text-center">Chỉnh sửa thông tin cá nhân</h2>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('client.profile.update') }}" enctype="multipart/form-data" class="card p-4 shadow-sm">
        @csrf

        <div class="mb-3">
            <label for="name" class="form-label">Họ tên</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $user->name) }}">
            @error('name') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label for="phone_number" class="form-label">Số điện thoại</label>
            <input type="text" name="phone_number" id="phone_number" class="form-control" value="{{ old('phone_number', $user->phone_number) }}">
        </div>

        <div class="mb-3">
            <label for="gender" class="form-label">Giới tính</label>
            <select name="gender" id="gender" class="form-select">
                <option value="">--Chọn--</option>
                <option value="male" {{ $user->gender == 'male' ? 'selected' : '' }}>Nam</option>
                <option value="female" {{ $user->gender == 'female' ? 'selected' : '' }}>Nữ</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="birthday" class="form-label">Ngày sinh</label>
            <input type="date" name="birthday" id="birthday" class="form-control" value="{{ old('birthday', $user->birthday?->format('Y-m-d')) }}">
        </div>

        <div class="mb-3">
            <label class="form-label d-block">Ảnh đại diện</label>
            @if($user->avatar)
                <div class="mb-2">
                    <img src="{{ asset('storage/' . $user->avatar) }}" width="100" class="rounded">
                </div>
            @endif
            <input type="file" name="avatar" class="form-control">
        </div>
<div class="d-flex justify-content-between mt-4">
    <a href="{{ route('client.profile.show') }}" class="btn btn-secondary btn-sm px-3" style="width: fit-content;">
        ← Quay lại
    </a>

    <button type="submit" class="btn btn-primary btn-sm px-3" style="width: fit-content;">
        Cập nhật
    </button>
</div>

</div>

    </form>
</div>
@endsection
