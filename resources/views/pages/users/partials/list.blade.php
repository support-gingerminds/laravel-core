
@forelse($items as $user)
    <tr>
        <td></td>
        <td>
            <h5 class="text-truncate font-size-14 mb-0">
                <a href="{{ route('gingerminds-core.users.edit', $user->id) }}" class="dropdown-item js-edit-item">
                    {{ $user->email }}
                </a>
            </h5>
        </td>
        <td>
            @if($user->roles && $user->roles->count())
                <span class="badge bg-secondary">{{ $user->roles->pluck('name')->join(', ') }}</span>
            @else
                <span class="text-muted">-</span>
            @endif
        </td>
        <td>
            {{ $user->contributor?->firstname }} {{ $user->contributor?->lastname }}
            @if($user->contributor?->trigram)
                ({{ $user->contributor?->trigram }})
            @endif
        </td>
        <td class="text-end">
            <div class="btn-group" role="group">
                <a href="{{ route('gingerminds-core.users.edit', $user->id) }}"
                   class="btn btn-sm btn-outline-primary js-edit-item"
                >
                    <i class="bx bx-edit"></i>
                </a>
                <button type="button"
                        class="btn btn-outline-danger btn-sm js-remove-item"
                        data-bs-toggle="modal"
                        data-bs-target="#removeModal"
                        data-gender="m"
                        data-model="@lang('gingerminds-core::translation.users.name_s')"
                        data-remove-name="{{ $user->email }}"
                        data-remove-id="{{ $user->id }}"
                        data-destroy-url="{{ route('gingerminds-core.users.destroy', $user->id) }}"
                >
                    <i class="bx bx-trash"></i>
                </button>
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="5" class="text-center text-muted py-4">
            <div class="py-4">
                <i class="bx bx-user font-size-24 mb-2"></i>
                <p>@lang('gingerminds-core::translation.users.message.no_users')</p>

                <button type="button"
                        class="btn btn-primary waves-effect waves-light mb-2"
                        data-bs-toggle="modal"
                        data-bs-target="#formModal"
                        data-mode="create"
                        data-gender="m"
                        data-model="@lang('gingerminds-core::translation.users.name_s')"
                >
                    <i class="mdi mdi-plus"></i>
                    @lang('gingerminds-core::translation.title_m_create', ['model' => __('gingerminds-core::translation.users.name_s')])
                </button>
            </div>
        </td>
    </tr>
@endforelse
