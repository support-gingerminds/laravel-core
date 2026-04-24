<?php

declare(strict_types=1);

namespace Gingerminds\LaravelCore\ApiProvider\User;

use ApiPlatform\State\ProviderInterface;
use Gingerminds\LaravelCore\ApiProvider\AbstractApiProvider;
use Gingerminds\LaravelCore\ApiProvider\ApiProviderInterface;
use Gingerminds\LaravelCore\Models\User\User;
use Gingerminds\LaravelCore\Repositories\User\UserRepository;

/**
 * @implements ProviderInterface<User>
 */
class UserProvider extends AbstractApiProvider implements ProviderInterface, ApiProviderInterface
{
    public function __construct(UserRepository $repository)
    {
        parent::__construct($repository);
    }
}
