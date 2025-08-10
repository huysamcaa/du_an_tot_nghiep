@extends('client.layouts.app')

@section('content')
<style>
    .pageBannerSection {
        background:#ECF5F4;
        padding: 10px 0;
    }
    .pageBannerContent h2 {
        
        font-size: 72px;
        color:#52586D;
        font-family: 'Jost', sans-serif;
    }
    .pageBannerPath a {
        color: #007bff;
        text-decoration: none;
    }
    .checkoutPage {
    margin-top: 0 !important;
    padding-top: 0 !important;
}
</style>
<div class="checkoutPage">
    <section class="pageBannerSection">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="pageBannerContent text-center">
                        <h2>Chỉnh sửa thông tin cá nhân</h2>
                        <div class="pageBannerPath">
                            <a href="{{ route('client.home') }}">Trang chủ</a>&nbsp;&nbsp;&gt;&nbsp;&nbsp;<span>Chỉnh sửa thông tin cá nhân</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<div class="container py-5">
    <h4 class="mb-1 fw-bold">Chỉnh sửa thông tin cá nhân</h4>
    <p class="text-muted mb-4">Cập nhật thông tin để bảo vệ tài khoản và nhận ưu đãi phù hợp</p>
<div class="mb-4">
    <a href="{{ route('client.profile.show') }}"
       class="btn btn-warning px-4 rounded-pill">
        <i class="fa-solid fa-arrow-left me-2"></i> Quay lại
    </a>
</div>

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
                    <input type="text" name="email" id="email" class="form-control" value="{{ old('email', $user->email) }}">

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
    <button type="submit" class=" ulinaBTN d-flex align-items-center px-2 py-2 btn-sm">
        <i class="fa-solid fa-save me-2"></i>
        <span>Lưu thay đổi</span>
    </button>
</div>

    </form>
</div>
</div>
@endsection
