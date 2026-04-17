<div class="col-lg-12">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <x-gingerminds-core::form.inputs.basic
                    id="name"
                    :label="__('gingerminds-core::translation.permissions.form.name')"
                    :value="old('name', isset($permission) ? $permission->name : null)"
                    size="sm"
                />
            </div>
        </div>
    </div>
</div>
