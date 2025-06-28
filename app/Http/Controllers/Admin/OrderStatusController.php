<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\OrderStatus;
use Illuminate\Http\Request;

class OrderStatusController extends Controller
{
    public function index()
    {
        $statuses = OrderStatus::paginate(10);
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

    public function edit($id)
    {
        $status = OrderStatus::findOrFail($id);
        return view('admin.order_statuses.edit', compact('status'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $status = OrderStatus::findOrFail($id);
        $status->update($request->only('name'));

        return redirect()->route('admin.order_statuses.index')->with('success', 'Cập nhật trạng thái thành công!');
    }

    public function destroy($id)
    {
        $status = OrderStatus::findOrFail($id);
        $status->delete();

        return redirect()->route('admin.order_statuses.index')->with('success', 'Xóa trạng thái thành công!');
    }
}

