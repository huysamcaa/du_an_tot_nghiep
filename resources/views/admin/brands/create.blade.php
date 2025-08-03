@extends('admin.layouts.app')

@section('content')
{{-- Breadcrumbs --}}
<div class="breadcrumbs mb-4">
    <div class="breadcrumbs-inner">
        <div class="row m-0">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1>Thêm thương hiệu</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
                            <li><a href="{{ route('admin.dashboard') }}">Trang chủ</a></li>
                            <li><a href="{{ route('admin.brands.index') }}">Thương hiệu</a></li>
                            <li class="active">Thêm mới</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Nội dung --}}
<div class="content">
    <div class="animated fadeIn">
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Thêm thương hiệu mới</h5>
            </div>
            <div class="card-body">

                {{-- Hiển thị lỗi --}}
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $e)
                                <li>{{ $e }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Form --}}
                <form action="{{ route('admin.brands.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    {{-- Dòng 1: Tên, Slug --}}
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="font-weight-bold">Tên thương hiệu <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="font-weight-bold">Slug</label>
                            <input type="text" name="slug" class="form-control" value="{{ old('slug') }}">
                        </div>
                    </div>

                    {{-- Dòng 2: Logo, Trạng thái --}}
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="font-weight-bold">Logo</label>
                            <input type="file" name="logo" id="logoInput" class="form-control" accept="image/*">
                            <div class="mt-3" id="logoPreviewArea" style="display:none;">
                                <img id="logoPreview" src="" alt="Preview" class="img-thumbnail" style="max-width: 200px;">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="font-weight-bold">Trạng thái</label>
                            <select name="is_active" class="form-control">
                                <option value="1" {{ old('is_active', 1) == 1 ? 'selected' : '' }}>Hiển thị</option>
                                <option value="0" {{ old('is_active') == 0 ? 'selected' : '' }}>Ẩn</option>
                            </select>
                        </div>
                    </div>

                    {{-- Nút hành động --}}
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="fa fa-save"></i> Lưu
                            </button>
                            <a href="{{ route('admin.brands.index') }}" class="btn btn-secondary">
                                <i class="fa fa-arrow-left"></i> Quay lại
                            </a>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

{{-- Preview ảnh --}}
@push('scripts')
<script>
    $(document).ready(function () {
        $('#logoInput').on('change', function () {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    $('#logoPreview').attr('src', e.target.result);
                    $('#logoPreviewArea').show();
                };
                reader.readAsDataURL(this.files[0]);
            } else {
                $('#logoPreviewArea').hide();
                $('#logoPreview').attr('src', '');
            }
        });
    });
</script>
@endpush

@endsection
