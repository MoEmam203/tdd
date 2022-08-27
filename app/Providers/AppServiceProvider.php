<?php

namespace App\Providers;

use Google\Client;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Client::class,function(){
            $client = new  Client();

            $google_drive = config('services.google-drive');
            $client->setClientId($google_drive['id']);
            $client->setClientSecret($google_drive['secret']);
            $client->setRedirectUri($google_drive['url']);

            return $client;
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
