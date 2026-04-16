<?php

namespace Gingerminds\LaravelCore\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class CreatePolicy extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:policy {name : Namespace/Model name (e.g. Model/Model)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a Policy and register it in AuthServiceProvider';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $name = trim((string) $this->argument('name'));

        if ($name === '') {
            $this->error('Name is required');
            return Command::FAILURE;
        }

        $parts = explode('/', $name);
        if (count($parts) < 2) {
            $this->error('Name must be in the format Namespace/Model (e.g. Client/Client)');
            return Command::FAILURE;
        }

        $namespace = $parts[0];
        $model     = $parts[1];

        $policyPath = app_path("Policies/{$namespace}/{$model}Policy.php");

        if (file_exists($policyPath)) {
            $this->error("Policy already exists at: {$policyPath}");
            return Command::FAILURE;
        }

        if (!is_dir(dirname($policyPath))) {
            mkdir(dirname($policyPath), 0755, true);
        }

        $stub = file_get_contents(base_path('stubs/policy.stub'));
        if ($stub === false) {
            $this->error('Stub file not found');
            return Command::FAILURE;
        }

        $pluralSnakeModel = Str::plural(Str::snake($model, ' '));
        $pluralSnakeModel = Str::lower($pluralSnakeModel);

        $policyContent = str_replace(
            ['{{namespace}}', '{{model}}', '{{camelModel}}', '{{pluralSnakeModel}}'],
            [$namespace, $model, Str::camel($model), $pluralSnakeModel],
            $stub
        );

        file_put_contents($policyPath, $policyContent);
        $this->info("Policy created: {$policyPath}");

        $this->registerPolicy($namespace, $model);

        return Command::SUCCESS;
    }

    /**
     * Register the policy in AuthServiceProvider.
     */
    protected function registerPolicy(string $namespace, string $model): void
    {
        $providerPath = app_path('Providers/AuthServiceProvider.php');

        if (!file_exists($providerPath)) {
            $this->warn('AuthServiceProvider not found. Registration skipped.');
            return;
        }

        $content = file_get_contents($providerPath);
        if ($content === false) {
            $this->error('Could not read AuthServiceProvider');
            return;
        }

        $modelFqcn  = "App\\Models\\{$namespace}\\{$model}";
        $policyFqcn = "App\\Policies\\{$namespace}\\{$model}Policy";

        // Check if already registered
        if (strpos($content, "{$model}::class") !== false && strpos($content, "{$model}Policy::class") !== false) {
            $this->info('Policy already registered in AuthServiceProvider');
            return;
        }

        // Add use statements
        if (strpos($content, "use {$modelFqcn};") === false) {
            $replaced = preg_replace(
                '/(use .*;\n)(?!use)/',
                "$1use {$modelFqcn};\n",
                $content,
                1
            );
            if ($replaced !== null) {
                $content = $replaced;
            }
        }

        if (strpos((string) $content, "use {$policyFqcn};") === false) {
            $replaced = preg_replace(
                '/(use .*;\n)(?!use)/',
                "$1use {$policyFqcn};\n",
                (string) $content,
                1
            );
            if ($replaced !== null) {
                $content = $replaced;
            }
        }

        // Add to $policies array
        // Find the $policies array
        if (preg_match('/protected \$policies = \[(.*?)]/s', (string) $content, $matches)) {
            $policiesArray = $matches[1];
            $lines         = explode("\n", trim($policiesArray));
            $lines         = array_map('trim', $lines);
            $lines         = array_filter($lines);
            $lines[]       = "{$model}::class => {$model}Policy::class,";
            $lines         = array_unique($lines);
            sort($lines);

            $newPoliciesArray = "\n        " . implode("\n        ", $lines) . "\n    ";
            $content          = str_replace($matches[1], $newPoliciesArray, (string) $content);
        }

        file_put_contents($providerPath, (string) $content);
        $this->info('Policy registered in AuthServiceProvider');
    }
}
