<?php

namespace Gingerminds\LaravelCore\Repositories\Filters;

/**
 * Maps a `getFilters()` "type" string to the FilterHandlerInterface responsible
 * for applying it to a query.
 *
 * Bound as a singleton by LaravelCoreServiceProvider, which pre-registers the
 * built-in types (date, number, select, select-model, select-state, boolean).
 * Any other package can call register() from its own service provider to add
 * a new filter type without modifying gingerminds-laravel-core.
 */
class FilterHandlerRegistry
{
    /** @var array<string, FilterHandlerInterface> */
    private array $handlers = [];

    public function register(string $type, FilterHandlerInterface $handler): void
    {
        $this->handlers[$type] = $handler;
    }

    public function get(string $type): ?FilterHandlerInterface
    {
        return $this->handlers[$type] ?? null;
    }

    public function has(string $type): bool
    {
        return isset($this->handlers[$type]);
    }
}
