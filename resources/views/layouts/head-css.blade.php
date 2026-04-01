<link href="{{ URL::asset('build/libs/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css"/>

@yield('css')

@vite([
    'resources/scss/vendor/gingerminds-core/bootstrap.scss',
    'resources/scss/vendor/gingerminds-core/icons.scss',
    'resources/scss/vendor/gingerminds-core/app.scss',
    'resources/js/vendor/gingerminds-core/plugin.js'
])
