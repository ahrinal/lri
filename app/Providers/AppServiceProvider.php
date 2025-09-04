<?php

namespace App\Providers;

use App\Models\Institution;
use Illuminate\Support\Facades\View;

use App\Models\LriType;
use Illuminate\Support\ServiceProvider;

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
        $lri = Institution::whereNull('parent_id')->get();
        // var_dump($lri);die;
        View::share(['lri' => $lri]); 
    }
}
