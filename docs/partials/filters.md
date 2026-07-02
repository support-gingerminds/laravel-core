# Filters

Filters power the collapsible panel on `crud.list` [pages](../templating/layouts.md#crudlist). To add one, implement `Gingerminds\LaravelCore\Models\FilterableModelInterface` on your model and add a static `getFilters(): array` method (see [Resource Model](../ResourceModel.md#optional-interfaces)). Each key of the returned array is a request/query parameter name; the value configures how it's rendered and applied.

## Date filter

```php
public static function getFilters(): array
{
    return [
        'date_property' => [ // Ex: updated_at
            'type' => 'date',
            'label' => 'your_label_translation_key',
            'disabled_for_back' => true, // this option is optional, by default is true,
        ],
    ];
}
```

This will automatically add two fields into crud filters `date_property.from` and `date_property.to`.

## Number filter

```php
public static function getFilters(): array
{
    return [
        'number_property' => [ // Ex: cost
            'type' => 'number',
            'label' => 'your_label_translation_key',
            'disabled_for_back' => true, // this option is optional, by default is true,
        ],
    ];
}
```

This will automatically add two fields into crud filters `number_property.from` and `number_property.to`.

## Boolean filter

```php
public static function getFilters(): array
{
    return [
        'boolean_property' => [ //Ex: is_default
            'type' => 'boolean',
            'label' => 'your_label_translation_key',
            'disabled_for_back' => true, // this option is optional, by default is true,
        ],
    ];
}
```

This will automatically add a select field into crud filters `boolean_property`.

## Select filters

### Select filter

```php
public static function getFilters(): array
{
    return [
        'select_state_property' => [
            'type'    => 'select',
            'label'   => 'your_label_translation_key',
            'choices' => [
                    0 => 'translation.choice.0',
                    1 => 'translation.choice.1',
                    2 => 'translation.choice.2',
                    3 => 'translation.choice.3',
                    4 => 'translation.choice.4',
                ],
            'multiple' => true, // this option is optional, by default if not set it's false
            'disabled_for_back' => true, // this option is optional, by default is true,
        ],
    ];
}
```

### Select state filter

```php
public static function getFilters(): array
{
    $stateChoices = [];

    foreach (State::getStateMapping() as $state) {
        $stateChoices[$state::label()] = 'translation.models.states.' . $state::label(); // label translation key will be different between your cases
    }
    
    return [
        'select_state_property' => [
            'type'    => 'select-state',
            'label'   => 'your_label_translation_key',
            'choices' => $stateChoices,
            'multiple' => true, // this option is optional, by default if not set it's false
            'disabled_for_back' => true, // this option is optional, by default is true,
        ],
    ];
}
```

This will automatically add a select field into crud filters `select_state_property`. It will work as same as api you send de stringyfied code of state
and the repository will translate it into a std class.

### Select Model filter

```php
public static function getFilters(): array
{
    return [
        'model_property' => [ // Ex: model_id
            'type'    => 'select-model',
            'label'   => 'your_label_translation_key',
            'model'   => Model::class, 
            'multiple' => true, // this option is optional, by default if not set it's false
            'disabled_for_back' => true, // this option is optional, by default is true,
        ],
    ];
}
```

This will automatically add a select field into crud filters `model_property`, backed by a `SelectModel` Livewire component that queries `model` directly. If `multiple` is set to `true`, the select also enables search.

## See also

- [Resource Model](../ResourceModel.md#optional-interfaces) — where `FilterableModelInterface` fits among the other optional model interfaces.
- [Layouts](../templating/layouts.md#crudlist) — how the filters panel is rendered on list pages.
- [Sorting](../Sorting.md) — column sorting, the other list-refinement mechanism.
