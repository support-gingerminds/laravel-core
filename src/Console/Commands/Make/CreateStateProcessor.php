<?php

namespace Gingerminds\LaravelCore\Console\Commands\Make;

use Illuminate\Console\Command;

class CreateStateProcessor extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:state-processor {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Artisan make command to create StateProcessor';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        /** @var string $stateProcessorName */
        $stateProcessorName = $this->argument('name');

        $stateProcessorPathParts = explode('/', $stateProcessorName);

        $modelClass = array_pop($stateProcessorPathParts);

        $namespace = 'App\\StateProcessor';

        if ($stateProcessorPathParts !== []) {
            $namespace .= '\\' . implode('\\', $stateProcessorPathParts);
        }

        $stubPath = base_path('stubs/state-processor.stub');
        if (!file_exists($stubPath)) {
            $this->error("Stub file not found: {$stubPath}");
            return;
        }

        $stub = file_get_contents($stubPath);

        if ($stub === false) {
            $this->error("Failed to read stub file: {$stubPath}");
            return;
        }

        $stub           = str_replace('{{ class }}', class_basename($stateProcessorName), $stub);
        $stub           = str_replace('{{ namespace }}', $namespace, $stub);
        $modelShortName = class_basename($modelClass);
        $stub           = str_replace('{{ resourceModel }}', $modelShortName, $stub);

        $modelFqcn = 'App\\Models\\' . str_replace('/', '\\', $stateProcessorName);
        $stub      = str_replace('{{ resourceModelFqcn }}', $modelFqcn, $stub);

        $repositoryFqcn = 'App\\Repositories\\'
            . str_replace('/', '\\', $stateProcessorName)
            . 'Repository'
        ;
        $stub = str_replace('{{ resourceRepositoryFqcn }}', $repositoryFqcn, $stub);
        $stub = str_replace('{{ resourceRepository }}', $modelShortName . 'Repository', $stub);

        $requestFqcn = 'App\\Http\\Requests\\' . str_replace('/', '\\', $stateProcessorName) . 'Request';
        $stub        = str_replace('{{ resourceRequestFqcn }}', $requestFqcn, $stub);
        $stub        = str_replace('{{ resourceRequest }}', $modelShortName . 'Request', $stub);

        $path = app_path(
            'StateProcessor/' . str_replace('\\', '/', $stateProcessorName) . 'StateProcessor.php'
        );

        if (!is_dir(dirname($path))) {
            mkdir(dirname($path), 0755, true);
        }

        file_put_contents($path, $stub);

        $this->info("State processor created successfully: {$path}");
    }
}
