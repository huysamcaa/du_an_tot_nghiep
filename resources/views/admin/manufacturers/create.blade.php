@extends('admin.layouts.app')

@section('content')
    <h1>Thêm mới nhà sản xuất</h1>

     @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.manufacturers.store')}}" method="POST">
        @csrf
        <div class="form-group">
            <label for="name">Tên nhà sản xuất</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" placeholder="Nhập tên nhà sản xuất">
            @error('name')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="form-group">
            <label for="address">Địa chỉ</label>
            <input type="text" name="address" id="address" class="form-control" value="{{ old('address') }}" placeholder="Nhập địa chỉ">
            @error('address')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="form-group">
            <label for="phone">Số điện thoại</label>
            <input type="tel" name="phone" id="phone" class="form-control" value="{{ old('phone') }}" placeholder="Nhập số điện thoại" pattern="[0-9]{10}">
            @error('phone')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="form-group">
            <label for="is_active">Trạng thái</label>
            <select name="is_active" id="is_active" class="form-control">
                <option value="1" {{ old('is_active') == '1' ? 'selected' : '' }}>Hoạt động</option>
                <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>Không hoạt động</option>
            </select>
            @error('is_active')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <button type="submit" class="btn btn-success">Thêm</button>
        <a href="{{route('admin.manufacturers.index')}}" class="btn btn-info">Quay lại</a>
        </form>
@endsection
