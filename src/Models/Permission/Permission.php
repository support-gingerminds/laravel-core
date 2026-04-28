<?php

declare(strict_types=1);

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
use Gingerminds\LaravelCore\Models\Role\Role;
use Gingerminds\LaravelCore\Models\SearchableModelInterface;
use Gingerminds\LaravelCore\Models\SortableModelInterface;
use Gingerminds\LaravelCore\StateProcessor\Permission\PermissionStateProcessor;
use Spatie\Permission\Models\Permission as SpatiePermission;
use Symfony\Component\Serializer\Attribute\Groups;

#[ApiResource(
    operations: [
        new GetCollection(
            normalizationContext: ['groups' => [Permission::GROUP_LIST]],
            provider: PermissionProvider::class
        ),
        new Get(
            normalizationContext: ['groups' => [Permission::GROUP_READ]],
            provider: PermissionProvider::class
        ),
        new Post(
            normalizationContext: ['groups' => [Permission::GROUP_READ]],
            denormalizationContext: ['groups' => [Permission::GROUP_EDIT]],
            deserialize: false,
            provider: PermissionProvider::class,
            processor: PermissionStateProcessor::class
        ),
        new Delete(),
        new Patch(
            normalizationContext: ['groups' => [Permission::GROUP_READ]],
            denormalizationContext: ['groups' => [Permission::GROUP_EDIT]],
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
        Permission::GROUP_LIST,
        Permission::GROUP_READ,
        Role::GROUP_READ,
    ])
)]
#[ApiProperty(property: 'name', serialize: new Groups([
    Permission::GROUP_LIST,
    Permission::GROUP_READ,
    Permission::GROUP_EDIT,
    Role::GROUP_READ,
]))]
class Permission extends SpatiePermission implements
    ResourceModelInterface,
    SortableModelInterface,
    SearchableModelInterface
{
    public const string GROUP_LIST = 'permission:list';
    public const string GROUP_READ = 'permission:read';
    public const string GROUP_EDIT = 'permission:edit';

    public static function getSearchableFields(): array
    {
        return ['name'];
    }
}
