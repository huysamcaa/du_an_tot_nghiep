@extends('admin.layouts.app')

@section('content')
<div class="animated fadeIn">
    <h1>Thêm khuyến mãi mới</h1>

    <form action="{{ route('admin.promotions.store') }}" method="post">
        @csrf
        <div class="card-body">


            <div class="mb-3">
                <label for="title" class="form-label">Tiêu đề:</label>
                <input type="text" name="title" class="form-control" id="title" value="{{ old('title') }}" required>
                @error('title') <small class="text-danger">{{ $message }}</small> @enderror
            </div>


            <div class="mb-3">
                <label for="discount_percent" class="form-label">Phần trăm giảm giá:</label>
                <input type="number" name="discount_percent" class="form-control" id="discount_percent" value="{{ old('discount_percent') }}" required>
                @error('discount_percent') <small class="text-danger">{{ $message }}</small> @enderror
            </div>


            <div class="mb-3">
                <label for="code" class="form-label">Mã khuyến mãi (để trống nếu muốn hệ thống tự tạo):</label>
                <input type="text" name="code" class="form-control" placeholder="VD: SUMMER20" value="{{ old('code') }}">
                @error('code') <small class="text-danger">{{ $message }}</small> @enderror
            </div>


            <div class="mb-3">
                <label for="start_date" class="form-label">Ngày bắt đầu:</label>
                <input type="date" name="start_date" class="form-control" value="{{ old('start_date') }}" required>
                @error('start_date') <small class="text-danger">{{ $message }}</small> @enderror
            </div>


            <div class="mb-3">
                <label for="end_date" class="form-label">Ngày kết thúc:</label>
                <input type="date" name="end_date" class="form-control" value="{{ old('end_date') }}" required>
                @error('end_date') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

           
            <button type="submit" class="btn btn-primary">Thêm mã giảm giá</button>
            <a href="{{ route('admin.promotions.index') }}" class="btn btn-warning">Quay lại</a>
        </div>
    </form>
</div>
@endsection
