<?php

namespace Gingerminds\LaravelCore\Models\Role;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use Gingerminds\LaravelCore\ApiProvider\Role\RoleProvider;
use Gingerminds\LaravelCore\Models\Permission\Permission;
use Gingerminds\LaravelCore\Models\ResourceModelInterface;
use Gingerminds\LaravelCore\Models\SearchableModelInterface;
use Gingerminds\LaravelCore\Models\SortableModelInterface;
use Gingerminds\LaravelCore\StateProcessor\Role\RoleStateProcessor;
use Spatie\Permission\Models\Role as SpatieRole;
use Symfony\Component\Serializer\Attribute\Groups;

/**
 * @property bool|null $is_external
 * @property bool|null $is_default
 * @property string $name
 * @property string $guard_name
 * @property int $permissions_count
 */
#[ApiResource(
    operations: [
        new GetCollection(
            normalizationContext: ['groups' => ['role:list']],
            provider: RoleProvider::class
        ),
        new Get(
            normalizationContext: ['groups' => ['role:read']],
            provider: RoleProvider::class
        ),
        new Post(
            normalizationContext: ['groups' => ['role:read']],
            denormalizationContext: ['groups' => ['role:edit']],
            deserialize: false,
            provider: RoleProvider::class,
            processor: RoleStateProcessor::class
        ),
        new Delete(),
        new Patch(
            normalizationContext: ['groups' => ['role:read']],
            denormalizationContext: ['groups' => ['role:edit']],
            deserialize: false,
            provider: RoleProvider::class,
            processor: RoleStateProcessor::class
        ),
    ],
)]
#[ApiProperty(
    identifier: true,
    property: 'id',
    serialize: new Groups([
        'role:list',
        'role:read',
        'user:list',
        'user:read',
    ])
)]
#[ApiProperty(property: 'name', serialize: new Groups([
    'role:list',
    'role:read',
    'role:edit',
    'user:list',
    'user:read',
]))]
#[ApiProperty(property: 'permissions', serialize: new Groups([
    'role:read',
    'role:edit',
]))]
#[ApiProperty(property: 'permissions_count', serialize: new Groups([
    'role:list',
]))]
#[ApiProperty(property: 'is_external', serialize: new Groups([
    'role:list',
    'role:read',
    'role:edit',
]))]
#[ApiProperty(property: 'is_default', serialize: new Groups([
    'role:list',
    'role:read',
    'role:edit',
]))]
class Role extends SpatieRole implements
    ResourceModelInterface,
    SortableModelInterface,
    SearchableModelInterface
{
    protected $fillable = [
        'name',
        'is_external',
        'is_default',
        'guard_name',
    ];

    protected $casts = [
        'is_external' => 'boolean',
        'is_default'  => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function (Role $role): void {
            if (empty($role->guard_name)) {
                $role->guard_name = config('auth.defaults.guard');
            }
        });

        static::saving(function (Role $role): void {
            if ($role->is_default) {
                Role::query()
                    ->where('id', '!=', $role->id)
                    ->where('is_external', $role->is_external)
                    ->update(['is_default' => null]);
            } else {
                $role->is_default = null;
            }
        });
    }

    /**
     * @param Permission|string ...$permissions
     */
    public function syncPermissions(...$permissions): Role
    {
        return parent::syncPermissions($permissions);
    }

    public function getPermissionsCountAttribute(): int
    {
        return $this->permissions->count();
    }

    public static function getSearchableFields(): array
    {
        return ['name'];
    }
}
