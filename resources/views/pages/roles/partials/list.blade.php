@forelse($items as $role)
    <tr>
        <td></td>
        <td class="fw-medium">{{ $role->name }}
            @if($role->is_external)<span class="badge badge-soft-info">@lang('gingerminds-core::translation.external')</span>@endif
            @if($role->is_default)<span class="badge badge-soft-success">@lang('gingerminds-core::translation.default')</span>@endif
        </td>
        <td class="text-center">{{ $role->permissions_count ?? $role->permissions()->count() }}</td>
        <td class="text-end">
            <div class="btn-group" role="group">
                <a class="btn btn-sm btn-outline-primary" href="{{ route('gingerminds-core.roles.edit', $role->id) }}">
                    <i class="bx bx-edit"></i>
                </a>
                <button type="button"
                    class="btn btn-outline-danger btn-sm js-remove-item"
                    data-bs-toggle="modal"
                    data-bs-target="#removeModal"
                    data-model="@lang('gingerminds-core::translation.roles.name_s')"
                    data-remove-name="{{ $role->name }}"
                    data-remove-id="{{ $role->id }}"
                    data-destroy-url="{{ route('gingerminds-core.roles.destroy', $role->id) }}"
                >
                    <i class="bx bx-trash"></i>
                </button>
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="4" class="text-center text-muted py-4">
            <div class="py-4">
                <i class="bx bx-category font-size-24 mb-2"></i>
                <p>@lang('gingerminds-core::translation.roles.message.no_roles')</p>
            </div>
        </td>
    </tr>
@endforelse
