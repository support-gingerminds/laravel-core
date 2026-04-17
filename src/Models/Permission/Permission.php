<?php

namespace Gingerminds\LaravelCore\Models\Permission;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use Gingerminds\LaravelCore\ApiProvider\Permission\PermissionProvider;
use Gingerminds\LaravelCore\Models\ResourceModelInterface;
use Gingerminds\LaravelCore\Models\SearchableModelInterface;
use Gingerminds\LaravelCore\Models\SortableModelInterface;
use Gingerminds\LaravelCore\StateProcessor\Permission\PermissionStateProcessor;
use Spatie\Permission\Models\Permission as SpatiePermission;
use Symfony\Component\Serializer\Attribute\Groups;

#[ApiResource(
    operations: [
        new GetCollection(
            normalizationContext: ['groups' => ['permission:list']],
            provider: PermissionProvider::class
        ),
        new Get(
            normalizationContext: ['groups' => ['permission:read']],
            provider: PermissionProvider::class
        ),
        new Post(
            normalizationContext: ['groups' => ['permission:read']],
            denormalizationContext: ['groups' => ['permission:edit']],
            deserialize: false,
            provider: PermissionProvider::class,
            processor: PermissionStateProcessor::class
        ),
        new Delete(),
        new Patch(
            normalizationContext: ['groups' => ['permission:read']],
            denormalizationContext: ['groups' => ['permission:edit']],
            deserialize: false,
            provider: PermissionProvider::class,
            processor: PermissionStateProcessor::class
        ),
    ],
)]
#[ApiProperty(
    identifier: true,
    property: 'id',
    serialize: new Groups([
        'permission:list',
        'permission:read',
        'role:read',
    ])
)]
#[ApiProperty(property: 'name', serialize: new Groups([
    'permission:list',
    'permission:read',
    'permission:edit',
    'role:read',
]))]
class Permission extends SpatiePermission implements
    ResourceModelInterface,
    SortableModelInterface,
    SearchableModelInterface
{

    public static function getSearchableFields(): array
    {
        return ['name'];
    }
}
