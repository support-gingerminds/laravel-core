<?php

namespace Gingerminds\LaravelCore\Console\Commands\Make;

use Illuminate\Console\Command;

class CreateRepository extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:repository {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Artisan make command to create repository';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $repositoryName = $this->argument('name');

        $repositoryPathParts = explode('/', $repositoryName);

        $modelClass = array_pop($repositoryPathParts);

        $namespace = 'App\\Repositories';

        if ($repositoryPathParts !== []) {
            $namespace .= '\\' . implode('\\', $repositoryPathParts);
        }

        $stubPath = base_path('stubs/repository.stub');
        if (!file_exists($stubPath)) {
            $this->error("Stub file not found: {$stubPath}");
            return;
        }

        $stub = file_get_contents($stubPath);

        if ($stub === false) {
            $this->error("Failed to read stub file: {$stubPath}");
            return;
        }

        $stub           = str_replace('{{ class }}', class_basename($repositoryName), $stub);
        $stub           = str_replace('{{ namespace }}', $namespace, $stub);
        $modelShortName = class_basename($modelClass);
        $stub           = str_replace('{{ resourceModel }}', $modelShortName, $stub);

        $modelFqcn = 'App\\Models\\' . str_replace('/', '\\', $repositoryName);
        $stub      = str_replace('{{ resourceModelFqcn }}', $modelFqcn, $stub);

        $path = app_path(
            'Repositories/' . str_replace('\\', '/', $repositoryName) . 'Repository.php'
        );

        if (!is_dir(dirname($path))) {
            mkdir(dirname($path), 0755, true);
        }

        file_put_contents($path, $stub);

        $this->info("Repository created successfully: {$path}");
    }
}
