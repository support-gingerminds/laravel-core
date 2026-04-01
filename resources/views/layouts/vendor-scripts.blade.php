<!-- JAVASCRIPT -->

@vite([
    'resources/js/app.js',
    'resources/js/plugin.js'
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
