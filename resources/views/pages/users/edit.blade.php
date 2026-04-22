@extends('gingerminds-core::layouts.crud.form')

@section('title')
    @lang('gingerminds-core::translation.title_m_edit', ['model' => __('gingerminds-core::translation.users.name_s')])
@endsection

@section('breadcrumb')
    <x-gingerminds-core::navigation.breadcrumb
        :title="__('gingerminds-core::translation.title_m_edit', ['model' => __('gingerminds-core::translation.users.name_s')])"
        :items="[
            ['label' => __('gingerminds-core::translation.users.name_p'), 'url' => route('gingerminds-core.users.index')],
            ['label' => __('gingerminds-core::translation.users.manage'), 'active' => true],
        ]"
    />
@endsection

@php
    $action = route('gingerminds-core.users.update', $user->id);
    $indexRoute = route('gingerminds-core.users.index');
    $method = 'PUT';
    $id = 'edituser-form';
    $title = __('gingerminds-core::translation.title_m_edit', ['model' => __('gingerminds-core::translation.users.name_s')]);
    $subtitle = null;
@endphp

@section('fields')
    @include('gingerminds-core::pages.users.partials.fields')
@endsection

@push('scripts')
    @include('gingerminds-core::pages.users.partials.form_script')
@endpush
