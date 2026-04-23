<?php

namespace Gingerminds\LaravelCore\Http\Controllers\User;

use Gingerminds\LaravelCore\Http\Controllers\AbstractController as Controller;
use Gingerminds\LaravelCore\Http\Requests\User\ProfileRequest;
use Gingerminds\LaravelCore\Models\User\User;
use Gingerminds\LaravelCore\Repositories\User\UserRepository;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    private UserRepository $userRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
    }

    public function edit(): Factory|View
    {
        /** @var User $user */
        $user = Auth::user();

        /** @var view-string $view */
        $view = 'gingerminds-core::pages.profile.edit';

        return view($view, [
            'user' => $user->load('contributor'),
        ]);
    }

    public function update(ProfileRequest $request): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();

        // On injecte un contributor_id virtuel pour la logique de UserRepository
        if ($user->contributor) {
            $request->merge(['contributor_id' => $user->contributor->id]);
        } else {
            $request->merge(['contributor_id' => '__new__']);
        }

        $this->userRepository->update($request, $user);

        return redirect()
            ->route('gingerminds-core.profile.edit')
            ->with('success', __('gingerminds-core::translation.successfully_updated', [
                'model' => __('gingerminds-core::translation.profile.name'),
            ]));
    }
}
