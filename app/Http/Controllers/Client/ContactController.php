<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contact;

class ContactController extends Controller
{
      public function index()
    {   
        $contact = Contact::first(); // Hoặc Contact::all() nếu có nhiều

        return view('client.contact.index', compact('contact'));
    }
  public function submit(Request $request)
{
  $request->validate([
    'name' => 'required|string|min:7|max:50',
    'email' => ['required', 'email', 'regex:/^[\w.+-]+@gmail\.com$/i'],
    'phone' => ['required', 'regex:/^0\d{9}$/'],
    'message' => 'required|string',
], [
     'name.required' => 'Vui lòng nhập họ và tên',
    'name.min' => 'Họ và tên phải lớn hơn 6 ký tự',
    'name.max' => 'Họ và tên không được vượt quá 50 ký tự',

    'email.required' => 'Vui lòng nhập email',
    'email.email' => 'Email không đúng định dạng cơ bản',
    'email.regex' => 'Email phải có đuôi @gmail.com',

    'phone.required' => 'Vui lòng nhập số điện thoại',
    'phone.regex' => 'Số điện thoại phải bắt đầu bằng 0 và đủ 10 chữ số',

    'message.required' => 'Vui lòng nhập nội dung tin nhắn',
]);


    Contact::create([
        'name' => $request->name,
        'email' => $request->email,
        'phone' => $request->phone,
        'message' => $request->message,
    ]);

    return redirect()->route('client.contact.index')->with('success', 'Cảm ơn bạn đã liên hệ!');
}

}
