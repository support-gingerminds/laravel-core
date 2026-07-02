# Configuration & Resource Extensibility

The package publishes a single config file, `config/gingerminds-core.php`, which drives two things:

1. The URL prefix for the whole admin area.
2. The class bindings for every built-in resource (`user`, `contributor`, `role`, `permission`).

Publish it once in your project so you can override it:

```bash
php artisan vendor:publish --tag=gingerminds-config
```

## Route prefix

```php
'admin_prefix' => env('GINGERMINDS_CORE_PREFIX', 'admin'),
```

Every admin route (from the package itself and from other Gingerminds packages) is registered under this prefix. Change the `.env` value if you need a different prefix (e.g. `back-office`).

This value is also what the [authentication guard](Authentication.md) uses to decide whether a URL belongs to the admin area, so changing it is enough — you don't need to touch any middleware.

## Health check route

```php
'health_check_path' => env('HEALTH_CHECK_PATH', 'health'),
```

Registers a `GET {health_check_path}` route (default `/health`) that always returns a `200` JSON response (`{"status": "ok"}`), outside the admin prefix and without any auth middleware. Since the application root typically redirects into the admin area (and therefore into a login redirect for guests), point CI/monitoring health checks at this route instead of `/`.

## Resource bindings: the `resources` array

```php
'resources' => [
    'user' => [
        'model'           => User::class,
        'controller'      => UserController::class,
        'repository'      => UserRepository::class,
        'provider'        => UserProvider::class,
        'request'         => UserRequest::class,
        'state_processor' => UserStateProcessor::class,
    ],
    // contributor, role, permission ...
],
```

These entries are read by `Gingerminds\LaravelCore\Resolver\ResourceResolver`:

```php
ResourceResolver::model('user');      // -> config('gingerminds-core.resources.user.model')
ResourceResolver::controller('user'); // -> config('gingerminds-core.resources.user.controller')
ResourceResolver::repository('user');
ResourceResolver::provider('user');
ResourceResolver::request('user');
ResourceResolver::stateProcessor('user');
```

Routes, `Route::model()` bindings, and API Platform resources all call `ResourceResolver` instead of hardcoding the package's classes. **This is the extension point for the whole package.**

## Overriding a built-in resource

> Per project convention: never edit these classes inside `vendor/gingerminds/laravel-core`. Extend them in the main project, then point the config at your subclass.

1. Extend the model:

```php
namespace App\Models\User;

use Gingerminds\LaravelCore\Models\User\User as CoreUser;

class User extends CoreUser
{
    // add project-specific behaviour here
}
```

2. In your project's **own copy** of `config/gingerminds-core.php` (published via the command above), swap the class:

```php
'resources' => [
    'user' => [
        'model'           => \App\Models\User\User::class, // <- overridden
        'controller'      => UserController::class,        // still the package's
        'repository'      => UserRepository::class,
        'provider'        => UserProvider::class,
        'request'         => UserRequest::class,
        'state_processor' => UserStateProcessor::class,
    ],
],
```

You don't have to override every key — only replace the ones you actually extended. The rest can keep pointing to the package's classes. This is exactly the pattern used for `App\Models\User\User`, `App\Models\Role\Role`, `App\Models\Permission\Permission` and `App\Models\User\Contributor` in most projects.

The same mechanism works for `controller`, `repository`, `request`, `provider` and `state_processor`: extend the package class, then swap the binding.

## Adding a brand-new resource

The `resources` array isn't limited to the four built-in entries. Any resource created with [`php artisan make:resource`](Commands.md) can be registered the same way if it needs to be resolved dynamically (e.g. used by [`select-model` filters](partials/filters.md#select-model-filter) or generic Livewire components).

## See also

- [Resource Model](ResourceModel.md) — base structure a model/repository/request must follow.
- [Authentication](Authentication.md) — how `admin_prefix` is used to protect routes.
- [Commands](Commands.md) — generators that scaffold new resources.
