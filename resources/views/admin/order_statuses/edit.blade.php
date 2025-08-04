@extends('admin.layouts.app')

@section('title', 'Cập nhật trạng thái đơn hàng')

@section('content')
<!-- Breadcrumbs -->
<div class="breadcrumbs">
  <div class="breadcrumbs-inner">
    <div class="row m-0">
      <div class="col-sm-4">
        <div class="page-header float-left">
          <div class="page-title">
            <h1>Cập nhật trạng thái</h1>
          </div>
        </div>
      </div>
      <div class="col-sm-8">
        <div class="page-header float-right">
          <div class="page-title">
            <ol class="breadcrumb text-right">
              <li><a href="{{ route('admin.dashboard') }}">Trang chủ</a></li>
              <li><a href="{{ route('admin.order_statuses.index') }}">Trạng thái đơn hàng</a></li>
              <li class="active">Cập nhật</li>
            </ol>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Content -->
<div class="content">
  <div class="animated fadeIn">
    <div class="card mb-4 shadow-sm">
      <div class="card-header bg-primary text-white">
        <h5 class="mb-0">Cập nhật trạng thái đơn hàng</h5>
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

        <form action="{{ route('admin.order_statuses.update', $status->id) }}" method="POST">
          @csrf
          @method('PUT')

          <div class="row mb-4">
            <div class="col-md-6">
              <label for="name" class="font-weight-bold">Tên trạng thái <span class="text-danger">*</span></label>
              <input type="text" name="name" id="name" class="form-control" value="{{ $status->name }}" required>
            </div>
          </div>

          <div class="row">
            <div class="col-md-12 text-right">
              <button type="submit" class="btn btn-success">
                <i class="fa fa-save"></i> Cập nhật
              </button>
              <a href="{{ route('admin.order_statuses.index') }}" class="btn btn-secondary">
                <i class="fa fa-arrow-left"></i> Quay lại
              </a>
            </div>
          </div>

        </form>
      </div>
    </div>
  </div>
</div>
@endsection
