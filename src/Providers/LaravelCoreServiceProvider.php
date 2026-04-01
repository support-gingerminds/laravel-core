<?php

namespace Gingerminds\LaravelCore\Providers;

use Illuminate\Support\ServiceProvider;
use Gingerminds\LaravelCore\Console\Commands\CreateApiProvider;
use Gingerminds\LaravelCore\Console\Commands\CreateResource;
use Gingerminds\LaravelCore\Console\Commands\CreateControllerFull;
use Gingerminds\LaravelCore\Console\Commands\CreateFormRequest;
use Gingerminds\LaravelCore\Console\Commands\CreateRepository;
use Gingerminds\LaravelCore\Console\Commands\CreateStateProcessor;
use Gingerminds\LaravelCore\Livewire\Component\List\Filter\SelectModel;
use Livewire\Livewire;

class LaravelCoreServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Enregistrement des configurations ou services si nécessaire
    }

    public function boot(): void
    {
        // Enregistrement des composants Livewire
        Livewire::component('gingerminds.core.list.filter.select-model', SelectModel::class);

        // Chargement des vues
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'gingerminds-core');

        // Chargement des traductions
        $this->loadTranslationsFrom(__DIR__ . '/../../resources/lang', 'gingerminds-core');

        // Publication des ressources pour surcharge par le projet Skeleton
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../../resources/views' => resource_path('views/vendor/gingerminds-core'),
            ], 'gingerminds-views');

            $this->publishes([
                __DIR__ . '/../../resources/lang' => $this->app->langPath('vendor/gingerminds-core'),
            ], 'gingerminds-lang');

            $this->publishes([
                __DIR__ . '/../../stubs' => base_path('stubs/vendor/gingerminds-core'),
            ], 'gingerminds-stubs');

            $this->commands([
                CreateApiProvider::class,
                CreateResource::class,
                CreateControllerFull::class,
                CreateFormRequest::class,
                CreateRepository::class,
                CreateStateProcessor::class,
            ]);
        }
    }
}
