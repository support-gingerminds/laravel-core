# API Documentation

This project work on ApiPlatform v4
## Operations configuration

With laravel we need to create specific configuration to work with our own repository logic and with plain ID's instead of ApiPlatform's IRI. To do so we will need:
- A state processor (it will do the request mapping between ApiPlatform and our repositories)
- An Api provider (it will do the request mapping between our repositories and ApiPlatform)
- Model configuration
### StateProcessor Configuration
This processor will be used in the model configuration, it extends BaseStateProcessor and we only need to set:

- The repository of the resource (RepositoryInterface)
- The form request of the resource (FormRequestInterface)
- The resource model of the resource (ResourceModelInterface)

All base configs are explained in the [ResourceModel.md](ResourceModel.md)

```php
use ApiPlatform\State\ProcessorInterface;  
use App\Http\Requests\Model\ModelRequest;  
use App\Models\Model\Model;  
use App\Repositories\Model\ModelRepository;  
use App\StateProcessor\BaseStateProcessor;  
use Illuminate\Contracts\Validation\Factory as ValidationFactory;  
use Illuminate\Http\Request;  
  
/**  
 * @implements ProcessorInterface<Model, Model>  
 */class ModelStateProcessor extends BaseStateProcessor implements ProcessorInterface  
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

You can easily create a new StateProcessor by using command : `php artisan make:state-processor Model/Model`

### Api Provider configuration
To centralize logic between Back-office and API we will need to add a provider to our ApiPlatform configuration.

```php
use ApiPlatform\State\ProviderInterface;
use App\ApiProvider\AbstractApiProvider;
use App\ApiProvider\ApiProviderInterface;
use App\Repositories\Model\ModelRepository;  
use App\Models\Model\Model;  

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
You can easily create a new ApiProvider by using command : `php artisan make:api-provider Model/Model`

#### ApiProvider Additional configuration
In some cases you will need to convert uriVariables into filters.
To do so you will need to add a method to your ApiProvider :

*For Example we have an endpoint `/api/properties/{property}/{id}`*

You don't need to define the logic for the `{id}`. It's managed by the AbstractApiProvider and the Repository.

```php
public function addFilters(Request $request, array $uriVariables, array $context): void
{
    $filters  = $request->query('filters', []);
    $propertyId = $uriVariables['property'] ?? $request->route('property');

    if ($propertyId) {
        $filters['property_id'] = $propertyId;
    }

    $request->query->set('filters', $filters);
}
```

After that you will need to enable filtering for your resource : Look at the [filters.md](partials/filters.md)

**If the resource does not have filters or relation contexted property. You don't need to do anything.**

### Model configuration
To work with our state we need:
- To disable ApiPlatform's deserialization (To prevent ApiPlatform to automatically transform sub resources into IRI)
- A processor with the stateProcessor created just above
- A provider with the ApiProvider created just above

```php
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Patch;  
use ApiPlatform\Metadata\Post;
use Illuminate\Database\Eloquent\Model;
use App\Models\ResourceModelInterface;
use App\StateProcessor\Model\ModelStateProcessor;
use App\ApiProvider\Model\ModelProvider;

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
	use LogsActivity;
	
	protected $fillable = [];
}
```
