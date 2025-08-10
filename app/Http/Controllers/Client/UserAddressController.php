<?php

namespace App\Http\Controllers\Client;

use App\Models\Client\UserAddress;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class UserAddressController extends Controller
{
    /**
     * Hiển thị danh sách địa chỉ của người dùng.
     */
    public function index()
    {
        $user = Auth::user();

        $addresses = $user->addresses()
            ->orderByDesc('id_default')
            ->orderByDesc('created_at')
            ->get();

        // Tải dữ liệu địa chỉ từ file JSON và truyền vào view
        $vnLocationsPath = public_path('assets/Client/js/vn-location.json');
        $vnLocationsData = [];
        if (File::exists($vnLocationsPath)) {
            $vnLocationsData = json_decode(File::get($vnLocationsPath), true);
        }

        return view('client.user_addresses.index', compact('addresses', 'vnLocationsData'));
    }

    /**
     * Lưu địa chỉ mới của người dùng.
     */
    public function store(Request $request)
    {
        $request->validate([
            'fullname'     => 'required|string|max:100',
            'phone_number' => ['required', 'regex:/^0[0-9]{9}$/'],
            'province'     => 'required|string|max:100',
            'ward'         => 'required|string|max:100',
            'address'      => 'required|string|max:255',
        ], [
            'fullname.required'      => 'Vui lòng nhập họ tên.',
            'fullname.max'           => 'Họ tên không được vượt quá 100 ký tự.',
            'phone_number.required'  => 'Vui lòng nhập số điện thoại.',
            'phone_number.regex'     => 'Số điện thoại phải bắt đầu bằng 0 và gồm đúng 10 chữ số.',
            'province.required'      => 'Vui lòng chọn Tỉnh/Thành phố.',
            'ward.required'          => 'Vui lòng chọn Phường/Xã.',
            'address.required'       => 'Vui lòng nhập địa chỉ cụ thể.',
            'address.max'            => 'Địa chỉ không được vượt quá 255 ký tự.',
        ]);

        $fullAddress = sprintf('%s, %s, %s',
            $request->address,
            $request->ward,
            $request->province
        );


        // Cập nhật địa chỉ mặc định nếu có
        if ($request->has('id_default')) {
            Auth::user()->addresses()->update(['id_default' => 0]);
        }

        UserAddress::create([
            'user_id'      => Auth::id(),
            'fullname'     => $request->fullname,
            'phone_number' => $request->phone_number,
            'province'     => $request->province,
            'ward'         => $request->ward,
            'address'      => $fullAddress,
            'id_default'   => $request->has('id_default') ? 1 : 0,
        ]);

        return redirect()->route('user.addresses.index')->with('success', 'Thêm địa chỉ thành công!');
    }

    /**
     * Cập nhật địa chỉ hiện có.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'fullname'     => 'required|string|max:100',
            'phone_number' => ['required', 'regex:/^0[0-9]{9}$/'],
            'province'     => 'required|string|max:100',
            'ward'         => 'required|string|max:100',
            'address'      => 'required|string|max:255',
            'id_default'   => 'nullable|boolean',
        ], [
            'fullname.required'      => 'Vui lòng nhập họ tên.',
            'fullname.max'           => 'Họ tên không được vượt quá 100 ký tự.',
            'phone_number.required'  => 'Vui lòng nhập số điện thoại.',
            'phone_number.regex'     => 'Số điện thoại phải bắt đầu bằng 0 và gồm đúng 10 chữ số.',
            'province.required'      => 'Vui lòng chọn Tỉnh/Thành phố.',
            'ward.required'          => 'Vui lòng chọn Phường/Xã.',
            'address.required'       => 'Vui lòng nhập địa chỉ cụ thể.',
            'address.max'            => 'Địa chỉ không được vượt quá 255 ký tự.',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->route('user.addresses.index')
                ->withErrors($validator)
                ->withInput()
                ->with('edit_address_id', $id);
        }

        $data = $validator->validated();

        $address = UserAddress::where('user_id', Auth::id())->findOrFail($id);

        if ($address->id_default) {
            $data['id_default'] = 1;
        } elseif (isset($data['id_default'])) {
            Auth::user()->addresses()->update(['id_default' => 0]);
            $data['id_default'] = 1;
        } else {
            $data['id_default'] = 0;
        }

        $data['address'] = sprintf('%s, %s, %s',
            $data['address'],
            $data['ward'],
            $data['province']
        );

        $address->update($data);

        return redirect()
            ->route('user.addresses.index')
            ->with('success', 'Cập nhật địa chỉ thành công!');
    }

    /**
     * Xóa một địa chỉ của người dùng.
     */
    public function destroy($id)
    {
        $user = Auth::user();
        $address = UserAddress::where('user_id', $user->id)->findOrFail($id);

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

    /**
     * Thiết lập một địa chỉ làm mặc định.
     */
    public function setDefault($id)
    {
        $user = Auth::user();

        $user->addresses()->update(['id_default' => 0]);

        $address = $user->addresses()->findOrFail($id);
        $address->id_default = 1;
        $address->save();

        return redirect()
            ->route('user.addresses.index')
            ->withFragment('address-' . $id)
            ->with('success', 'Đã thiết lập địa chỉ mặc định');
    }
}
