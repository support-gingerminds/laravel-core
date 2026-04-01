@foreach($filtersConfigs as $key => $options)
    @if(!array_key_exists('disabled_for_back', $options) || !$options['disabled_for_back'])
        @if('number' === $options['type'])
            @include('gingerminds-core::components.list.filters.number', ['property' => $key, 'options' => $options, 'filters' => $filters])
        @elseif('date' === $options['type'])
            @include('gingerminds-core::components.list.filters.date', ['property' => $key, 'options' => $options, 'filters' => $filters])
        @elseif(in_array($options['type'], ['select', 'select-state']))
            @include('gingerminds-core::components.list.filters.select', ['property' => $key, 'options' => $options, 'filters' => $filters])
        @elseif('select-model' === $options['type'])
            @include('gingerminds-core::components.list.filters.select-model', ['property' => $key, 'options' => $options, 'filters' => $filters])
        @elseif('boolean' === $options['type'])
            @include('gingerminds-core::components.list.filters.boolean', ['property' => $key, 'options' => $options, 'filters' => $filters])
        @endif
    @endif
@endforeach
