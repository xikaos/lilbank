<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Contracts\Domain\TransferenciaServiceContract;
use App\Services\Domain\TransferenciaService;

class TransferenciaServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(TransferenciaServiceContract::class, TransferenciaService::class);

        # Breaks on tests because of no type-hinting (empty buildStack on container)
        /*
            $this->app
                ->when(TransferenciaTest::class)
                ->needs(TransferenciaServiceContract::class)
                ->give(TransferenciaService::class); 
        */
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
