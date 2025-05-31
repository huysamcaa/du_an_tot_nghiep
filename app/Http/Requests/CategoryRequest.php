<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $id = $this->route('category') ? $this->route('category')->id : null;

        return [
            'parent_id' => 'nullable|exists:categories,id',
            'name' => 'required|string|max:255|unique:categories,name,' . $id,
            'icon' => 'nullable|string|max:255',
            'ordinal' => 'required|integer',
            'is_active' => 'required|boolean',
        ];
    }

    public function messages()
    {
        return [
            'parent_id.exists' => 'Danh mục cha không hợp lệ.',
            'name.required' => 'Vui lòng nhập tên danh mục.',
            'name.unique' => 'Tên danh mục đã tồn tại.',
            'name.max' => 'Tên danh mục không được vượt quá 255 ký tự.',
            'icon.max' => 'Icon không được vượt quá 255 ký tự.',
            'ordinal.required' => 'Vui lòng nhập thứ tự hiển thị.',
            'ordinal.integer' => 'Thứ tự hiển thị phải là số.',
            'is_active.required' => 'Vui lòng chọn trạng thái.',
            'is_active.boolean' => 'Trạng thái không hợp lệ.',
        ];
    }
}