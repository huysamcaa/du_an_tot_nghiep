@extends('admin.layouts.app')

@section('title', 'Thêm thuộc tính')

@section('content')
<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4>Thêm thuộc tính</h4>
            <h6>Tạo mới thông tin thuộc tính</h6>
        </div>
        <div class="page-btn">
            <a href="{{ route('admin.attributes.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i> Quay lại
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <form action="{{ route('admin.attributes.store') }}" method="POST">
                @csrf

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Tên thuộc tính <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="attribute-name" class="form-control" 
                               value="{{ old('name') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Slug <span class="text-danger">*</span></label>
                        <input type="text" name="slug" class="form-control" 
                               value="{{ old('slug') }}" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Biến thể?</label>
                        <select name="is_variant" class="form-select">
                            <option value="1" {{ old('is_variant', 1) == 1 ? 'selected' : '' }}>Có</option>
                            <option value="0" {{ old('is_variant', 1) == 0 ? 'selected' : '' }}>Không</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Hiển thị?</label>
                        <select name="is_active" class="form-select">
                            <option value="1" {{ old('is_active', 1) == 1 ? 'selected' : '' }}>Có</option>
                            <option value="0" {{ old('is_active', 1) == 0 ? 'selected' : '' }}>Không</option>
                        </select>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label">Giá trị thuộc tính</label>
                    
                    <div id="values-list" class="mb-2">
                        <div class="row mb-2 value-row">
                            <div class="col-md-6">
                                <input type="text" name="values[0][name]" class="form-control" placeholder="Tên giá trị" required>
                            </div>
                            <div class="col-md-4 d-flex align-items-center color-picker-group" style="display: none!important;">
                                <input type="color" name="values[0][hex]" class="form-control form-control-color color-picker" value="#000000">
                                <span class="hex-value ms-2">#000000</span>
                            </div>
                            <div class="col-md-2 d-flex align-items-center">
                                <button type="button" class="btn btn-danger remove-value">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <button type="button" class="btn btn-success" id="add-value">
                        <i class="fas fa-plus me-2"></i> Thêm giá trị
                    </button>
                </div>

                <div class="d-flex justify-content-between border-top pt-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i> Lưu lại
                    </button>
                    <a href="{{ route('admin.attributes.index') }}" class="btn btn-outline-secondary">
                        Hủy bỏ
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let valueIndex = 1;
    const nameInput = document.getElementById('attribute-name');

    function isColorAttribute() {
        const name = nameInput.value.trim().toLowerCase();
        return ['color', 'màu'].includes(name);
    }

    function toggleColorInputs() {
        const showColor = isColorAttribute();
        document.querySelectorAll('.color-picker-group').forEach(el => {
            el.style.display = showColor ? '' : 'none';
        });
        
        const addButton = document.getElementById('add-value');
        if (addButton) {
            addButton.innerHTML = `<i class="fas fa-plus me-2"></i> Thêm ${showColor ? 'màu' : 'giá trị'}`;
        }
    }

    nameInput.addEventListener('input', toggleColorInputs);
    toggleColorInputs();

    // Thêm giá trị mới
    document.getElementById('add-value').addEventListener('click', function() {
        const isColor = isColorAttribute();
        const colorPickerHtml = isColor ? `
            <div class="col-md-4 d-flex align-items-center color-picker-group">
                <input type="color" name="values[${valueIndex}][hex]" 
                       class="form-control form-control-color color-picker" value="#000000">
                <span class="hex-value ms-2">#000000</span>
            </div>
        ` : '<div class="col-md-4 d-flex align-items-center color-picker-group" style="display: none!important;"></div>';
        
        const html = `
        <div class="row mb-2 value-row">
            <div class="col-md-6">
                <input type="text" name="values[${valueIndex}][name]" class="form-control" placeholder="Tên giá trị" required>
            </div>
            ${colorPickerHtml}
            <div class="col-md-2 d-flex align-items-center">
                <button type="button" class="btn btn-danger remove-value">
                    <i class="fas fa-trash-alt"></i>
                </button>
            </div>
        </div>`;
        
        document.getElementById('values-list').insertAdjacentHTML('beforeend', html);
        valueIndex++;
    });

    // Xóa giá trị
    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-value')) {
            e.target.closest('.value-row').remove();
        }
    });

    // Cập nhật hiển thị mã màu
    document.addEventListener('input', function(e) {
        if (e.target.classList.contains('color-picker')) {
            const span = e.target.parentElement.querySelector('.hex-value');
            if (span) span.textContent = e.target.value;
        }
    });
</script>
@endpush

@push('styles')
<style>
    .form-control-color {
        width: 40px;
        height: 40px;
        padding: 2px;
    }
    .hex-value {
        font-family: monospace;
    }
</style>
@endpush