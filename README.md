# Gingerminds Laravel Core

Core package for Gingerminds admin panels: CRUD scaffolding, an API layer built on API Platform 4, authentication, and resource caching.

- CRUD generators (models, repositories, controllers, Blade views, routes)
- API Platform 4 integration (providers, state processors)
- Session-based admin authentication
- Resource caching

## Installation

```bash
composer require gingerminds/laravel-core
```

Publish the config file to customize the admin route prefix or override a resource binding (see [Configuration](docs/Configuration.md)):

```bash
php artisan vendor:publish --tag=gingerminds-config
```

## Documentation

**Getting started**

- [Resource Model](docs/ResourceModel.md) — the model/repository/request structure a resource must follow.
- [Configuration](docs/Configuration.md) — the `admin_prefix` setting and how to override a built-in resource without touching the package.
- [Commands](docs/Commands.md) — reference for every `make:*` generator.

**Admin panel**

- [Authentication](docs/Authentication.md) — login flow, "remember me", and how admin routes get protected.
- [User](docs/User.md) — the User/Contributor split, roles and permissions.
- [Layouts](docs/templating/layouts.md) — which Blade layout to extend for a list, a form, or a tree view.
- [Forms](docs/templating/forms.md) — form field components (`<x-form.inputs.*>`).
- [Filters](docs/partials/filters.md) — list filters (date, number, boolean, select, select-model).
- [Sorting](docs/Sorting.md) — column sorting and drag & drop reordering.

**API**

- [API](docs/API.md) — wiring a model to API Platform (providers, state processors).
