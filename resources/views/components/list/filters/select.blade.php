@php
    $isMultiple = array_key_exists('multiple', $options) && $options['multiple'];
@endphp
<div class="col-md-4">
    @if('select-state' === $options['type'])
        <label for="filter-{{ $property }}" class="form-label">@lang($options['label'])</label>
        <select id="filter-{{ $property }}" class="form-select select2"
                name="filters[{{ $property }}]{{ $isMultiple ? '[]' : '' }}" @if($isMultiple) multiple @endif>
            @if(!$isMultiple)
                <option value="all">@lang('gingerminds-core::translation.all')</option>
            @endif
            @foreach($options['choices'] as $key => $label)
                @php
                    if ($isMultiple) {
                        $isSelected = array_key_exists($property, $filters) && in_array($key, $filters[$property]);
                    } else {
                        $isSelected = array_key_exists($property, $filters) && $filters[$property] === $key;
                    }
                @endphp
                <option value="{{ $key }}"
                    {{ $isSelected ? 'selected' : '' }}>@lang($label)</option>
            @endforeach
        </select>
    @endif
</div>
