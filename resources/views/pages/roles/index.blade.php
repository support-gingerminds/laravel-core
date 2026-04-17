@extends('gingerminds-core::layouts.crud.list')

@php
    $filters = request()->get('filters', []);
    $indexRoute = 'gingerminds-core.roles.index';
@endphp

@section('title')
    @lang('gingerminds-core::translation.roles.manage')
@endsection

@section('breadcrumb')
    @component('gingerminds-core::components.navigation.breadcrumb')
        @slot('li_1')
            @lang('gingerminds-core::translation.settings')
        @endslot
        @slot('title')
            @lang('gingerminds-core::translation.roles.name_p')
        @endslot
        @slot('current')
            @lang('gingerminds-core::translation.roles.manage')
        @endslot
    @endcomponent
@endsection

@section('actions')
    <a href="{{ route('gingerminds-core.roles.create') }}"
       class="btn btn-success btn-rounded waves-effect waves-light  mb-2">
        <i class="mdi mdi-plus me-1"></i> @lang('gingerminds-core::translation.title_m_create', ['model' => __('gingerminds-core::translation.roles.name_s')])
    </a>
@endsection

@php
    $columns = [
        ['name' => '#', 'sortable' => false],
        ['name' => __('gingerminds-core::translation.roles.name_s'), 'sortable' => true, 'property' => 'name'],
        ['name' => __('gingerminds-core::translation.permissions.name_p'), 'sortable' => false],
        ['name' => __('gingerminds-core::translation.actions'), 'sortable' => false],
    ];
    $sortBy = request()->query('sortBy');
    $sortOrder = request()->query('sort');
@endphp

@section('table_list')
    @include('gingerminds-core::pages.roles.partials.list')
@endsection

@push('modals')
    <x-gingerminds-core::modal.modal-delete :model="__('gingerminds-core::translation.roles.name_s')" routing="roles"/>
@endpush

