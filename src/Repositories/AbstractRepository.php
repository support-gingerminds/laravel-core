<?php

namespace Gingerminds\LaravelCore\Repositories;

use Carbon\Carbon;
use Gingerminds\LaravelCore\Models\CacheableResourceInterface;
use Gingerminds\LaravelCore\Models\FilterableModelInterface;
use Gingerminds\LaravelCore\Models\SearchableModelInterface;
use Gingerminds\LaravelCore\Models\SortableModelInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Spatie\ModelStates\State;
use Throwable;

/**
 * @template TModel of Model
 * @implements RepositoryInterface<TModel>
 */
abstract class AbstractRepository implements RepositoryInterface
{
    protected int $perPage = 10;

    abstract public function getModelClass(): string;

    /**
     * @param array<mixed> $with
     * @return LengthAwarePaginator<int,TModel>
     */
    public function get(Request $request, array $with = []): LengthAwarePaginator
    {
        $modelClass = $this->getModelClass();
        $cacheTag   = $this->getCacheTag($modelClass);
        if ($cacheTag !== null) {
            $ttlSeconds = $this->resolveCacheTtlSeconds($modelClass);
            if ($ttlSeconds <= 0) {
                return $this->runGetQuery($request, $with);
            }

            return Cache::tags([$cacheTag])->remember(
                $this->buildCacheKey($cacheTag, $request, $with),
                now()->addSeconds($ttlSeconds),
                function () use ($request, $with) {
                    return $this->runGetQuery($request, $with);
                }
            );
        }

        return $this->runGetQuery($request, $with);
    }

    /**
     * @param array<mixed> $with
     * @return LengthAwarePaginator<int,TModel>
     */
    protected function runGetQuery(Request $request, array $with = []): LengthAwarePaginator
    {
        $page  = $request->query('page', 1);
        $query = $this->getModelClass()::query();
        $this->initGetQueryBuilder($query, $request);
        $this->applyAllFilters($query, $request);

        $query->with($with);

        return $query
            ->paginate((int)$request->query('itemsPerPage', $this->perPage), ['*'], 'page', $page)
            ->withQueryString();
    }

    protected function getCacheTag(string $modelClass): ?string
    {
        if (!is_subclass_of($modelClass, CacheableResourceInterface::class)) {
            return null;
        }

        /** @var class-string<CacheableResourceInterface> $modelClass */
        return $modelClass::getCacheKey();
    }

    protected function resolveCacheTtlSeconds(string $modelClass): int
    {
        if (!is_subclass_of($modelClass, CacheableResourceInterface::class)) {
            return (int)config('cache.resource_ttl_seconds', 3600);
        }

        /** @var class-string<CacheableResourceInterface> $modelClass */
        $ttl = $modelClass::getCacheTtlSeconds();
        if ($ttl !== null) {
            return (int)$ttl;
        }

        return (int)config('cache.resource_ttl_seconds', 3600);
    }

    /**
     * @param array<mixed> $with
     */
    protected function buildCacheKey(string $tag, Request $request, array $with): string
    {
        $cacheType = $this->resolveCacheType($request);
        return "{$tag}_{$cacheType}_" . md5(serialize($request->all()) . serialize($with));
    }

    protected function resolveCacheType(Request $request): string
    {
        return $request->input('filters.id') !== null ? 'item' : 'list';
    }

    /**
     * @param Builder<TModel> $query
     * @return Builder<TModel>
     */
    protected function initGetQueryBuilder(Builder $query, Request $request): Builder
    {
        if (
            $query->getModel() instanceof SortableModelInterface
            && ($request->filled('sort') && $request->filled('sortBy'))
        ) {
            $sortBy    = $request->sortBy;
            $sortOrder = $request->sort;
            $this->applySort($query, $sortBy, $sortOrder);
        }

        return $query;
    }

