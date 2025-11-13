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

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->loadApiVersions();
    }


    // protected function loadApiVersions(): void
    // {
    // $path = base_path('routes/api_versions.php');
    // if (file_exists($path)) {
    //     require $path;
    //     }
    // }

    protected function loadApiVersions(): void
{
    $path = base_path('routes/api_versions.php');
    if (file_exists($path)) {
        require $path;
    }
}
}
