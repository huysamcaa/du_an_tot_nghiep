<?php

namespace App\Http\Controllers\Client;

use App\Models\Client\UserAddress;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


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
        'fullname' => 'required|string|max:100',
        'phone_number' => 'required|digits_between:10,11',
        'area' => 'required|string|max:255',
    ], [
        'fullname.required' => 'Vui lòng nhập họ tên.',
        'phone_number.required' => 'Vui lòng nhập số điện thoại.',
        'phone_number.digits_between' => 'Số điện thoại phải có từ 10 đến 11 chữ số và chỉ chứa số.',
        'area.required' => 'Vui lòng nhập khu vực.',
    ]);

        $fullAddress = $request->address . ', ' . $request->area;

        if ($request->has('id_default')) {
            Auth::user()->addresses()->update(['id_default' => 0]);
        }

        UserAddress::create([
            'user_id' => Auth::id(),
            'fullname' => $request->fullname,
            'phone_number' => $request->phone_number,
            'address' => $fullAddress,
            'id_default' => $request->has('id_default') ? 1 : 0,
        ]);

        return redirect()->route('user.addresses.index')->with('success', 'Thêm địa chỉ thành công!');
    }



public function update(Request $request, $id)
{
    // Validate dữ liệu đầu vào
    $data = $request->validate([
        'fullname'     => 'required|string|max:100',
        'phone_number' => 'required|digits_between:10,11',
        'area'         => 'required|string|max:255',
        'address'      => 'required|string|max:255',
        'id_default'   => 'nullable|boolean',
    ], [
        'fullname.required'           => 'Vui lòng nhập họ tên.',
        'fullname.max'                => 'Họ tên không được vượt quá 100 ký tự.',
        'phone_number.digits_between' => 'Số điện thoại phải có từ 10 đến 11 số.',
        'area.required'               => 'Vui lòng nhập khu vực.',
        'address.required'            => 'Vui lòng nhập địa chỉ cụ thể.',
    ]);

    // Tìm địa chỉ của người dùng hiện tại
    $address = UserAddress::where('user_id', Auth::id())->findOrFail($id);

    // Nếu địa chỉ hiện tại là mặc định → không được huỷ mặc định
    if ($address->id_default) {
        $data['id_default'] = 1;
    } else {
        // Nếu địa chỉ không mặc định và checkbox được chọn
        if ($request->has('id_default')) {
            // Reset tất cả về 0 trước
            Auth::user()->addresses()->update(['id_default' => 0]);
            $data['id_default'] = 1;
        } else {
            $data['id_default'] = 0;
        }
    }

    // Gộp khu vực vào địa chỉ
    $data['address'] = $data['address'] . ', ' . $data['area'];

    // Cập nhật địa chỉ
    $address->update([
        'fullname'     => $data['fullname'],
        'phone_number' => $data['phone_number'],
        'address'      => $data['address'],
        'id_default'   => $data['id_default'],
    ]);

    return redirect()->route('user.addresses.index')
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

