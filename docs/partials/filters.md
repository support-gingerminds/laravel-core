# Filters

You can add filters to your crud list. To do so add `FilterableModelInterface` to your model + add `getFilters` method.

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
```

This will automatically add a select field into crud filters `model_property`.
If multiple is set to true, the select will enable search.
