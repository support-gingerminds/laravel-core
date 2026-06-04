<?php

namespace Gingerminds\LaravelCore\Providers;

use ApiPlatform\State\ProviderInterface;
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
use Gingerminds\LaravelCore\Models\CacheableResourceInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Console\ModelMakeCommand as BaseModelMakeCommand;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class LaravelCoreServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/gingerminds-core.php',
            'gingerminds-core'
        );

        // Enregistrement des configurations ou services si nécessaire
        $this->app->register(LaravelCoreAuthServiceProvider::class);

        $this->app->resolving('config', function ($config) {
            $resources = $config->get('api-platform.resources', []);
            $corePath  = realpath(__DIR__ . '/../Models');
            if ($corePath && !in_array($corePath, $resources, true)) {
                $config->set('api-platform.resources', array_merge($resources, [$corePath]));
            }
        });

        $providerPath = __DIR__ . '/../ApiProvider';
        $iterator     = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($providerPath)
        );
        $toTag = [];
        foreach ($iterator as $file) {
            if (!$file->isFile() || $file->getExtension() !== 'php') {
                continue;
            }
            $relativePath = $file->getPathname();
            $relativePath = substr($relativePath, strlen($providerPath) + 1, -4); // retire le préfixe et .php
            $class        = 'Gingerminds\\LaravelCore\\ApiProvider\\'
                . str_replace(DIRECTORY_SEPARATOR, '\\', $relativePath);
            if (class_exists($class) && is_subclass_of($class, ProviderInterface::class)) {
                $toTag[] = $class;
            }
        }
        if ($toTag !== []) {
            $this->app->tag($toTag, ProviderInterface::class);
        }
    }

    public function boot(): void
    {
        // Chargement des routes du package
        if (! $this->app->routesAreCached()) {
            $this->loadRoutesFrom(__DIR__ . '/../../routes/web.php');
            $this->loadRoutesFrom(__DIR__ . '/../../routes/api.php');
        }

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
                __DIR__ . '/../../config/gingerminds-core.php' => config_path('gingerminds-core.php'),
            ], 'gingerminds-config');

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
                    // ← le 2ème argument est le container
                    return new class ($app->make('files')) extends BaseModelMakeCommand {
                        protected function getStub(): string
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

        // Avoid web server static-file rules intercepting "/livewire/livewire.js" with a 404.
        Livewire::setScriptRoute(function ($handle) {
            return Route::get('/livewire/script', $handle);
        });

        $this->listenAndFlushCacheFor('eloquent.saved: *');
        $this->listenAndFlushCacheFor('eloquent.deleted: *');
        $this->listenAndFlushCacheFor('eloquent.restored: *');
        $this->listenAndFlushCacheFor('eloquent.forceDeleted: *');
    }

    private function listenAndFlushCacheFor(string $eventName): void
    {
        Event::listen($eventName, function (string $eventName, array $payload): void {
            $model = $payload[0] ?? null;
            if (! $model instanceof Model) {
                return;
            }

            $this->flushResourceCache($model);
        });
    }

    private function flushResourceCache(Model $model): void
    {
        if (! $model instanceof CacheableResourceInterface) {
            return;
        }

        Cache::tags([$model::getCacheKey()])->flush();
    }
}
