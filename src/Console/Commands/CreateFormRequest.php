<?php

namespace Gingerminds\LaravelCore\Console\Commands;

use Illuminate\Console\Command;

class CreateFormRequest extends Command
{
    protected $signature = 'make:form-request {name}';

    protected $description = 'Artisan make command to create FormRequest';

    public function handle(): void
    {
        $requestName = $this->argument('name');
        $repositoryPathParts = explode('/', $requestName);
        $namespace = 'App\\Http\\Requests';
        array_pop($repositoryPathParts);

        if ($repositoryPathParts !== []) {
            $namespace .= '\\' . implode('\\', $repositoryPathParts);
        }

        $stubPath = base_path('stubs/vendor/gingerminds-core/form-request.stub');
        if (!file_exists($stubPath)) {
            $stubPath = __DIR__ . '/../../../stubs/form-request.stub';
        }

        $stub = file_get_contents($stubPath);
        if ($stub === false) {
            $this->error("Failed to read stub file: {$stubPath}");
            return;
        }

        $stub = str_replace('{{ class }}', class_basename($requestName), $stub);
        $stub = str_replace('{{ namespace }}', $namespace, $stub);

        $path = app_path(
            'Http/Requests/' . str_replace('\\', '/', $requestName) . 'Request.php'
        );

        if (!is_dir(dirname($path))) {
            mkdir(dirname($path), 0755, true);
        }

        file_put_contents($path, $stub);
        $this->info("Form Request created successfully: {$path}");
    }
}
