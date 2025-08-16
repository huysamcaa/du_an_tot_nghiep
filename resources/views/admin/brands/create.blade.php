@extends('admin.layouts.app')

@section('content')
    {{-- Thông báo session --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    <div class="content">
         <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h4 class="mb-0">Thêm Thương Hiệu</h4>
                <small class="text-muted">Tạo mới thương hiệu</small>
            </div>

        </div>

        <form action="{{ route('admin.brands.store') }}" method="POST" enctype="multipart/form-data"
            class="card p-4 shadow-sm">
            @csrf

            <div class="form-group mb-3">
                <label for="name">Tên thương hiệu <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}">
                @error('name')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="form-group mb-3">
                <label for="slug">Slug</label>
                <input type="text" name="slug" class="form-control" value="{{ old('slug') }}">
                @error('slug')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>







           {{-- Logo (drag & drop) --}}
<div class="form-group mb-3">
    <label class="mb-2" for="logoInput">Logo Thương Hiệu</label>

    {{-- input thật (ẩn) --}}
    <input id="logoInput" type="file" name="logo" accept="image/*" class="d-none">

    {{-- vùng kéo–thả/nhấp để chọn --}}
    <div id="logoDropzone"
         style="border:1.5px dashed #ffb200; border-radius:10px; background:#fffdf7;
                padding:40px 16px; text-align:center; cursor:pointer; position:relative;">
        <div id="logoEmpty">
            <i class="fa fa-cloud-upload" aria-hidden="true"
               style="font-size:36px; color:#ffa200;"></i>
            <div class="mt-2" style="color:#334155;">Kéo và thả tệp để tải lên</div>
            <small style="color:#64748b;">(hoặc nhấp để chọn ảnh — PNG, JPG, WEBP)</small>
        </div>

        {{-- xem trước --}}
        <div id="logoPreview" class="d-none">
            <img id="logoImg" src="" alt="Xem trước"
                 style="max-height:160px; border-radius:10px; box-shadow:0 1px 6px rgba(0,0,0,.08);">
            <div class="mt-2 d-flex justify-content-center align-items-center" style="gap:10px;">
                <small id="logoName" class="text-muted"></small>
                <button id="logoClear" type="button" class="btn btn-sm btn-outline-secondary">
                    Xóa chọn
                </button>
            </div>
        </div>
    </div>

    {{-- lỗi validate --}}
    @error('logo')
        <small class="text-danger d-block mt-1">{{ $message }}</small>
    @enderror
</div>
            <div class="form-check mb-4">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" class="form-check-input" id="is_active" value="1"
                    {{ old('is_active', 1) ? 'checked' : '' }}>
                <label class="form-check-label" for="is_active">Hiển thị</label>
                @error('is_active')
                    <small class="text-danger">{{ $message }}</small>
                @enderror

            </div>





            <div class="mt-3">
                <button type="submit" class="btn btn-success">Lưu</button>
                <a href="{{ route('admin.brands.index') }}" class="btn btn-warning">Quay lại</a>

        </form>
    </div>
    </div>

    </div>
    </div>
    </div>
<script>
document.addEventListener("DOMContentLoaded", function() {
  const dropzone = document.getElementById('logoDropzone');
  const input    = document.getElementById('logoInput');

  // phần tử UI đang có trong HTML
  const emptyBox = document.getElementById('logoEmpty');
  const preview  = document.getElementById('logoPreview');
  const imgEl    = document.getElementById('logoImg');
  const nameEl   = document.getElementById('logoName');
  const clearBtn = document.getElementById('logoClear');

  let objectUrl; // để revoke khi đổi ảnh

  function showEmpty() {
    preview.classList.add('d-none');
    emptyBox.classList.remove('d-none');
  }
  function showPreview() {
    emptyBox.classList.add('d-none');
    preview.classList.remove('d-none');
  }

  function renderPreview(file){
    if (!file) return;

    // Validate nhẹ
    if (!/^image\//i.test(file.type)) {
      alert('Vui lòng chọn tệp ảnh (PNG/JPG/WEBP).');
      return;
    }

    // Cập nhật tên file
    nameEl.textContent = `${file.name} (${Math.round(file.size/1024)} KB)`;

    // Tạo URL xem trước
    if (objectUrl) URL.revokeObjectURL(objectUrl);
    objectUrl = URL.createObjectURL(file);
    imgEl.src = objectUrl;

    showPreview();
  }

  // Click vùng để mở chọn tệp
  dropzone.addEventListener('click', () => input.click());

  // Kéo–thả (thêm highlight đẹp mắt)
  ['dragenter','dragover'].forEach(evt => {
    dropzone.addEventListener(evt, e => {
      e.preventDefault(); e.stopPropagation();
      dropzone.style.background = '#fff7e6';
    });
  });
  ['dragleave','drop'].forEach(evt => {
    dropzone.addEventListener(evt, e => {
      e.preventDefault(); e.stopPropagation();
      dropzone.style.background = '#fffdf7';
    });
  });

  dropzone.addEventListener('drop', e => {
    const files = e.dataTransfer && e.dataTransfer.files ? e.dataTransfer.files : null;
    if (files && files.length) {
      const file = files[0];

      // Gán lại input.files bằng DataTransfer để form submit được
      const dt = new DataTransfer();
      dt.items.add(file);
      input.files = dt.files;

      // Cập nhật UI
      renderPreview(file);
    }
  });

  // Chọn bằng dialog
  input.addEventListener('change', () => {
    if (input.files && input.files.length) {
      renderPreview(input.files[0]);
    } else {
      showEmpty();
    }
  });

  // Xóa chọn
  clearBtn.addEventListener('click', () => {
    input.value = ''; // clear input
    if (objectUrl) { URL.revokeObjectURL(objectUrl); objectUrl = undefined; }
    imgEl.src = '';
    showEmpty();
  });

  // Khởi tạo trạng thái
  showEmpty();
});
</script>

@endsection


