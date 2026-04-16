<div class="col-md-2">
    <div class="form-group">
        <label for="filter-{{ $property }}-from">{{ __($options['label']) . ' | ' . __('gingerminds-core::translation.number_from') }}</label>
        <input type="number" min="0" step="0.1" placeholder="0.00" class="form-control" id="filter-{{ $property }}-from" name="filters[{{ $property }}][from]" value="{{ array_key_exists($property, $filters) && array_key_exists('from', $filters[$property]) ? $filters[$property]['from'] : null }}">
    </div>
</div>
<div class="col-md-2">
    <div class="form-group">
        <label for="filter-{{ $property }}-to">{{ __($options['label']) . ' | ' . __('gingerminds-core::translation.number_to') }}</label>
        <input type="number" min="0" step="0.1" placeholder="0.00" class="form-control" id="filter-{{ $property }}-to" name="filters[{{ $property }}][to]" value="{{ array_key_exists($property, $filters) && array_key_exists('to', $filters[$property]) ? $filters[$property]['to'] : null }}">
    </div>
</div>
