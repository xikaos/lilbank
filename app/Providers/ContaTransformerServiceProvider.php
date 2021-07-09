<?php

namespace App\Providers;

use App\Transformers\ContaJsonTransformer;
use App\Transformers\ContaTransformerInterface;
use Illuminate\Support\ServiceProvider;

class ContaTransformerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            ContaTransformerInterface::class,
            ContaJsonTransformer::class
        );
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
    }
}
