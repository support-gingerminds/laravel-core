<div class="col-lg-8">
    <div class="card">
        <div class="card-body">
            <div class="row mb-3">
                <x-gingerminds-core::form.inputs.basic
                    id="name"
                    :label="__('gingerminds-core::translation.form.name')"
                    :value="old('name', isset($role) ? $role->name : null)"
                />
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header bg-transparent border-bottom">
            <h5 class="card-title mb-0">{{ __('gingerminds-core::translation.roles.form.permissions') }}</h5>
        </div>
        <div class="card-body">
            @php
                $rolePermissions = isset($role) ? $role->permissions->pluck('id')->toArray() : [];
            @endphp
            <div class="table-responsive">
                <table class="table table-nowrap align-middle mb-0">
                    <thead>
                    <tr>
                        <th>{{ __('gingerminds-core::translation.roles.form.resource') }}</th>
                        <th>{{ __('gingerminds-core::translation.roles.form.permissions') }}</th>
                        <th class="text-end">
                            <div class="form-check form-check-right">
                                <input class="form-check-input check-all-permissions" type="checkbox" id="checkAllPermissions">
                                <label class="form-check-label" for="checkAllPermissions">{{ __('gingerminds-core::translation.roles.form.check_all') }}</label>
                            </div>
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($permissionsGrouped as $resource => $permissions)
                        <tr>
                            <td style="width: 200px;"><span class="badge badge-soft-primary font-size-13 text-uppercase">{{ $resource }}</span></td>
                            <td>
                                <div class="d-flex flex-wrap gap-3">
                                    @foreach($permissions as $permission)
                                        <div class="form-check">
                                            <input class="form-check-input permission-checkbox"
                                                   type="checkbox"
                                                   name="permissions[]"
                                                   value="{{ $permission->id }}"
                                                   id="perm-{{ $permission->id }}"
                                                   @if(in_array($permission->id, old('permissions', $rolePermissions))) checked @endif>
                                            <label class="form-check-label" for="perm-{{ $permission->id }}">
                                                {{ explode(' ', $permission->name)[0] }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </td>
                            <td class="text-end">
                                <div class="form-check form-check-right">
                                    <input class="form-check-input check-resource-all" type="checkbox" data-resource="{{ $resource }}">
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
    <script src="{{ URL::asset('build/pages/permissions.init.js') }}"></script>
@endpush

<div class="col-lg-4">
    <div class="card">
        <div class="card-body">
            <div class="row mb-3">
                <x-gingerminds-core::form.inputs.toggle
                    id="is_external"
                    :label="__('gingerminds-core::translation.roles.form.is_external')"
                    :checked="old('is_external', isset($role) ? $role->is_external : false)"
                />
            </div>
            <div class="row">
                <x-gingerminds-core::form.inputs.toggle
                    id="is_default"
                    :label="__('gingerminds-core::translation.roles.form.is_default')"
                    :checked="old('is_default', isset($role) ? $role->is_default : false)"
                />
            </div>
        </div>
    </div>
</div>
