@extends('admin.layouts.app')

@section('content')
    <h1>Cập nhật nhà sản xuất</h1>

     @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.manufacturers.update', $manufacturer)}}" method="POST">
        @csrf
        @method('PUT') <!-- Thêm dòng này để giả lập phương thức PUT -->
        <div class="form-group">
            <label for="name">Tên nhà sản xuất</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $manufacturer->name) }}" placeholder="Nhập tên nhà sản xuất">
            @error('name')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="form-group">
            <label for="address">Địa chỉ</label>
            <input type="text" name="address" id="address" class="form-control" value="{{ old('address', $manufacturer->address) }}" placeholder="Nhập địa chỉ">
            @error('address')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="form-group">
            <label for="phone">Số điện thoại</label>
            <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone', $manufacturer->phone) }}" placeholder="Nhập số điện thoại">
            @error('phone')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="form-group">
            <label for="is_active">Trạng thái</label>
            <select name="is_active" id="is_active" class="form-control">
                <option value="1" {{ old('is_active', $manufacturer->is_active) == '1' ? 'selected' : '' }}>Hoạt động</option>
                <option value="0" {{ old('is_active', $manufacturer->is_active) == '0' ? 'selected' : '' }}>Không hoạt động</option>
            </select>
            @error('is_active')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <button type="submit" class="btn btn-success">Cập nhật</button>
        <a href="{{route('admin.manufacturers.index')}}" class="btn btn-info">Quay lại</a>
        </form>
@endsection

