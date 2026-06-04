@php use Illuminate\Pagination\LengthAwarePaginator; @endphp

@forelse($items as $contributor)
    <tr>
        <td class="text-center text-muted small">{{ $loop->iteration + ($items instanceof LengthAwarePaginator ? ($items->currentPage() - 1) * $items->perPage() : 0) }}</td>
        <td>
            <div class="d-flex align-items-center">
                <div class="flex-grow-1">
                    <div class="fs-13 mb-0">
                        <a href="{{ route('gingerminds-core.contributors.edit', $contributor->id) }}"
                           class="text-body fw-medium">
                            {{ $contributor->firstname }} {{ $contributor->lastname }}
                        </a>
                    </div>
                    @if($contributor->trigram)
                        <span class="text-muted small fs-12">{{ $contributor->trigram }}</span>
                    @endif
                </div>
            </div>
        </td>
        <td>
            @if($contributor->user)
                <div class="d-flex align-items-center">
                    <i class="bi bi-person-check me-1 text-success"></i>
                    <span class="text-muted">{{ $contributor->user->email }}</span>
                </div>
            @else
                <span class="badge bg-warning-subtle text-warning fs-11">@lang('gingerminds-core::translation.none')</span>
            @endif
        </td>
        <td class="text-end">
            <div class="btn-group" role="group">
                <a href="{{ route('gingerminds-core.contributors.edit', $contributor->id) }}"
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
                        data-model="@lang('gingerminds-core::translation.contributors.name_s')"
                        data-remove-name="{{ $contributor->firstname.' '.$contributor->lastname }}"
                        data-remove-id="{{ $contributor->id }}"
                        data-destroy-url="{{ route('gingerminds-core.contributors.destroy', $contributor->id) }}"
                        title="@lang('gingerminds-core::translation.action.delete')"
                >
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="4" class="text-center py-5">
            <div class="avatar-md mx-auto mb-4">
                <div class="avatar-title bg-light text-primary rounded-circle display-4">
                    <i class="bi bi-people"></i>
                </div>
            </div>
            <div class="fs-14 fw-semibold mt-2">@lang('gingerminds-core::translation.message.no_contributors')</div>
            <p class="text-muted mb-4">@lang('gingerminds-core::translation.message.no_result')</p>
            <a href="{{ route('gingerminds-core.contributors.create') }}" class="btn btn-success">
                <i class="bi bi-plus-lg me-1"></i>
                @lang('gingerminds-core::translation.title_m_create', ['model' => __('gingerminds-core::translation.contributors.name_s')])
            </a>
        </td>
    </tr>
@endforelse
