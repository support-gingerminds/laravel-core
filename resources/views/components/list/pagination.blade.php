@if ($items->lastPage() > 1)
    @php
        $current = $items->currentPage();
        $last = $items->lastPage();

        // Définir fenêtre de pages (2 avant, 2 après)
        $start = max($current - 2, 1);
        $end = min($current + 2, $last);
    @endphp

    <nav aria-label="pagination">
        <ul class="pagination justify-content-center">

            {{-- First --}}
            <li @class(['page-item', 'disabled' => $current === 1])>
                <a class="page-link" href="{{ $current === 1 ? '#' : url()->current() . '?' . http_build_query(array_merge(request()->query(), ['page' => 1])) }}">
                    <i class="mdi mdi-chevron-double-left"></i>
                </a>
            </li>

            {{-- Previous --}}
            <li @class(['page-item', 'disabled' => $items->onFirstPage()])>
                <a class="page-link" href="{{ $items->onFirstPage() ? '#' : url()->current() . '?' . http_build_query(array_merge(request()->query(), ['page' => $current - 1])) }}">
                    @lang('translation.previous')
                </a>
            </li>

            {{-- Pages --}}
            @for ($i = $start; $i <= $end; $i++)
                <li @class(['page-item', 'active' => $current === $i])>
                    <a class="page-link" href="{{ url()->current() . '?' . http_build_query(array_merge(request()->query(), ['page' => $i])) }}">
                        {{ $i }}
                    </a>
                </li>
            @endfor

            {{-- Next --}}
            <li @class(['page-item', 'disabled' => !$items->hasMorePages()])>
                <a class="page-link" href="{{ !$items->hasMorePages() ? '#' : url()->current() . '?' . http_build_query(array_merge(request()->query(), ['page' => $current + 1])) }}">
                    @lang('translation.next')
                </a>
            </li>

            {{-- Last --}}
            <li @class(['page-item', 'disabled' => $current === $last])>
                <a class="page-link" href="{{ $current === $last ? '#' : url()->current() . '?' . http_build_query(array_merge(request()->query(), ['page' => $last])) }}">
                    <i class="mdi mdi-chevron-double-right"></i>
                </a>
            </li>

        </ul>
    </nav>
@endif
