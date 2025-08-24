<?php

namespace App\Providers;

use App\Models\Admin\Category;
use App\Models\BlogCategory; // 👈 nhớ import model blog category
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Admin\OrderOrderStatus;
use App\Observers\OrderOrderStatusObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrap();

        OrderOrderStatus::observe(OrderOrderStatusObserver::class);
        
            View::composer('*', function ($view) {
        $footerCategories = Category::where('is_active', 1)
            ->whereNull('parent_id') // nếu bạn chỉ muốn lấy danh mục cha
            ->orderBy('ordinal')
            ->take(8) // lấy 8 danh mục đầu tiên
            ->get();
        $view->with('footerCategories', $footerCategories);
    });


        // Truyền biến ra mọi view
        View::composer('*', function ($view) {
            // Danh mục ở footer
            $footerCategories = Category::where('is_active', 1)
                ->whereNull('parent_id') // nếu bạn chỉ muốn lấy danh mục cha
                ->orderBy('ordinal')
                ->take(8) // lấy 8 danh mục đầu tiên
                ->get();

            // Danh mục blog
            $blogCategories = BlogCategory::all();


            $view->with([
                'footerCategories' => $footerCategories,
                'blogCategories'   => $blogCategories,
            ]);
        });

    }
}
