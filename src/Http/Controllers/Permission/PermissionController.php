<?php

namespace Gingerminds\LaravelCore\Http\Controllers\Permission;

use Gingerminds\LaravelCore\Http\Controllers\AbstractController as Controller;
use Gingerminds\LaravelCore\Http\Requests\Permission\PermissionRequest;
use Gingerminds\LaravelCore\Models\Permission\Permission;
use Gingerminds\LaravelCore\Repositories\Permission\PermissionRepository;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    private PermissionRepository $permissionRepository;

    public function __construct()
    {
        $this->permissionRepository = new PermissionRepository();
    }

    public function index(Request $request): Factory|View
    {
        $this->authorize('viewAny');

        $permissions = $this->permissionRepository->get($request);

        return view('gingerminds-core::pages.permissions.index', [
            'resource' => Permission::class,
            'items'    => $permissions,
        ]);
    }

    public function create(): View
    {
        return view('gingerminds-core::pages.permissions.create');
    }

    public function edit(Permission $permission): View
    {
        return view('gingerminds-core::pages.permissions.edit', ['permission' => $permission]);
    }

    public function store(PermissionRequest $request): RedirectResponse
    {
        $this->authorize('create', Permission::class);

        $request->validated();

        $permission = new Permission();
        $this->permissionRepository->update($request, $permission);

        return redirect()
            ->route('gingerminds-core.permissions.index')
            ->with(
                'success',
                __(
                    'gingerminds-core::translation.successfully_created',
                    ['model' => __('gingerminds-core::translation.permissions.name_s') . ' ' . $permission->name]
                )
            );
    }

    public function update(PermissionRequest $request, Permission $permission): RedirectResponse
    {
        $this->authorize('update', $permission);

        $request->validated();

        $this->permissionRepository->update($request, $permission);

        return redirect()
            ->route('gingerminds-core.permissions.edit', $permission->id)
            ->with('success', __(
                'gingerminds-core::translation.successfully_updated',
                [
                    'model' => __('gingerminds-core::translation.permissions.name_s') . ' ' . $permission->name,
                ]
            ));
    }

    public function destroy(Permission $permission): RedirectResponse
    {
        $this->authorize('delete', $permission);

        $permission->delete();

        return redirect()
            ->route('gingerminds-core.permissions.index')
            ->with(
                'success',
                __(
                    'gingerminds-core::translation.successfully_deleted',
                    ['model' => __('gingerminds-core::translation.permissions.name_s') . ' ' . $permission->name]
                )
            );
    }
}
