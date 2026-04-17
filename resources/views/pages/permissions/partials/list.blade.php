@forelse($items as $permission)
    <tr>
        <td></td>
        <td class="fw-medium">{{ $permission->name }}</td>
        <td class="text-end">
            <div class="btn-group" role="group">
                <a class="btn btn-sm btn-outline-primary" href="{{ route('gingerminds-core.permissions.edit', $permission->id) }}">
                    <i class="bx bx-edit"></i>
                </a>
                <button type="button"
                        class="btn btn-outline-danger btn-sm js-remove-item"
                        data-bs-toggle="modal"
                        data-bs-target="#removeModal"
                        data-gender="f"
                        data-model="@lang('gingerminds-core::translation.permissions.name_s')"
                        data-remove-name="{{ $permission->name }}"
                        data-remove-id="{{ $permission->id }}"
                        data-destroy-url="{{ route('gingerminds-core.permissions.destroy', $permission->id) }}"
                >
                    <i class="bx bx-trash"></i>
                </button>
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="3" class="text-center text-muted py-4">
            <div class="py-4">
                <i class="bx bx-category font-size-24 mb-2"></i>
                <p>@lang('gingerminds-core::translation.premissions.message.no_permissions')</p>
            </div>
        </td>
    </tr>
@endforelse
