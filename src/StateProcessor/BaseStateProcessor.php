<?php

namespace Gingerminds\LaravelCore\StateProcessor;

use ApiPlatform\Metadata\Operation;
use Gingerminds\LaravelCore\Http\Requests\FormRequestInterface;
use Gingerminds\LaravelCore\Models\ResourceModelInterface;
use Gingerminds\LaravelCore\Repositories\RepositoryInterface;
use Illuminate\Contracts\Validation\Factory as ValidationFactory;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;
use ReflectionMethod;

class BaseStateProcessor
{
    /** @var RepositoryInterface<*>|null */
    protected ?RepositoryInterface $repository = null;

    protected ?FormRequestInterface $formRequest = null;

    protected ?ResourceModelInterface $resourceModel = null;

    public function __construct(
        private readonly ValidationFactory $validationFactory
    ) {
    }

    /**
     * @param array<mixed> $uriVariables
     * @param array<mixed> $context
     */
    public function process(
        mixed $data,
        Operation $operation,
        array $uriVariables = [],
        array $context = []
    ): ?ResourceModelInterface {
        $currentRequest = request();
        $inputData      = $currentRequest->all();

        $rawContent = $currentRequest->getContent();

        if (!empty($rawContent)) {
            $cleanContent = preg_replace('/,\s*}/', '}', $rawContent);
            $json         = json_decode($cleanContent ?? '', true);

            if (is_array($json)) {
                $inputData = array_merge($inputData, $json);
            }
        }

        $this->formRequest?->setMethod($currentRequest->getMethod());
        $this->formRequest?->replace($inputData);

        if ($this->formRequest instanceof FormRequest) {
            $reflector = new ReflectionMethod($this->formRequest, 'prepareForValidation');
            $reflector->invoke($this->formRequest);
        }

        $validator = $this->validationFactory->make(
            (array)$this->formRequest?->all(),
            $this->formRequest?->rules() ?? [],
            (array)$this->formRequest?->messages(),
            (array)$this->formRequest?->attributes()
        );

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $this->formRequest?->setValidator($validator);
        $this->formRequest?->validated();

        if (null === $data) {
            $data = $this->resourceModel;
        }

        return $this->repository?->update($this->formRequest, $data);
    }
}
