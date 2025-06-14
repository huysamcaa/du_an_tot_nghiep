<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ManufacturerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        $id=$this->route('manufacturer') ?->id;
        return [
            'name'        => 'required|max:120|unique:manufacturers,name,'.$id,
            'slug'        => 'required|max:150|unique:manufacturers,slug,'.$id,
            'logo'        => 'nullable|image|max:2048',
            'website'     => 'nullable|url',
            'description' => 'nullable|string',
            'is_active'   => 'boolean',
        ];
    }
    public function messages(): array{
        return[
            'name.required'=>'Tên không được bỏ trống',
            'name.unique'=>'Tên đã tồn tại',
            'slug.required'=>'Slug không được bỏ trống',
            'slug.unique'=>'Slug đã tồn tạitại',
            'logo.image'=>'Logo phải là định dạng ảnh',
            'logo.max'=>'Logo không được  quá 2MB',
            'website.url'=>'Website phải là url hợp lệ',
        ];
    }
}
