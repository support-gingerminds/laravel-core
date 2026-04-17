@extends('gingerminds-core::layouts.crud.list')

@php
    $filters = request()->get('filters', []);
    $indexRoute = 'gingerminds-core.permissions.index';
@endphp

@section('title')
    @lang('gingerminds-core::translation.permissions.manage')
@endsection

@section('breadcrumb')
    @component('gingerminds-core::components.navigation.breadcrumb')
        @slot('li_1')
            @lang('gingerminds-core::translation.settings')
        @endslot
        @slot('title')
            @lang('gingerminds-core::translation.permissions.name_p')
        @endslot
        @slot('current')
            @lang('gingerminds-core::translation.permissions.manage')
        @endslot
    @endcomponent
@endsection

@section('actions')
    <a href="{{ route('gingerminds-core.permissions.create') }}" class="btn btn-sm btn-success">
        <i class="bi bi-plus-lg me-1"></i> @lang('gingerminds-core::translation.title_m_create', ['model' => __('gingerminds-core::translation.permissions.name_s')])
    </a>
@endsection

@php
    $columns = [
        ['name' => '#', 'sortable' => false],
        ['name' => __('gingerminds-core::translation.form.name'), 'sortable' => true, 'property' => 'name'],
        ['name' => __('gingerminds-core::translation.actions'), 'sortable' => false],
    ];
    $sortBy = request()->query('sortBy');
    $sortOrder = request()->query('sort');
@endphp

@section('table_list')
    @include('gingerminds-core::pages.permissions.partials.list')
@endsection

@push('modals')
    <x-gingerminds-core::modal.modal-delete :model="__('gingerminds-core::translation.permissions.name_s')" routing="permissions"/>
@endpush


