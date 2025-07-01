<?php

namespace App\Http\Controllers\Admin;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Shared\Order;
use App\Models\Admin\Product;
class AdminController extends Controller
{
    public function dashboard()
    {
        $revenue = Order::where('is_paid', true)->sum('total_amount');
        $orderCount = Order::count();
        $productCount = Product::count();
        $userCount = User::count();

        return view('admin.dashboard', compact('revenue', 'orderCount', 'productCount', 'userCount'));
    }
    public function manageUsers()
    {
        $users = User::all(); // Lấy tất cả user
        return view('admin.users', compact('users')); // Trang quản lý người dùng
    }
}
