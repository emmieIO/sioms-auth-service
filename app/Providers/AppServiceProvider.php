<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Response;

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
        Response::macro("success", fn(array $data, string $message, int $status=200) => Response::json([
                'status' => $status,
                'message' => $message,
                'data' => $data,
            ], $status
        ));

        Response::macro("error", fn(string $message = 'Something went wrong', int $status = 400, array $errors = [])=> Response::json([
            "status" => $status,
            "message"=> $message,
            "errors" => $errors
        ],$status));
    }
}
