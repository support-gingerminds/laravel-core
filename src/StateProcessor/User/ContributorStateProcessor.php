<?php

declare(strict_types=1);

namespace Gingerminds\LaravelCore\StateProcessor\User;

use ApiPlatform\State\ProcessorInterface;
use Gingerminds\LaravelCore\Http\Requests\User\ContributorRequest;
use Gingerminds\LaravelCore\Models\User\Contributor;
use Gingerminds\LaravelCore\Repositories\User\ContributorRepository;
use Gingerminds\LaravelCore\StateProcessor\BaseStateProcessor;
use Illuminate\Contracts\Validation\Factory as ValidationFactory;

/**
 * @implements ProcessorInterface<Contributor, Contributor>
 */
class ContributorStateProcessor extends BaseStateProcessor implements ProcessorInterface
{
    public function __construct(
        ContributorRepository $repository,
        ValidationFactory $validationFactory
    ) {
        $this->repository    = $repository;
        $this->formRequest   = new ContributorRequest();
        $this->resourceModel = new Contributor();

        parent::__construct($validationFactory);
    }
}
