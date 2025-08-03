@extends('admin.layouts.app')
@section('content')
<div class="breadcrumbs">
    <div class="breadcrumbs-inner">
        <div class="row m-0">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <h1>Thêm thuộc tính</h1>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <ol class="breadcrumb text-right">
                        <li><a href="{{ route('admin.dashboard') }}">Trang chủ</a></li>
                        <li><a href="{{ route('admin.attributes.index') }}">Thuộc tính</a></li>
                        <li class="active">Thêm mới</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="card mx-auto" style="max-width:600px">
        <div class="card-header px-3 py-2">
            <strong> Thêm thuộc tính</strong>
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

            <form action="{{ route('admin.attributes.store') }}" method="POST">
                @csrf
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="attribute-name">Tên thuộc tính</label>
                        <input type="text" name="name" id="attribute-name" class="form-control" value="{{ old('name') }}">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="attribute-slug">Slug</label>
                        <input type="text" name="slug" id="attribute-slug" class="form-control" value="{{ old('slug') }}">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="is-variant">Biến thể?</label>
                        <select name="is_variant" id="is-variant" class="form-control">
                            <option value="1" {{ old('is_variant',1)==1?'selected':'' }}>Có</option>
                            <option value="0" {{ old('is_variant',1)==0?'selected':'' }}>Không</option>
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="is-active">Hiển thị?</label>
                        <select name="is_active" id="is-active" class="form-control">
                            <option value="1" {{ old('is_active',1)==1?'selected':'' }}>Có</option>
                            <option value="0" {{ old('is_active',1)==0?'selected':'' }}>Không</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label>Giá trị thuộc tính</label>
                    <div id="color-values-list">
                        <div class="d-flex mb-2 align-items-center color-value-row">
                            <input type="text" name="values[0][name]" class="form-control mr-2" placeholder="Tên">
                            <input type="color" name="values[0][hex]" class="form-control mr-2 color-picker-group" value="#000000">
                            <button type="button" class="btn btn-danger remove-color-value">X</button>
                        </div>
                    </div>
                    <button type="button" class="btn btn-success btn-sm mt-1" id="add-color-value">
                        <i class="fa fa-plus"></i> Thêm giá trị
                    </button>
                </div>

                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-save"></i> Lưu lại
                    </button>
                    <a href="{{ route('admin.attributes.index') }}" class="btn btn-outline-secondary btn-sm align-self-center">
                        Hủy
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let colorIndex = 1;
    const nameInput = document.getElementById('attribute-name');

    function isColorAttribute() {
        const name = nameInput.value.trim().toLowerCase();
        return ['color', 'màu'].includes(name);
    }

    function toggleColorInputs() {
        document.querySelectorAll('.color-picker-group').forEach(el => el.style.display = isColorAttribute() ? '' : 'none');
    }
    nameInput.addEventListener('input', toggleColorInputs);
    toggleColorInputs();

    document.getElementById('add-color-value').addEventListener('click', () => {
        const isColor = isColorAttribute();
        const wrapper = document.createElement('div');
        wrapper.className = 'd-flex mb-2 align-items-center color-value-row';
        wrapper.innerHTML = `
        <input type="text" name="values[${colorIndex}][name]" class="form-control mr-2" placeholder="Tên" >
        <input type="color" name="values[${colorIndex}][hex]" class="form-control mr-2 color-picker-group" style="${isColor?'':'display:none;'}" value="#000000">
        <button type="button" class="btn btn-danger remove-color-value">X</button>
    `;
        document.getElementById('color-values-list').appendChild(wrapper);
        colorIndex++;
    });

    document.addEventListener('click', e => {
        if (e.target.classList.contains('remove-color-value')) {
            e.target.closest('.color-value-row').remove();
        }
    });
</script>
@endpush
@endsection
