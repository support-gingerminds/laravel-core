<?php

namespace Gingerminds\LaravelCore\Repositories\Filters\Handlers;

use Gingerminds\LaravelCore\Repositories\Filters\FilterHandlerInterface;
use Illuminate\Database\Eloquent\Builder;

class NumberFilterHandler implements FilterHandlerInterface
{
    public function apply(Builder $query, string $property, mixed $value): void
    {
        if (!is_array($value)) {
            return;
        }

        $from = empty($value['from']) ? null : floatval($value['from']);
        $to   = empty($value['to']) ? null : floatval($value['to']);

        $table         = $query->getModel()->getTable();
        $tableProperty = str_contains($property, '.') ? $property : "$table.$property";

        if ($from && $to) {
            $query->whereBetween($tableProperty, [$from, $to]);
        } elseif ($from) {
            $query->where($tableProperty, '>=', $from);
        } elseif ($to) {
            $query->where($tableProperty, '<=', $to);
        }
    }
}
