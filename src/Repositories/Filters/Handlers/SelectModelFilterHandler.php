<?php

namespace Gingerminds\LaravelCore\Repositories\Filters\Handlers;

use Gingerminds\LaravelCore\Repositories\Filters\FilterHandlerInterface;
use Illuminate\Database\Eloquent\Builder;

class SelectModelFilterHandler implements FilterHandlerInterface
{
    public function apply(Builder $query, string $property, mixed $value): void
    {
        if ($value === 'all' || $value === null) {
            if ($value === null) {
                $query->whereNull($property);
            }

            return;
        }

        $model = $query->getModel();

        // Check if the property is a relation
        if (method_exists($model, $property) && !str_ends_with($property, '_id')) {
            $query->whereHas($property, function (Builder $query) use ($value) {
                if (is_array($value)) {
                    $query->whereIn($query->getModel()->getTable() . '.id', $value);
                } else {
                    $query->where($query->getModel()->getTable() . '.id', '=', $value);
                }
            });

            return;
        }

        if (is_array($value)) {
            $query->whereIn($property, $value);

            return;
        }

        $table         = $model->getTable();
        $tableProperty = str_contains($property, '.') ? $property : "$table.$property";

        $query->where($tableProperty, '=', $value);
    }
}
