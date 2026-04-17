@extends('gingerminds-core::layouts.crud.list')

@php
    $filters = request()->get('filters', []);
    $indexRoute = 'gingerminds-core.users.index';
@endphp

@section('title')
    @lang('gingerminds-core::translation.users.manage')
@endsection

@section('breadcrumb')
    @component('gingerminds-core::components.navigation.breadcrumb')
        @slot('li_1_link')
            {{ route($indexRoute) }}
        @endslot
        @slot('li_1')
            @lang('gingerminds-core::translation.users.name_p')
        @endslot
        @slot('title')
            @lang('gingerminds-core::translation.title_list', ['model' => __('gingerminds-core::translation.users.name_p')])
        @endslot
        @slot('current')
            @lang('gingerminds-core::translation.users.manage')
        @endslot
    @endcomponent
@endsection

@section('actions')
    <a href="{{ route('gingerminds-core.users.create') }}" class="btn btn-sm btn-success">
        <i class="bi bi-plus-lg me-1"></i> @lang('gingerminds-core::translation.title_m_create', ['model' => __('gingerminds-core::translation.users.name_s')])
    </a>
@endsection

@php
    $columns = [
        ['name' => '#', 'sortable' => false],
        ['name' => __('gingerminds-core::translation.form.email'), 'sortable' => true, 'property' => 'email'],
        ['name' => __('gingerminds-core::translation.roles.name_p'), 'sortable' => false],
        ['name' => __('gingerminds-core::translation.contributors.name_s'), 'sortable' => true, 'property' => 'contributor.lastname'],
        ['name' => __('gingerminds-core::translation.actions'), 'sortable' => false],
    ];
    $sortBy = request()->query('sortBy');
    $sortOrder = request()->query('sort');
@endphp

@section('table_list')
    @include('gingerminds-core::pages.users.partials.list')
@endsection

@push('modals')
    <x-gingerminds-core::modal.modal-delete :model="__('gingerminds-core::translation.users.name_s')" routing="users" />
@endpush
