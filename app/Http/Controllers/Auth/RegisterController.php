<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
// XÓA DÒNG NÀY: use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered; // Giữ lại nếu bạn muốn bắn event Registered
use Illuminate\Support\Facades\Auth;   // Giữ lại để đăng nhập sau khi đăng ký

class RegisterController extends Controller
{
    // XÓA DÒNG NÀY: use RegistersUsers;

    protected $redirectTo = '/dashboard';

    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Hiển thị form đăng ký.
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Xử lý request đăng ký.
     */
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        // event(new Registered($user = $this->create($request->all())));
        $user = $this->create($request->all());
        Auth::login($user); // Đăng nhập người dùng ngay sau khi đăng ký

        return $this->registered($request, $user)
            ?: redirect($this->redirectPath());
    }

    /**
     * Get a validator for an incoming registration request.
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],

            'email' => [
                'required',
                'string',
                'email:rfc,dns',
                'max:255',
                'unique:users',
                'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/'
            ],

            'password' => [
                'required',
                'string',
                'min:8',                     // Ít nhất 8 ký tự
                'confirmed',                 // So sánh với password_confirmation
                'regex:/[A-Z]/',             // Ít nhất 1 chữ hoa
                'regex:/[a-z]/',             // Ít nhất 1 chữ thường
                'regex:/[0-9]/',             // Ít nhất 1 chữ số
                'regex:/[@$!%*?&]/'          // Ít nhất 1 ký tự đặc biệt
            ],

            'phone_number' => ['nullable', 'string', 'max:20'],
            'gender' => ['nullable', 'string', 'in:male,female,other'],
            'birthday' => ['nullable', 'date'],
        ], [
            // Custom message
            'email.regex' => 'Email phải đúng định dạng, có đuôi tên miền như .com, .vn, v.v.',
            'password.regex' => 'Mật khẩu phải có ít nhất 1 chữ hoa, 1 chữ thường, 1 số và 1 ký tự đặc biệt.',
        ]);
    }


    /**
     * Create a new user instance after a valid registration.
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => 'user', // Mặc định khi đăng ký là 'user'
            'phone_number' => $data['phone_number'] ?? null,
            'gender' => $data['gender'] ?? null,
            'birthday' => $data['birthday'] ?? null,
            'status' => 'active', // Mặc định là 'active'
            'user_group' => 'member',
        ]);
    }

    /**
     * The user has been registered.
     * Chuyển hướng sau khi đăng ký thành công.
     */
    protected function registered(Request $request, $user)
    {
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard')->with('success', 'Bạn đã đăng ký tài khoản admin thành công.');
        }
        return redirect()->route('user.dashboard')->with('success', 'Đăng ký tài khoản thành công!');
    }

    // Helper method để trả về đường dẫn chuyển hướng mặc định
    protected function redirectPath()
    {
        return $this->redirectTo;
    }
}
