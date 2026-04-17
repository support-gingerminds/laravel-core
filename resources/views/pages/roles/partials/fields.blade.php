<div class="col-lg-12">
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light d-flex align-items-center justify-content-between py-3">
            <h5 class="card-title mb-0">
                <i class="bi bi-shield-lock me-1 text-primary"></i>
                {{ __('gingerminds-core::translation.roles.name_s') }}
            </h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <x-gingerminds-core::form.inputs.basic
                        id="name"
                        :label="__('gingerminds-core::translation.form.name')"
                        :value="old('name', isset($role) ? $role->name : null)"
                        placeholder="{{ __('gingerminds-core::translation.roles.form.name_placeholder') }}"
                    />
                </div>
                <div class="col-md-3">
                    <div class="mt-4 pt-2">
                        <x-gingerminds-core::form.inputs.toggle
                            id="is_external"
                            :label="__('gingerminds-core::translation.roles.form.is_external')"
                            :checked="old('is_external', isset($role) ? $role->is_external : false)"
                        />
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mt-4 pt-2">
                        <x-gingerminds-core::form.inputs.toggle
                            id="is_default"
                            :label="__('gingerminds-core::translation.roles.form.is_default')"
                            :checked="old('is_default', isset($role) ? $role->is_default : false)"
                        />
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-light d-flex align-items-center justify-content-between py-3">
            <h5 class="card-title mb-0">
                <i class="bi bi-lock me-1 text-primary"></i>
                {{ __('gingerminds-core::translation.roles.form.permissions') }}
            </h5>
            <div class="form-check form-switch form-check-right mb-0">
                <input class="form-check-input check-all-permissions" type="checkbox" id="checkAllPermissions" style="cursor: pointer;">
                <label class="form-check-label fw-medium" for="checkAllPermissions" style="cursor: pointer;">
                    {{ __('gingerminds-core::translation.roles.form.check_all') }}
                </label>
            </div>
        </div>
        <div class="card-body p-0">
            @php
                $rolePermissions = isset($role) ? $role->permissions->pluck('id')->toArray() : [];
            @endphp
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-muted">
                        <tr>
                            <th scope="col" style="width: 25%;">{{ __('gingerminds-core::translation.roles.form.resource') }}</th>
                            <th scope="col">{{ __('gingerminds-core::translation.roles.form.permissions') }}</th>
                            <th scope="col" class="text-end" style="width: 100px;">
                                <span class="me-2">{{ __('gingerminds-core::translation.roles.form.check_all') }}</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($permissionsGrouped as $resource => $permissions)
                            <tr>
                                <td class="fw-bold text-uppercase text-primary small">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-folder2-open me-2 fs-5 op-5"></i>
                                        {{ $resource }}
                                    </div>
                                </td>
                                <td>
                                    <div class="row g-2">
                                        @foreach($permissions as $permission)
                                            <div class="col-auto">
                                                <div class="form-check form-check-inline border rounded px-3 py-1 mb-0 bg-light-subtle hover-bg-light transition-all" style="min-width: 120px;">
                                                    <input class="form-check-input permission-checkbox"
                                                           type="checkbox"
                                                           name="permissions[]"
                                                           value="{{ $permission->id }}"
                                                           id="perm-{{ $permission->id }}"
                                                           data-resource="{{ $resource }}"
                                                           @if(in_array($permission->id, old('permissions', $rolePermissions))) checked @endif
                                                           style="cursor: pointer;">
                                                    <label class="form-check-label small mb-0 ms-1 text-capitalize" for="perm-{{ $permission->id }}" style="cursor: pointer; user-select: none;">
                                                        {{ str_replace($resource . '.', '', $permission->name) }}
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </td>
                                <td class="text-end">
                                    <div class="form-check form-switch form-check-right d-inline-block mb-0">
                                        <input class="form-check-input check-resource-all" 
                                               type="checkbox" 
                                               data-resource="{{ $resource }}"
                                               id="check-{{ Str::slug($resource) }}"
                                               style="cursor: pointer;">
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Sélection globale de toutes les permissions
            const checkAllTop = document.getElementById('checkAllPermissions');
            const permissionCheckboxes = document.querySelectorAll('.permission-checkbox');
            const resourceAllCheckboxes = document.querySelectorAll('.check-resource-all');

            if (checkAllTop) {
                checkAllTop.addEventListener('change', function () {
                    permissionCheckboxes.forEach(cb => {
                        cb.checked = this.checked;
                    });
                    resourceAllCheckboxes.forEach(cb => {
                        cb.checked = this.checked;
                    });
                });
            }

            // Sélection par ressource
            resourceAllCheckboxes.forEach(resourceCb => {
                resourceCb.addEventListener('change', function () {
                    const resource = this.dataset.resource;
                    const resourcePermissions = document.querySelectorAll(`.permission-checkbox[data-resource="${resource}"]`);
                    resourcePermissions.forEach(cb => {
                        cb.checked = this.checked;
                    });
                    updateGlobalCheck();
                });
            });

            // Mise à jour de l'état "Tout cocher" quand on clique sur une permission individuelle
            permissionCheckboxes.forEach(cb => {
                cb.addEventListener('change', function () {
                    const resource = this.dataset.resource;
                    updateResourceCheck(resource);
                    updateGlobalCheck();
                });
            });

            function updateResourceCheck(resource) {
                const resourceCb = document.querySelector(`.check-resource-all[data-resource="${resource}"]`);
                const allResPerms = document.querySelectorAll(`.permission-checkbox[data-resource="${resource}"]`);
                const checkedResPerms = document.querySelectorAll(`.permission-checkbox[data-resource="${resource}"]:checked`);
                
                if (resourceCb) {
                    resourceCb.checked = allResPerms.length === checkedResPerms.length;
                    resourceCb.indeterminate = checkedResPerms.length > 0 && checkedResPerms.length < allResPerms.length;
                }
            }

            function updateGlobalCheck() {
                if (checkAllTop) {
                    const checkedCount = document.querySelectorAll('.permission-checkbox:checked').length;
                    checkAllTop.checked = checkedCount === permissionCheckboxes.length;
                    checkAllTop.indeterminate = checkedCount > 0 && checkedCount < permissionCheckboxes.length;
                }
            }

            // Initialisation de l'état des checkboxes de ressources au chargement
            const resources = [...new Set([...resourceAllCheckboxes].map(cb => cb.dataset.resource))];
            resources.forEach(res => updateResourceCheck(res));
            updateGlobalCheck();
        });
    </script>
@endpush

<style>
    .transition-all { transition: all 0.2s ease-in-out; }
    .hover-bg-light:hover { background-color: var(--bs-light) !important; border-color: var(--bs-primary) !important; }
    .form-check-input:checked + .form-check-label { font-weight: 600; color: var(--bs-primary); }
    .op-5 { opacity: 0.5; }
</style>
