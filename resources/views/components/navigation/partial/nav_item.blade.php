@props([
    'route' => null,
    'icon' => null,
    'label' => null,
    'name' => null,
    'items' => null,
    'id' => null,
    'expanded' => false,
    'permission' => null,
])

@php
    $label = $label ?? $name;

    // Filter items based on permissions
    $filteredItems = [];
    if ($items) {
        foreach ($items as $item) {
            if (!isset($item['permission']) || auth()->user()?->can($item['permission'])) {
                $filteredItems[] = $item;
            }
        }
    }

    // Check if current item or any sub-item is active
    $isActive = false;
    
    // Function to check if a URL matches current request (including patterns)
    $isUrlActive = function($url) {
        if (!$url) return false;
        $path = parse_url($url, PHP_URL_PATH);
        if (!$path) return false;
        // Trim leading slash for request()->is()
        $pattern = ltrim($path, '/') . '*';
        return request()->is($pattern);
    };

    if ($route && $isUrlActive($route)) {
        $isActive = true;
    } elseif ($filteredItems) {
        foreach ($filteredItems as $item) {
            if (isset($item['route']) && $isUrlActive($item['route'])) {
                $isActive = true;
                $expanded = true; // Auto-expand if a sub-item is active
                break;
            }
        }
    }

    // Determine if the main item should be displayed
    $shouldDisplay = (!$permission || auth()->user()?->can($permission));
    if ($items && empty($filteredItems)) {
        $shouldDisplay = false;
    }
@endphp

@if($shouldDisplay)
    <li class="mb-1">
        @if($items)
            <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 {{ $expanded ? '' : 'collapsed' }} {{ $isActive ? 'active' : '' }}" data-bs-toggle="collapse"
                    data-bs-target="#{{ $id }}" aria-expanded="{{ $expanded ? 'true' : 'false' }}">
                <i class="{{ $icon }} me-2"></i>
                {{ $label }}
            </button>
            <div class="collapse {{ $expanded ? 'show' : '' }}" id="{{ $id }}">
                <ul class="btn-toggle-nav list-unstyled fw-normal pb-1">
                    @foreach($filteredItems as $item)
                        <li>
                            <a href="{{ $item['route'] }}" class="d-inline-flex text-decoration-none rounded align-items-center {{ $isUrlActive($item['route']) ? 'active' : '' }}">
                                @if(isset($item['icon']))
                                    <i class="{{ $item['icon'] }} me-2"></i>
                                @endif
                                {{ $item['label'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        @else
            <a href="{{ $route }}" class="btn btn-single rounded border-0 d-inline-flex align-items-center {{ $isActive ? 'active' : '' }}">
                <i class="{{ $icon }} me-2"></i>
                {{ $label }}
            </a>
        @endif
    </li>
@endif