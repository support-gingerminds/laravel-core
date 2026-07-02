# Authentication & Admin Route Protection

The admin panel is a classic session-based (guard `web`) Blade application, separate from the stateless API exposed through API Platform. This page covers how login works and how admin routes are protected.

## Login flow

Routes are registered by the package under [`admin_prefix`](Configuration.md#route-prefix) (default `admin`):

| Method | URI                    | Name                          |
|--------|------------------------|-------------------------------|
| GET    | `/admin/login`         | `gingerminds-core.login`      |
| POST   | `/admin/login`         | `gingerminds-core.authenticate` |
| GET    | `/admin/reset-password`| `gingerminds-core.reset-password` |
| POST   | `/admin/logout`        | `gingerminds-core.logout`     |

`AuthController::authenticate()` delegates credential checking to `AuthService::login()`, which:

1. Rate-limits attempts per `email|ip` (5 attempts).
2. Calls `Auth::attempt(['email' => ..., 'password' => ...], $request->boolean('remember'))`.
3. Checks the request `Origin` header against `config('auth.authorized_domains')` (skipped when `APP_ENV=local`).

The login form (`resources/views/auth/login.blade.php`) can be overridden per project the usual way, by publishing/copying it to `resources/views/vendor/gingerminds-core/auth/login.blade.php`.

### "Remember me"

The checkbox posts `remember=on` (default HTML checkbox value). `AuthService` never runs a strict `boolean` validation rule against it — it reads the value with `$request->boolean('remember')`, which correctly understands `"on"`, `"1"`, `"true"`, etc. Keep it that way: adding a `'remember' => 'boolean'` validation rule will reject `"on"` and silently break login whenever the box is checked.

## Protecting a route

There are two layers. In practice you rarely need to think about the second one — it exists as a safety net.

### 1. The `gingerminds-core.auth` middleware (explicit)

This is a custom `Illuminate\Auth\Middleware\Authenticate` subclass (`Gingerminds\LaravelCore\Http\Middelware\Authenticate`) registered as the `gingerminds-core.auth` alias. Its only difference from Laravel's default: it always redirects non-JSON guests to `route('gingerminds-core.login')`, instead of relying on a route literally named `login` (which doesn't exist here — the login route is namespaced).

Apply it explicitly to any route group that needs a logged-in user:

```php
Route::middleware(['web', 'gingerminds-core.auth'])
    ->prefix(config('gingerminds-core.admin_prefix'))
    ->group(function () {
        Route::resource('my-resource', MyResourceController::class);
    });
```

This is how every admin route in the package, and in `laravel-media-manager` / `laravel-cms` / `laravel-multisite`, is protected.

### 2. The global admin-prefix guard (safety net)

`EnsureAdminAreaIsAuthenticated` is pushed onto the `web` middleware group for the whole application (`LaravelCoreServiceProvider::boot()`). On every request it checks:

- Does the URL start with [`admin_prefix`](Configuration.md#route-prefix)? If not, it does nothing.
- Is the route one of the public auth routes (`gingerminds-core.login`, `.authenticate`, `.reset-password`)? If so, it lets it through.
- Otherwise, is there an authenticated `web` user? If not, redirect to `gingerminds-core.login` (or `401` for JSON requests).

This means **any route registered under the admin prefix is protected automatically**, even if a new route group forgets to add `gingerminds-core.auth`. It's a defense-in-depth measure, not a replacement for adding the middleware explicitly — always add `gingerminds-core.auth` to new admin route groups; don't rely on the safety net alone.

## Authorization (Policies)

Authentication (who are you) is separate from authorization (what can you do). Once a resource has a Policy — generated with `php artisan make:policy` (see [Commands](Commands.md)) — it's registered in `AuthServiceProvider`'s `$policies` array and can be checked the usual Laravel way (`$this->authorize(...)`, `@can` in Blade, `Gate::allows(...)`).

## See also

- [Configuration](Configuration.md) — `admin_prefix` and resource bindings.
- [Commands](Commands.md) — `make:policy` and `gingerminds:create:user`.
- [User](User.md) — the User/Contributor model split and roles.
