@extends('admin.layouts.app')

@section('content')

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const parentRadio = document.getElementById('type_parent');
        const childRadio = document.getElementById('type_child');
        const parentSelect = document.getElementById('parent_id');

        parentRadio.onclick = function() { parentSelect.disabled = true; parentSelect.value = ""; }
        childRadio.onclick = function() { parentSelect.disabled = false; }

        // Trạng thái khởi tạo
        if (parentRadio.checked) {
            parentSelect.disabled = true;
            parentSelect.value = "";
        }
    });
</script>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Thêm Danh Mục Sản Phẩm</h5>
            </div>
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul style="margin-bottom: 0;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.categories.store') }}" method="POST">
                    @csrf

                    <h5 class="card-title">Thông Tin Danh Mục</h5>
                    <div class="row">
                        {{-- Tên danh mục --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tên danh mục *</label>
                                <input type="text" name="name" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label>Thứ tự hiển thị *</label>
                                <input type="number" name="ordinal" class="form-control" required value="0">
                            </div>

                            <div class="form-group">
                                <label>Trạng thái *</label>
                                <select name="is_active" class="form-control" required>
                                    <option value="1">Hiển thị</option>
                                    <option value="0">Ẩn</option>
                                </select>
                            </div>
                        </div>

                        {{-- Loại danh mục + Danh mục cha --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Loại danh mục</label><br>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="type" value="parent" id="type_parent" checked>
                                    <label class="form-check-label" for="type_parent">Danh mục cha</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="type" value="child" id="type_child">
                                    <label class="form-check-label" for="type_child">Danh mục con</label>
                                </div>
                            </div>

                            <div class="form-group mt-3">
                                <label>Danh mục cha</label>
                                <select name="parent_id" id="parent_id" class="form-control">
                                    <option value="">-- Không có --</option>
                                    @foreach ($parentCategories as $parent)
                                        <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Icon</label>
                                <input type="text" name="icon" class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="text-end mt-4">
                        <button type="submit" class="btn btn-primary">Lưu</button>
                        <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Hủy</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
