@extends('gingerminds-core::layouts.crud.form')

@section('title')
    @lang('gingerminds-core::translation.title_m_edit', ['model' => __('gingerminds-core::translation.users.name_s')])
@endsection

@section('breadcrumb')
    @component('gingerminds-core::components.navigation.breadcrumb')
        @slot('li_1_link')
            {{ route('gingerminds-core.users.index') }}
        @endslot
        @slot('li_1')
            @lang('gingerminds-core::translation.users.name_p')
        @endslot
        @slot('title')
            @lang('gingerminds-core::translation.title_m_edit', ['model' => __('gingerminds-core::translation.users.name_s')])
        @endslot
        @slot('current')
            @lang('gingerminds-core::translation.users.manage')
        @endslot
    @endcomponent
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
