<?php

declare(strict_types=1);

namespace Gingerminds\LaravelCore\Repositories\Filters;

use Illuminate\Database\Eloquent\Builder;

/**
 * Applies a single `getFilters()` entry (identified by its `type`) to a query.
 *
 * Any package can implement this and register it under a new type via
 * FilterHandlerRegistry::register() from its own service provider — no need
 * to touch AbstractRepository to introduce a new filter type.
 */
interface FilterHandlerInterface
{
    /**
     * @param Builder<*> $query
     */
    public function apply(Builder $query, string $property, mixed $value): void;
}
