<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered; // Giữ lại nếu bạn muốn bắn event Registered
use Illuminate\Support\Facades\Auth;   // Giữ lại để đăng nhập sau khi đăng ký
use Illuminate\Support\Facades\Mail;
use App\Mail\VerifyEmail;
use Illuminate\Support\Str;

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

        $user = $this->create($request->all());

        // Gửi mail xác thực
        Mail::to($user->email)->send(new VerifyEmail($user));

        // Chuyển hướng về trang thông báo
        return redirect()->route('verification.otp.form', ['email' => $user->email]);
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
                'unique:users,email',
                'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/'
            ],

            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/[A-Z]/',
                'regex:/[a-z]/',
                'regex:/[0-9]/',
            ],

            'phone_number' => [
                'required',
                'string',
                'max:20',
                'unique:users,phone_number',
                'regex:/^(0[0-9]{9})$/'
            ],

            'gender' => ['nullable', 'string', 'in:male,female,other'],
            'birthday' => ['nullable', 'date'],
        ], [
            'email.regex' => 'Email phải đúng định dạng, có đuôi tên miền như .com, .vn, v.v.',
            'password.regex' => 'Mật khẩu phải có ít nhất 1 chữ hoa, 1 chữ thường và 1 số',
            'phone_number.unique' => 'Số điện thoại đã được đăng ký.',
            'phone_number.regex' => 'Số điện thoại phải có 10 chữ số và bắt đầu bằng số 0.',
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
            'code_verified_email' => rand(100000, 999999), // OTP 6 số
            'code_verified_at' => now(),
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
    public function showOtpForm(Request $request)
    {
        $email = $request->query('email');
        return view('auth.verify_otp', compact('email'));
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|digits:6',
        ]);
        $user = User::where('email', $request->email)
            ->where('code_verified_email', $request->otp)
            ->first();

        if (!$user) {
            return back()->withErrors(['otp' => 'Mã xác thực không đúng!'])->withInput();
        }

        $user->email_verified_at = now();
        $user->code_verified_email = null;
        $user->save();

        return redirect()->route('login')->with('success', 'Xác thực email thành công! Bạn có thể đăng nhập.');
    }
}
