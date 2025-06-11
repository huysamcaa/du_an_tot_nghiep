<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
// XÓA DÒNG NÀY: use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException; // Import để xử lý lỗi validation

class LoginController extends Controller
{
    // XÓA DÒNG NÀY: use AuthenticatesUsers;

    protected $redirectTo = '/dashboard';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Hiển thị form đăng nhập.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Xử lý request đăng nhập.
     */
    public function login(Request $request)
    {
        // 1. Validate dữ liệu
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // 2. Thử xác thực người dùng
        $credentials = $request->only('email', 'password');
        $remember = $request->filled('remember'); // Kiểm tra checkbox "Ghi nhớ"

        if (Auth::attempt($credentials, $remember)) {
            // Xác thực thành công
            $request->session()->regenerate(); // Tạo lại session ID để tránh session fixation

            // Chuyển hướng người dùng dựa trên vai trò
            return $this->authenticated($request, Auth::user());
        }

        // Xác thực thất bại
        throw ValidationException::withMessages([
            'email' => [trans('auth.failed')], // Thông báo lỗi mặc định của Laravel
        ]);
    }

    /**
     * The user has been authenticated.
     * Chuyển hướng sau khi đăng nhập thành công dựa trên vai trò.
     */
    protected function authenticated(Request $request, $user)
    {
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard'); // Chuyển hướng Admin
        }
        return redirect()->route('client.home'); // Chuyển hướng User thường
    }

    /**
     * Đăng xuất người dùng.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login')->with('success', 'Bạn đã đăng xuất thành công.');
    }
}
