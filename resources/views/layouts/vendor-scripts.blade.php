<!-- JAVASCRIPT -->
<script src="{{ URL::asset('build/libs/jquery/jquery.min.js')}}"></script>
<script src="{{ URL::asset('build/libs/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{ URL::asset('build/libs/metismenu/metisMenu.min.js')}}"></script>
<script src="{{ URL::asset('build/libs/simplebar/simplebar.min.js')}}"></script>
<script src="{{ URL::asset('build/libs/node-waves/waves.min.js')}}"></script>
<script src="{{ URL::asset('build/libs/select2/js/select2.full.min.js') }}"></script>

@vite([
    'resources/js/app.js'
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

        selectSearch.each(function () {
            const $el = $(this);
            const ajaxUrl = $el.data('ajax-url');

            let config = {
                width: '100%',
                placeholder: '— Sélectionner —'
            };

            if (ajaxUrl) {
                config.ajax = {
                    url: ajaxUrl,
                    dataType: 'json',
                    delay: 250,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    xhrFields: {
                        withCredentials: true
                    },
                    beforeSend: function (xhr) {
                        // Forcer l'envoi des cookies même si Select2 hésite
                        xhr.withCredentials = true;
                    },
                    data: function (params) {
                        return {
                            'filters[search]': params.term, // Pour correspondre à AbstractRepository::applySearch
                            'page': params.page || 1
                        };
                    },
                    processResults: function (data, params) {
                        params.page = params.page || 1;

                        // API Platform / AbstractRepository renvoie une pagination
                        // On suppose que data.items ou data directement si c'est une liste simple
                        // Mais AbstractRepository::runGetQuery renvoie ->paginate() qui a une clé 'data' en JSON
                        // Note: ApiPlatform GetCollection renvoie souvent l'array directement au top level ou hydra:member
                        const results = data.data || data['hydra:member'] || data;

                        return {
                            results: results.map(function (item) {
                                return {
                                    id: item.id,
                                    text: item.name || item.text || item.label || item.id
                                };
                            }),
                            pagination: {
                                more: data.next_page_url || (data['hydra:view'] && data['hydra:view']['hydra:next'])
                            }
                        };
                    },
                    cache: true
                };
            }

            $el.select2(config);
        });
    })();
</script>
@stack('scripts')
