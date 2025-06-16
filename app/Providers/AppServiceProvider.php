<?php

namespace App\Providers;


use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\PersonalAccessToken;
use Laravel\Sanctum\Sanctum;
use Dedoc\Scramble\Scramble;
use Illuminate\Routing\Route; // <- Tambahkan ini
use Illuminate\Support\Str;
use Dedoc\Scramble\Support\Generator\OpenApi;
use Dedoc\Scramble\Support\Generator\SecurityScheme;


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
        //Model::preventLazyLoading(!app()->isProduction());
        Paginator::useTailwind();
        Gate::define('admin', function ($user) {
            return $user->is_admin == true;
        });
        // Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);
        // Scramble::configure()->routes(function (Route $route) {
        //     return Str::startsWith($route->uri, 'api/');
        // });
        Scramble::configure()->routes(function (Route $route) {
            return Str::startsWith($route->uri, 'api/');
        })->withDocumentTransformers(cb: function (OpenApi $openApi): void {
            $openApi->secure(
                securityScheme:SecurityScheme::http(scheme: 'bearer')
            );
        });
    }
}
