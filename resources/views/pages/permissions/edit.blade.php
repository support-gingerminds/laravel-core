@extends('gingerminds-core::layouts.crud.form')

@section('title')
    @lang('gingerminds-core::translation.title_f_edit', ['model' => __('gingerminds-core::translation.permissions.name_s')])
@endsection

@section('breadcrumb')
    <x-gingerminds-core::navigation.breadcrumb
        :title="__('gingerminds-core::translation.title_f_edit', ['model' => __('gingerminds-core::translation.permissions.name_s')])"
        :items="[
            ['label' => __('gingerminds-core::translation.permissions.name_p'), 'url' => route('gingerminds-core.permissions.index')],
            ['label' => __('gingerminds-core::translation.permissions.manage'), 'active' => true],
        ]"
    />
@endsection

@php
    $action = route('gingerminds-core.permissions.update', $permission->id);
    $indexRoute = route('gingerminds-core.permissions.index');
    $method = 'PUT';
    $id = 'edit-permissions-form';
    $title = __('gingerminds-core::translation.title_f_edit', ['model' => __('gingerminds-core::translation.permissions.name_s')]);
@endphp

@section('fields')
    @include('gingerminds-core::pages.permissions.partials.fields')
@endsection
