

<ul class="list-unstyled ps-0">
    <li class="mb-1">
        <a href="{{ route('dashboard') }}" class="btn btn-single rounded border-0 d-inline-flex align-items-center">
            <i class="bi bi-speedometer2 me-2"></i>
            @lang('gingerminds-core::translation.dashboard')
        </a>
    </li>
    <li class="mb-1">
        <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0" data-bs-toggle="collapse"
                data-bs-target="#settings-collapse" aria-expanded="true">
            <i class="bi bi-gear me-2"></i>
            @lang('gingerminds-core::translation.settings')
        </button>
        <div class="collapse show" id="settings-collapse">
            <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                <li>
                    <a href="{{ route('gingerminds-core.users.index') }}" class="d-inline-flex text-decoration-none rounded align-items-center">
                        <i class="bi bi-people me-2"></i>
                        @lang('gingerminds-core::translation.users.name_p')
                    </a>
                </li>
                <li>
                    <a href="{{ route('gingerminds-core.contributors.index') }}" class="d-inline-flex text-decoration-none rounded align-items-center">
                        <i class="bi bi-person-badge me-2"></i>
                        @lang('gingerminds-core::translation.contributors.name_p')
                    </a>
                </li>
                <li>
                    <a href="{{ route('gingerminds-core.roles.index') }}" class="d-inline-flex text-decoration-none rounded align-items-center">
                        <i class="bi bi-shield-check me-2"></i>
                        @lang('gingerminds-core::translation.roles.name_p')
                    </a>
                </li>
                <li>
                    <a href="{{ route('gingerminds-core.permissions.index') }}" class="d-inline-flex text-decoration-none rounded align-items-center">
                        <i class="bi bi-key me-2"></i>
                        @lang('gingerminds-core::translation.permissions.name_p')
                    </a>
                </li>
            </ul>
        </div>
    </li>
</ul>
