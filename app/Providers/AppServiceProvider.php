<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Pendaftar;

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
        // View Composer untuk sidebar badge notifikasi
        View::composer('admin.layouts.sidebar', function ($view) {
            $dokumenMenungguCount = Pendaftar::whereHas('payments', function ($q) {
                    $q->where('status', 'confirmed');
                })
                ->where('status', '!=', 'submitted')
                ->count();

            $view->with('dokumenMenungguCount', $dokumenMenungguCount);
        });
    }
}
