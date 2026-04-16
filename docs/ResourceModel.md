# Resource model Documentation

To create a new resource, you can use the command : `php artisan make:resource Model/Model`

You can add the `--api` option to create a resource for ApiPlatform.
You can add the `--trad-base` option to create a resource with a base translation key.

In order to work with ApiPlatform. All bases models, repository and formRequest need to have some structure like following.
## Base model structure

```php
use Illuminate\Database\Eloquent\Model;
use App\Models\ResourceModelInterface;

class Model extends Model implements ResourceModelInterface
{
	protected $fillable = [];
}
```

You can easily create a new Model by using command : `php artisan make:model Model/Model`

### Optionnal Interfaces

#### App\Models\EntityContextedModelInterface
If the resource is entity contextualized you will need this interface + `use App\Models\Trait\EntityContextedModelTrait`

#### App\Models\SortableModelInterface
If you want to enable sorting on your resource

#### App\Models\SearchableModelInterface
If you want to enable search on your resource. This will add getSearchableFields method to your model

#### App\Models\FilterableModelInterface
If you want to enable filters on your resource. This will add getFilters method to your model. If you want to see all available filters,
go to [filters.md](partials/filters.md)

#### App\Models\CacheableResourceInterface
If you want to enable cache on your resource. This will add getCacheKey method to your model

## Base repository structure

```php
use App\Http\Requests\FormRequestInterface;
use App\Models\ResourceModelInterface;
use App\Repositories\RepositoryInterface;
use App\Repositories\AbstractRepository;
use App\Models\Model;
use InvalidArgumentException;

/**
 * @extends AbstractRepository<Model>
 */
class ModelRepository extends AbstractRepository  implements RepositoryInterface
{
    public function update(
        ?FormRequestInterface $request,
        ResourceModelInterface $resourceModel
    ): ResourceModelInterface {
        if (!$resourceModel instanceof Model) {
            throw new InvalidArgumentException('ResourceModelInterface must be an instance of Model');
        }

        if (!$request instanceof FormRequestInterface) {
            return $resourceModel;
        }
        
        $resourceModel->fill($request->all());
        $resourceModel->save();
        
        return $resourceModel;
    }
}
```

You can easily create a new Repository by using command : `php artisan make:repository Model/Model`

## Base form request structure

```php
use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\FormRequestInterface;

class ModelRequest extends FormRequest implements FormRequestInterface
{
    public function rules(): array
    {
        return [];
    }
}
```

You can easily create a new FormRequest by using command : `php artisan make:form-request Model/Model`

## Base controller structure

The controller will be built with the following structure :

- index
- create
- edit
- store
- update
- destroy

The base will be configured with The model FormRequest and the repository.

You can easily create a new Controller by using command : `php artisan make:controller-full Model/Model`

You can add the `--trad-base` option to create a resource with a base translation key.

