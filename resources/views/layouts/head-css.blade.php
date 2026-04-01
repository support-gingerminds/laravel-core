<link href="{{ URL::asset('build/libs/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css"/>

@yield('css')

@vite([
    'vendor/gingerminds/laravel-core/resources/scss/bootstrap.scss',
    'vendor/gingerminds/laravel-core/resources/scss/icons.scss',
    'vendor/gingerminds/laravel-core/resources/scss/app.scss',
    'vendor/gingerminds/laravel-core/resources/js/plugin.js'
])
