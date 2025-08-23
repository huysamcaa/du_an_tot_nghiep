<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\OrderStatus;
use Illuminate\Http\Request;

class OrderStatusController extends Controller
{
   public function index(Request $request)
{
    $perPage = $request->input('perPage', 10);
    $search = $request->input('search');

    $query = OrderStatus::query();

    if ($search) {
        $query->where('name', 'LIKE', "%{$search}%");
    }

    $statuses = $query->orderBy('id', 'asc')->paginate($perPage)->withQueryString();

    return view('admin.order_statuses.index', compact('statuses'));
}


    public function create()
    {
        return view('admin.order_statuses.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        OrderStatus::create($request->only('name'));

        return redirect()->route('admin.order_statuses.index')->with('success', 'Thêm trạng thái thành công!');
    }
}

