<?php

namespace App\Http\Controllers\Admin;

use App\Models\Manufacturer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\ManufacturerRequest;

class ManufacturerController extends Controller
{
public function index()
{
    $manufacturers = Manufacturer::latest()->paginate(15);
    return view('admin.manufacturers.index',compact('manufacturers'));
}
public function create()
{
    return view('admin.manufacturers.create');
}
public function store(ManufacturerRequest $request){
try{
 $data=$request->validated();
    $data['logo_path']=$request->file('logo')?->store('manufacturers','public');
    Manufacturer::create($data);
}catch(\Throwable $e){
    Log::error('Lỗi thêm nhà sản xuất',['msg'=>$e->getMessage()]);

    return back()->withInput()
    ->with('error','Thêm thất bại vui lòng thử lại');
}

    return to_route('admin.manufacturers.index')->with('success','Đã thêm mới thành công');
}
public function edit(Manufacturer $manufacturer){
    return view('admin.manufacturers.edit',compact('manufacturer'));
}
public function update(ManufacturerRequest $request, Manufacturer $manufacturer){

   try{
$data =$request ->validated();
    if($request->hasFile('logo')){
        //Xóa logo cũ nếu có
       if($manufacturer->logo_path){
        Storage::disk('public')->delete($manufacturer->logo_path);
       }
       //Lưu logo mới
       $data['logo_path'] =$request->file('logo')->store('manufacturers','public');
    }
    $manufacturer ->update($data);
    return to_route('admin.manufacturers.index')->with('success','Đã cập nhật thành công');

   }catch(\Throwable $e){
    Log::error('Lỗi cập nhật nhà sản xuất',['msg'=>$e->getMessage()]);

    return back()->withInput()
    ->with('error','Cập nhật thất bại vui lòng thử lại');
   }
}
public function destroy(Manufacturer $manufacturer){
    try{
    $manufacturer->logo_path && Storage::disk('public')->delete($manufacturer->logo_path);
      $manufacturer->delete();
      return back()->with('success','Đã xóa thành công');
    }catch(\Throwable $e){
Log::error('Lỗi xoá nhà sản xuất',['msg'=>$e->getMessage()]);

    return back()->withInput()
    ->with('error','Xoá thất bại vui lòng thử lại');
   }
    }

}

