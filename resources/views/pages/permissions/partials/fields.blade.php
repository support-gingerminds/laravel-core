<div class="col-lg-12">
    <div class="card shadow-sm">
        <div class="card-header bg-light d-flex align-items-center py-3">
            <h5 class="card-title mb-0">
                <i class="bi bi-key me-1 text-primary"></i>
                {{ __('gingerminds-core::translation.permissions.name_s') }}
            </h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <x-gingerminds-core::form.inputs.basic
                        id="name"
                        :label="__('gingerminds-core::translation.permissions.form.name')"
                        :value="old('name', isset($permission) ? $permission->name : null)"
                        placeholder="{{ __('gingerminds-core::translation.permissions.form.name_placeholder') }}"
                    />
                </div>
            </div>
        </div>
    </div>
</div>
