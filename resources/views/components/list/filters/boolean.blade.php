<div class="col-md-2">
    <label for="filter-{{ $property }}" class="form-label">@lang($options['label'])</label>
    <select id="filter-{{ $property }}" class="form-select select2"
            name="filters[{{ $property }}]">
        <option value="all">@lang('gingerminds-core::translation.all')</option>
        <option value="yes"
            {{ array_key_exists($property, $filters) && $filters[$property] === 'yes' ? 'selected' : '' }}>@lang('gingerminds-core::translation.yes')</option>
        <option value="no"
            {{ array_key_exists($property, $filters) && $filters[$property] === 'no' ? 'selected' : '' }}>@lang('gingerminds-core::translation.no')</option>
    </select>
</div>
