<?php

namespace App\Providers;

use App\Interfaces\BrandInterface;
use App\Interfaces\CategoryInterface;
use App\Interfaces\SliderInterface;
use App\Repositories\SliderRepositories;
use App\Repositories\BrandRepositories;
use App\Repositories\CategoryRepositories;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(
            CategoryInterface::class,
            CategoryRepositories::class,
        );

        $this->app->bind(
            BrandInterface::class,
            BrandRepositories::class,
        );

        $this->app->bind(
            SliderInterface::class,
            SliderRepositories::class,
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
