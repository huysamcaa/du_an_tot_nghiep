@extends('admin.layouts.app')

@section('title', 'Sửa thuộc tính')

@section('content')
<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4>Sửa thuộc tính</h4>
            <h6>Cập nhật thông tin thuộc tính</h6>
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

            <form action="{{ route('admin.attributes.update', $attribute) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Tên thuộc tính <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" 
                               value="{{ old('name', $attribute->name) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Slug <span class="text-danger">*</span></label>
                        <input type="text" name="slug" class="form-control" 
                               value="{{ old('slug', $attribute->slug) }}" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Biến thể?</label>
                        <select name="is_variant" class="form-select">
                            <option value="1" {{ old('is_variant', $attribute->is_variant) == 1 ? 'selected' : '' }}>Có</option>
                            <option value="0" {{ old('is_variant', $attribute->is_variant) == 0 ? 'selected' : '' }}>Không</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Hiển thị?</label>
                        <select name="is_active" class="form-select">
                            <option value="1" {{ old('is_active', $attribute->is_active) == 1 ? 'selected' : '' }}>Có</option>
                            <option value="0" {{ old('is_active', $attribute->is_active) == 0 ? 'selected' : '' }}>Không</option>
                        </select>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label">Giá trị thuộc tính 
                        @if(strtolower($attribute->name) == 'color' || strtolower($attribute->name) == 'màu')
                        <small class="text-muted">(Tên & Mã màu)</small>
                        @else
                        <small class="text-muted">(Tên giá trị)</small>
                        @endif
                    </label>
                    
                    <div id="values-list" class="mb-2">
                        @foreach($attribute->attributeValues as $i => $value)
                        <div class="row mb-2 value-row">
                            <div class="col-md-6">
                                <input type="text" name="values[{{ $i }}][name]" class="form-control"
                                    value="{{ old("values.$i.name", $value->value) }}" placeholder="Tên giá trị" required>
                            </div>
                            @if(strtolower($attribute->name) == 'color' || strtolower($attribute->name) == 'màu')
                            <div class="col-md-4 d-flex align-items-center">
                                <input type="color" name="values[{{ $i }}][hex]" 
                                       class="form-control form-control-color color-picker"
                                    value="{{ old("values.$i.hex", $value->hex ?? '#000000') }}" required>
                                <span class="hex-value ms-2">{{ old("values.$i.hex", $value->hex ?? '#000000') }}</span>
                            </div>
                            @endif
                            <div class="col-md-2 d-flex align-items-center">
                                <button type="button" class="btn btn-danger remove-value">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                                <input type="hidden" name="values[{{ $i }}][id]" value="{{ $value->id }}">
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    <button type="button" class="btn btn-success" id="add-value">
                        <i class="fas fa-plus me-2"></i> Thêm {{ strtolower($attribute->name) == 'color' || strtolower($attribute->name) == 'màu' ? 'màu' : 'giá trị' }}
                    </button>
                </div>

                <div class="d-flex justify-content-between border-top pt-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i> Cập nhật
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
    let valueIndex = {{ count($attribute->attributeValues) }};
    const isColor = {{ strtolower($attribute->name) == 'color' || strtolower($attribute->name) == 'màu' ? 'true' : 'false' }};
    
    // Thêm giá trị mới
    document.getElementById('add-value').addEventListener('click', function() {
        const colorPickerHtml = isColor ? `
            <div class="col-md-4 d-flex align-items-center">
                <input type="color" name="values[${valueIndex}][hex]" 
                       class="form-control form-control-color color-picker" value="#000000" required>
                <span class="hex-value ms-2">#000000</span>
            </div>
        ` : '';
        
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