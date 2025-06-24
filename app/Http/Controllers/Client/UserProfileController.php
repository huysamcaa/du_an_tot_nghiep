<?php

namespace App\Http\Controllers\Client;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user(); // Lấy thông tin người dùng đang đăng nhập
        return view('client.profile.show', compact('user'));
    }
    public function edit(){
        $user= Auth::user();
        return view('client.profile.edit',compact('user'));
    }
    public function update(Request $request){
        $user=  Auth::user();
        $data=$request->validate([
             'name' => 'required|string|max:255',
            'phone_number' => 'nullable|string|max:20',
            'gender' => 'nullable|in:male,female',
            'birthday' => 'nullable|date',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);
        if($request -> hasFile('avatar')){
            if($user->avatar){
                Storage::disk('public')->delete($user->avatar);
            }
            $data['avatar']=$request->file('avatar')->store('avatars','public');
        }
        $user->update($data);
        return redirect()->route('client.profile.show')->with('success', 'Cập nhật thông tin thành công!');

        }
}
