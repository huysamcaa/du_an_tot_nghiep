{{-- filepath: resources/views/admin/attributes/edit.blade.php --}}
@extends('admin.layouts.app')
@section('content')
<h2>Sửa thuộc tính</h2>
<form action="{{ route('admin.attributes.update', $attribute) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="form-group">
        <label>Tên thuộc tính</label>
        <input type="text" name="name" class="form-control" value="{{ old('name', $attribute->name) }}" required>
    </div>
    <div class="form-group">
        <label>Slug</label>
        <input type="text" name="slug" class="form-control" value="{{ old('slug', $attribute->slug) }}" required>
    </div>
    
    <div class="form-group">
        <label>Hiển thị?</label>
        <select name="is_active" class="form-control">
            <option value="1" {{ old('is_active', $attribute->is_active)==1?'selected':'' }}>Có</option>
            <option value="0" {{ old('is_active', $attribute->is_active)==0?'selected':'' }}>Không</option>
        </select>
    </div>
   <div class="form-group">
    <label>Giá trị thuộc tính (tên màu & mã màu)</label>
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
    @if(strtolower($attribute->name) == 'color' || strtolower($attribute->name) == 'màu')
    <button type="button" class="btn btn-success" id="add-color-value">+ Thêm màu</button>
    @else
    <button type="button" class="btn btn-success" id="add-color-value">+ Thêm giá trị</button>
    @endif
</div>
  <button type="submit" class="btn btn-primary mt-3">Thêm mới</button>

</form>

<script>
let colorIndex = {{ count($attribute->attributeValues) }};
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
        </div>
        ` : ''}
        <div class="col-2">
            <button type="button" class="btn btn-danger remove-color-value">X</button>
        </div>
    </div>
    `;
    document.getElementById('color-values-list').insertAdjacentHTML('beforeend', html);
    colorIndex++;
};

// Cập nhật mã màu khi chọn màu mới
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