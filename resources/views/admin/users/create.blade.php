@extends('admin.layouts.app')

@section('content')
<div class="breadcrumbs">
    <div class="breadcrumbs-inner">
        <div class="row m-0">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1>Thêm người dùng</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
                            <li><a href="{{ route('admin.dashboard') }}">Trang chủ</a></li>
                            <li><a href="{{ route('admin.users.index') }}">Người dùng</a></li>
                            <li class="active">Thêm mới</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="animated fadeIn">
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Thêm tài khoản người dùng</h5>
            </div>
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="font-weight-bold">Họ tên <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" value="{{ old('name') }}" >
                        </div>
                        <div class="col-md-6">
                            <label class="font-weight-bold">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control" value="{{ old('email') }}" >
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="font-weight-bold">Mật khẩu <span class="text-danger">*</span></label>
                            <input type="password" name="password" class="form-control" >
                        </div>
                        <div class="col-md-6">
                            <label class="font-weight-bold">Số điện thoại</label>
                            <input type="text" name="phone_number" class="form-control" value="{{ old('phone_number') }}">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="font-weight-bold">Avatar</label>
                            <input type="file" name="avatar" id="avatarInput" class="form-control" accept="image/*">
                            <div class="mt-2" id="avatarPreviewArea" style="display:none;">
                                <img id="avatarPreview" src="" alt="Avatar Preview" class="img-thumbnail" style="max-width: 150px;">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="font-weight-bold">Giới tính</label>
                            <select name="gender" class="form-control">
                                <option value="">-- Chọn --</option>
                                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Nam</option>
                                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Nữ</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="font-weight-bold">Ngày sinh</label>
                            <input type="date" name="birthday" class="form-control" value="{{ old('birthday') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="font-weight-bold">Vai trò <span class="text-danger">*</span></label>
                            <select name="role" class="form-control" >
                                <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>User</option>
                                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="font-weight-bold">Nhóm người dùng</label>
                            <select name="user_group" class="form-control">
                                <option value="member" {{ old('user_group') == 'member' ? 'selected' : '' }}>Thành viên</option>
                                <option value="vip" {{ old('user_group') == 'vip' ? 'selected' : '' }}>VIP</option>
                                <option value="guest" {{ old('user_group') == 'guest' ? 'selected' : '' }}>Khách</option>
                            </select>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-plus"></i> Thêm mới
                        </button>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                            <i class="fa fa-arrow-left"></i> Quay lại
                        </a>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.getElementById('avatarInput').addEventListener('change', function (e) {
        const input = this;
        const preview = document.getElementById('avatarPreview');
        const previewArea = document.getElementById('avatarPreviewArea');

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function (e) {
                preview.src = e.target.result;
                previewArea.style.display = 'block';
            };
            reader.readAsDataURL(input.files[0]);
        } else {
            preview.src = '';
            previewArea.style.display = 'none';
        }
    });
</script>
@endpush
@endsection
