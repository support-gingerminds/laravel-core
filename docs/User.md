# User

## The User / Contributor split

The core defines two related models: `User` and `Contributor`.

Splitting them keeps login data separate from profile/business data, and avoids losing profile history when a login account is removed.

### The User

The `User` model is the one that authenticates. It holds login-related data: email, password, and roles ([Spatie permissions](https://spatie.be/docs/laravel-permission)). See [Authentication](Authentication.md) for the login flow.

### The Contributor

The `Contributor` represents the person's profile data (name, contact info, etc.) and is linked to a `User`. Any feature that needs "who is this" data should reference the `Contributor`, not the `User` directly — the `User` should only ever be used for authentication concerns.

## Roles & Permissions

Every generated resource can be assigned permissions. The default convention is:

1. `view resource` — access the list and detail pages.
2. `edit resource` — access the create/edit pages.
3. `delete resource` — perform deletion.

Permissions are enforced through Policies — see [`make:policy`](Commands.md#makepolicy) and [Authentication → Authorization](Authentication.md#authorization-policies).

## Commands

Create a new user (interactive prompt for email, role, name and password):

```bash
php artisan gingerminds:create:user
```

See [Commands](Commands.md#gingermindscreateuser) for details.