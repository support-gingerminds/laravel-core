<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between mb-4">
            <h4 class="mb-sm-0 fs-18">{{ $current ?? $title }}</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    @foreach ($items as $item)
                        <li class="breadcrumb-item {{ $item['active'] ?? false ? 'active' : '' }}">
                            @if(!empty($item['url']) && !($item['active'] ?? false))
                                <a href="{{ $item['url'] }}">{{ $item['label'] }}</a>
                            @else
                                {{ $item['label'] }}
                            @endif
                        </li>
                    @endforeach
                </ol>
            </div>

        </div>
    </div>
</div>
