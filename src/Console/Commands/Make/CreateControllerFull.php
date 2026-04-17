<?php

namespace Gingerminds\LaravelCore\Console\Commands\Make;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use RuntimeException;
use Symfony\Component\Console\Command\Command as CommandAlias;

class CreateControllerFull extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:controller-full
        {name : Namespace/Model name (e.g. PartnerCompany/PartnerCompany)}
        {--trad-base=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a controller with CRUD methods (except show), ' .
        'blades, routes, and translation scaffolding';

    public function handle(): int
    {
        $name = trim((string) $this->argument('name'));
        if ($name === '') {
            $this->error('Name is required');
            return CommandAlias::FAILURE;
        }

        $parts           = explode('/', $name);
        $model           = array_pop($parts);
        $controllerClass = $model . 'Controller';

        $controllerNamespace = 'App\\Http\\Controllers';
        $modelNamespace      = 'App\\Models';
        if ($parts !== []) {
            $controllerNamespace .= '\\' . implode('\\', $parts);
            $modelNamespace      .= '\\' . implode('\\', $parts);
        }

        $modelFqcn      = $modelNamespace . '\\' . $model;
        $controllerFqcn = $controllerNamespace . '\\' . $controllerClass;

        $requestNamespace    = 'App\\Http\\Requests';
        $repositoryNamespace = 'App\\Repositories';
        if ($parts !== []) {
            $requestNamespace    .= '\\' . implode('\\', $parts);
            $repositoryNamespace .= '\\' . implode('\\', $parts);
        }

        // Compute resource/route segment and views path
        $resourceSegment = Str::kebab(Str::pluralStudly($model));
        $viewsDir        = resource_path('views/pages/' . $resourceSegment);
        $modelVariable   = Str::camel($model);

        $tradBase = $this->option('trad-base');
        if (!$tradBase) {
            // Try to infer from resource segment (snake with underscores)
            $tradBase = Str::snake($resourceSegment);
        }

        $data = [
            'namespace'           => $controllerNamespace,
            'class'               => $controllerClass,
            'modelFqcn'           => $modelFqcn,
            'model'               => $model,
            'modelVariable'       => $modelVariable,
            'resource'            => $resourceSegment,
            'tradBase'            => $tradBase,
            'requestNamespace'    => $requestNamespace,
            'repositoryNamespace' => $repositoryNamespace,
        ];

        $stub = $this->compileStub('controller-full', $data);

        $controllerPath = app_path(
            'Http/Controllers/' . ($parts !== [] ? implode('/', $parts) . '/' : '') . $controllerClass . '.php'
        );
        if (!is_dir(dirname($controllerPath))) {
            mkdir(dirname($controllerPath), 0755, true);
        }
        file_put_contents($controllerPath, $stub);
        $this->info("Controller created: {$controllerPath}");

        if (!is_dir($viewsDir)) {
            mkdir($viewsDir, 0755, true);
        }
        $this->createBlade($viewsDir . '/index.blade.php', $this->compileStub('controller/blade-index', $data));
        $this->createBlade($viewsDir . '/create.blade.php', $this->compileStub('controller/blade-create', $data));
        $this->createBlade($viewsDir . '/edit.blade.php', $this->compileStub('controller/blade-edit', $data));

        $partialsDir = $viewsDir . '/partials';
        if (!is_dir($partialsDir)) {
            mkdir($partialsDir, 0755, true);
        }
        $this->createBlade(
            $partialsDir . '/list.blade.php',
            $this->compileStub('controller/blade-partials-list', $data)
        );
        $this->createBlade(
            $partialsDir . '/fields.blade.php',
            $this->compileStub('controller/blade-partials-fields', $data)
        );

        $this->registerResourceRoute($resourceSegment, $controllerFqcn);

        $this->ensureTranslation($tradBase, $model);

        $this->info('All done.');
        return Command::SUCCESS;
    }

    private function createBlade(string $path, string $content): void
    {
        if (!is_dir(dirname($path))) {
            mkdir(dirname($path), 0755, true);
        }
        if (!file_exists($path)) {
            file_put_contents($path, $content);
            $this->info("View created: {$path}");
        } else {
            $this->line("View already exists, skipped: {$path}");
        }
    }

    private function registerResourceRoute(string $segment, string $controllerFqcn): void
    {
        $web     = base_path('routes/web.php');
        $content = (string) file_get_contents($web);

        $routeLine = "    Route::resource('{$segment}', \\{$controllerFqcn}::class);";
        if (str_contains($content, $routeLine)) {
            $this->line('Route already present, skipped.');
            return;
        }

        // Insert before the end of the auth middleware group.
        $needle = "});\n\nRoute::get('/health'";
        if (str_contains($content, $needle)) {
            $content = str_replace($needle, $routeLine . "\n});\n\nRoute::get('/health'", $content);
            file_put_contents($web, $content);
            $this->info('Route registered in routes/web.php');
            return;
        }

        // Fallback: append at end of file
        $content .= "\n" . $routeLine . "\n";
        file_put_contents($web, $content);
        $this->warn('Could not find auth group end, route appended at end of routes/web.php');
    }

    private function ensureTranslation(string $base, string $model): void
    {
        $file = base_path('lang/fr/translation.php');
        if (!file_exists($file)) {
            $this->warn('lang/fr/translation.php not found, skipping translation scaffolding.');
            return;
        }
        $content = (string) file_get_contents($file);
        if (preg_match('/\'' . preg_quote($base, '/') . '\'\s*=>/m', $content)) {
            $this->line("Translation key '{$base}' already exists, skipped.");
            return;
        }

        $singular = Str::headline($model);
        $plural   = Str::headline(Str::pluralStudly($model));

        $block = "    '{$base}' => [\n" .
            "        'name_s' => '{$singular}',\n" .
            "        'name_p' => '{$plural}',\n" .
            "        'manage' => 'Gestion des {$plural}',\n" .
            "        'message' => [\n" .
            "            'no_{$base}' => 'Aucun {$singular} trouvé'\n" .
            "        ]\n" .
            '    ]';

        // Try to insert before final "];"
        if (preg_match('/(\n\s*)\s*];\s*$/', $content, $matches)) {
            $indent  = $matches[1];
            $content = preg_replace('/(\n\s*)\s*];\s*$/', ",\n\n" . $block . $indent . "];\n", $content);
        } else {
            // Fallback: append near end
            $content .= "\n{$block}\n";
        }

        file_put_contents($file, $content);
        $this->info("Translation scaffolding added under key '{$base}'.");
    }

    /**
     * @param array<string, string> $data
     */
    private function compileStub(string $name, array $data): string
    {
        $path = base_path("stubs/{$name}.stub");
        if (!file_exists($path)) {
            throw new RuntimeException("Stub not found: {$path}");
        }

        $content = (string) file_get_contents($path);

        foreach ($data as $key => $value) {
            $content = str_replace("{{ {$key} }}", $value, $content);
        }

        return $content;
    }
}
