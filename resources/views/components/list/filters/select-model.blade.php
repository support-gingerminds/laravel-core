@php
    $value = array_key_exists($property, $filters) ? $filters[$property] : null;
@endphp
<div class="col-md-4">
    <livewire:component.list.filter.select-model
        :property="$property"
        :options="$options"
        :value="$value"
    />
</div>
