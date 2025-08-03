@extends('admin.layouts.app')

@section('content')
<div class="breadcrumbs">
  <div class="breadcrumbs-inner">
    <div class="row m-0">
      <div class="col-sm-4">
        <div class="page-header float-left">
          <div class="page-title">
            <h1>Thêm danh mục</h1>
          </div>
        </div>
      </div>
      <div class="col-sm-8">
        <div class="page-header float-right">
          <div class="page-title">
            <ol class="breadcrumb text-right">
              <li><a href="{{ route('admin.dashboard') }}">Trang chủ</a></li>
              <li><a href="{{ route('admin.categories.index') }}">Danh mục</a></li>
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

    {{-- Form thêm danh mục --}}
    <div class="card mb-4 shadow-sm">
      <div class="card-header bg-primary text-white">
        <h5 class="mb-0">Thêm danh mục mới</h5>
      </div>
      <div class="card-body">
        @if($errors->any())
          <div class="alert alert-danger">
            <ul class="mb-0">
              @foreach($errors->all() as $e)
                <li>{{ $e }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <form action="{{ route('admin.categories.store') }}" method="POST">
          @csrf

          <div class="row mb-3">
            <div class="col-md-6">
              <label class="font-weight-bold d-block">Loại danh mục</label>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" id="type_parent" name="type" value="parent" checked>
                <label class="form-check-label" for="type_parent">Cha</label>
              </div>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" id="type_child" name="type" value="child">
                <label class="form-check-label" for="type_child">Con</label>
              </div>
            </div>
            <div class="col-md-6">
              <label for="parent_id" class="font-weight-bold">Chọn danh mục cha</label>
              <select name="parent_id" id="parent_id" class="form-control">
                <option value="">-- Không chọn --</option>
                @foreach($parentCategories as $p)
                  <option value="{{ $p->id }}">{{ $p->name }}</option>
                @endforeach
              </select>
            </div>
          </div>

          <div class="row mb-3">
            <div class="col-md-6">
              <label for="name" class="font-weight-bold">Tên danh mục <span class="text-danger">*</span></label>
              <input type="text" name="name" id="name" class="form-control" required>
            </div>
            <div class="col-md-6">
              <label for="slug" class="font-weight-bold">Slug</label>
              <input type="text" name="slug" id="slug" class="form-control">
            </div>
          </div>

          <div class="row mb-3">
            <div class="col-md-6">
              <label for="icon" class="font-weight-bold">Icon</label>
              <input type="text" name="icon" id="icon" class="form-control" placeholder="<i class='fa fa-icon'></i>">
            </div>
            <div class="col-md-6">
              <label for="ordinal" class="font-weight-bold">Thứ tự hiển thị <span class="text-danger">*</span></label>
              <input type="number" name="ordinal" id="ordinal" class="form-control" value="0" required>
            </div>
          </div>

          <div class="row mb-4">
            <div class="col-md-6">
              <label for="is_active" class="font-weight-bold">Trạng thái <span class="text-danger">*</span></label>
              <select name="is_active" id="is_active" class="form-control">
                <option value="1">Hiển thị</option>
                <option value="0">Ẩn</option>
              </select>
            </div>
            <div class="col-md-6 text-right align-self-end">
              <button type="submit" class="btn btn-primary">
                <i class="fa fa-save"></i> Lưu
              </button>
              <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
                <i class="fa fa-arrow-left"></i> Quay lại
              </a>
            </div>
          </div>
        </form>
      </div>
    </div>

  </div>
</div>

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const parentRadio = document.getElementById('type_parent');
    const childRadio  = document.getElementById('type_child');
    const parentSel   = document.getElementById('parent_id');

    function toggleParent() {
      parentSel.disabled = parentRadio.checked;
      if (parentRadio.checked) parentSel.value = "";
    }

    parentRadio.addEventListener('change', toggleParent);
    childRadio.addEventListener('change', toggleParent);
    toggleParent();
  });
</script>
@endpush
@endsection
