<?php

declare(strict_types=1);

namespace Gingerminds\LaravelCore\Repositories\Filters\Handlers;

use Gingerminds\LaravelCore\Repositories\Filters\FilterHandlerInterface;
use Illuminate\Database\Eloquent\Builder;

class SelectFilterHandler implements FilterHandlerInterface
{
    public function apply(Builder $query, string $property, mixed $value): void
    {
        if (is_array($value)) {
            $query->whereIn($property, $value);

            return;
        }

        if ($value === 'all') {
            return;
        }

        $table         = $query->getModel()->getTable();
        $tableProperty = str_contains($property, '.') ? $property : "$table.$property";

        $query->where($tableProperty, '=', $value);
    }
}
