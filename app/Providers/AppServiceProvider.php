<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\Storage\StorageAdapterInterface;
use App\Services\Storage\LocalStorageAdapter;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * Aquí registramos el binding para el adaptador de storage.
     *
     * @return void
     */
    public function register(): void
    {
        // Cuando se solicite StorageAdapterInterface, inyectamos LocalStorageAdapter
        $this->app->bind(StorageAdapterInterface::class, LocalStorageAdapter::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        //
    }
}