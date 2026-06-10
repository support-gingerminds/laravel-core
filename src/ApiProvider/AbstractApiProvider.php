<?php

namespace Gingerminds\LaravelCore\ApiProvider;

use ApiPlatform\Laravel\Eloquent\Paginator as ApiPlatformPaginator;
use ApiPlatform\Metadata\CollectionOperationInterface;
use ApiPlatform\Metadata\Operation;
use Gingerminds\LaravelCore\Repositories\RepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

abstract class AbstractApiProvider
{
    /** @var RepositoryInterface<*> */
    protected RepositoryInterface $repository;

    /**
     * @param RepositoryInterface<*> $repository
     */
    public function __construct(
        RepositoryInterface $repository
    ) {
        $this->repository = $repository;
    }

    /**
     * @param array<string, mixed> $uriVariables
     * @param array<string, mixed> $context
     *
     * @return object|array<mixed>|null
     */
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $request = request();

        $this->addFilters($request, $uriVariables, $context);

        if ($operation instanceof CollectionOperationInterface) {
            $result = $this->repository->get($request);

            if ($result instanceof LengthAwarePaginator) {
                return new ApiPlatformPaginator($result);
            }

            return $result;
        }

        $id = $uriVariables['id'] ?? $request->route('id');

        $filters       = (array)$request->query('filters', []);
        $filters['id'] = $id;
        $request->query->set('filters', $filters);

        $results = $this->repository->get($request);

        return $results->total() > 0 ? collect($results->items())->first() : null;
    }

    /**
     * @param array<string, mixed> $uriVariables
     * @param array<string, mixed> $context
     */
    public function addFilters(Request $request, array $uriVariables, array $context): void
    {
    }
}
