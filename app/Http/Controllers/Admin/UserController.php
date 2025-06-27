<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->get();
        return view('admin.users.index', compact('users'));
    }
    public function create()
    {
        return view('admin.users.create');
    }


public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'phone_number' => 'nullable|string|max:20',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'gender' => 'nullable|in:male,female',
            'birthday' => 'nullable|date',
            'role' => 'required|in:user,admin',
        ]);

        $data = $request->only([
            'name', 'email', 'phone_number', 'gender', 'birthday', 'role'
        ]);
        $data['password'] = Hash::make($request->password);
        $data['status'] = 'active';
        $data['loyalty_points'] = 0;
        $data['is_change_password'] = false;

        // Xử lý upload avatar nếu có
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $data['avatar'] = $avatarPath;
        }

        User::create($data);

        return redirect()->route('admin.users.index')->with('success', 'Tạo tài khoản thành công');
    }
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'phone_number' => 'nullable|string|max:20',
            'avatar' => 'nullable|image|max:2048',
            'gender' => 'nullable|in:male,female',
            'birthday' => 'nullable|date',
            'role' => 'required|in:user,admin',
        ]);

        if ($request->hasFile('avatar')) {
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'Cập nhật người dùng thành công');
    }
    public function locked()
    {
        $users = User::where('status', 'locked')->get(); // Giả sử status = 'locked' là bị khóa
        return view('admin.users.locked', compact('users'));
    }
    public function unlock($id)
    {
        $user = User::findOrFail($id);
        $user->status = 'active'; // hoặc null nếu mặc định là chưa khóa
        $user->save();

        return redirect()->route('admin.users.locked')->with('success', 'Đã mở khóa tài khoản.');
    }
    public function lock(User $user)
    {
        $user->status = 'locked';
        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'Tài khoản đã bị khóa.');
    }
}
