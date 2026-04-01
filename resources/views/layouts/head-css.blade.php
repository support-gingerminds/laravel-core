<link href="{{ URL::asset('build/libs/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css"/>

@yield('css')

@vite([
    'resources/scss/bootstrap.scss',
    'resources/scss/icons.scss',
    'resources/scss/app.scss',
])
