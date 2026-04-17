@extends('gingerminds-core::layouts.crud.list')

@php
    $filters = request()->get('filters', []);
    $indexRoute = 'gingerminds-core.contributors.index';
@endphp

@section('title')
    @lang('gingerminds-core::translation.contributors.manage')
@endsection

@section('breadcrumb')
    <x-gingerminds-core::navigation.breadcrumb
        :title="__('gingerminds-core::translation.title_list', ['model' => __('gingerminds-core::translation.contributors.name_p')])"
        :items="[
            ['label' => __('gingerminds-core::translation.contributors.name_p'), 'url' => route($indexRoute)],
            ['label' => __('gingerminds-core::translation.contributors.manage'), 'active' => true],
        ]"
    />
@endsection

@section('actions')
    <a href="{{ route('gingerminds-core.contributors.create') }}" class="btn btn-sm btn-success">
        <i class="bi bi-plus-lg me-1"></i> @lang('gingerminds-core::translation.title_m_create', ['model' => __('gingerminds-core::translation.contributors.name_s')])
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
