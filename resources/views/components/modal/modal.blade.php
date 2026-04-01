@props([
    'model',
    'routing',
    'size',
])

<div class="modal fade" id="formModal" tabindex="-1" aria-labelledby="formModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered @isset($size){{ $size }}@endisset">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="formModalLabel"
                    data-title-create="@lang('gingerminds-core::translation.modal.title_create')"
                    data-title-edit="@lang('gingerminds-core::translation.modal.title_edit')">
                    @lang('gingerminds-core::translation.modal.title_default')
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ isset($data) ? route($routing.'.update', $data->id) : '#' }}"
                      autocomplete="off" class="needs-validation modal-form" id="form" novalidate
                      enctype="multipart/form-data"
                >
                    @csrf

                    {{ $body ?? $slot }}

                    <div class="col-lg-12 justify-content-between d-flex">
                        <div>
                            <button type="button"
                                    class="btn btn-danger"
                                    id="btn-delete-modal"
                                    style="display: none;"
                                    data-bs-toggle="modal"
                                    data-bs-target="#deleteRecordModal">
                                @lang('gingerminds-core::translation.action.remove')
                            </button>
                        </div>
                        <div class="text-end">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                @lang('gingerminds-core::translation.action.cancel')
                            </button>
                            <button type="submit" class="btn btn-primary">
                                @lang('gingerminds-core::translation.action.save')
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const modal = document.getElementById('formModal');
            if (!modal) return;

            const form = modal.querySelector('#form');
            const titleEl = modal.querySelector('#formModalLabel');

            // ===== Helper pour pré-remplir les champs =====
            function fillFields(values) {
                Object.entries(values).forEach(([key, value]) => {
                    // 1) input / textarea (on ignore les hidden s'ils servent de fallback pour checkbox/radio)
                    // on ignore aussi les checkbox/radio ici pour les traiter spécifiquement plus bas
                    const input = modal.querySelector(`input[name="${key}"]:not([type="hidden"]):not([type="checkbox"]):not([type="radio"]), textarea[name="${key}"]`);
                    if (input) {
                        if (input.type === 'file') {
                            // pour input file : on ne touche jamais .value
                            const preview = modal.querySelector(`#${key}-preview`) || modal.querySelector(`#edit-${key}-preview`);
                            if (preview) preview.src = value || preview.getAttribute('data-default-src') || preview.src;

                            // attach listener sur ce file input si pas déjà attaché
                            if (!input.dataset.listenerAttached) {
                                input.addEventListener('change', e => {
                                    const file = e.target.files[0];
                                    if (!file) return;
                                    const reader = new FileReader();
                                    reader.onload = ev => preview.src = ev.target.result;
                                    reader.readAsDataURL(file);
                                });
                                input.dataset.listenerAttached = 'true';
                            }
                        } else {
                            input.value = value ?? '';
                        }
                        return;
                    }

                    // 2) select multiple
                    const selectMultiple = modal.querySelector(`select[name="${key}[]"]`);
                    if (selectMultiple) {
                        Array.from(selectMultiple.options).forEach(opt => opt.selected = false);
                        if (Array.isArray(value)) {
                            const set = new Set(value.map(v => String(v)));
                            Array.from(selectMultiple.options).forEach(opt => { if (set.has(String(opt.value))) opt.selected = true; });
                        } else {
                            Array.from(selectMultiple.options).forEach(opt => { opt.selected = String(opt.value) === String(value); });
                        }
                        return;
                    }

                    // 3) select simple
                    const select = modal.querySelector(`select[name="${key}"]`);
                    if (select) {
                        Array.from(select.options).forEach(opt => { opt.selected = String(opt.value) === String(value); });
                        return;
                    }

                    // 4) checkbox / radio
                    const checkboxes = modal.querySelectorAll(`input[type="checkbox"][name="${key}"], input[type="radio"][name="${key}"]`);
                    if (checkboxes.length) {
                        if (Array.isArray(value)) {
                            const set = new Set(value.map(v => String(v)));
                            checkboxes.forEach(cb => cb.checked = set.has(String(cb.value)));
                        } else {
                            checkboxes.forEach(cb => {
                                // Si value est un booléen et que la checkbox n'a pas de value spécifique (ou value="1")
                                if (typeof value === 'boolean') {
                                    cb.checked = value;
                                } else {
                                    cb.checked = String(cb.value) === String(value);
                                }
                            });
                        }
                        return;
                    }

                    // 5) fallback par id
                    const byId = modal.querySelector(`#edit-${key}`) || modal.querySelector(`#${key}`);
                    if (byId) {
                        if (byId.tagName === 'IMG') {
                            byId.src = value || byId.getAttribute('data-default') || byId.src;
                        } else {
                            byId.value = value ?? '';
                        }
                    }
                });
            }

            // ===== Pré-remplir à l'ouverture du modal =====
            modal.addEventListener('show.bs.modal', event => {
                const button = event.relatedTarget;
                if (!button) return;

                // Titre de la modale
                const mode   = button.dataset.mode || 'edit'; // create | edit
                const gender = button.dataset.gender || 'm';  // m | f
                const model  = button.dataset.model  || '';   // ex: "rôle", "catégorie"

                // article masculin/féminin
                const article = gender === 'f' ? 'une' : 'un';

                // récupère la template depuis data-title-create ou data-title-edit
                const template = titleEl.dataset[`title${mode.charAt(0).toUpperCase() + mode.slice(1)}`];

                // remplace les placeholders
                titleEl.textContent = template
                    .replace('{article}', article)
                    .replace('{model}', model);


                // parse JSON des valeurs
                let raw = button.getAttribute('data-values') || '{}';
                let values = {};
                // tenter JSON.parse ; si échec, remplacer &quot; par " puis retenter
                try {
                    values = JSON.parse(raw);
                } catch (e) {
                    try {
                        const normalized = raw.replace(/&quot;/g, '"').replace(/&amp;/g, '&');
                        values = JSON.parse(normalized);
                    } catch (e2) {
                        console.error('Impossible de parser data-values:', raw, e2);
                        values = {};
                    }
                }

                // Gestion de l'URL d'action et de la méthode (POST par défaut, spoof PUT seulement en édition)
                if (form) {
                    // Nettoyer un éventuel spoof précédent
                    const existingMethod = form.querySelector('input[name="_method"]');
                    if (existingMethod) existingMethod.remove();

                    // Déterminer l'URL selon le mode
                    const updateUrl = button.getAttribute('data-update-url') || button.dataset.updateUrl;

                    if (mode === 'edit' && updateUrl) {
                        form.action = updateUrl;
                        // Ajouter le spoof PUT
                        const spoof = document.createElement('input');
                        spoof.type = 'hidden';
                        spoof.name = '_method';
                        spoof.value = 'PUT';
                        form.appendChild(spoof);
                    }

                    // Toujours en POST côté balise form
                    form.method = 'POST';
                }

                fillFields(values);
            });

            // ===== Reset du modal à la fermeture =====
            modal.addEventListener('hidden.bs.modal', () => {
                if (!form) return;
                form.reset();
                modal.querySelectorAll('select').forEach(s => Array.from(s.options).forEach(o => o.selected = false));
                modal.querySelectorAll('img[id$="-preview"]').forEach(img => img.src = img.getAttribute('data-default-src'));
                modal.querySelectorAll('input[type="file"]').forEach(f => f.value = '');
                modal.querySelectorAll('input[type="checkbox"][name^="remove_"]').forEach(cb => cb.checked = false);
            });
        });
    </script>

@endpush
