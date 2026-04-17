@extends('gingerminds-core::layouts.crud.form')

@section('title')
    @lang('gingerminds-core::translation.title_m_create', ['model' => __('gingerminds-core::translation.roles.name_s')])
@endsection

@section('breadcrumb')
    @component('gingerminds-core::components.navigation.breadcrumb')
        @slot('li_1_link')
            {{ route('gingerminds-core.roles.index') }}
        @endslot
        @slot('li_1')
            @lang('gingerminds-core::translation.roles.name_p')
        @endslot
        @slot('title')
            @lang('gingerminds-core::translation.title_m_create', ['model' => __('gingerminds-core::translation.roles.name_s')])
        @endslot
    @endcomponent
@endsection

@php
    $action = route('gingerminds-core.roles.store');
    $indexRoute = route('gingerminds-core.roles.index');
    $method = 'POST';
    $id = 'create-roles-form';
    $title = __('gingerminds-core::translation.title_m_create', ['model' => __('gingerminds-core::translation.roles.name_s')]);
    $subtitle = null;
@endphp

@section('fields')
    @include('gingerminds-core::pages.roles.partials.fields')
@endsection
