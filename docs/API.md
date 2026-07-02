# API

The API layer runs on API Platform 4.

## Operations configuration

Laravel needs a bit of glue to work with API Platform's IRI-based model and our own repository logic, using plain IDs instead. Three pieces are involved:

- A **state processor** — maps requests from API Platform to our repositories (writes).
- An **API provider** — maps our repositories to API Platform (reads).
- **Model configuration** — wires the two above to the `#[ApiResource]` attribute.

### StateProcessor configuration

Extends `BaseStateProcessor` and only needs to set the resource's repository, form request, and model. Base model/repository/request structure is covered in [Resource Model](ResourceModel.md).

```php
use ApiPlatform\State\ProcessorInterface;
use App\Http\Requests\Model\ModelRequest;
use App\Models\Model\Model;
use App\Repositories\Model\ModelRepository;
use Gingerminds\LaravelCore\StateProcessor\BaseStateProcessor;
use Illuminate\Contracts\Validation\Factory as ValidationFactory;
use Illuminate\Http\Request;

/**
 * @implements ProcessorInterface<Model, Model>
 */
class ModelStateProcessor extends BaseStateProcessor implements ProcessorInterface
{
    public function __construct(
        ModelRepository $repository,
        Request $request,
        ValidationFactory $validationFactory
    ) {
        $this->repository = $repository;
        $this->formRequest = new ModelRequest();
        $this->resourceModel = new Model();

        parent::__construct($request, $validationFactory);
    }
}
```

Generate the scaffold with `php artisan make:state-processor Namespace/Model` (see [Commands](Commands.md)).

### API Provider configuration

Centralizes the read logic shared between the admin panel and the API.

```php
use ApiPlatform\State\ProviderInterface;
use App\Models\Model\Model;
use App\Repositories\Model\ModelRepository;
use Gingerminds\LaravelCore\ApiProvider\AbstractApiProvider;
use Gingerminds\LaravelCore\ApiProvider\ApiProviderInterface;

/**
 * @implements ProviderInterface<Model>
 */
class ModelProvider extends AbstractApiProvider implements ProviderInterface, ApiProviderInterface
{
    public function __construct(ModelRepository $repository)
    {
        parent::__construct($repository);
    }
}
```

Generate the scaffold with `php artisan make:api-provider Namespace/Model`.

#### Mapping URI variables to filters

For nested endpoints such as `/api/properties/{property}/{id}`, you don't need to handle `{id}` — `AbstractApiProvider` and the repository already do. But `{property}` needs to be turned into a filter:

```php
public function addFilters(Request $request, array $uriVariables, array $context): void
{
    $filters    = $request->query('filters', []);
    $propertyId = $uriVariables['property'] ?? $request->route('property');

    if ($propertyId) {
        $filters['property_id'] = $propertyId;
    }

    $request->query->set('filters', $filters);
}
```

You then need to enable that filter for the resource — see [Filters](partials/filters.md). If the resource has no filters or contextual/nested property, you don't need to do anything.

### Model configuration

To work with the state processor/provider above, the model needs to:

- Disable API Platform's deserialization (so it doesn't try to turn sub-resources into IRIs itself).
- Point each write operation at the state processor.
- Point each operation at the API provider.

```php
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\ApiProvider\Model\ModelProvider;
use App\StateProcessor\Model\ModelStateProcessor;
use Gingerminds\LaravelCore\Models\ResourceModelInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

#[ApiResource(
    operations: [
        new GetCollection(
            provider: ModelProvider::class
        ),
        new Get(
            provider: ModelProvider::class
        ),
        new Post(
            deserialize: false,
            provider: ModelProvider::class,
            processor: ModelStateProcessor::class
        ),
        new Patch(
            deserialize: false,
            provider: ModelProvider::class,
            processor: ModelStateProcessor::class
        ),
    ],
)]
class Model extends Model implements ResourceModelInterface
{
    use HasFactory;

    protected $fillable = [];
}
```

`make:resource --api` (see [Commands](Commands.md)) scaffolds this whole chain — model, repository, form request, state processor and API provider — in one go.

## See also

- [Resource Model](ResourceModel.md) — the model/repository/request contract these classes build on.
- [Configuration](Configuration.md) — registering the resource in `config/gingerminds-core.php` so it can also be resolved dynamically (e.g. by `ResourceResolver`).
- [Filters](partials/filters.md) — enabling filters consumed by both the admin panel and the API.
