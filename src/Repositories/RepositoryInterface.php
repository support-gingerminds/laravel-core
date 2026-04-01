<?php

namespace Gingerminds\LaravelCore\Repositories;

use  Gingerminds\LaravelCore\Http\Requests\FormRequestInterface;
use  Gingerminds\LaravelCore\Models\ResourceModelInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

/**
 * @template TModel of Model
 */
interface RepositoryInterface
{
    public function getModelClass(): string;

    /**
     * @param array<mixed> $with
     * @return LengthAwarePaginator<int,TModel>
     */
    public function get(Request $request, array $with = []): LengthAwarePaginator;

    public function update(
        ?FormRequestInterface $request,
        ResourceModelInterface $resourceModel
    ): ResourceModelInterface;
}
