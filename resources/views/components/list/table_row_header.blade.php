<th
    scope="col"
    class="@if($sortable) sortable @endif @if(isset($align) && 'center' === $align) text-center @endif @if(isset($property) && $sortBy === $property) {{ $sortOrder }}@endif"
    @if(isset($property)) data-sort="{{$property}}" @endif>
    {{ $name }}@if(isset($property) && $sortBy === $property && in_array($sortOrder, ['desc', 'asc']))
        @if('desc' === $sortOrder)
            <i class="bi bi-chevron-down ms-1"></i>
        @elseif('asc' === $sortOrder)
            <i class="bi bi-chevron-up ms-1"></i>
        @endif
    @else
        @if($sortable)
            <i class="bi bi-arrow-down-up ms-1 opacity-50 fs-11"></i>
        @endif
    @endif
</th>
