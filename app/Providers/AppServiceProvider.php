<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\UrlGenerator;

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
    public function boot(UrlGenerator $url)
    {
        //
        Blade::directive('date', function ($expression) {
            return "<?php echo date('Y-m-d', strtotime($expression)); ?>";
        });

        Blade::directive('jsDateFormatString', function($e) {
           return "<?php echo 'yyyy-mm-dd' ?>"; 
        });

        if(env('REDIRECT_HTTPS', false)){
            $url->forceSchema('https');
        }
    }
}
