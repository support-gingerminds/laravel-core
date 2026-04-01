<?php

namespace Gingerminds\LaravelCore\Repositories;

use Gingerminds\LaravelCore\Http\Requests\FormRequestInterface;
use Gingerminds\LaravelCore\Models\FilterableModelInterface;
use Gingerminds\LaravelCore\Models\ResourceModelInterface;
use Gingerminds\LaravelCore\Models\SearchableModelInterface;
use Gingerminds\LaravelCore\Models\SortableModelInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

/**
 * @template TModel of Model
 * @implements RepositoryInterface<TModel>
 */
abstract class AbstractRepository implements RepositoryInterface
{
    /** @return class-string<TModel> */
    abstract public function getModelClass(): string;

    /**
     * @param array<mixed> $with
     * @return LengthAwarePaginator<int, TModel>
     */
    public function get(Request $request, array $with = []): LengthAwarePaginator
    {
        $query = $this->getModelClass()::query()->with($with);

        $this->applyFilters($query, $request);
        $this->applySearch($query, $request);
        $this->applySorting($query, $request);

        return $query->paginate($request->get('per_page', 15));
    }

    public function update(
        ?FormRequestInterface $request,
        ResourceModelInterface $resourceModel
    ): ResourceModelInterface {
        if (!$resourceModel instanceof Model) {
            throw new \InvalidArgumentException("Resource model must be an Eloquent Model");
        }

        if ($request) {
            $resourceModel->fill($request->validated());
        }

        $resourceModel->save();

        return $resourceModel;
    }

    protected function applyFilters(Builder $query, Request $request): void
    {
        $model = $query->getModel();
        if ($model instanceof FilterableModelInterface) {
            foreach ($model::getFilters() as $filter => $config) {
                if ($request->has($filter)) {
                    $query->where($filter, $request->get($filter));
                }
            }
        }
    }

    protected function applySearch(Builder $query, Request $request): void
    {
        $model = $query->getModel();
        if ($model instanceof SearchableModelInterface && $request->has('search')) {
            $search = $request->get('search');
            $query->where(function (Builder $q) use ($model, $search) {
                foreach ($model::getSearchableFields() as $field) {
                    $q->orWhere($field, 'like', "%{$search}%");
                }
            });
        }
    }

    protected function applySorting(Builder $query, Request $request): void
    {
        $model = $query->getModel();
        if ($model instanceof SortableModelInterface) {
            $sortField = $request->get('sort_by', 'id');
            $sortOrder = $request->get('sort_order', 'asc');
            $query->orderBy($sortField, $sortOrder);
        }
    }
}
