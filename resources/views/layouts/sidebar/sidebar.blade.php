<div class="sidebar d-flex flex-column flex-shrink-0 p-3 text-bg-dark">
    @include('gingerminds-core::layouts.header.partials.logo')
    <hr>
    @include('gingerminds-core::layouts.sidebar.partials.dashboard_menu_items')
    <hr>
    <div class="dropdown">
        <a href="#"
           class="d-flex align-items-center text-white text-decoration-none dropdown-toggle"
           data-bs-toggle="dropdown" aria-expanded="false">
            <img src="https://github.com/mdo.png"
                 alt="" width="32" height="32"
                 class="rounded-circle me-2">
            <strong>mdo</strong>
        </a>
        <ul class="dropdown-menu dropdown-menu-dark text-small shadow">
            <li><a class="dropdown-item" href="#">@lang('gingerminds-core::translation.profile.settings')</a></li>
            <li>
                <hr class="dropdown-divider">
            </li>
            <li><a class="dropdown-item" href="#">@lang('gingerminds-core::translation.action.sign_out')</a></li>
        </ul>
    </div>
</div>
