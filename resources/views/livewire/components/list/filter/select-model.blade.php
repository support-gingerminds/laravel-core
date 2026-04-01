<div wire:ignore class="livewire-select2-container">
    <label for="filter-{{ $property }}" class="form-label">@lang($options['label'])</label>
    <select id="filter-{{ $property }}"
            class="form-select select2"
            name="filters[{{ $property }}]{{ $isMultiple ? '[]' : '' }}"
            @if($isMultiple) multiple @endif
            data-property="{{ $property }}">
        @if(!$isMultiple)
            <option value="all">@lang('gingerminds-core::translation.all')</option>
            @foreach($allItems as $item)
                <option value="{{ $item->id }}" {{ (string)$value === (string)$item->id ? 'selected' : '' }}>
                    {{ $item->name ?? $item->label ?? $item->title ?? $item->id }}
                </option>
            @endforeach
        @else
            @foreach($selectedItems as $item)
                <option value="{{ $item->id }}" selected>
                    {{ $item->name ?? $item->label ?? $item->title ?? $item->id }}
                </option>
            @endforeach
        @endif
    </select>
</div>

@script
<script>
    const initSelect2 = () => {
        const $select = $('select[data-property="{{ $property }}"]');

        if ($select.hasClass("select2-hidden-accessible")) {
            $select.select2('destroy');
        }

        const isMultiple = {{ $isMultiple ? 'true' : 'false' }};

        const select2Options = {
            width: '100%',
            placeholder: '{{ __('translation.form.placeholder.select') }}',
            allowClear: isMultiple,
            dropdownParent: $select.parent(),
        };

        if (isMultiple) {
            select2Options.minimumInputLength = 0;
            select2Options.ajax = {
                delay: 250,
                data: (params) => ({ term: params.term || '', page: params.page || 1 }),
                transport: (params, success, failure) => {
                    $wire.search(params.data.term || '')
                        .then(results => success({ results }))
                        .catch(failure);
                }
            };
        } else {
            select2Options.minimumResultsForSearch = Infinity;
        }

        $select.select2(select2Options);
    };

    initSelect2();

    Livewire.hook('morph.updated', ({ component, el }) => {
        initSelect2();
    });
</script>
@endscript
