@extends('gingerminds-core::layouts.crud.form')

@section('title')
    @lang('gingerminds-core::translation.title_m_edit', ['model' => __('gingerminds-core::translation.contributors.name_s')])
@endsection

@section('breadcrumb')
    @component('gingerminds-core::components.navigation.breadcrumb')
        @slot('li_1_link')
            {{ route('gingerminds-core.contributors.index') }}
        @endslot
        @slot('li_1')
            @lang('gingerminds-core::translation.contributors.name_p')
        @endslot
        @slot('title')
            @lang('gingerminds-core::translation.title_m_edit', ['model' => __('gingerminds-core::translation.contributors.name_s')])
        @endslot
        @slot('current')
            @lang('gingerminds-core::translation.contributors.manage')
        @endslot
    @endcomponent
@endsection

@php
    $action = route('gingerminds-core.contributors.update', $contributor->id);
    $indexRoute = route('gingerminds-core.contributors.index');
    $method = 'PUT';
    $id = 'editcontributeur-form';
    $title = __('gingerminds-core::translation.title_m_edit', ['model' => __('gingerminds-core::translation.contributors.name_s')]);
    $subtitle = null;
@endphp

@section('fields')
    @include('gingerminds-core::pages.contributors.partials.fields')
@endsection

@push('scripts')
    @include('gingerminds-core::pages.contributors.partials.form_script')
@endpush
