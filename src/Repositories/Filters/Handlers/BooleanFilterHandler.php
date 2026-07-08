<?php

declare(strict_types=1);

namespace Gingerminds\LaravelCore\Repositories\Filters\Handlers;

use Gingerminds\LaravelCore\Repositories\Filters\FilterHandlerInterface;
use Illuminate\Database\Eloquent\Builder;

class BooleanFilterHandler implements FilterHandlerInterface
{
    public function apply(Builder $query, string $property, mixed $value): void
    {
        if ($value === 'all') {
            return;
        }

        $table         = $query->getModel()->getTable();
        $tableProperty = str_contains($property, '.') ? $property : "$table.$property";

        if ('yes' === $value) {
            $query->where($tableProperty, true);
        } elseif ('no' === $value) {
            $query->where(function (Builder $query) use ($tableProperty) {
                $query->where($tableProperty, false)->orWhereNull($tableProperty);
            });
        }
    }
}
