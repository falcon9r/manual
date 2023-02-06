<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Response;

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
        Response::macro('attachment', function ($content) {

            $headers = [
                'Content-type'        => 'text/html',
                'Content-Disposition' => 'attachment; filename="manual.html"',
            ];

            return Response::make($content, 200, $headers);

        });
        //
    }
}
