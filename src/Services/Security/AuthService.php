<?php

namespace Gingerminds\LaravelCore\Services\Security;

use Gingerminds\LaravelCore\Http\Requests\Security\LoginRequest;
use Gingerminds\LaravelCore\Models\User\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class AuthService
{
    /**
     * @return array<mixed>
     */
    public function login(LoginRequest $request): array
    {
        $credentials = $request->validate([
            'username' => 'required|email',
            'password' => 'required',
            'remember' => 'boolean',
        ]);
        $throttleKey = Str::lower($request->input('login')) . '|' . $request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            return [
                'success' => false,
                'error'   => 'USER_BLOCKED',
                'message' => 'Trop de tentatives. Réessayez dans 5 minutes.',
                'status'  => 403,
            ];
        }
        $loginCredentials = [
            'email'    => $credentials['username'],
            'password' => $credentials['password'],
        ];
        if (Auth::attempt($loginCredentials, $request->input('remember', false))) {
            //$request->session()->regenerate();
            /** @var User $user */
            $user = Auth::user();

            $host = (string) $request->header('origin');

            /** @var array<int, string> $domains */
            $domains = [];
            if (config('app.env') == 'local') {
                $domains[] = config('app.url');
            }

            if ($domains === [] || ! in_array($host, $domains, true)) {
                return [
                    'success' => false,
                    'error'   => 'USER_NOT_AUTHORIZED',
                    'message' => 'Domaine non autorisé.',
                    'status'  => 401,
                ];
            }

            RateLimiter::clear($throttleKey);

            return [
                'success' => true,
                'data'    => [
                    //'token' => $token,
                    'user' => $user,
                ],
            ];
        }

        return [
            'success' => false,
            'error'   => 'CREDENTIALS_NOT_VALID',
            'message' => 'Identifiants incorrects.',
            'status'  => 401,
        ];
    }
}
