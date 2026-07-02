<?php

declare(strict_types=1);

namespace Gingerminds\LaravelCore\Http\Middelware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdminAreaIsAuthenticated
{
    /**
     * @var string[]
     */
    private const array GUEST_ROUTE_NAMES = [
        'gingerminds-core.login',
        'gingerminds-core.authenticate',
        'gingerminds-core.reset-password',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $prefix = trim((string) config('gingerminds-core.admin_prefix'), '/');

        if ($prefix === '' || ! $request->is($prefix, $prefix . '/*')) {
            return $next($request);
        }

        $routeName = $request->route()?->getName();

        if ($routeName !== null && in_array($routeName, self::GUEST_ROUTE_NAMES, true)) {
            return $next($request);
        }

        if (! Auth::guard('web')->check()) {
            if ($request->expectsJson()) {
                abort(401);
            }

            return redirect()->guest(route('gingerminds-core.login'));
        }

        return $next($request);
    }
}
