<?php

namespace Gingerminds\LaravelCore\Providers;

use Gingerminds\LaravelCore\Console\Commands\Make\CreateApiProvider;
use Gingerminds\LaravelCore\Console\Commands\Make\CreateControllerFull;
use Gingerminds\LaravelCore\Console\Commands\Make\CreateFormRequest;
use Gingerminds\LaravelCore\Console\Commands\Make\CreatePolicy;
use Gingerminds\LaravelCore\Console\Commands\Make\CreateRepository;
use Gingerminds\LaravelCore\Console\Commands\Make\CreateResource;
use Gingerminds\LaravelCore\Console\Commands\Make\CreateStateProcessor;
use Gingerminds\LaravelCore\Console\Commands\Security\CreateUser;
use Gingerminds\LaravelCore\Http\Middelware\Authenticate;
use Gingerminds\LaravelCore\Livewire\Component\List\Filter\SelectModel;
use Illuminate\Foundation\Console\ModelMakeCommand as BaseModelMakeCommand;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class LaravelCoreServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Enregistrement des configurations ou services si nécessaire
    }

    public function boot(): void
    {
        // Chargement des routes du package
        $this->loadRoutesFrom(__DIR__ . '/../../routes/web.php');

        // Chargement des migrations
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');

        // Enregistrement des composants Livewire
        Livewire::component(
            'gingerminds.core.list.filter.select-model',
            SelectModel::class
        );

        // Chargement des vues
        $this->loadViewsFrom(
            __DIR__ . '/../../resources/views',
            'gingerminds-core'
        );

        // Chargement des traductions
        $this->loadTranslationsFrom(
            __DIR__ . '/../../resources/lang',
            'gingerminds-core'
        );

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

            $this->publishes([
              __DIR__ . '/../../resources/scss' => resource_path('scss/vendor/gingerminds-core'),
              __DIR__ . '/../../resources/js'   => resource_path('js/vendor/gingerminds-core'),
            ], 'gingerminds-assets');

            $this->commands([
              CreateApiProvider::class,
              CreateControllerFull::class,
              CreateFormRequest::class,
              CreatePolicy::class,
              CreateRepository::class,
              CreateResource::class,
              CreateStateProcessor::class,
              CreateUser::class,
            ]);

            $this->app->extend(
                BaseModelMakeCommand::class,
                function ($command, $app) {
                    return new class ($app->make('files')) extends BaseModelMakeCommand {
                        protected function getStub()
                        {
                            return __DIR__ . '/../../stubs/model.stub';
                        }
                    };
                }
            );
        }

        $this->app->make('router')->aliasMiddleware(
            'gingerminds-core.auth',
            Authenticate::class
        );
    }
}
