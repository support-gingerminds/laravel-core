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
                    {{ $this->getDisplayValue($item) }}
                </option>
            @endforeach
        @else
            @foreach($allItems as $item)
                <option value="{{ $item->id }}"
                    {{ in_array((string)$item->id, array_map('strval', (array)$value)) ? 'selected' : '' }}>
                    {{ $this->getDisplayValue($item) }}
                </option>
            @endforeach
        @endif
    </select>
</div>
