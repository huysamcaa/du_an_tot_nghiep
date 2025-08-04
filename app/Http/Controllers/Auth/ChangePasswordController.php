<?php
namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ChangePasswordController extends Controller
{
    public function showForm()
    {
        return view('auth.change-password');
    }

    public function update(Request $request)
{
    $request->validate([
        'current_password' => 'required',
        'new_password' => [
            'required',
            'string',
            'min:8',
            'confirmed',
            'regex:/[A-Z]/', // Ít nhất 1 chữ hoa
            'regex:/[a-z]/', // Ít nhất 1 chữ thường
            'regex:/[0-9]/', // Ít nhất 1 chữ số
            // Thêm regex ký tự đặc biệt nếu muốn
        ],
    ], [
        'new_password.regex' => 'Mật khẩu phải có ít nhất 1 chữ hoa, 1 chữ thường và 1 số.',
    ]);
/** @var \App\Models\User $user */
    $user = Auth::user();

    if (!Hash::check($request->current_password, $user->password)) {
        return back()->withErrors(['current_password' => 'Mật khẩu hiện tại không đúng']);
    }

    $user->password = Hash::make($request->new_password);
    $user->save();

    return back()->with('status', 'Đổi mật khẩu thành công!');
}

}

