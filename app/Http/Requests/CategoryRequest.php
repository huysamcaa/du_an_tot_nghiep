<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
{
    /**
     * Xác định xem người dùng có được phép thực hiện request này không.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Lấy các quy tắc xác thực áp dụng cho request.
     *
     * @return array
     */
    public function rules()
    {
        $id = $this->route('category') ? $this->route('category')->id : null;

        $rules = [
            'parent_id' => 'nullable|exists:categories,id',
            'name' => 'required|string|max:255|unique:categories,name,' . $id,
            'ordinal' => 'required|integer',
            'is_active' => 'required|boolean',
        ];

        // Quy tắc cho trường icon
        // Nếu là phương thức POST (tạo mới), icon là bắt buộc
        if ($this->isMethod('post')) {
            $rules['icon'] = 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048';
        }
        // Nếu là phương thức PUT/PATCH (cập nhật), icon là tùy chọn
        else {
            $rules['icon'] = 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048';
        }

        return $rules;
    }

    /**
     * Lấy các thông báo lỗi tùy chỉnh cho các quy tắc xác thực.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'parent_id.exists' => 'Danh mục cha không hợp lệ.',
            'name.required' => 'Vui lòng nhập tên danh mục.',
            'name.unique' => 'Tên danh mục đã tồn tại.',
            'name.max' => 'Tên danh mục không được vượt quá 255 ký tự.',
            'icon.required' => 'Vui lòng tải lên một icon cho danh mục.',
            'icon.image' => 'File tải lên phải là một hình ảnh.',
            'icon.mimes' => 'Hình ảnh phải có định dạng jpeg, png, jpg, gif hoặc svg.',
            'icon.max' => 'Kích thước hình ảnh không được vượt quá 2MB.',
            'ordinal.required' => 'Vui lòng nhập thứ tự hiển thị.',
            'ordinal.integer' => 'Thứ tự hiển thị phải là số.',
            'is_active.required' => 'Vui lòng chọn trạng thái.',
            'is_active.boolean' => 'Trạng thái không hợp lệ.',
        ];
    }
}
