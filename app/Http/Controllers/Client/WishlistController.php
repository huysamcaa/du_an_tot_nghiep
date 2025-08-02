<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Client\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class WishlistController extends Controller
{
    public function store(Request $request)
    {
        try {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Bạn cần đăng nhập'], 401);
        }
        // Validate để đảm bảo product_id hợp lệ
            $request->validate([
                'product_id' => 'required|integer|exists:products,id'
            ]);
        // Kiểm tra nếu thích rồi thì không thích nữa
        $exists = Wishlist::where('user_id', $user->id)
                ->where('product_id',$request->product_id)
                ->exists();
        if($exists){
            // Nếu đã có thì xoá
            Wishlist::where('user_id', $user->id)
                    ->where('product_id',$request->product_id)
                    ->delete();
            return response()->json([
                'success' => true,
                'message' =>'Đã xoá khỏi danh sách yêu thích',
                'action' => 'removed'
            ]);
        }else{
            // nếu chưa có thì thêm
            Wishlist::create([
                'user_id' => $user->id,
                'product_id' => $request->product_id
            ]);
            return response()->json([
                'success' => true,
                'message' => 'Đã thêm vào danh sách yêu thích',
                'action' => 'added'
            ]);
        }
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Sản phẩm không hợp lệ'
            ], 422);
        } catch (\Exception $e) {
            Log::error("Wishlist error:". $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Lỗi server: ' . $e->getMessage()
            ], 500);
        }
    }
    public function index()
    {
        $wishlists = Wishlist::with('product')
                    ->where('user_id', Auth::id())
                    ->get();
        return view('client.wishlists.wishlist',compact('wishlists'));
    }
    // Thêm method destroy để xóa wishlist
    public function destroy($id)
    {
        try {
            $wishlist = Wishlist::where('id', $id)
                              ->where('user_id', Auth::id())
                              ->first();

            if (!$wishlist) {
                return redirect()->back()->with('error', 'Không tìm thấy sản phẩm trong danh sách yêu thích');
            }

            $wishlist->delete();
            return redirect()->back()->with('success', 'Đã xóa sản phẩm khỏi danh sách yêu thích');

        } catch (\Exception $e) {
            Log::error("Wishlist delete error: " . $e->getMessage());
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi xóa sản phẩm');
        }
    }
}
