<?php

declare(strict_types=1);

namespace Gingerminds\LaravelCore\StateProcessor\Permission;

use ApiPlatform\State\ProcessorInterface;
use Gingerminds\LaravelCore\Http\Requests\Permission\PermissionRequest;
use Gingerminds\LaravelCore\Models\Permission\Permission;
use Gingerminds\LaravelCore\Repositories\Permission\PermissionRepository;
use Gingerminds\LaravelCore\StateProcessor\BaseStateProcessor;
use Illuminate\Contracts\Validation\Factory as ValidationFactory;

/**
 * @implements ProcessorInterface<Permission, Permission>
 */
class PermissionStateProcessor extends BaseStateProcessor implements ProcessorInterface
{
    public function __construct(
        PermissionRepository $repository,
        ValidationFactory $validationFactory
    ) {
        $this->repository    = $repository;
        $this->formRequest   = new PermissionRequest();
        $this->resourceModel = new Permission();

        parent::__construct($validationFactory);
    }
}
