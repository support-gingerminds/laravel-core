<?php

namespace Gingerminds\LaravelCore\Repositories\Decorators;

use Gingerminds\LaravelCore\Http\Requests\FormRequestInterface;
use Gingerminds\LaravelCore\Models\CacheableResourceInterface;
use Gingerminds\LaravelCore\Models\ResourceModelInterface;
use Gingerminds\LaravelCore\Repositories\RepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

/**
 * Repository decorator to implements Caching on all resource that implements CacheableResourceInterface.
 *
 * @template TModel of Model
 * @implements RepositoryInterface<TModel>
 */
class CachingRepositoryDecorator implements RepositoryInterface
{
    /**
     * @param RepositoryInterface<TModel> $repository
     * @param class-string<CacheableResourceInterface> $resourceClass
     */
    public function __construct(
        private RepositoryInterface $repository,
        private string $resourceClass
    ) {
    }

    public function get(Request $request, array $with = []): LengthAwarePaginator
    {
        $tag       = $this->resourceClass::getCacheKey();
        $cacheType = $this->resolveCacheType($request);
        $cacheKey  = "{$tag}_{$cacheType}_" . md5(serialize($request->all()) . serialize($with));
        $ttl       = $this->resolveCacheTtlSeconds();
        if ($ttl <= 0) {
            return $this->repository->get($request, $with);
        }

        return Cache::tags([$tag])->remember($cacheKey, now()->addSeconds($ttl), function () use ($request, $with) {
            return $this->repository->get($request, $with);
        });
    }

    private function resolveCacheTtlSeconds(): int
    {
        $ttl = $this->resourceClass::getCacheTtlSeconds();
        if ($ttl !== null) {
            return (int) $ttl;
        }

        return (int) config('cache.resource_ttl_seconds', 3600);
    }

    private function resolveCacheType(Request $request): string
    {
        return $request->input('filters.id') !== null ? 'item' : 'list';
    }

    public function update(
        ?FormRequestInterface $request,
        ResourceModelInterface $resourceModel
    ): ResourceModelInterface {
        return $this->repository->update($request, $resourceModel);
    }

    /**
     * @param array<int, mixed> $args
     */
    public function __call(string $method, array $args): mixed
    {
        return $this->repository->$method(...$args);
    }

    public function getModelClass(): string
    {
        return $this->repository->getModelClass();
    }
}
