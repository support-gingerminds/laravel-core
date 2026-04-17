@php use Gingerminds\LaravelCore\Models\FilterableModelInterface;use Gingerminds\LaravelCore\Models\SearchableModelInterface; @endphp
@extends('gingerminds-core::layouts.master')

@section('content')
    @yield('breadcrumb')

    @hasSection('back-btn')
        <div class="row">
            <div class="col-3 mb-3">
                @yield('back-btn')
            </div>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-12">
            @hasSection('actions')
                <div class="row">
                    <div class="col-sm-12 mb-3 mt-3">
                        <div class="text-sm-end">
                            @yield('actions')
                        </div>
                    </div>
                </div>
            @endif
            @php
                $isSearchable = in_array(SearchableModelInterface::class, class_implements($resource));
                $isFilterable = in_array(FilterableModelInterface::class, class_implements($resource));
                $isFiltered = request()->has('filters')
            @endphp
            @if(isset($indexRoute) && ($isSearchable || $isFilterable))
                <div class="card">
                    <div class="card-body">
                        <div class="accordion" id="filtersAccordion">
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button @if(!$isFiltered) collapsed @endif"
                                            type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne"
                                            aria-expanded="true" aria-controls="collapseOne">
                                        @lang('gingerminds-core::translation.action.filters')
                                    </button>
                                </h2>
                                <div id="collapseOne"
                                     class="accordion-collapse collapse @if($isFiltered) show @endif p-3"
                                     data-bs-parent="#filtersAccordion">
                                    <div class="col-md-12">
                                        <form method="get" action="{{ route($indexRoute) }}"
                                              class="row g-3 align-items-end">
                                            @if($isSearchable)
                                                @include('gingerminds-core::components.list.filters.text', [
                                                'property' => 'search',
                                                'label' => __('gingerminds-core::translation.action.search'),
                                                'placeholder' => __('gingerminds-core::translation.form.placeholder.search'),
                                                'filters' => $filters
                                            ])
                                            @endif
                                            @if($isFilterable)
                                                @include('gingerminds-core::components.list.filters_collection', ['filtersConfigs' => $resource::getFilters(), 'filters' => $filters])
                                            @endif
                                            <div class="col-md-12 d-flex justify-content-end gap-2">
                                                <a href="{{ route($indexRoute, request()->only(['itemsPerPage', 'sort', 'sortBy'])) }}"
                                                   class="btn btn-outline-secondary">@lang('gingerminds-core::translation.action.clear_filters')</a>
                                                <button class="btn btn-primary"
                                                        type="submit">@lang('gingerminds-core::translation.action.filter')</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            <div class="card">
                <div class="card-body">
                    <div class="row mb-3 justify-content-end">
                        @if(!isset($isPaginationDisabled) || !$isPaginationDisabled)
                            @include('gingerminds-core::components.list.items_per_page_selector')
                        @endif
                    </div>

                    <div class="table-responsive">
                        @hasSection('table')
                            @yield('table')
                        @else
                            <table class="table align-middle table-hover text-nowrap"
                                   id="list-table">
                                <thead class="table-light">
                                <tr>
                                    @foreach($columns as $col)
                                        @include('gingerminds-core::components.list.table_row_header', array_merge($col, [
                                            'sortBy' => $sortBy,
                                            'sortOrder' => $sortOrder
                                        ]))
                                    @endforeach
                                </tr>
                                </thead>
                                <tbody>
                                @if($items instanceof Illuminate\Database\Eloquent\Collection ? $items->count() > 0 : $items->total() > 0)
                                    @yield('table_list')
                                @else
                                    <tr>
                                        <td colspan="{{ count($columns) }}"
                                            class="text-center">@lang('gingerminds-core::translation.message.no_result')</td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                        @endif
                    </div>

                    <div class="row mb-3">
                        @if(!isset($isPaginationDisabled) || !$isPaginationDisabled)
                            @include('gingerminds-core::components.list.pagination')
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection


@section('css')
    <link href="{{ URL::asset('build/libs/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}" rel="stylesheet"
          type="text/css"/>
@endsection


@push('scripts')
    <script src="{{ URL::asset('build/libs/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/bootstrap-datepicker/locales/bootstrap-datepicker.fr.min.js') }}"></script>
    @include('gingerminds-core::layouts.crud.partials.list.script')
@endpush
