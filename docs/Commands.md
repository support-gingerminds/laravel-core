# Artisan Commands Reference

All generator commands live under `Gingerminds\LaravelCore\Console\Commands` and follow the same convention for the resource name argument: **`Namespace/Model`**, e.g. `Product/Product` or `Media/MediaCategory`. The namespace becomes the sub-folder (`app/Models/Product/...`), the model becomes the class name.

| Command                    | Generates                                                              |
|-----------------------------|-------------------------------------------------------------------------|
| `make:resource`             | Model + Repository + FormRequest + Controller + blades + routes + translations (everything at once) |
| `make:model`                | Model only (uses the package's stub, implementing `ResourceModelInterface`) |
| `make:repository`           | Repository extending `AbstractRepository`                              |
| `make:form-request`         | FormRequest implementing `FormRequestInterface`                        |
| `make:controller-full`      | Controller (CRUD except `show`) + blades + routes + translation scaffolding |
| `make:api-provider`         | API Platform `ProviderInterface` implementation                        |
| `make:state-processor`      | API Platform `ProcessorInterface` implementation                       |
| `make:policy`               | Policy class, auto-registered in `AuthServiceProvider`                 |
| `gingerminds:create:user`   | Interactive: creates a `User` + linked `Contributor` and assigns a role |

## `make:resource`

```bash
php artisan make:resource Product/Product [options]
```

| Option           | Effect                                                     |
|-------------------|-------------------------------------------------------------|
| `--api`           | Also wires the model for API Platform (see [API](API.md)) |
| `--trad-base=`    | Use a custom base translation key instead of the default   |
| `-m`, `--migration` | Also create a migration                                   |
| `-f`, `--factory`   | Also create a factory                                      |

This is the fastest way to scaffold a whole CRUD resource. Full structure requirements (model, repository, form request) are detailed in [Resource Model](ResourceModel.md).

## `make:controller-full`

```bash
php artisan make:controller-full Product/Product [--trad-base=]
```

Generates a controller with `index`, `create`, `edit`, `store`, `update`, `destroy` (no `show` — the admin panel uses `edit` as the detail page), plus the matching Blade views and route registration.

## `make:api-provider` / `make:state-processor`

```bash
php artisan make:api-provider Product/Product
php artisan make:state-processor Product/Product
```

Required only for resources exposed through API Platform. See [API](API.md) for how the generated classes plug into `#[ApiResource]`.

## `make:policy`

```bash
php artisan make:policy Product/Product
```

Creates `app/Policies/Product/ProductPolicy.php` and automatically adds the `use` statements and the `Model::class => Policy::class` entry to `app/Providers/AuthServiceProvider.php` (or the package's `LaravelCoreAuthServiceProvider` if the app doesn't have its own). Safe to re-run — it detects an existing registration and skips it.

## `gingerminds:create:user`

```bash
php artisan gingerminds:create:user
```

Interactive prompt (email, role, last/first name, password). Creates the `User` record and its linked `Contributor` in the same transaction, then assigns the chosen role. Requires at least one `Role` to already exist (run your seeders first).

## See also

- [Resource Model](ResourceModel.md) — what a generated Model/Repository/FormRequest must implement.
- [Configuration](Configuration.md) — registering a new resource in `config/gingerminds-core.php` once it's generated.
