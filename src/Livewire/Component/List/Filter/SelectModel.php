<?php

namespace Gingerminds\LaravelCore\Livewire\Component\List\Filter;

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

        // Déterminer le champ d'ordre
        $orderField = 'id';
        $testItem   = $modelClass::first();
        if ($testItem) {
            if (isset($testItem->name)) {
                $orderField = 'name';
            } elseif (isset($testItem->label)) {
                $orderField = 'label';
            } elseif (isset($testItem->title)) {
                $orderField = 'title';
            }
        }

        if ($search !== '' && $search !== '0') {
            if (is_subclass_of($modelClass, SearchableModelInterface::class)) {
                $query->where(function ($q) use ($modelClass, $search) {
                    foreach ($modelClass::getSearchableFields() as $field) {
                        $q->orWhere($field, 'like', '%' . $search . '%');
                    }
                });
            } else {
                $query->where('id', 'like', '%' . $search . '%');
            }
        }

        $query->orderBy($orderField, 'asc');

        return $query->limit(10)->get()->map(function ($item) {
            return [
                'id'   => $item->id,
                'text' => $item->name ?? $item->label ?? $item->title ?? $item->id,
            ];
        })->toArray();
    }

    public function render(): View
    {
        $modelClass = $this->options['model'];

        if (!$this->isMultiple) {
            // Mode single : on charge tous les items pour les afficher comme <option>
            $query    = $modelClass::query();
            $allItems = $query->limit(100)->get();
        }

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
}
