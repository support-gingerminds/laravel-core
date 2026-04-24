<?php

namespace Gingerminds\LaravelCore\Repositories\Role;

use Gingerminds\LaravelCore\Http\Requests\FormRequestInterface;
use Gingerminds\LaravelCore\Models\Permission\Permission;
use Gingerminds\LaravelCore\Models\ResourceModelInterface;
use Gingerminds\LaravelCore\Models\Role\Role;
use Gingerminds\LaravelCore\Repositories\AbstractRepository;
use Gingerminds\LaravelCore\Repositories\RepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use InvalidArgumentException;

/**
 * @extends AbstractRepository<Role>
 * @implements RepositoryInterface<Role>
 */
class RoleRepository extends AbstractRepository implements RepositoryInterface
{
    public function getModelClass(): string
    {
        return Role::class;
    }

    /**
     * @return LengthAwarePaginator<int, Role>
     */
    public function get(Request $request, array $with = []): LengthAwarePaginator
    {
        $page  = $request->query('page', 1);
        $query = $this->getModelClass()::query();
        $this->initGetQueryBuilder($query, $request);
        $this->applyAllFilters($query, $request);

        $query->with(['permissions'])->withCount('permissions');

        return $query
            ->paginate((int) $request->query('itemsPerPage', $this->perPage), ['*'], 'page', $page)
            ->withQueryString();
    }

    public function update(
        ?FormRequestInterface $request,
        ResourceModelInterface $resourceModel
    ): ResourceModelInterface {
        if (!$resourceModel instanceof Role) {
            throw new InvalidArgumentException('ResourceModelInterface must be an instance of Role');
        }

        if (!$request instanceof FormRequestInterface) {
            return $resourceModel;
        }

        $resourceModel->name        = $request->input('name');
        $resourceModel->is_external = $request->boolean('is_external');
        $resourceModel->is_default  = $request->boolean('is_default');
        $resourceModel->save();

        $permissions = collect();
        if ($request->has('permissions')) {
            $permissions = Permission::whereIn('id', $request->input('permissions'))->get();
        }
        $resourceModel->syncPermissions(...$permissions);

        return $resourceModel;
    }

    public function getDefaultRole(bool $isExternal = false): ?Role
    {
        return Role::where('is_default', true)->where('is_external', $isExternal)->first();
    }
}
