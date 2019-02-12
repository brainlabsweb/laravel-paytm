<?php

namespace Brainlabsweb\Paytm;

use Illuminate\Support\ServiceProvider;

class PaytmServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->bind('paytm',function() {
           return new PaytmService;
        });
    }

    public function boot()
    {
        $this->publishes([
            __DIR__ .'/config/paytm.php' => base_path('config/paytm.php')
        ]);
    }
}
