<?php

declare(strict_types=1);

namespace Gingerminds\LaravelCore\ApiProvider\User;

use ApiPlatform\State\ProviderInterface;
use Gingerminds\LaravelCore\ApiProvider\AbstractApiProvider;
use Gingerminds\LaravelCore\ApiProvider\ApiProviderInterface;
use Gingerminds\LaravelCore\Models\User\Contributor;
use Gingerminds\LaravelCore\Repositories\User\ContributorRepository;

/**
 * @implements ProviderInterface<Contributor>
 */
class ContributorProvider extends AbstractApiProvider implements ProviderInterface, ApiProviderInterface
{
    public function __construct(ContributorRepository $repository)
    {
        parent::__construct($repository);
    }
}
