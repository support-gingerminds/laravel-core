<div class="col-lg-8">
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light d-flex align-items-center py-3">
            <h5 class="card-title mb-0">
                <i class="bi bi-person-gear me-1 text-primary"></i>
                {{ __('gingerminds-core::translation.profile.settings') }}
            </h5>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <x-gingerminds-core::form.inputs.basic
                        id="email"
                        type="email"
                        :label="__('gingerminds-core::translation.form.email')"
                        :value="old('email', $user->email)"
                        placeholder="example@domain.com"
                        required
                />
            </div>
            <div class="row">
                <x-gingerminds-core::form.inputs.basic
                        id="password"
                        type="password"
                        :label="__('gingerminds-core::translation.users.form.password')"
                        :helper="__('gingerminds-core::translation.form.helpers.no_change_if_kept_empty')"
                        :required="false"
                />
                <x-gingerminds-core::form.inputs.basic
                        id="password_confirmation"
                        type="password"
                        :label="__('gingerminds-core::translation.users.form.password_confirmation')"
                        :required="false"
                />
            </div>
        </div>
    </div>
</div>

<div class="col-lg-4">
    <div class="card shadow-sm">
        <div class="card-header bg-light d-flex align-items-center py-3">
            <h5 class="card-title mb-0">
                <i class="bi bi-person-badge me-1 text-primary"></i>
                {{ __('gingerminds-core::translation.users.form.contributor') }}
            </h5>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <x-gingerminds-core::form.inputs.select
                        id="contributor_civility"
                        :label="__('gingerminds-core::translation.users.form.contributor_civility')"
                        :required="false"
                >
                    <option value="">—</option>
                    <option value="mr" {{ old('contributor_civility', optional($user->contributor)->civility) === 'mr' ? 'selected' : '' }}>@lang('gingerminds-core::translation.users.form.mr')</option>
                    <option value="mrs" {{ old('contributor_civility', optional($user->contributor)->civility) === 'mrs' ? 'selected' : '' }}>@lang('gingerminds-core::translation.users.form.mrs')</option>
                </x-gingerminds-core::form.inputs.select>
            </div>
            <div class="row mb-3">
                <x-gingerminds-core::form.inputs.basic
                        id="contributor_lastname"
                        :label="__('gingerminds-core::translation.users.form.contributor_lastname')"
                        :value="old('contributor_lastname', optional($user->contributor)->lastname)"
                        :required="false"
                />
                <x-gingerminds-core::form.inputs.basic
                        id="contributor_firstname"
                        :label="__('gingerminds-core::translation.users.form.contributor_firstname')"
                        :value="old('contributor_firstname', optional($user->contributor)->firstname)"
                        :required="false"
                />
            </div>
            <div class="row">
                <x-gingerminds-core::form.inputs.basic
                        id="contributor_trigram"
                        :label="__('gingerminds-core::translation.users.form.contributor_trigram')"
                        :value="old('contributor_trigram', optional($user->contributor)->trigram)"
                        :required="false"
                />
            </div>
        </div>
    </div>
</div>
