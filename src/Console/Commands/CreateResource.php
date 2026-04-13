<?php

namespace Gingerminds\LaravelCore\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class CreateResource extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:resource
        {name : Namespace/Model name (e.g. PartnerCompany/PartnerCompany)}
        {--trad-base=}
        {--api}
        {--m|migration}
        {--f|factory}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a full resource: Model, Repository, FormRequest, '
    . 'and Controller with blades, routes, and translations';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $name     = trim((string) $this->argument('name'));
        $tradBase = $this->option('trad-base');

        if ($name === '') {
            $this->error('Name is required');
            return Command::FAILURE;
        }

        $this->info("Creating resource for: {$name}");

        // 0. Create Model
        $this->info('Step 0: Creating Model...');
        $modelOptions = ['name' => $name];
        if ($this->option('migration')) {
            $modelOptions['--migration'] = true;
        }
        if ($this->option('factory')) {
            $modelOptions['--factory'] = true;
        }
        $this->call('make:model', $modelOptions);

        $this->info('Step 1: Creating Repository...');
        $this->call('make:repository', ['name' => $name]);

        $this->info('Step 2: Creating FormRequest...');
        $requestName = $name;
        $this->call('make:form-request', ['name' => $requestName]);

        $this->info('Step 3: Creating Controller, Blades, Routes and Translations...');
        $controllerOptions = ['name' => $name];
        if ($tradBase) {
            $controllerOptions['--trad-base'] = $tradBase;
        }
        $this->call('make:controller-full', $controllerOptions);

        $this->info('Step 4: Creating Policy...');
        $this->call('make:policy', ['name' => $name]);

        if ($this->option('api')) {
            $this->info('Step 5: Setting up API Resource in Model...');
            $this->setupApi($name);
        }

        $this->info('Resource created successfully.');

        return Command::SUCCESS;
    }

    /**
     * Setup API Resource in the model.
     */
    protected function setupApi(string $name): void
    {
        $modelPath = app_path('Models/' . str_replace('\\', '/', $name) . '.php');
        if (!file_exists($modelPath)) {
            $this->error("Model not found at: {$modelPath}");
            return;
        }

        $this->call('make:state-processor', ['name' => $name]);
        $this->call('make:api-provider', ['name' => $name]);

        $fileContent = file_get_contents($modelPath);
        if ($fileContent === false) {
            $this->error("Could not read model file: {$modelPath}");
            return;
        }
        $content             = $fileContent;
        $modelBasename       = class_basename($name);
        $snakeModel          = Str::snake($modelBasename);
        $stateProcessorClass = "{$modelBasename}StateProcessor";
        $stateProcessorFqcn  = 'App\\StateProcessor\\' . str_replace('/', '\\', $name) . 'StateProcessor';
        $stateProviderClass  = "{$modelBasename}Provider";
        $stateProviderFqcn   = 'App\\ApiProvider\\' . str_replace('/', '\\', $name) . 'Provider';

        $uses = [
            $stateProcessorFqcn,
            $stateProviderFqcn,
            'ApiPlatform\Metadata\ApiProperty',
            'ApiPlatform\Metadata\ApiResource',
            'ApiPlatform\Metadata\Delete',
            'ApiPlatform\Metadata\Get',
            'ApiPlatform\Metadata\GetCollection',
            'ApiPlatform\Metadata\Patch',
            'ApiPlatform\Metadata\Post',
            'Symfony\Component\Serializer\Attribute\Groups',
        ];

        $usesContent = '';
        foreach ($uses as $use) {
            if (!str_contains($content, "use {$use};")) {
                $usesContent .= "use {$use};\n";
            }
        }

        if ($usesContent !== '') {
            $content = (string) preg_replace('/namespace .*;/', "$0\n\n" . rtrim($usesContent), $content);
        }

        $stubPath = base_path('stubs/api-resource.stub');
        if (!file_exists($stubPath)) {
            $this->error("Stub not found at: {$stubPath}");
            return;
        }

        $stubContent = file_get_contents($stubPath);
        if ($stubContent === false) {
            $this->error("Could not read stub file: {$stubPath}");
            return;
        }
        $apiResourceAttr = $stubContent;
        $apiResourceAttr = str_replace(
            ['{{snakeModel}}', '{{stateProcessorClass}}', '{{stateProviderClass}}'],
            [$snakeModel, $stateProcessorClass, $stateProviderClass],
            $apiResourceAttr
        );

        if (!str_contains($content, '#[ApiResource')) {
            $content = (string) preg_replace(
                '/class ' . $modelBasename . '/',
                "{$apiResourceAttr}class {$modelBasename}",
                $content
            );
        }

        file_put_contents($modelPath, $content);
        $this->info("API Resource added to Model: {$modelBasename}");
    }
}
