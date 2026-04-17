<div class="col-lg-8">
    <div class="card">
        <div class="card-body">
            <div class="row mb-3">
                <x-gingerminds-core::form.inputs.select
                    id="civility"
                    :label="__('gingerminds-core::translation.contributors.form.civility')"
                    :required="false"
                    size="sm"
                >
                    <option></option>
                    <option
                        value="mr" {{ old('civility', isset($contributor) ? $contributor->civility : null) === 'mr' ? 'selected' : '' }}>@lang('gingerminds-core::translation.users.form.mr')</option>
                    <option
                        value="mrs" {{ old('civility', isset($contributor) ? $contributor->civility : null) === 'mrs' ? 'selected' : '' }}>@lang('gingerminds-core::translation.users.form.mrs')</option>
                </x-gingerminds-core::form.inputs.select>
                <x-gingerminds-core::form.inputs.basic
                  id="lastname"
                  :label="__('gingerminds-core::translation.form.lastname')"
                  :value="old('lastname', isset($contributor) ? $contributor->lastname : null)"
                  size="sm"
              />
                <x-gingerminds-core::form.inputs.basic
                  id="firstname"
                  :label="__('gingerminds-core::translation.form.firstname')"
                  :value="old('firstname', isset($contributor) ? $contributor->firstname : null)"
                  size="sm"
              />
            </div>
            <div class="row mb-3">
                <x-gingerminds-core::form.inputs.basic
                 id="trigram"
                 :label="__('gingerminds-core::translation.contributors.form.trigram')"
                 :value="old('trigram', isset($contributor) ? $contributor->trigram : null)"
                 size="sm"
             />
            </div>
        </div>
    </div>
</div>

<div class="col-lg-4">
    <div class="card">
        <div class="card-body">
            <div class="row mb-3">
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

