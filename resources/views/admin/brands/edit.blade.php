@extends('admin.layouts.app')

@section('content')

    <div class="content">
        <div class="animated fadeIn">
            <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h4 class="mb-0">Chỉnh sửa Thương Hiệu</h4>
                <small class="text-muted">Cập nhật thương hiệu của bạn</small>
            </div>

        </div>

            {{-- Form cập nhật thương hiệu --}}
            <div class="card mb-4 shadow-sm">
                
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


                    <form action="{{ route('admin.brands.update', $brand) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')


                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="name" class="font-weight-bold">Tên thương hiệu <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="name" id="name" class="form-control"
                                    value="{{ old('name', $brand->name) }}">
                                @error('name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="slug" class="font-weight-bold">Slug</label>
                                <input type="text" name="slug" id="slug" class="form-control"
                                    value="{{ old('slug', $brand->slug) }}">
                                @error('slug')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
  <div class="col-md-6">

    <label class="font-weight-bold mb-2" for="logoInput">Logo</label>

    {{-- input thật (ẩn) --}}
    <input id="logoInput" type="file" name="logo" accept="image/*" class="d-none">

    {{-- vùng kéo–thả/nhấp để chọn (giống trang create) --}}
    <div id="logoDropzone"
        style="border:1.5px dashed #ffb200; border-radius:10px; background:#fffdf7;
              padding:40px 16px; text-align:center; cursor:pointer; position:relative;">
      <div id="logoEmpty" class="{{ $brand->logo ? 'd-none' : '' }}">
        <i class="fa fa-cloud-upload" aria-hidden="true" style="font-size:36px; color:#ffa200;"></i>
        <div class="mt-2" style="color:#334155;">Kéo và thả tệp để tải lên</div>
        <small style="color:#64748b;">(hoặc nhấp để chọn ảnh — PNG, JPG, WEBP)</small>
      </div>

      {{-- xem trước --}}
      <div id="logoPreview" class="{{ $brand->logo ? '' : 'd-none' }}">
        <img id="logoImg" src="{{ $brand->logo ? Storage::url($brand->logo) : '' }}" alt="Xem trước"
             style="max-height:160px; border-radius:10px; box-shadow:0 1px 6px rgba(0,0,0,.08);">
        <div class="mt-2 d-flex justify-content-center align-items-center" style="gap:10px;">
          <small id="logoName" class="text-muted">
            {{ $brand->logo ? basename($brand->logo) : '' }}
            @if($brand->logo)
              <span class="badge bg-light text-dark ms-1">Đang dùng ảnh hiện tại</span>
            @endif
          </small>
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

    {{-- Gợi ý nhỏ --}}
    <small class="text-muted d-block mt-2">
      Không chọn tệp mới thì hệ thống sẽ giữ nguyên logo hiện tại.
    </small>

  </div>

  <div class="col-md-6">
    <label for="is_active" class="font-weight-bold">Trạng thái <span class="text-danger">*</span></label>
    <select name="is_active" id="is_active" class="form-control">
      <option value="1" {{ (string) old('is_active', (string) $brand->is_active) === '1' ? 'selected' : '' }}>Hiển thị</option>
      <option value="0" {{ (string) old('is_active', (string) $brand->is_active) === '0' ? 'selected' : '' }}>Ẩn</option>
    </select>
    @error('is_active')
      <small class="text-danger">{{ $message }}</small>
    @enderror
  </div>
</div>


                        <div class="text-end">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="fa fa-save"></i> Cập nhật
                            </button>
                            <a href="{{ route('admin.brands.index') }}" class="btn btn-secondary">
                                <i class="fa fa-arrow-left"></i> Quay lại
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
document.addEventListener("DOMContentLoaded", function() {
  const dropzone = document.getElementById('logoDropzone');
  const input    = document.getElementById('logoInput');

  const emptyBox = document.getElementById('logoEmpty');
  const preview  = document.getElementById('logoPreview');
  const imgEl    = document.getElementById('logoImg');
  const nameEl   = document.getElementById('logoName');
  const clearBtn = document.getElementById('logoClear');

  let objectUrl; // revoke khi đổi ảnh
  const hadInitialImage = !!imgEl.getAttribute('src'); // có logo sẵn từ server

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

    if (!/^image\//i.test(file.type)) {
      alert('Vui lòng chọn tệp ảnh (PNG/JPG/WEBP).');
      return;
    }
    nameEl.textContent = `${file.name} (${Math.round(file.size/1024)} KB)`;

    if (objectUrl) URL.revokeObjectURL(objectUrl);
    objectUrl = URL.createObjectURL(file);
    imgEl.src = objectUrl;

    showPreview();
  }

  dropzone.addEventListener('click', () => input.click());

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
      const dt = new DataTransfer(); dt.items.add(file);
      input.files = dt.files;
      renderPreview(file);
    }
  });

  input.addEventListener('change', () => {
    if (input.files && input.files.length) {
      renderPreview(input.files[0]);
    } else {
      // Không chọn mới → nếu có ảnh sẵn thì hiển thị lại; còn không thì empty
      if (hadInitialImage) showPreview(); else showEmpty();
    }
  });

  clearBtn.addEventListener('click', () => {
    input.value = '';
    if (objectUrl) { URL.revokeObjectURL(objectUrl); objectUrl = undefined; }
    if (hadInitialImage) {
      // quay về ảnh hiện tại từ server
      showPreview();
      nameEl.textContent = '{{ $brand->logo ? basename($brand->logo) : '' }}';
      imgEl.src = '{{ $brand->logo ? addslashes(Storage::url($brand->logo)) : '' }}';
    } else {
      imgEl.src = '';
      showEmpty();
    }
  });
});
</script>

@endsection
