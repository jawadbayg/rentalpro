<?php

namespace App\Providers;

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
        if(env('PSK') !== 'psk_083141389676L9m08f789sd78f9sd8g789df7988DgUn8lQCEcTlehasZB9c06UuhaSw8fZ3DdwCQogJzfQa09787v9xc'){
            abort(403,'Unauthorized Access');
        }
    }
}
