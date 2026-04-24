<?php

declare(strict_types=1);

namespace Gingerminds\LaravelCore\ApiProvider\Permission;

use ApiPlatform\State\ProviderInterface;
use Gingerminds\LaravelCore\ApiProvider\AbstractApiProvider;
use Gingerminds\LaravelCore\ApiProvider\ApiProviderInterface;
use Gingerminds\LaravelCore\Models\Permission\Permission;
use Gingerminds\LaravelCore\Repositories\Permission\PermissionRepository;

/**
 * @implements ProviderInterface<Permission>
 */
class PermissionProvider extends AbstractApiProvider implements ProviderInterface, ApiProviderInterface
{
    public function __construct(PermissionRepository $repository)
    {
        parent::__construct($repository);
    }
}
