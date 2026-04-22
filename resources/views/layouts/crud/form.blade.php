@extends('gingerminds-core::layouts.master')

@props([
    'action' => '#',
    'indexRoute' => '',
    'method' => 'POST',
    'id' => 'form',
    'title' => '',
    'subtitle' => ''
])

@section('content')
    @hasSection('subheader')
        <div class="mb-3">
            @yield('subheader')
        </div>
    @endif
    @hasSection('actions')
        <ul class="nav nav-pills justify-content-end mb-3">
            @yield('actions')
        </ul>
    @endif
    @php
        $htmlMethod = (isset($method) && strtoupper($method) === 'GET') ? 'GET' : 'POST';
    @endphp
    <form method="{{ $htmlMethod }}" action="{{ $action }}" id="{{ $id }}" autocomplete="off" class="crud-form needs-validation" enctype="multipart/form-data" novalidate>
        @csrf

        @if(isset($method) && in_array(strtoupper($method), ['PUT', 'PATCH', 'DELETE']))
            @method($method)
        @endif

        <div class="row">
            @yield('form-nav')
            @yield('fields')

            <div class="col-lg-12">
                <div class="text-end">
                    <a href="{{ $indexRoute }}" class="btn btn-secondary me-2">@lang('gingerminds-core::translation.action.cancel')</a>
                    <button type="submit" class="btn btn-primary" @if(isset($isDisabled) && $isDisabled) disabled @endif>@lang('gingerminds-core::translation.action.save')</button>
                </div>
            </div>
        </div>
    </form>

    @hasSection('additional-infos')
        <section class="mt-4">
            @yield('additional-infos')
        </section>
    @endif
@endsection