    /**
     * Add sort to query builder even for BelongsTo relations
     *
     * @param Builder<TModel> $query
     */
    protected function applySort(Builder $query, string $sortBy, string $sortOrder = 'desc'): void
    {
        $model = $query->getModel();
        $table = $model->getTable();

        if (str_contains($sortBy, '.')) {
            [$relationName, $column] = explode('.', $sortBy, 2);

            if (!method_exists($model, $relationName)) {
                return;
            }

            $relation     = $model->{$relationName}();
            $relatedTable = null;

            // BELONGS TO
            if ($relation instanceof BelongsTo) {
                $relatedTable = $relation->getRelated()->getTable();
                $foreignKey   = $relation->getForeignKeyName(); // missions.applicant_id
                $ownerKey     = $relation->getOwnerKeyName();     // applicants.id

                $query->leftJoin(
                    $relatedTable,
                    "$table.$foreignKey",
                    '=',
                    "$relatedTable.$ownerKey"
                );
            }

            // HAS ONE
            if ($relation instanceof HasOne) {
                $relatedTable = $relation->getRelated()->getTable();
                $foreignKey   = $relation->getForeignKeyName(); // needs.mission_id
                $localKey     = $relation->getLocalKeyName();     // missions.id

                $query->leftJoin(
                    $relatedTable,
                    "$relatedTable.$foreignKey",
                    '=',
                    "$table.$localKey"
                );
            }

            if ($relatedTable) {
                $query
                    ->orderBy("$relatedTable.$column", $sortOrder)
                    ->select("$table.*");
            }
        }

        $query->orderBy($sortBy, $sortOrder);
    }

    /**
     * @param Builder<TModel> $query
     */
    protected function applyAllFilters(Builder $query, Request $request): void
    {
        if ($request->query->has('filters')) {
            $filters = $request->all()['filters'];

            $this->getItem($query, $filters);
            $this->applySearch($query, $filters);
            $this->applyFilters($query, $filters);
        }
    }

    /**
     * @param Builder<TModel> $query
     * @param array<mixed> $filters
     */
    protected function getItem(Builder $query, array $filters): void
    {
        if (array_key_exists('id', $filters)) {
            $query->where('id', $filters['id']);
        }
    }

    /**
     * @param Builder<TModel> $query
     * @param array<mixed> $filters
     */
    protected function applySearch(Builder $query, array $filters): void
    {
        $model = $query->getModel();
        if ($model instanceof SearchableModelInterface && array_key_exists('search', $filters)) {
            $query->where(function (Builder $query) use ($filters, $model) {
                foreach ($model::getSearchableFields() as $field) {
                    $query->orWhere($field, 'like', '%' . $filters['search'] . '%');
                }
            });
        }
    }

    /**
     * @param Builder<TModel> $query
     * @param array<mixed> $filters
     */
    protected function applyFilters(Builder $query, array $filters): void
    {
        $model = $query->getModel();

        if (!$model instanceof FilterableModelInterface) {
            return;
        }

        $resourceFilterConfig = $model::getFilters();

        foreach ($filters as $property => $value) {
            if (array_key_exists($property, $resourceFilterConfig)) {
                match ($resourceFilterConfig[$property]['type']) {
                    'date'         => $this->applyDateFilter($query, $property, $value),
                    'number'       => $this->applyNumberFilter($query, $property, $value),
                    'select'       => $this->applySelectFilter($query, $property, $value),
                    'select-state' => $this->applySelectStateFilter($query, $property, $value),
                    'select-model' => $this->applySelectModelFilter($query, $property, $value),
                    'boolean'      => $this->applyBooleanFilter($query, $property, $value),
                    default        => null,
                };
            }
        }
    }

