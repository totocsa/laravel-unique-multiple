<?php

namespace Totocsa\UniqueMultiple;

use Illuminate\Support\ServiceProvider;

class UniqueMultipleServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Ha van konfigurációs fájl, azt itt töltheted be
        //$this->mergeConfigFrom(__DIR__.'/../config/destroy-confirm-modal.php', 'destroy-confirm-modal');
    }

    public function boot()
    {
        // Publikálható migrációk
        $this->publishes([
            __DIR__ . '/resources' => resource_path(),
        ], 'laravel-unique-multiple');
    }
}
