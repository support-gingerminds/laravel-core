<!-- JAVASCRIPT -->
<script src="{{ URL::asset('build/libs/jquery/jquery.min.js')}}"></script>
<script src="{{ URL::asset('build/libs/metismenu/metisMenu.min.js')}}"></script>
<script src="{{ URL::asset('build/libs/simplebar/simplebar.min.js')}}"></script>
<script src="{{ URL::asset('build/libs/node-waves/waves.min.js')}}"></script>
<script src="{{ URL::asset('build/libs/select2/js/select2.full.min.js') }}"></script>

@vite([
    'vendor/gingerminds/laravel-core/resources/js/app.js'
])
<script>
    (function () {
        // Avoid initializing Select2 on elements managed by the Livewire/Alpine contact-select component
        const select = $('.select2').not('[data-managed="contact-select"]');
        const selectSearch = $('.select2-search');

        // Init select2
        select.select2({
            minimumResultsForSearch: Infinity,
            width: '100%',
            placeholder: '— Sélectionner —'
        });
        selectSearch.select2({
            width: '100%',
            placeholder: '— Sélectionner —'
        });
    })();
</script>
@stack('scripts')
