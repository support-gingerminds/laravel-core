@forelse($items as $role)
    <tr>
        <td class="text-center text-muted small">{{ $loop->iteration + ($items instanceof \Illuminate\Pagination\LengthAwarePaginator ? ($items->currentPage() - 1) * $items->perPage() : 0) }}</td>
        <td>
            <div class="d-flex align-items-center">
                <div class="flex-grow-1">
                    <div class="fs-13 mb-0">
                        <a href="{{ route('gingerminds-core.roles.edit', $role->id) }}" class="text-body fw-medium">
                            {{ $role->name }}
                        </a>
                    </div>
                </div>
            </div>
        </td>
        <td class="text-center">
            <span class="badge bg-light text-body border fs-11">{{ $role->permissions_count ?? $role->permissions()->count() }}</span>
        </td>
        <td class="text-end">
            <div class="d-flex justify-content-end gap-2">
                <a class="btn btn-sm btn-primary fs-12" 
                   href="{{ route('gingerminds-core.roles.edit', $role->id) }}"
                   data-bs-toggle="tooltip"
                   title="@lang('gingerminds-core::translation.action.edit')"
                >
                    <i class="bi bi-pencil-square"></i> Edit
                </a>
                <button type="button"
                    class="btn btn-sm btn-danger js-remove-item fs-12" 
                    data-bs-toggle="modal"
                    data-bs-target="#removeModal"
                    data-model="@lang('gingerminds-core::translation.roles.name_s')"
                    data-remove-name="{{ $role->name }}"
                    data-remove-id="{{ $role->id }}"
                    data-destroy-url="{{ route('gingerminds-core.roles.destroy', $role->id) }}"
                    title="@lang('gingerminds-core::translation.action.delete')"
                >
                    <i class="bi bi-trash"></i> Delete
                </button>
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="4" class="text-center py-5">
            <div class="avatar-md mx-auto mb-4">
                <div class="avatar-title bg-light text-primary rounded-circle display-4">
                    <i class="bi bi-shield-check"></i>
                </div>
            </div>
            <div class="fs-14 fw-semibold mt-2">@lang('gingerminds-core::translation.roles.message.no_roles')</div>
            <p class="text-muted mb-4">@lang('gingerminds-core::translation.message.no_result')</p>
            <a href="{{ route('gingerminds-core.roles.create') }}" class="btn btn-success">
                <i class="bi bi-plus-lg me-1"></i>
                @lang('gingerminds-core::translation.title_m_create', ['model' => __('gingerminds-core::translation.roles.name_s')])
            </a>
        </td>
    </tr>
@endforelse
