@extends('gingerminds-core::layouts.crud.form')

@section('title')
    @lang('gingerminds-core::translation.title_m_create', ['model' => __('gingerminds-core::translation.roles.name_s')])
@endsection

@section('breadcrumb')
    <x-gingerminds-core::navigation.breadcrumb
        :title="__('gingerminds-core::translation.title_m_create', ['model' => __('gingerminds-core::translation.roles.name_s')])"
        :items="[
            ['label' => __('gingerminds-core::translation.roles.name_p'), 'url' => route('gingerminds-core.roles.index')],
            ['label' => __('gingerminds-core::translation.title_m_create', ['model' => __('gingerminds-core::translation.roles.name_s')]), 'active' => true],
        ]"
    />
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
