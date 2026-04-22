<?php

namespace Gingerminds\LaravelCore\Http\Controllers\User;

use Gingerminds\LaravelCore\Http\Controllers\AbstractController as Controller;
use Gingerminds\LaravelCore\Http\Requests\User\UserRequest;
use Gingerminds\LaravelCore\Models\User\Contributor;
use Gingerminds\LaravelCore\Models\User\User;
use Gingerminds\LaravelCore\Repositories\User\UserRepository;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Gingerminds\LaravelCore\Models\Role\Role;

class UserController extends Controller
{
    private UserRepository $userRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
    }

    public function index(Request $request): Factory|View
    {
        $this->authorize('viewAny');

        $users = $this->userRepository->get($request);
        $roles = Role::query()->orderBy('name')->get();

        return view('gingerminds-core::pages.users.index', [
            'resource' => User::class,
            'items'    => $users,
            'roles'    => $roles,
        ]);
    }

    public function create(): Factory|View
    {
        $this->authorize('create', User::class);

        $roles        = Role::query()->orderBy('name')->get();
        $contributors = Contributor::query()
            ->orderBy('lastname')
            ->orderBy('firstname')
            ->get();

        return view('gingerminds-core::pages.users.create', [
            'roles'        => $roles,
            'contributors' => $contributors,
        ]);
    }

    public function store(UserRequest $request): RedirectResponse
    {
        $this->authorize('create', User::class);

        $request->validated();

        /** @var User $user */
        $user = $this->userRepository->update($request, new User());

        return redirect()
            ->route('gingerminds-core.users.index')
            ->with('success', __('gingerminds-core::translation.successfully_created', [
                'model' => __('gingerminds-core::translation.users.name_s') . ' ' . $user->email,
            ]));
    }

    public function update(UserRequest $request, User $user): RedirectResponse
    {
        // Autorisation d'édition
        $this->authorize('update', $user);

        $request->validated();

        /** @var User $user */
        $user = $this->userRepository->update($request, $user);

        return redirect()
            ->route('gingerminds-core.users.edit', $user->id)
            ->with('success', __('gingerminds-core::translation.successfully_updated', [
                'model' => __('gingerminds-core::translation.users.name_s') . ' ' . $user->email,
            ]));
    }

    public function edit(User $user): Factory|View
    {
        // Autorisation de consulter le formulaire d'édition
        $this->authorize('update', $user);

        $roles        = Role::query()->orderBy('name')->get();
        $contributors = Contributor::query()
            ->orderBy('lastname')
            ->orderBy('firstname')
            ->get();

        return view('gingerminds-core::pages.users.edit', [
            'user'         => $user->load(['roles', 'contributor']),
            'roles'        => $roles,
            'contributors' => $contributors,
        ]);
    }

    public function destroy(User $user): RedirectResponse
    {
        $user->delete();

        return redirect()
            ->route('gingerminds-core.users.index')
            ->with('success', __('gingerminds-core::translation.successfully_deleted', [
                'model' => __('gingerminds-core::translation.users.name_s') . ' ' . $user->email,
            ]));
    }
}
