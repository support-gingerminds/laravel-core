<?php

declare(strict_types=1);

namespace Gingerminds\LaravelCore\Contracts\Auth;

use Illuminate\Contracts\Auth\Authenticatable;

interface LoginResponseEnricherInterface
{
    /**
     * Enrich the login API response data for the given authenticated user.
     *
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     */
    public function enrich(Authenticatable $user, array $data): array;
}
