@extends('gingerminds-core::layouts.crud.form')

@section('title')
    @lang('gingerminds-core::translation.title_m_edit', ['model' => __('gingerminds-core::translation.contributors.name_s')])
@endsection

@section('breadcrumb')
    <x-gingerminds-core::navigation.breadcrumb
        :title="__('gingerminds-core::translation.title_m_edit', ['model' => __('gingerminds-core::translation.contributors.name_s')])"
        :items="[
            ['label' => __('gingerminds-core::translation.contributors.name_p'), 'url' => route('gingerminds-core.contributors.index')],
            ['label' => __('gingerminds-core::translation.contributors.manage'), 'active' => true],
        ]"
    />
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
