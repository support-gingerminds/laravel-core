
@forelse($items as $user)
    <tr>
        <td class="text-center text-muted small">{{ $loop->iteration + ($items instanceof \Illuminate\Pagination\LengthAwarePaginator ? ($items->currentPage() - 1) * $items->perPage() : 0) }}</td>
        <td>
            <div class="d-flex align-items-center">
                <div class="flex-grow-1">
                    <div class="fs-13 mb-0">
                        <a href="{{ route('gingerminds-core.users.edit', $user->id) }}" class="text-body fw-medium">
                            {{ $user->email }}
                        </a>
                    </div>
                </div>
            </div>
        </td>
        <td>
            @if($user->roles && $user->roles->count())
                <div class="d-flex flex-wrap gap-1">
                    @foreach($user->roles as $role)
                        <span class="badge bg-primary-subtle text-primary fs-11">{{ $role->name }}</span>
                    @endforeach
                </div>
            @else
                <span class="text-muted small fs-12">@lang('gingerminds-core::translation.none')</span>
            @endif
        </td>
        <td>
            @if($user->contributor)
                <div class="fw-medium fs-13">{{ $user->contributor->firstname }} {{ $user->contributor->lastname }}</div>
                @if($user->contributor->trigram)
                    <div class="text-muted small fs-12">{{ $user->contributor->trigram }}</div>
                @endif
            @else
                <span class="text-muted small fs-12">@lang('gingerminds-core::translation.none')</span>
            @endif
        </td>
        <td class="text-end">
            <div class="btn-group" role="group">
                <a href="{{ route('gingerminds-core.users.edit', $user->id) }}"
                   class="btn btn-sm btn-outline-primary fs-12"
                   data-bs-toggle="tooltip"
                   title="@lang('gingerminds-core::translation.action.edit')"
                >
                    <i class="bi bi-pencil-square"></i>
                </a>
                <button type="button"
                        class="btn btn-sm btn-outline-danger js-remove-item fs-12"
                        data-bs-toggle="modal"
                        data-bs-target="#removeModal"
                        data-gender="m"
                        data-model="@lang('gingerminds-core::translation.users.name_s')"
                        data-remove-name="{{ $user->email }}"
                        data-remove-id="{{ $user->id }}"
                        data-destroy-url="{{ route('gingerminds-core.users.destroy', $user->id) }}"
                        title="@lang('gingerminds-core::translation.action.delete')"
                >
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="5" class="text-center py-5">
            <div class="avatar-md mx-auto mb-4">
                <div class="avatar-title bg-light text-primary rounded-circle display-4">
                    <i class="bi bi-person-search"></i>
                </div>
            </div>
            <div class="fs-14 fw-semibold mt-2">@lang('gingerminds-core::translation.users.message.no_users')</div>
            <p class="text-muted mb-4">@lang('gingerminds-core::translation.message.no_result')</p>
            <a href="{{ route('gingerminds-core.users.create') }}" class="btn btn-success">
                <i class="bi bi-plus-lg me-1"></i>
                @lang('gingerminds-core::translation.title_m_create', ['model' => __('gingerminds-core::translation.users.name_s')])
            </a>
        </td>
    </tr>
@endforelse
