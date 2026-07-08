<?php

namespace Gingerminds\LaravelCore\Repositories\Filters\Handlers;

use Gingerminds\LaravelCore\Repositories\Filters\FilterHandlerInterface;
use Gingerminds\LaravelCore\Repositories\Filters\StateFilterResolver;
use Illuminate\Database\Eloquent\Builder;

class SelectStateFilterHandler implements FilterHandlerInterface
{
    public function __construct(
        private readonly StateFilterResolver $resolver = new StateFilterResolver(),
    ) {
    }

    public function apply(Builder $query, string $property, mixed $value): void
    {
        $table         = $query->getModel()->getTable();
        $tableProperty = str_contains($property, '.') ? $property : "$table.$property";

        if (is_array($value)) {
            $formattedValues = [];
            foreach ($value as $stateValue) {
                $formattedValues[] = $this->resolver->resolve(get_class($query->getModel()), $property, $stateValue);
            }

            $query->whereIn($tableProperty, $formattedValues);

            return;
        }

        if ($value === 'all') {
            return;
        }

        $query->where($tableProperty, '=', $this->resolver->resolve(get_class($query->getModel()), $property, $value));
    }
}
