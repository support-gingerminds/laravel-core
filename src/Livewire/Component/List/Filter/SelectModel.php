<?php

namespace Gingerminds\LaravelCore\Livewire\Component\List\Filter;

use Gingerminds\LaravelCore\Models\ResourceModelInterface;
use Gingerminds\LaravelCore\Models\SearchableModelInterface;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Livewire\Component;

class SelectModel extends Component
{
    public string $property;
    /** @var array<mixed>  */
    public array $options;
    /** @var string|int|null|array<mixed> */
    public string|int|null|array $value;
    public bool $isMultiple = false;

    /**
     * @param array<mixed> $options
     * @param string|int|null|array<mixed> $value
     */
    public function mount(string $property, array $options, string|int|null|array $value = null): void
    {
        $this->property   = $property;
        $this->options    = $options;
        $this->value      = $value;
        $this->isMultiple = $options['multiple'] ?? false;
    }

    /**
     * @return array<mixed>
     */
    public function search(string $search): array
    {
        $modelClass = $this->options['model'];
        $query      = $modelClass::query();

        $this->applySearchFilter($query, $modelClass, $search);

        $query->orderBy($this->resolveOrderField($modelClass), 'asc');

        return $query->limit(10)->get()
            ->map(fn ($item) => $this->formatSearchResult($item))
            ->toArray();
    }

    /**
     * Détermine le champ à utiliser pour trier les résultats.
     *
     * @param class-string $modelClass
     */
    private function resolveOrderField(string $modelClass): string
    {
        $testItem = $modelClass::first();

        return match (true) {
            $testItem === null      => 'id',
            isset($testItem->name)  => 'name',
            isset($testItem->label) => 'label',
            isset($testItem->title) => 'title',
            default                 => 'id',
        };
    }

    /**
     * Applique le filtre de recherche à la requête, selon que le modèle
     * soit "searchable" ou non.
     *
     * @param mixed $query
     * @param class-string $modelClass
     */
    private function applySearchFilter($query, string $modelClass, string $search): void
    {
        if ($search === '' || $search === '0') {
            return;
        }

        if (! is_subclass_of($modelClass, SearchableModelInterface::class)) {
            $query->where('id', 'like', '%' . $search . '%');

            return;
        }

        $query->where(function ($q) use ($modelClass, $search) {
            foreach ($modelClass::getSearchableFields() as $field) {
                $q->orWhere($field, 'like', '%' . $search . '%');
            }
        });
    }

    /**
     * @param mixed $item
     * @return array<string, mixed>
     */
    private function formatSearchResult($item): array
    {
        return [
            'id'   => $item->id,
            'text' => $this->getDisplayValue($item),
        ];
    }

    public function render(): View
    {
        $modelClass = $this->options['model'];

        // Mode single et multiple : on charge tous les items pour les afficher comme <option>
        $allItems = $modelClass::query()->limit(100)->get();

        $selectedItems = new Collection();
        if (isset($this->value) && !in_array($this->value, ['', '0', 0, []], true)) {
            $ids           = is_array($this->value) ? $this->value : [$this->value];
            $selectedItems = $modelClass::whereIn('id', $ids)->get();
        }

        /** @var view-string $view */
        $view = 'gingerminds-core::livewire.components.list.filter.select-model';

        return view($view, [
            'selectedItems' => $selectedItems,
            'allItems'      => $allItems ?? new Collection(),
        ]);
    }

    protected function getDisplayValue(ResourceModelInterface $item): string
    {
        $display = $this->options['display'] ?? null;

        if (is_callable($display)) {
            return $display($item);
        }

        return $this->resolveDisplayFromProperty($item, $display)
            ?? $item->name
            ?? $item->label
            ?? $item->title
            ?? (string) $item->getKey();
    }

    /**
     * Résout la valeur d'affichage à partir d'une méthode ou d'une propriété
     * du modèle, lorsque `display` est une chaîne.
     */
    private function resolveDisplayFromProperty(ResourceModelInterface $item, mixed $display): ?string
    {
        if (! is_string($display)) {
            return null;
        }

        if (method_exists($item, $display)) {
            return $item->{$display}();
        }

        return isset($item->{$display}) ? $item->{$display} : null;
    }
}
