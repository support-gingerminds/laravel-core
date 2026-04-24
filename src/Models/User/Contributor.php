<?php

namespace Gingerminds\LaravelCore\Models\User;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use Gingerminds\LaravelCore\ApiProvider\User\ContributorProvider;
use Gingerminds\LaravelCore\Database\Factories\User\ContributorFactory;
use Gingerminds\LaravelCore\Models\ResourceModelInterface;
use Gingerminds\LaravelCore\Models\SearchableModelInterface;
use Gingerminds\LaravelCore\Models\SortableModelInterface;
use Gingerminds\LaravelCore\StateProcessor\User\ContributorStateProcessor;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Symfony\Component\Serializer\Attribute\Groups;

/**
 * @property int|null $user_id
 * @property string $firstname
 * @property string $lastname
 * @property string $trigram
 * @property string $civility
 */
#[ApiResource(
    operations: [
        new GetCollection(
            normalizationContext: ['groups' => ['contributor:list']],
            provider: ContributorProvider::class,
        ),
        new Get(
            normalizationContext: ['groups' => ['contributor:read']],
            provider: ContributorProvider::class,
        ),
        new Post(
            normalizationContext: ['groups' => ['contributor:read']],
            denormalizationContext: ['groups' => ['contributor:edit']],
            deserialize: false,
            provider: ContributorProvider::class,
            processor: ContributorStateProcessor::class
        ),
        new Delete(),
        new Patch(
            normalizationContext: ['groups' => ['contributor:read']],
            denormalizationContext: ['groups' => ['contributor:edit']],
            deserialize: false,
            provider: ContributorProvider::class,
            processor: ContributorStateProcessor::class
        ),
    ],
)]
#[ApiProperty(property: 'id', serialize: new Groups([
    'contributor:list',
    'contributor:read',
    'user:read',
    'user:list',
]))]
#[ApiProperty(property: 'firstname', serialize: new Groups([
    'contributor:list',
    'contributor:read',
    'contributor:edit',
    'user:read',
    'user:list',
]))]
#[ApiProperty(property: 'lastname', serialize: new Groups([
    'contributor:list',
    'contributor:read',
    'contributor:edit',
    'user:read',
    'user:list',
]))]
#[ApiProperty(property: 'trigram', serialize: new Groups([
    'contributor:list',
    'contributor:read',
    'contributor:edit',
    'user:read',
    'user:list',
]))]
#[ApiProperty(property: 'civility', serialize: new Groups([
    'contributor:list',
    'contributor:read',
    'contributor:edit',
    'user:read',
    'user:list',
]))]
#[ApiProperty(property: 'user', serialize: new Groups([
    'contributor:list',
    'contributor:read',
]))]
#[ApiProperty(property: 'user_id', serialize: new Groups([
    'contributor:edit',
]))]
class Contributor extends Model implements
    ResourceModelInterface,
    SortableModelInterface,
    SearchableModelInterface
{
    /** @use HasFactory<ContributorFactory> */
    use HasFactory;
    use SoftDeletes;

    /**
     * @return string[]
     */
    public function getFillable(): array
    {
        return [
            'firstname',
            'lastname',
            'trigram',
            'civility',
            'avatar',
            'user_id',
        ];
    }

    /**
     * @return string[]
     */
    public function getCasts(): array
    {
        return [
            'user_id' => 'integer',
        ];
    }

    /**
     * Get the user that owns the Contributor
     *
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function getSearchableFields(): array
    {
        return ['firstname', 'lastname', 'trigram'];
    }

    /**
     * Create a new factory instance for the model.
     *
     * @return Factory<Contributor>
     */
    protected static function newFactory(): Factory
    {
        return ContributorFactory::new();
    }
}
