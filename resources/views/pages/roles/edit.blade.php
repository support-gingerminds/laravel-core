@extends('gingerminds-core::layouts.crud.form')

@section('title')
    @lang('gingerminds-core::translation.title_m_edit', ['model' => __('gingerminds-core::translation.roles.name_s')])
@endsection

@section('breadcrumb')
    <x-gingerminds-core::navigation.breadcrumb
        :title="__('gingerminds-core::translation.title_m_edit', ['model' => __('gingerminds-core::translation.roles.name_s')])"
        :items="[
            ['label' => __('gingerminds-core::translation.roles.name_p'), 'url' => route('gingerminds-core.roles.index')],
            ['label' => __('gingerminds-core::translation.roles.manage'), 'active' => true],
        ]"
    />
@endsection

@php
    $action = route('gingerminds-core.roles.update', $role->id);
    $indexRoute = route('gingerminds-core.roles.index');
    $method = 'PUT';
    $id = 'edit-roles-form';
    $title = __('gingerminds-core::translation.title_m_edit', ['model' => __('gingerminds-core::translation.roles.name_s')]);
@endphp

@section('fields')
    @include('gingerminds-core::pages.roles.partials.fields')
@endsection
