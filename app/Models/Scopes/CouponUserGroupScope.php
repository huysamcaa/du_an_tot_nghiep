<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\DB;

class CouponUserGroupScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        if (app()->runningInConsole()) return;

        $req = Request::instance();
        if ($req && $req->is('admin/*')) return;

        $user      = Auth::user();
        $userId    = $user?->id;
        $userGroup = $user?->user_group ?? 'guest';

        $table = $model->getTable(); // 'coupons'

        // Mã user đã claim nhưng CHƯA dùng và KHÔNG tồn tại dòng đã dùng
        $claimedIds = function ($q) use ($userId) {
            $q->from('coupon_user as cu1')
              ->select('cu1.coupon_id')
              ->where('cu1.user_id', $userId)
              ->whereNull('cu1.used_at')
              ->whereNull('cu1.order_id')
              ->whereNotExists(function ($qq) use ($userId) {
                  $qq->from('coupon_user as cu2')
                     ->select(DB::raw(1))
                     ->whereColumn('cu2.coupon_id', 'cu1.coupon_id')
                     ->where('cu2.user_id', $userId)
                     ->where(function ($w) {
                         $w->whereNotNull('cu2.used_at')
                           ->orWhereNotNull('cu2.order_id');
                     });
              });
        };

        // Mã user đã DÙNG (ít nhất 1 dòng used)
        $usedIds = function ($q) use ($userId) {
            $q->select('coupon_id')
              ->from('coupon_user')
              ->where('user_id', $userId)
              ->where(function ($w) {
                  $w->whereNotNull('used_at')
                    ->orWhereNotNull('order_id');
              });
        };

        $builder->where(function ($q) use ($userId, $userGroup, $claimedIds, $usedIds, $table) {
            // Public/đúng group và loại hết mã đã dùng
            $q->where(function ($qq) use ($userGroup, $usedIds, $userId, $table) {
                $qq->whereNull("$table.user_group")
                   ->orWhere("$table.user_group", $userGroup);

                if ($userId) {
                    $qq->whereNotIn("$table.id", $usedIds);
                }
            });

            // Hoặc là mã đã claim & chưa dùng (và không có dòng used nào)
            if ($userId) {
                $q->orWhereIn("$table.id", $claimedIds);
            }
        });
    }
}
