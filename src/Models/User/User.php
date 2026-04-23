<?php

namespace Gingerminds\LaravelCore\Models\User;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use Gingerminds\LaravelCore\ApiProvider\User\UserProvider;
use Gingerminds\LaravelCore\Database\Factories\User\UserFactory;
use Gingerminds\LaravelCore\Models\ResourceModelInterface;
use Gingerminds\LaravelCore\Models\SearchableModelInterface;
use Gingerminds\LaravelCore\Models\SortableModelInterface;
use Gingerminds\LaravelCore\StateProcessor\User\UserStateProcessor;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Symfony\Component\Serializer\Attribute\Groups;

/**
 * @property string $password
 * @property string $email
 * @property-read Contributor|null $contributor Relation to the contributor profile
 * @property-read int<0, max> $id
 */
#[ApiResource(
    operations: [
        new GetCollection(
            normalizationContext: ['groups' => ['user:list']],
            provider: UserProvider::class,
        ),
        new Get(
            normalizationContext: ['groups' => ['user:read']],
            provider: UserProvider::class,
        ),
        new Post(
            normalizationContext: ['groups' => ['user:read']],
            denormalizationContext: ['groups' => ['user:create']],
            deserialize: false,
            provider: UserProvider::class,
            processor: UserStateProcessor::class
        ),
        new Delete(),
        new Patch(
            normalizationContext: ['groups' => ['user:read']],
            denormalizationContext: ['groups' => ['user:edit']],
            deserialize: false,
            provider: UserProvider::class,
            processor: UserStateProcessor::class
        ),
    ],
)]
#[ApiProperty(
    identifier: true,
    property: 'id',
    serialize: new Groups([
        'user:list',
        'user:read',
        'contributor:list',
        'contributor:read',
    ])
)]
#[ApiProperty(property: 'email', serialize: new Groups([
    'user:list',
    'user:read',
    'user:create',
    'user:edit',
    'contributor:list',
    'contributor:read',
]))]
#[ApiProperty(property: 'contributor', serialize: new Groups([
    'user:list',
    'user:read',
]))]
#[ApiProperty(property: 'contributor_id', serialize: new Groups([
    'user:edit',
]))]
#[ApiProperty(property: 'contributor_firstname', serialize: new Groups([
    'user:edit',
]))]
#[ApiProperty(property: 'contributor_lastname', serialize: new Groups([
    'user:edit',
]))]
#[ApiProperty(property: 'contributor_trigram', serialize: new Groups([
    'user:edit',
]))]
#[ApiProperty(property: 'contributor_civility', serialize: new Groups([
    'user:edit',
]))]
class User extends Authenticatable implements
    ResourceModelInterface,
    SortableModelInterface,
    SearchableModelInterface
{
    /** @use HasFactory<UserFactory> */
    use HasFactory;
    use HasApiTokens;
    use Notifiable;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'email',
        'email_verified_at',
        'password',
    ];

    protected $casts = [
        'contributor_id'                  => 'integer',
        'contributor_show_personal_phone' => 'boolean',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    /**
     * Get the contributor profile for the user.
     *
     * @return HasOne<Contributor, $this>
     */
    public function contributor(): HasOne
    {
        return $this->hasOne(Contributor::class);
    }

    public static function getSearchableFields(): array
    {
        return ['email'];
    }

    /**
     * Create a new factory instance for the model.
     *
     * @return Factory<User>
     */
    protected static function newFactory(): Factory
    {
        return UserFactory::new();
    }
}
