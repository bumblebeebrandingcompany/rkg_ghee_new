<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        Blade::directive('num_format', function ($expression) {
            return "number_format($expression, 2, '.', ',')";
        });

        //Attaches the route prefix based user role.
        Blade::directive('prefix_route', function ($expression) {
            $user = Auth::user();
            if($user->role == 'distributor'){
                return "'dist." . $expression . "'";
            } elseif($user->role == 'sales_rep'){
                return "'sales_rep." . $expression . "'";
            } elseif($user->role == 'wholesaler'){
                return "'wholesaler." . $expression . "'";
            } else {
                return "'admin." . $expression . "'";
            }
        });
    }
}
