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
use Illuminate\Support\Facades\Mail;
use App\Mail\VerifyOtpEmail;

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
        $user->code_verified_at = now();
        $user->save();
        Mail::to($user->email)->send(new VerifyOtpEmail($user));
        return redirect()->route('verification.otp.form', ['email' => $user->email])
            ->with('success', 'Đăng ký thành công! Vui lòng kiểm tra email để xác thực tài khoản.');
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
            ],
            'phone_number' => ['nullable', 'string', 'max:20', 'unique:users,phone_number'],
            'gender' => ['nullable', 'string', 'in:male,female,other'],
            'birthday' => ['nullable', 'date'],
        ], [
            // Custom message
            'email.regex' => 'Email phải đúng định dạng, có đuôi tên miền như .com, .vn, v.v.',
            'password.regex' => 'Mật khẩu phải có ít nhất 1 chữ hoa, 1 chữ thường, 1 số và 1 ký tự đặc biệt.',
            'phone_number.unique' => 'Số điện thoại này đã được sử dụng cho một tài khoản khác.'
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
            'code_verified_at' => null,

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
    public function showOtpForm(Request $request)
    {
        $email = $request->email;
        return view('auth.verify_otp', compact('email'));
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|digits:6',
        ]);
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Email không tồn tại!']);
        }

        // Kiểm tra mã OTP và hạn dùng (ví dụ: 5 phút)
        $otpValid = $user->code_verified_email == $request->otp;
        $notExpired = $user->code_verified_at && now()->diffInMinutes($user->code_verified_at) <= 5;

        if (!$otpValid) {
            return back()->withErrors(['otp' => 'Mã xác thực không đúng!'])->withInput();
        }
        if (!$notExpired) {
            return back()->withErrors(['otp' => 'Mã xác thực đã hết hạn!'])->withInput();
        }

        $user->email_verified_at = now();
        $user->code_verified_email = null;
        $user->code_verified_at = null;
        $user->save();

        return redirect()->route('login')->with('success', 'Xác thực email thành công! Bạn có thể đăng nhập.');
    }
    // In RegisterController.php, resendOtp() method
    public function resendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Email không tồn tại!']);
        }

        // Tạo mã OTP mới và cập nhật vào cơ sở dữ liệu
        $newOtp = rand(100000, 999999);
        $user->code_verified_email = $newOtp;
        $user->code_verified_at = now();
        $user->save();
        // Gửi email chứa mã OTP mới
        Mail::to($user->email)->send(new VerifyOtpEmail($user));
        return back()->with('success', 'Đã gửi lại mã xác thực mới. Vui lòng kiểm tra email của bạn.');
    }
    // Helper method để trả về đường dẫn chuyển hướng mặc định
    protected function redirectPath()
    {
        return $this->redirectTo;
    }
}
