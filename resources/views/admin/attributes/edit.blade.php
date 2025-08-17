@extends('admin.layouts.app')
@section('content')

<div class="breadcrumbs">
    <div class="breadcrumbs-inner">
        <div class="row m-0">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <h1>Sửa thuộc tính</h1>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <ol class="breadcrumb text-right">
                        <li><a href="{{ route('admin.dashboard') }}">Trang chủ</a></li>
                        <li><a href="{{ route('admin.attributes.index') }}">Thuộc tính</a></li>
                        <li class="active">Sửa</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="card mx-auto" style="max-width:700px">
        <div class="card-header d-flex justify-content-between align-items-center px-3 py-2">
            <strong>Sửa thuộc tính</strong>
        </div>
        <div class="card-body">
            @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route('admin.attributes.update', $attribute) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>Tên thuộc tính</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $attribute->name) }}" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label>Slug</label>
                        <input type="text" name="slug" class="form-control" value="{{ old('slug', $attribute->slug) }}" required>
                    </div>
                </div>

                <div class="form-row">
                    {{-- <div class="form-group col-md-6">
                        <label>Biến thể?</label>
                        <select name="is_variant" class="form-control">
                            <option value="1" {{ old('is_variant', $attribute->is_variant)==1 ? 'selected' : '' }}>Có</option>
                            <option value="0" {{ old('is_variant', $attribute->is_variant)==0 ? 'selected' : '' }}>Không</option>
                        </select>
                    </div> --}}
                    <div class="form-group col-md-6">
                        <label>Hiển thị?</label>
                        <select name="is_active" class="form-control">
                            <option value="1" {{ old('is_active', $attribute->is_active)==1 ? 'selected' : '' }}>Có</option>
                            <option value="0" {{ old('is_active', $attribute->is_active)==0 ? 'selected' : '' }}>Không</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label>Giá trị thuộc tính (Tên @if(strtolower($attribute->name) == 'color' || strtolower($attribute->name) == 'màu') & Mã màu @endif)</label>
                    <div id="color-values-list">
                        @foreach($attribute->attributeValues as $i => $value)
                        <div class="row mb-2 color-value-row">
                            <div class="col-6">
                                <input type="text" name="values[{{ $i }}][name]" class="form-control"
                                    value="{{ old("values.$i.name", $value->value) }}" placeholder="Tên giá trị" required>
                            </div>
                            @if(strtolower($attribute->name) == 'color' || strtolower($attribute->name) == 'màu')
                            <div class="col-4 d-flex align-items-center">
                                <input type="color" name="values[{{ $i }}][hex]" class="form-control color-picker"
                                    value="{{ old("values.$i.hex", $value->hex ?? '#000000') }}" required>
                                <span class="hex-value ml-2">{{ old("values.$i.hex", $value->hex ?? '#000000') }}</span>
                            </div>
                            @endif
                            <div class="col-2">
                                <button type="button" class="btn btn-danger remove-color-value">X</button>
                                <input type="hidden" name="values[{{ $i }}][id]" value="{{ $value->id }}">
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <button type="button" class="btn btn-success btn-sm mt-2" id="add-color-value">
                        <i class="fa fa-plus"></i> Thêm {{ strtolower($attribute->name) == 'color' || strtolower($attribute->name) == 'màu' ? 'màu' : 'giá trị' }}
                    </button>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-save"></i> Cập nhật
                    </button>
                    <a href="{{ route('admin.attributes.index') }}" class="btn btn-outline-secondary btn-sm align-self-center">
                        Hủy
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    let colorIndex = {
        {
            count($attribute - > attributeValues)
        }
    };
    document.getElementById('add-color-value').onclick = function() {
        let isColor = "{{ strtolower($attribute->name) == 'color' || strtolower($attribute->name) == 'màu' ? '1' : '0' }}";
        let html = `
        <div class="row mb-2 color-value-row">
            <div class="col-6">
                <input type="text" name="values[${colorIndex}][name]" class="form-control" placeholder="Tên giá trị" required>
            </div>
            ${isColor === '1' ? `
            <div class="col-4 d-flex align-items-center">
                <input type="color" name="values[${colorIndex}][hex]" class="form-control color-picker" value="#000000" required>
                <span class="hex-value ml-2">#000000</span>
            </div>` : ''}
            <div class="col-2">
                <button type="button" class="btn btn-danger remove-color-value">X</button>
            </div>
        </div>`;
        document.getElementById('color-values-list').insertAdjacentHTML('beforeend', html);
        colorIndex++;
    };

    document.addEventListener('input', function(e) {
        if (e.target.classList.contains('color-picker')) {
            let span = e.target.parentElement.querySelector('.hex-value');
            if (span) span.textContent = e.target.value;
        }
    });

    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-color-value')) {
            e.target.closest('.color-value-row').remove();
        }
    });
</script>
@endsection