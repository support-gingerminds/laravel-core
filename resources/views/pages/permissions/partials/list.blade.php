@forelse($items as $permission)
    <tr>
        <td class="text-center text-muted small">{{ $loop->iteration + ($items instanceof \Illuminate\Pagination\LengthAwarePaginator ? ($items->currentPage() - 1) * $items->perPage() : 0) }}</td>
        <td>
            <div class="d-flex align-items-center">
                <i class="bi bi-key me-2 text-primary fs-13"></i>
                <span class="fw-medium fs-13">{{ $permission->name }}</span>
            </div>
        </td>
        <td class="text-end">
            <div class="d-flex justify-content-end gap-2">
                <a class="btn btn-sm btn-primary fs-12"
                   href="{{ route('gingerminds-core.permissions.edit', $permission->id) }}"
                   data-bs-toggle="tooltip"
                   title="@lang('gingerminds-core::translation.action.edit')"
                >
                    <i class="bi bi-pencil-square"></i> Edit
                </a>
                <button type="button"
                        class="btn btn-sm btn-danger js-remove-item fs-12"
                        data-bs-toggle="modal"
                        data-bs-target="#removeModal"
                        data-gender="f"
                        data-model="@lang('gingerminds-core::translation.permissions.name_s')"
                        data-remove-name="{{ $permission->name }}"
                        data-remove-id="{{ $permission->id }}"
                        data-destroy-url="{{ route('gingerminds-core.permissions.destroy', $permission->id) }}"
                        title="@lang('gingerminds-core::translation.action.delete')"
                >
                    <i class="bi bi-trash"></i> Delete
                </button>
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="3" class="text-center py-5">
            <div class="avatar-md mx-auto mb-4">
                <div class="avatar-title bg-light text-primary rounded-circle display-4">
                    <i class="bi bi-lock"></i>
                </div>
            </div>
            <div class="fs-14 fw-semibold mt-2">@lang('gingerminds-core::translation.premissions.message.no_permissions')</div>
            <p class="text-muted mb-4">@lang('gingerminds-core::translation.message.no_result')</p>
            <a href="{{ route('gingerminds-core.permissions.create') }}" class="btn btn-success">
                <i class="bi bi-plus-lg me-1"></i>
                @lang('gingerminds-core::translation.title_f_create', ['model' => __('gingerminds-core::translation.permissions.name_s')])
            </a>
        </td>
    </tr>
@endforelse
