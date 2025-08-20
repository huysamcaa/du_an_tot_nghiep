{{-- filepath: resources/views/admin/attributes/create.blade.php --}}
@extends('admin.layouts.app')
@section('content')
<h2>Thêm thuộc tính</h2>
<form action="{{ route('admin.attributes.store') }}" method="POST">
    @csrf
    <div class="form-group">
        <label>Tên thuộc tính</label>
        <input type="text" name="name" id="attribute-name" class="form-control" value="{{ old('name') }}" required>
    </div>
    <div class="form-group">
        <label>Slug</label>
        <input type="text" name="slug" class="form-control" value="{{ old('slug') }}" required>
    </div>
    
    <div class="form-group">
        <label>Hiển thị?</label>
        <select name="is_active" class="form-control">
            <option value="1" {{ old('is_active', 1)==1?'selected':'' }}>Có</option>
            <option value="0" {{ old('is_active', 1)==0?'selected':'' }}>Không</option>
        </select>
    </div>
    <div class="form-group">
        <label>Thuộc tính giá trị</label>
        <div id="color-values-list">
            <div class="row mb-2 color-value-row">
                <div class="col-6">
                    <input type="text" name="values[0][name]" class="form-control" placeholder="Tên thuộc tính" required>
                </div>
                <div class="col-4 color-picker-group">
                    <input type="color" name="values[0][hex]" class="form-control" value="#000000">
                </div>
                <div class="col-2">
                    <button type="button" class="btn btn-danger remove-color-value">X</button>
                </div>
            </div>
        </div>
        <button type="button" class="btn btn-success" id="add-color-value">Thuộc tính giá trị</button>
    </div>
    <button type="submit" class="btn btn-primary mt-3">Thêm mới</button>
</form>

<script>
let colorIndex = 1;
function isColorAttribute() {
    let name = document.getElementById('attribute-name').value.trim().toLowerCase();
    return name === 'color' || name === 'màu';
}
function toggleColorInputs() {
    document.querySelectorAll('.color-picker-group').forEach(function(el) {
        el.style.display = isColorAttribute() ? '' : 'none';
    });
}
document.getElementById('attribute-name').addEventListener('input', toggleColorInputs);
toggleColorInputs();

document.getElementById('add-color-value').onclick = function() {
    let isColor = isColorAttribute();
    let html = `
    <div class="row mb-2 color-value-row">
        <div class="col-6">
            <input type="text" name="values[${colorIndex}][name]" class="form-control" placeholder="Tên thuộc tính" required>
        </div>
        <div class="col-4 color-picker-group" style="${isColor ? '' : 'display:none;'}">
            <input type="color" name="values[${colorIndex}][hex]" class="form-control" value="#000000">
        </div>
        <div class="col-2">
            <button type="button" class="btn btn-danger remove-color-value">X</button>
        </div>
    </div>
    `;
    document.getElementById('color-values-list').insertAdjacentHTML('beforeend', html);
    colorIndex++;
};
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('remove-color-value')) {
        e.target.closest('.color-value-row').remove();
    }
});
</script>
@endsection