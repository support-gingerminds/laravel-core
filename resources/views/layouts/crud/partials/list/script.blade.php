<script>
    document.addEventListener('DOMContentLoaded', function () {
        const sortableHeaders = document.querySelectorAll('th.sortable');

        sortableHeaders.forEach(th => {
            th.addEventListener('click', function () {
                const sortProperty = th.dataset.sort;
                if (!sortProperty) return;

                // Récupérer les query params actuelles
                const urlParams = new URLSearchParams(window.location.search);

                // Supprimer la pagination
                urlParams.delete('page');

                // Déterminer le prochain sort
                let currentSortBy = urlParams.get('sortBy');
                let currentSort = urlParams.get('sort');

                if (currentSortBy === sortProperty) {
                    // même colonne : toggle desc/asc
                    currentSort = currentSort === 'desc' ? 'asc' : 'desc';
                } else {
                    // nouvelle colonne : mettre desc par défaut
                    currentSort = 'desc';
                }

                urlParams.set('sortBy', sortProperty);
                urlParams.set('sort', currentSort);

                // Redirection
                window.location.search = urlParams.toString();
            });
        });
    });
</script>
