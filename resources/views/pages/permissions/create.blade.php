@extends('gingerminds-core::layouts.crud.form')

@section('title')
    @lang('gingerminds-core::translation.title_f_create', ['model' => __('gingerminds-core::translation.permissions.name_s')])
@endsection

@section('breadcrumb')
    @component('gingerminds-core::components.navigation.breadcrumb')
        @slot('li_1_link')
            {{ route('gingerminds-core.permissions.index') }}
        @endslot
        @slot('li_1')
            @lang('gingerminds-core::translation.permissions.name_p')
        @endslot
        @slot('title')
            @lang('gingerminds-core::translation.title_f_create', ['model' => __('gingerminds-core::translation.permissions.name_s')])
        @endslot
    @endcomponent
@endsection

@php
    $action = route('gingerminds-core.permissions.store');
    $indexRoute = route('gingerminds-core.permissions.index');
    $method = 'POST';
    $id = 'create-permissions-form';
    $title = __('gingerminds-core::translation.title_f_create', ['model' => __('gingerminds-core::translation.permissions.name_s')]);
    $subtitle = null;
@endphp

@section('fields')
    @include('gingerminds-core::pages.permissions.partials.fields')
@endsection
