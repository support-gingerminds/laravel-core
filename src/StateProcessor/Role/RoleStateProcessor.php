<?php

declare(strict_types=1);

namespace Gingerminds\LaravelCore\StateProcessor\Role;

use ApiPlatform\State\ProcessorInterface;
use Gingerminds\LaravelCore\Http\Requests\Role\RoleRequest;
use Gingerminds\LaravelCore\Models\Role\Role;
use Gingerminds\LaravelCore\Repositories\Role\RoleRepository;
use Gingerminds\LaravelCore\StateProcessor\BaseStateProcessor;
use Illuminate\Contracts\Validation\Factory as ValidationFactory;

/**
 * @implements ProcessorInterface<Role, Role>
 */
class RoleStateProcessor extends BaseStateProcessor implements ProcessorInterface
{
    public function __construct(
        RoleRepository $repository,
        ValidationFactory $validationFactory
    ) {
        $this->repository    = $repository;
        $this->formRequest   = new RoleRequest();
        $this->resourceModel = new Role();

        parent::__construct($validationFactory);
    }
}
