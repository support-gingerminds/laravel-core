<div class="col-lg-12">
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light d-flex align-items-center py-3">
            <h5 class="card-title mb-0">
                <i class="bi bi-person-badge me-1 text-primary"></i>
                {{ __('gingerminds-core::translation.contributors.name_s') }}
            </h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-2">
                    <x-gingerminds-core::form.inputs.select
                        id="civility"
                        :label="__('gingerminds-core::translation.contributors.form.civility')"
                        :required="false"
                    >
                        <option></option>
                        <option
                            value="mr" {{ old('civility', isset($contributor) ? $contributor->civility : null) === 'mr' ? 'selected' : '' }}>@lang('gingerminds-core::translation.users.form.mr')</option>
                        <option
                            value="mrs" {{ old('civility', isset($contributor) ? $contributor->civility : null) === 'mrs' ? 'selected' : '' }}>@lang('gingerminds-core::translation.users.form.mrs')</option>
                    </x-gingerminds-core::form.inputs.select>
                </div>
                <div class="col-md-4">
                    <x-gingerminds-core::form.inputs.basic
                      id="lastname"
                      :label="__('gingerminds-core::translation.form.lastname')"
                      :value="old('lastname', isset($contributor) ? $contributor->lastname : null)"
                  />
                </div>
                <div class="col-md-4">
                    <x-gingerminds-core::form.inputs.basic
                      id="firstname"
                      :label="__('gingerminds-core::translation.form.firstname')"
                      :value="old('firstname', isset($contributor) ? $contributor->firstname : null)"
                  />
                </div>
                <div class="col-md-2">
                    <x-gingerminds-core::form.inputs.basic
                     id="trigram"
                     :label="__('gingerminds-core::translation.contributors.form.trigram')"
                     :value="old('trigram', isset($contributor) ? $contributor->trigram : null)"
                 />
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-light d-flex align-items-center py-3">
            <h5 class="card-title mb-0">
                <i class="bi bi-person-check me-1 text-primary"></i>
                {{ __('gingerminds-core::translation.contributors.associated_user') }}
            </h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <x-gingerminds-core::form.inputs.select
                        id="user_id"
                        :label="__('gingerminds-core::translation.contributors.associated_user')"
                        :required="false"
                        size="xl"
                    >
                        <option value="">@lang('gingerminds-core::translation.none')</option>
                        @foreach(($users ?? collect()) as $user)
                            <option
                                value="{{ $user->id }}" {{ (string) old('user_id', isset($contributor) ? $contributor->user_id : '') === (string) $user->id ? 'selected' : '' }}>
                                {{ $user->email }}
                            </option>
                        @endforeach
                    </x-gingerminds-core::form.inputs.select>
                </div>
            </div>
        </div>
    </div>
</div>