    /**
     * @param Builder<TModel> $query
     * @param string|array<string> $value
     */
    protected function applySelectModelFilter(Builder $query, string $property, string|array|null $value): void
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
        } else {
            $table         = $model->getTable();
            $tableProperty = str_contains($property, '.') ? $property : "$table.$property";

            $query->where($tableProperty, '=', $value);
        }
    }

    /**
     * @param Builder<TModel> $query
     * @param array<mixed> $value
     */
    protected function applyDateFilter(Builder $query, string $property, array $value): void
    {
        $from = empty($value['from']) ? null : Carbon::createFromFormat('Y-m-d', $value['from']);
        $to   = empty($value['to']) ? null : Carbon::createFromFormat('Y-m-d', $value['to']);

        if ($from instanceof Carbon) {
            $from = $from->startOfDay();
        }

        if ($to instanceof Carbon) {
            $to = $to->endOfDay();
        }

        $table    = $query->getModel()->getTable();
        $property = str_contains($property, '.') ? $property : "$table.$property";

        if ($from && $to) {
            $query->whereBetween($property, [$from, $to]);
        } elseif ($from) {
            $query->where($property, '>=', $from);
        } elseif ($to) {
            $query->where($property, '<=', $to);
        }
    }

    /**
     * @param Builder<TModel> $query
     * @param array<mixed> $value
     */
    protected function applyNumberFilter(Builder $query, string $property, array $value): void
    {
        $from = empty($value['from']) ? null : floatval($value['from']);
        $to   = empty($value['to']) ? null : floatval($value['to']);

        $table    = $query->getModel()->getTable();
        $property = str_contains($property, '.') ? $property : "$table.$property";

        if ($from && $to) {
            $query->whereBetween($property, [$from, $to]);
        } elseif ($from) {
            $query->where($property, '>=', $from);
        } elseif ($to) {
            $query->where($property, '<=', $to);
        }
    }

    /**
     * @param Builder<TModel> $query
     * @param string|array<string> $value
     */
    protected function applySelectFilter(Builder $query, string $property, string|array $value): void
    {
        if (is_array($value)) {
            $query->whereIn($property, $value);
        } else {
            if ($value === 'all') {
                return;
            }

            $table         = $query->getModel()->getTable();
            $tableProperty = str_contains($property, '.') ? $property : "$table.$property";

            $query->where($tableProperty, '=', $value);
        }
    }

    /**
     * @param Builder<TModel> $query
     * @param string|array<string> $value
     */
    protected function applySelectStateFilter(Builder $query, string $property, string|array $value): void
    {
        $table         = $query->getModel()->getTable();
        $tableProperty = str_contains($property, '.') ? $property : "$table.$property";

        if (is_array($value)) {
            $formattedValues = [];
            foreach ($value as $stateValue) {
                $formattedValues[] = $this->convertState(get_class($query->getModel()), $property, $stateValue);
            }

            $query->whereIn($tableProperty, $formattedValues);

            return;
        }

        if ($value === 'all') {
            return;
        }

        $query->where($tableProperty, '=', $this->convertState(get_class($query->getModel()), $property, $value));
    }

    /**
     * Resolves a filter value (state short name, morph class, or `code()`/`label()`
     * translation key) into the fully-qualified state class name stored in the
     * database. Falls back to the raw value untouched if it can't be resolved,
     * so an already-qualified class name (or an unrecognized value) still works.
     */
    protected function convertState(string $modelClass, string $property, ?string $value = null): ?string
    {
        if (null === $value || class_exists($value)) {
            return $value;
        }

        return $this->matchStateClass($modelClass, $property, $value) ?? $value;
    }

    /**
     * Looks up the fully-qualified state class matching $value among every state
     * registered on $property's base state class, or null if none matches
     * (unresolvable model/property/cast, or no state matched $value).
     */
    private function matchStateClass(string $modelClass, string $property, string $value): ?string
    {
        $stateBaseClass = $this->resolveStateBaseClass($modelClass, $property);

        if (null === $stateBaseClass) {
            return null;
        }

        foreach ($this->safeStateList($stateBaseClass) as $stateClass) {
            if (class_exists($stateClass) && $this->stateMatchesValue($stateClass, $value)) {
                return $stateClass;
            }
        }

        return null;
    }

    /**
     * Resolves the base Spatie state class registered as a cast for $property on
     * $modelClass, or null if it can't be resolved (unknown model, no `getCasts()`,
     * no cast for $property, or the cast isn't a state class).
     */
    private function resolveStateBaseClass(string $modelClass, string $property): ?string
    {
        if (!class_exists($modelClass)) {
            return null;
        }

        $model          = new $modelClass();
        $stateBaseClass = method_exists($model, 'getCasts') ? ($model->getCasts()[$property] ?? null) : null;

        if (!is_string($stateBaseClass) || !is_a($stateBaseClass, State::class, true)) {
            return null;
        }

        return $stateBaseClass;
    }

    /**
     * @return iterable<class-string>
     */
    private function safeStateList(string $stateBaseClass): iterable
    {
        try {
            return $stateBaseClass::all();
        } catch (Throwable) {
            return [];
        }
    }

    /**
     * Whether $value matches $stateClass's `code()`, `label()`, morph class, or
     * short class name, case-insensitively.
     */
    private function stateMatchesValue(string $stateClass, string $value): bool
    {
        $morph = $stateClass::getMorphClass();

        return (method_exists($stateClass, 'code') && strcasecmp((string) $stateClass::code(), $value) === 0)
            || (method_exists($stateClass, 'label') && strcasecmp((string) $stateClass::label(), $value) === 0)
            || (is_string($morph) && strcasecmp($morph, $value) === 0)
            || strcasecmp(class_basename($stateClass), $value) === 0;
    }

    /**
     * @param Builder<TModel> $query
     */
    protected function applyBooleanFilter(Builder $query, string $property, ?string $value): void
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
