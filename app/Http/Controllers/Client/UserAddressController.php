<?php

namespace App\Http\Controllers\Client;

use App\Models\Client\UserAddress;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserAddressController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $addresses = $user->addresses()
            ->orderByDesc('id_default')
            ->orderByDesc('created_at')
            ->get();

        return view('client.user_addresses.index', compact('addresses'));
    }


    public function create()
    {
        return view('client.user_addresses.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'fullname'     => 'required|string|max:100',
            'phone_number' => ['required', 'regex:/^(09|03)[0-9]{8}$/'],
            'area'         => 'required|string|max:255',
            'address'      => 'required|string|max:255',
        ], [
            'fullname.required'     => 'Vui lòng nhập họ tên.',
            'fullname.max'          => 'Họ tên không được vượt quá 100 ký tự.',
            'phone_number.required' => 'Vui lòng nhập số điện thoại.',
            'phone_number.regex'    => 'Số điện thoại phải bắt đầu bằng 09 hoặc 03 và gồm đúng 10 chữ số.',
            'area.required'         => 'Vui lòng nhập khu vực.',
            'area.max'              => 'Khu vực không được vượt quá 255 ký tự.',
            'address.required'      => 'Vui lòng nhập địa chỉ cụ thể.',
            'address.max'           => 'Địa chỉ không được vượt quá 255 ký tự.',
        ]);


        $fullAddress = $request->address . ', ' . $request->area;

        if ($request->has('id_default')) {
            Auth::user()->addresses()->update(['id_default' => 0]);
        }

        UserAddress::create([
            'user_id'       => Auth::id(),
            'fullname'      => $request->fullname,
            'phone_number'  => $request->phone_number,
            'address'       => $fullAddress,
            'id_default'    => $request->has('id_default') ? 1 : 0,
        ]);

        return redirect()->route('user.addresses.index')->with('success', 'Thêm địa chỉ thành công!');
    }




    public function update(Request $request, $id)
    {
        // 1. Khởi tạo Validator
        $validator = Validator::make($request->all(), [
            'fullname'     => 'required|string|max:100',
            'phone_number' => ['required', 'regex:/^(09|03)[0-9]{8}$/'],
            'area'         => 'required|string|max:255',
            'address'      => 'required|string|max:255',
            'id_default'   => 'nullable|boolean',
        ], [
            'fullname.required'     => 'Vui lòng nhập họ tên.',
            'fullname.max'          => 'Họ tên không được vượt quá 100 ký tự.',
            'phone_number.required' => 'Vui lòng nhập số điện thoại.',
            'phone_number.regex'    => 'Số điện thoại phải bắt đầu bằng 09 hoặc 03 và gồm đúng 10 chữ số.',
            'area.required'         => 'Vui lòng nhập khu vực.',
            'area.max'              => 'Khu vực không được vượt quá 255 ký tự.',
            'address.required'      => 'Vui lòng nhập địa chỉ cụ thể.',
            'address.max'           => 'Địa chỉ không được vượt quá 255 ký tự.',
        ]);
        // 2. Nếu có lỗi → redirect về index, mang theo:
        //    - errors trong Bag tên 'edit'
        //    - session key 'edit_address_id' để view biết modal nào mở
        if ($validator->fails()) {
            return redirect()
                ->route('user.addresses.index')
                ->withErrors($validator, 'edit')
                ->withInput()
                ->with('edit_address_id', $id);
        }

        // 3. Nếu pass, xử lý tiếp (không đổi logic của bạn)
        $data = $validator->validated();

        $address = UserAddress::where('user_id', Auth::id())->findOrFail($id);

        // Không cho huỷ mặc định nếu đang là mặc định
        if ($address->id_default) {
            $data['id_default'] = 1;
        } elseif ($request->has('id_default')) {
            Auth::user()->addresses()->update(['id_default' => 0]);
            $data['id_default'] = 1;
        } else {
            $data['id_default'] = 0;
        }

        // Gộp area vào address
        $data['address'] = $data['address'] . ', ' . $data['area'];

        // Cập nhật
        $address->update([
            'fullname'     => $data['fullname'],
            'phone_number' => $data['phone_number'],
            'address'      => $data['address'],
            'id_default'   => $data['id_default'],
        ]);

        return redirect()
            ->route('user.addresses.index')
            ->with('success', 'Cập nhật địa chỉ thành công!');
    }




    public function destroy($id)
    {
        $user = Auth::user();
        $address = UserAddress::where('user_id', $user->id)->findOrFail($id);

        // Nếu đang là mặc định thì không cho xóa
        if ($address->id_default) {
            return redirect()
                ->route('user.addresses.index')
                ->with('error', 'Không thể xóa địa chỉ mặc định. Vui lòng thiết lập một địa chỉ khác làm mặc định trước.');
        }

        $address->delete();

        return redirect()
            ->route('user.addresses.index')
            ->with('success', 'Xoá địa chỉ thành công');
    }


    public function setDefault($id)
    {
        $user = Auth::user();

        // Reset địa chỉ mặc định cũ
        $user->addresses()->update(['id_default' => 0]);

        // Thiết lập địa chỉ mới
        $address = $user->addresses()->findOrFail($id);
        $address->id_default = 1;
        $address->save();

        // Redirect về index kèm fragment #address-{id}
        return redirect()
            ->route('user.addresses.index')
            ->withFragment('address-' . $id)
            ->with('success', 'Đã thiết lập địa chỉ mặc định');
    }
}

