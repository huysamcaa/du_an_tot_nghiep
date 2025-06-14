<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Promotion;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;

class PromotionController extends Controller
{
    public function index()
    {
        $promotions = Promotion::latest()->get();
        return view('admin.promotions.index', compact('promotions'));
    }

    public function create()
    {
        return view('admin.promotions.create');
    }

    public function store(Request $request)
{
    $data = $this->validateRequest($request);

    $data['start_date'] = $this->toDate($data['start_date']);
    $data['end_date']   = $this->toDate($data['end_date']);

    if ($data['end_date'] < $data['start_date']) {
        return back()->withErrors(['end_date' => 'Ngày kết thúc phải lớn hơn hoặc bằng ngày bắt đầu'])->withInput();
    }

    // ✅ Tự tạo mã nếu người dùng không nhập
    if (empty($data['code'])) {
        do {
            $data['code'] = strtoupper(Str::random(8));
        } while (Promotion::where('code', $data['code'])->exists());
    }

    Promotion::create($data);

    return redirect()->route('admin.promotions.index')->with('success', 'Thêm khuyến mãi thành công!');
}
    public function edit(Promotion $promotion)
    {
        return view('admin.promotions.edit', compact('promotion'));
    }

    public function update(Request $request, Promotion $promotion)
    {
        $data = $this->validateRequest($request, $promotion);

        // Chuẩn hoá ngày
        $data['start_date'] = $this->toDate($data['start_date']);
        $data['end_date']   = $this->toDate($data['end_date']);

        if ($data['end_date'] < $data['start_date']) {
            return back()
                ->withErrors(['end_date' => 'Ngày kết thúc phải lớn hơn hoặc bằng ngày bắt đầu'])
                ->withInput();
        }

        $promotion->update($data);

        return redirect()->route('admin.promotions.index')
                         ->with('success', 'Cập nhật khuyến mãi thành công!');
    }

    public function destroy(Promotion $promotion)
    {
        $promotion->delete();
        return redirect()->route('admin.promotions.index')->with('success', 'Xóa khuyến mãi thành công');
    }

    private function validateRequest(Request $request, Promotion $promotion = null): array
    {
        return $request->validate([
            'title'            => 'required|max:255',
            'code'             => [
                'nullable',
                'string',
                'max:50',
                Rule::unique('promotions', 'code')->ignore($promotion?->id)
            ],
            'discount_percent' => 'required|numeric|min:0|max:100',
            'start_date'       => ['required', 'regex:/^\d{4}-\d{2}-\d{2}$|^\d{1,2}\/\d{1,2}\/\d{4}$/'],
            'end_date'         => ['required', 'regex:/^\d{4}-\d{2}-\d{2}$|^\d{1,2}\/\d{1,2}\/\d{4}$/'],
        ]);
    }

    /**
     * Chuyển d/m/Y → Y-m-d, giữ nguyên nếu đã đúng.
     */
    private function toDate(string $value): string
    {
        return preg_match('/^\d{4}-\d{2}-\d{2}$/', $value)
            ? $value
            : Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d');
    }
}
