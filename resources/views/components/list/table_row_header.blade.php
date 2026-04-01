<th
    scope="col"
    class="@if($sortable) sortable @endif @if(isset($property) && $sortBy === $property) {{ $sortOrder }}@endif"
    @if(isset($property)) data-sort="{{$property}}" @endif>
    {{ $name }}@if(isset($property) && $sortBy === $property && in_array($sortOrder, ['desc', 'asc']))
        @if('desc' === $sortOrder)
            <i class="mdi mdi-chevron-down"></i>
        @elseif('asc' === $sortOrder)
            <i class="mdi mdi-chevron-up"></i>
        @endif
    @else
        @if($sortable)
            <i class="mdi mdi-unfold-more-horizontal"></i>
        @endif
    @endif
</th>
