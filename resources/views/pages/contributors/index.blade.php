@extends('gingerminds-core::layouts.crud.list')

@php
    $filters = request()->get('filters', []);
    $indexRoute = 'gingerminds-core.contributors.index';
@endphp

@section('title')
    @lang('gingerminds-core::translation.contributors.manage')
@endsection

@section('breadcrumb')
    @component('gingerminds-core::components.navigation.breadcrumb')
        @slot('li_1_link')
            {{ route($indexRoute) }}
        @endslot
        @slot('li_1')
            @lang('gingerminds-core::translation.contributors.name_p')
        @endslot
        @slot('title')
            @lang('gingerminds-core::translation.title_list', ['model' => __('gingerminds-core::translation.contributors.name_p')])
        @endslot
        @slot('current')
            @lang('gingerminds-core::translation.contributors.manage')
        @endslot
    @endcomponent
@endsection

@section('actions')
    <a href="{{ route('gingerminds-core.contributors.create') }}"
       class="btn btn-success btn-rounded waves-effect waves-light mb-2"
    >
        <i class="mdi mdi-plus me-1"></i> @lang('gingerminds-core::translation.title_m_create', ['model' => __('gingerminds-core::translation.contributors.name_s')])
    </a>
@endsection

@php
    $columns = [
        ['name' => '#', 'sortable' => false],
        ['name' => __('gingerminds-core::translation.form.name'), 'sortable' => true, 'property' => 'lastname'],
        ['name' => __('gingerminds-core::translation.contributors.associated_user'), 'sortable' => false],
        ['name' => __('gingerminds-core::translation.actions'), 'sortable' => false],
    ];
    $sortBy = request()->query('sortBy');
    $sortOrder = request()->query('sort');
@endphp

@section('table_list')
    @include('gingerminds-core::pages.contributors.partials.list')
@endsection

@push('modals')
    <x-gingerminds-core::modal.modal-delete :model="__('gingerminds-core::translation.users.name_s')" routing="contributors" />
@endpush
