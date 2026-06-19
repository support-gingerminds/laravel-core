@extends('gingerminds-core::layouts.crud.form')

@section('form-nav')
    <nav>
        <div class="nav nav-tabs" role="tablist">
            @yield('tabs')
        </div>
    </nav>
@endsection

@section('fields')
    <div class="tab-content">
        @yield('tab-content')
    </div>
@endsection