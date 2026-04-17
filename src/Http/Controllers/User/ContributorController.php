<?php

namespace Gingerminds\LaravelCore\Http\Controllers\User;

use Gingerminds\LaravelCore\Http\Controllers\AbstractController as Controller;
use Gingerminds\LaravelCore\Http\Requests\User\ContributorRequest;
use Gingerminds\LaravelCore\Models\User\Contributor;
use Gingerminds\LaravelCore\Models\User\User;
use Gingerminds\LaravelCore\Repositories\User\ContributorRepository;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ContributorController extends Controller
{
    private ContributorRepository $contributorRepository;

    public function __construct()
    {
        $this->contributorRepository = new ContributorRepository();
    }

    public function index(Request $request): Factory|View
    {
        $this->authorize('viewAny');

        $contributors = $this->contributorRepository->get($request);

        return view('gingerminds-core::pages.contributors.index', [
            'resource' => Contributor::class,
            'items'    => $contributors,
        ]);
    }

    public function create(): Factory|View
    {
        // Autorisation de consulter le formulaire d'édition
        $this->authorize('create', Contributor::class);

        // Charger la liste des utilisateurs pour lier/délier un compte utilisateur
        $users = User::query()
            ->select(['id','email'])
            ->orderBy('email')
            ->get();

        return view('gingerminds-core::pages.contributors.create', [
            'users' => $users,
        ]);
    }

    public function store(ContributorRequest $request): RedirectResponse
    {
        $this->authorize('create', Contributor::class);

        $request->validated();

        /** @var Contributor $contributor */
        $contributor = $this->contributorRepository->update($request, new Contributor());

        return redirect()
            ->route('gingerminds-core.contributors.index')
            ->with(
                'success',
                __(
                    'gingerminds-core::translation.successfully_created',
                    [
                        'model' => __('gingerminds-core::translation.contributors.name_s')
                            . ' '
                            . $contributor->firstname
                            . ' '
                            . $contributor->lastname,
                    ]
                )
            );
    }

    public function edit(Contributor $contributor): Factory|View
    {
        // Autorisation de consulter le formulaire d'édition
        $this->authorize('update', $contributor);

        // Charger la liste des utilisateurs pour lier/délier un compte utilisateur
        $users = User::query()
            ->select(['id','email'])
            ->orderBy('email')
            ->get();

        return view('gingerminds-core::pages.contributors.edit', [
            'contributor'       => $contributor,
            'users'             => $users,
        ]);
    }

    public function update(ContributorRequest $request, Contributor $contributor): RedirectResponse
    {
        // Autorisation d'édition
        $this->authorize('update', $contributor);

        $request->validated();

        /** @var Contributor $contributor */
        $contributor = $this->contributorRepository->update($request, $contributor);

        return redirect()
            ->route('gingerminds-core.contributors.index')
            ->with(
                'success',
                __(
                    'gingerminds-core::translation.successfully_updated',
                    [
                        'model' => __('gingerminds-core::translation.contributors.name_s')
                            . ' '
                            . $contributor->firstname
                            . ' '
                            . $contributor->lastname,
                    ]
                )
            );
    }

    public function destroy(Contributor $contributor): RedirectResponse
    {
        $contributor->delete();

        return redirect()
            ->route('gingerminds-core.contributors.index')
            ->with(
                'success',
                __(
                    'gingerminds-core::translation.successfully_deleted',
                    [
                        'model' => __('gingerminds-core::translation.profils.name_s')
                            . ' '
                            . $contributor->firstname
                            . ' '
                            . $contributor->lastname,
                    ]
                )
            );
    }
}
