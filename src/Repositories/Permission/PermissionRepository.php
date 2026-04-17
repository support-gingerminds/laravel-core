<?php

namespace Gingerminds\LaravelCore\Repositories\Permission;

use Gingerminds\LaravelCore\Http\Requests\FormRequestInterface;
use Gingerminds\LaravelCore\Models\Permission\Permission;
use Gingerminds\LaravelCore\Models\ResourceModelInterface;
use Gingerminds\LaravelCore\Repositories\AbstractRepository;
use Gingerminds\LaravelCore\Repositories\RepositoryInterface;
use InvalidArgumentException;

/**
 * @extends AbstractRepository<Permission>
 * @implements RepositoryInterface<Permission>
 */
class PermissionRepository extends AbstractRepository implements RepositoryInterface
{
    public function getModelClass(): string
    {
        return Permission::class;
    }

    public function update(
        ?FormRequestInterface $request,
        ResourceModelInterface $resourceModel
    ): ResourceModelInterface {
        if (!$resourceModel instanceof Permission) {
            throw new InvalidArgumentException('ResourceModelInterface must be an instance of Permission');
        }

        if (!$request instanceof FormRequestInterface) {
            return $resourceModel;
        }

        $resourceModel->name = $request->input('name');
        $resourceModel->save();

        return $resourceModel;
    }
}
