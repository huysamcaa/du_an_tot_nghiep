<?php

namespace App\Http\Controllers\Admin;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard');
    }
    public function manageUsers()
    {
        $users = User::all(); // Lấy tất cả user
        return view('admin.users', compact('users')); // Trang quản lý người dùng
    }
}
