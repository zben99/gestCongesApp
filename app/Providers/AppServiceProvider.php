<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use App\Services\CongeAlertService;
use App\Services\HolidaysService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(CongeAlertService::class, function ($app) {
            return new CongeAlertService();
        });

                // Enregistrement de HolidaysService
        $this->app->singleton(HolidaysService::class, function ($app) {
            return new HolidaysService();
    });
    }

    

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);
        
    }
    



}
