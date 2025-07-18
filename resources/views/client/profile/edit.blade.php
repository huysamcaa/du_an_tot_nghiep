@extends('client.layouts.app')

@section('content')
<div class="checkoutPage">
<div class="container py-5">
    <h4 class="mb-1 fw-bold">Chỉnh sửa thông tin cá nhân</h4>
    <p class="text-muted mb-4">Cập nhật thông tin để bảo vệ tài khoản và nhận ưu đãi phù hợp</p>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('client.profile.update') }}" enctype="multipart/form-data" class="card shadow-sm p-4 border-0">
        @csrf
        <div class="row">
            <!-- Form trái -->
            <div class="col-md-8">
                <div class="mb-3">
                    <label for="name" class="form-label">Họ tên <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $user->name) }}">
                    @error('name') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                    <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $user->email) }}">
                    @error('email') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="phone_number" class="form-label">Số điện thoại</label>
                    <input type="text" name="phone_number" id="phone_number" class="form-control" value="{{ old('phone_number', $user->phone_number) }}">
                    @error('phone_number') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="gender" class="form-label">Giới tính</label>
                    <select name="gender" id="gender" class="form-select">
                        <option value="">-- Chọn giới tính --</option>
                        <option value="male" {{ old('gender', $user->gender) == 'male' ? 'selected' : '' }}>Nam</option>
                        <option value="female" {{ old('gender', $user->gender) == 'female' ? 'selected' : '' }}>Nữ</option>
                    </select>
                    @error('gender') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="birthday" class="form-label">Ngày sinh</label>
                    <input type="date" name="birthday" id="birthday" class="form-control" value="{{ old('birthday', optional($user->birthday)->format('Y-m-d')) }}">
                    @error('birthday') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>
            </div>

            <!-- Form phải: Avatar -->
            <div class="col-md-4 text-center">
                <label class="form-label">Ảnh đại diện</label>
                <div class="mb-3">
                    @if($user->avatar)
                        <img src="{{ asset('storage/' . $user->avatar) }}" class="rounded-circle border shadow-sm" style="width: 120px; height: 120px; object-fit: cover;">
                    @else
                        <div class="bg-light border rounded-circle d-flex align-items-center justify-content-center text-muted mb-2" style="width: 120px; height: 120px;">
                            <small>Chưa có ảnh</small>
                        </div>
                    @endif
                </div>
                <input type="file" name="avatar" class="form-control">
                <small class="text-muted d-block mt-1">Chấp nhận JPG, PNG (≤ 2MB)</small>
                @error('avatar') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="text-end mt-4">
            <a href="{{ route('client.profile.show') }}" class="btn btn-outline-secondary px-4 me-2">
                Quay lại
            </a>
            <button type="submit" class="btn btn-primary px-4">
                <i class="fa-solid fa-save me-1"></i> Lưu thay đổi
            </button>
        </div>
    </form>
</div>
</div>
@endsection
