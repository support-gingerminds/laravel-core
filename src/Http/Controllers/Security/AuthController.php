<?php

namespace Gingerminds\LaravelCore\Http\Controllers\Security;

use Gingerminds\LaravelCore\Http\Controllers\AbstractController as Controller;
use Gingerminds\LaravelCore\Http\Requests\Security\LoginRequest;
use Gingerminds\LaravelCore\Services\Security\AuthService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function __construct(private AuthService $authService)
    {
    }

    public function login(): Factory|View
    {
        /** @var view-string $view */
        $view = 'gingerminds-core::auth.login';

        return view($view);
    }

    public function authenticate(LoginRequest $request): RedirectResponse
    {
        $request->validated();

        $result = $this->authService->login($request);

        if (! $result['success']) {
            return back()->withErrors([
                'message' => $result['message'],
            ])->withInput();
        }

        Auth::login($result['data']['user'], $request->boolean('remember'));

        return redirect()->route('dashboard');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        return redirect()->route('gingerminds-core.login');
    }
}
