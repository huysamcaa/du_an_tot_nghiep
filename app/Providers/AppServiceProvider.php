<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use App\Models\Admin\Category;

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

         View::composer('*', function ($view) {
            $categories = Category::where('is_active', 1)
                ->whereNull('parent_id')
                ->with('children')
                ->orderBy('ordinal')
                ->get();

            $view->with('categories', $categories);
        });
    }
}
