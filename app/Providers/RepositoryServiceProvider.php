<?php

namespace App\Providers;

use App\Interfaces\BrandInterface;
use App\Interfaces\CategoryInterface;
use App\Interfaces\SliderInterface;
use App\Interfaces\AttributeInterface;
use App\Interfaces\VariationInterface;
use App\Repositories\SliderRepositories;
use App\Repositories\BrandRepositories;
use App\Repositories\CategoryRepositories;
use App\Repositories\AttributeRepositories;
use App\Repositories\VariationRepositories;
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

        $this->app->bind(
            AttributeInterface::class,
            AttributeRepositories::class,
        );

        $this->app->bind(
            VariationInterface::class,
            VariationRepositories::class,
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
