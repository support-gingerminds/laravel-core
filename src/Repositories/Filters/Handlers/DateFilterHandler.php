<?php

namespace Gingerminds\LaravelCore\Repositories\Filters\Handlers;

use Carbon\Carbon;
use Gingerminds\LaravelCore\Repositories\Filters\FilterHandlerInterface;
use Illuminate\Database\Eloquent\Builder;

class DateFilterHandler implements FilterHandlerInterface
{
    public function apply(Builder $query, string $property, mixed $value): void
    {
        if (!is_array($value)) {
            return;
        }

        $from = empty($value['from']) ? null : Carbon::createFromFormat('Y-m-d', $value['from']);
        $to   = empty($value['to']) ? null : Carbon::createFromFormat('Y-m-d', $value['to']);

        if ($from instanceof Carbon) {
            $from = $from->startOfDay();
        }

        if ($to instanceof Carbon) {
            $to = $to->endOfDay();
        }

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
