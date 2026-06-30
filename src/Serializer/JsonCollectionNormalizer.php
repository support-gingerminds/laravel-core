<?php

declare(strict_types=1);

namespace Gingerminds\LaravelCore\Serializer;

use ApiPlatform\Metadata\Resource\Factory\ResourceMetadataCollectionFactoryInterface;
use ApiPlatform\Metadata\ResourceClassResolverInterface;
use ApiPlatform\Serializer\AbstractCollectionNormalizer;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

class JsonCollectionNormalizer extends AbstractCollectionNormalizer
{
    public const string FORMAT = 'json';

    public function __construct(
        ResourceClassResolverInterface $resourceClassResolver,
        string $pageParameterName,
        ResourceMetadataCollectionFactoryInterface $resourceMetadataFactory,
    ) {
        parent::__construct($resourceClassResolver, $pageParameterName, $resourceMetadataFactory);
    }

    /**
     * @param iterable<object> $object
     * @param array<string, mixed> $context
     * @return array<string, mixed>
     */
    protected function getPaginationData(iterable $object, array $context = []): array
    {
        [$paginator, , $currentPage, $itemsPerPage, $lastPage, , $totalItems] = $this->getPaginationConfig(
            $object,
            $context
        );

        $pagination = [];

        if ($paginator) {
            $pagination['page']         = (int) $currentPage;
            $pagination['itemsPerPage'] = (int) $itemsPerPage;
            $pagination['totalItems']   = (int) ($totalItems ?? 0);
            $pagination['totalPages']   = (int) ($lastPage ?? 1);
        } elseif (null !== $totalItems) {
            $pagination['page']         = 1;
            $pagination['itemsPerPage'] = (int) $totalItems;
            $pagination['totalItems']   = (int) $totalItems;
            $pagination['totalPages']   = 1;
        }

        return $pagination !== [] ? ['pagination' => $pagination] : [];
    }

    /**
     * @param iterable<object> $object
     * @param array<array-key, mixed> $context
     * @return array<mixed>[]
     * @throws ExceptionInterface
     */
    protected function getItemsData(iterable $object, ?string $format = null, array $context = []): array
    {
        $items = [];

        foreach ($object as $obj) {
            $items[] = $this->normalizer->normalize($obj, $format, $context);
        }

        return ['member' => $items];
    }
}
