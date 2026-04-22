@extends('gingerminds-core::layouts.crud.form')

@section('title')
    @lang('gingerminds-core::translation.title_m_create', ['model' => __('gingerminds-core::translation.users.name_s')])
@endsection

@section('breadcrumb')
    <x-gingerminds-core::navigation.breadcrumb
        :title="__('gingerminds-core::translation.title_m_create', ['model' => __('gingerminds-core::translation.users.name_s')])"
        :items="[
            ['label' => __('gingerminds-core::translation.users.name_p'), 'url' => route('gingerminds-core.users.index')],
            ['label' => __('gingerminds-core::translation.title_m_create', ['model' => __('gingerminds-core::translation.users.name_s')]), 'active' => true],
        ]"
    />
@endsection

@php
    $action = route('gingerminds-core.users.store');
    $indexRoute = route('gingerminds-core.users.index');
    $method = 'POST';
    $id = 'create-users-form';
    $title = __('gingerminds-core::translation.title_m_create', ['model' => __('gingerminds-core::translation.users.name_s')]);
    $subtitle = null;
@endphp

@section('fields')
    @include('gingerminds-core::pages.users.partials.fields')
@endsection
