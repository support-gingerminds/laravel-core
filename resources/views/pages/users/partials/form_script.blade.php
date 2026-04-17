<script>
    (function () {
        const select = document.getElementById('contributor_id');
        const box = document.getElementById('contributor-fields');
        const fFirst = document.getElementById('contributor_firstname');
        const fLast = document.getElementById('contributor_lastname');
        const fTrig = document.getElementById('contributor_trigram');
        const fCiv = document.getElementById('contributor_civility');

        function syncVisibility() {
            if (!select) return;
            const hasSelection = !!select.value;
            if (box) box.style.display = hasSelection ? '' : 'none';

            if (hasSelection) {
                const opt = select.options[select.selectedIndex];
                if (opt) {
                    if (fFirst && !fFirst.value) fFirst.value = opt.getAttribute('data-firstname') || '';
                    if (fLast && !fLast.value) fLast.value = opt.getAttribute('data-lastname') || '';
                    if (fTrig && !fTrig.value) fTrig.value = opt.getAttribute('data-trigram') || '';
                    if (fCiv && !fCiv.value) fCiv.value = opt.getAttribute('data-civility') || '';
                }
            }
        }

        if (select) {
            select.addEventListener('change', function () {
                // À chaque changement, on alimente les champs avec les valeurs de l'option sélectionnée
                const value = select.value;
                const opt = select.options[select.selectedIndex];
                if (value === '__new__') {
                    // Nouveau contributeur: on vide les champs pour saisie
                    if (fFirst) fFirst.value = '';
                    if (fLast) fLast.value = '';
                    if (fTrig) fTrig.value = '';
                    if (fCiv) fCiv.value = '';
                } else if (opt) {
                    if (fFirst) fFirst.value = opt.getAttribute('data-firstname') || '';
                    if (fLast) fLast.value = opt.getAttribute('data-lastname') || '';
                    if (fTrig) fTrig.value = opt.getAttribute('data-trigram') || '';
                    if (fCiv) fCiv.value = opt.getAttribute('data-civility') || '';
                }
                syncVisibility();
            });
        }

        // Initialiser à l'ouverture de la page
        syncVisibility();
    })();
</script>
