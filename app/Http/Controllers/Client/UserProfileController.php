<?php

namespace App\Http\Controllers\Client;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        return view('client.profile.show', compact('user'));
    }

    public function edit()
    {
        $user = Auth::user();
        return view('client.profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

    $data = $request->validate(
    [
        'name' => 'required|string|max:50',

        'email' => [
            'required',
            'email',
            'max:255',
            'regex:/^[a-zA-Z0-9._%+-]+@gmail\.com$/',
            'unique:users,email,' . $user->id,
        ],

        'phone_number' => [
            'nullable',
            'string',
            'regex:/^(0|\+84)(\d{9,10})$/',
            'max:15',
            'unique:users,phone_number,' . $user->id,
        ],

        'gender' => 'nullable|in:male,female',

        'birthday' => 'nullable|date',

        'avatar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ],
    [
        
        'name.required' => 'Vui lòng nhập họ tên.',
        'name.string' => 'Họ tên phải là chuỗi ký tự.',
        'name.max' => 'Họ tên không được vượt quá 50 ký tự.',


        'email.required' => 'Email không được để trống.',
        'email.email' => 'Email không đúng định dạng (ví dụ: ten@gmail.com).',
        'email.regex' => 'Email phải là địa chỉ Gmail hợp lệ (ví dụ: ten@gmail.com).',
        'email.max' => 'Email không được vượt quá 255 ký tự.',
        'email.unique' => 'Email đã được sử dụng.',


        'phone_number.regex' => 'Số điện thoại phải bắt đầu bằng 0 hoặc +84 và có 9-10 chữ số.',
        'phone_number.max' => 'Số điện thoại không được vượt quá 15 ký tự.',
        'phone_number.unique' => 'Số điện thoại đã tồn tại.',


        'gender.in' => 'Giới tính không hợp lệ. Chỉ chấp nhận male hoặc female.',


        'birthday.date' => 'Ngày sinh không đúng định dạng ngày tháng.',


        'avatar.image' => 'Ảnh đại diện phải là hình ảnh.',
        'avatar.mimes' => 'Ảnh chỉ được dùng định dạng JPG, JPEG hoặc PNG.',
        'avatar.max' => 'Ảnh không được vượt quá 2MB.',
    ]
);


        // Xử lý ảnh đại diện nếu có
        if ($request->hasFile('avatar')) {
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        // Cập nhật dữ liệu
        $user->update($data);

        return redirect()->route('client.profile.show')->with('success', 'Cập nhật thông tin thành công!');
    }
}
