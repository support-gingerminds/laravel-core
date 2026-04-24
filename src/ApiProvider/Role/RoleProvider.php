<?php

declare(strict_types=1);

namespace Gingerminds\LaravelCore\ApiProvider\Role;

use ApiPlatform\State\ProviderInterface;
use Gingerminds\LaravelCore\ApiProvider\AbstractApiProvider;
use Gingerminds\LaravelCore\ApiProvider\ApiProviderInterface;
use Gingerminds\LaravelCore\Models\Role\Role;
use Gingerminds\LaravelCore\Repositories\Role\RoleRepository;

/**
 * @implements ProviderInterface<Role>
 */
class RoleProvider extends AbstractApiProvider implements ProviderInterface, ApiProviderInterface
{
    public function __construct(RoleRepository $repository)
    {
        parent::__construct($repository);
    }
}
