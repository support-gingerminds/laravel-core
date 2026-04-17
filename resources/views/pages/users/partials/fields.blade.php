<div class="col-lg-8">
    <div class="card">
        <div class="card-body">
            <div class="row mb-3">
                <x-gingerminds-core::form.inputs.basic
                   id="email"
                   type="email"
                   :label="__('gingerminds-core::translation.form.email')"
                   :value="old('email', isset($user) ? $user->email : null)"
               />
            </div>

            <div class="row mb-3">
                <x-gingerminds-core::form.inputs.basic
                   id="password"
                   type="password"
                   :label="__('gingerminds-core::translation.users.form.password')"
                   :helper="__('gingerminds-core::translation.form.helpers.no_change_if_kept_empty')"
                   :required="!isset($user)"
               />
                <x-gingerminds-core::form.inputs.basic
                   id="password_confirmation"
                   type="password"
                   :label="__('gingerminds-core::translation.users.form.password_confirmation')"
                   :required="!isset($user)"
               />
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="row mb-3">
                <x-gingerminds-core::form.inputs.select
                    id="contributor_id"
                    :label="__('gingerminds-core::translation.users.form.contributor_associate')"
                    :required="false"
                    size="xl"
                >
                    <option value="">— @lang('gingerminds-core::translation.none') —</option>
                    <option value="__new__" {{ old('contributor_id') === '__new__' ? 'selected' : '' }}>— @lang('gingerminds-core::translation.users.form.contributor_new') —</option>
                    @php($currentContributorId = isset($user) ? optional($user->contributor)->id : null)
                    @foreach($contributors as $contributor)
                        @php($label = trim(($contributor->lastname ? strtoupper($contributor->lastname) : '').' '.($contributor->firstname ?? '')))
                        <option value="{{ $contributor->id }}"
                                data-firstname="{{ $contributor->firstname }}"
                                data-lastname="{{ $contributor->lastname }}"
                                data-trigram="{{ $contributor->trigram }}"
                                data-civility="{{ $contributor->civility }}""
                            {{ (string) old('contributor_id', $currentContributorId) === (string) $contributor->id ? 'selected' : '' }}>
                            {{ $label }} @if($contributor->trigram) ({{ $contributor->trigram }}) @endif
                        </option>
                    @endforeach
                </x-gingerminds-core::form.inputs.select>
            </div>

            <div id="contributor-fields" class="border rounded p-3 mb-3" style="display: none;">
                <small class="text-muted">@lang('gingerminds-core::translation.users.form.contributor_info')</small>
                <div class="row mb-3 mt-3">
                    <x-gingerminds-core::form.inputs.select
                        id="contributor_civility"
                        :label="__('gingerminds-core::translation.users.form.contributor_civility')"
                        size="sm"
                        :required="false"
                    >
                        <option value="">—</option>
                        <option value="mr" {{ old('contributor_civility', isset($user) ?  optional($user->contributor)->civility : null) === 'mr' ? 'selected' : '' }}>@lang('gingerminds-core::translation.users.form.mr')</option>
                        <option value="mrs" {{ old('contributor_civility', isset($user) ?  optional($user->contributor)->civility : null) === 'mrs' ? 'selected' : '' }}>@lang('gingerminds-core::translation.users.form.mrs')</option>
                    </x-gingerminds-core::form.inputs.select>
                    <x-gingerminds-core::form.inputs.basic
                       id="contributor_lastname"
                       :label="__('gingerminds-core::translation.users.form.contributor_lastname')"
                       :value="old('contributor_lastname', isset($user) ? optional($user->contributor)->lastname : null)"
                       :required="false"
                       size="sm"
                   />
                    <x-gingerminds-core::form.inputs.basic
                       id="contributor_firstname"
                       :label="__('gingerminds-core::translation.users.form.contributor_firstname')"
                       :value="old('contributor_firstname', isset($user) ? optional($user->contributor)->firstname : null)"
                       :required="false"
                       size="sm"
                   />
                </div>
                <div class="row mb-3">
                    <x-gingerminds-core::form.inputs.basic
                       id="contributor_trigram"
                       :label="__('gingerminds-core::translation.users.form.contributor_trigram')"
                       :value="old('contributor_trigram', isset($user) ? optional($user->contributor)->trigram : null)"
                       :required="false"
                       size="sm"
                   />
                </div>
            </div>
        </div>
    </div>
</div>

<div class="col-lg-4">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <x-gingerminds-core::form.inputs.select
                    id="roles[]"
                    :label="__('gingerminds-core::translation.users.form.roles')"
                    size="xl"
                    :required="false"
                    :multiple="true"
                    :search="true"
                >
                    @php($userRoleNames = isset($user) ? $user->roles->pluck('name')->all() : [])
                    @foreach($roles as $role)
                        <option value="{{ $role->name }}" {{ in_array($role->name, old('roles', $userRoleNames)) ? 'selected' : '' }}>
                            {{ $role->name }}
                        </option>
                    @endforeach
                </x-gingerminds-core::form.inputs.select>
            </div>
        </div>
    </div>
</div>
