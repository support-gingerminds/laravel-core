
@forelse($items as $contributor)
    <tr>
        <td></td>
        <td>
            <h5 class="text-truncate font-size-14 mb-0">
                <a href="{{ route('gingerminds-core.contributors.edit', $contributor->id) }}" class="dropdown-item js-edit-item">
                    {{ $contributor->firstname }} {{ $contributor->lastname }}
                    @if($contributor->trigram)
                        ({{ $contributor->trigram }})
                    @endif
                </a>
            </h5>
        </td>
        <td>
            @if($contributor->user)
                <span class="badge bg-success p-1"><i class="bx bxs-contact"></i> {{ $contributor->user?->email }}</span>
            @else
                <span class="badge bg-warning p-1"><i class="bx bx-x"></i>@lang('gingerminds-core::translation.users.message.no_users')</span>
            @endif
        </td>
        <td class="text-end">
            <div class="btn-group" role="group">
                <a href="{{ route('gingerminds-core.contributors.edit', $contributor->id) }}"
                   class="btn btn-sm btn-outline-primary js-edit-item"
                >
                    <i class="bx bx-edit"></i>
                </a>
                <button type="button"
                        class="btn btn-outline-danger btn-sm js-remove-item"
                        data-bs-toggle="modal"
                        data-bs-target="#removeModal"
                        data-gender="m"
                        data-model="contributeur"
                        data-remove-name="{{ $contributor->firstname.' '.$contributor->lastname }}"
                        data-remove-id="{{ $contributor->id }}"
                        data-destroy-url="{{ route('gingerminds-core.contributors.destroy', $contributor->id) }}"
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
                <p>@lang('gingerminds-core::translation.message.no_contributors')</p>

                <a href="{{ route('gingerminds-core.contributors.create') }}"
                   class="btn btn-primary waves-effect waves-light mb-2"
                >
                    <i class="mdi mdi-plus"></i>
                    @lang('gingerminds-core::translation.title_m_create', ['model' => __('gingerminds-core::translation.contributors.name_s')])
                </a>
            </div>
        </td>
    </tr>
@endforelse
