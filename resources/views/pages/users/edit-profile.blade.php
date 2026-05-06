@extends('gingerminds-core::layouts.crud.form')

@section('title')
    @lang('gingerminds-core::translation.profile.name')
@endsection

@section('breadcrumb')
    <x-gingerminds-core::navigation.breadcrumb
            :title="__('gingerminds-core::translation.profile.name')"
            :items="[
            ['label' => __('gingerminds-core::translation.dashboard'), 'url' => route('dashboard')],
            ['label' => __('gingerminds-core::translation.profile.name'), 'active' => true],
        ]"
    />
@endsection

@php
    $action = route('gingerminds-core.profile.update-profile');
    $indexRoute = null;
    $method = 'PUT';
    $id = 'edit-profile-form';
    $title = __('gingerminds-core::translation.profile.name');
@endphp

@section('fields')
    @include('gingerminds-core::pages.users.partials.fields-profile')
@endsection

