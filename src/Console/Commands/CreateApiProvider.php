<?php

namespace Gingerminds\LaravelCore\Console\Commands;

use Illuminate\Console\Command;

class CreateApiProvider extends Command
{
    protected $signature = 'make:api-provider {name}';

    protected $description = 'Artisan make command to create Api Provider';

    public function handle(): void
    {
        $providerName = $this->argument('name');
        $providerPathParts = explode('/', $providerName);
        $modelClass = array_pop($providerPathParts);
        $namespace = 'App\\ApiProvider';

        if ($providerPathParts !== []) {
            $namespace .= '\\' . implode('\\', $providerPathParts);
        }

        $stubPath = base_path('stubs/vendor/gingerminds-core/api-provider.stub');
        if (!file_exists($stubPath)) {
            $stubPath = __DIR__ . '/../../../stubs/api-provider.stub';
        }

        $stub = file_get_contents($stubPath);
        if ($stub === false) {
            $this->error("Failed to read stub file: {$stubPath}");
            return;
        }

        $stub           = str_replace('{{ class }}', class_basename($providerName), $stub);
        $stub           = str_replace('{{ namespace }}', $namespace, $stub);
        $modelShortName = class_basename($modelClass);
        $stub           = str_replace('{{ resourceModel }}', $modelShortName, $stub);

        $modelFqcn = 'App\\Models\\' . str_replace('/', '\\', $providerName);
        $stub      = str_replace('{{ resourceModelFqcn }}', $modelFqcn, $stub);

        if ($providerPathParts !== []) {
            $repositoryFqcn = 'App\\Repositories\\' . str_replace('/', '\\', $providerName);
            $stub           = str_replace('{{ resourceRepositoryFqcn }}', $repositoryFqcn, $stub);
        }

        $path = app_path(
            'ApiProvider/' . str_replace('\\', '/', $providerName) . 'Provider.php'
        );

        if (!is_dir(dirname($path))) {
            mkdir(dirname($path), 0755, true);
        }

        file_put_contents($path, $stub);

        $this->info("Api Provider created successfully: {$path}");
    }
}
