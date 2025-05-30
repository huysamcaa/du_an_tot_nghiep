<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        return view('client.home'); // Trả về giao diện client
    }

    public function admin()
    {
        return view('admin.dashboard'); // Trả về giao diện admin
    }
}
