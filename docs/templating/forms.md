# Forms

Form field components live under `resources/views/components/form/inputs/` and are auto-discoverable as `<x-form.inputs.*>`. They're meant to be used for creating or editing a resource, inside the [`crud.form` layout](layouts.md#crudform)'s `@section('fields')`, and are styled with Bootstrap 5.

## Form fields

### Basic

Renders any native `<input>` type (text, number, email, date, color...) via the `type` prop. Covers most simple fields.

```html
<x-form.inputs.basic
    id="property"
    type="text" {{-- optional, by default 'text'. Any native input type: number, email, date, color... --}}
    name="property" {{-- optional, defaults to id. Use bracket notation for nested/array fields, e.g. "translations[fr][name]" --}}
    :label="__('translation.form.property')"
    :placeholder="__('translation.form.placeholder.property')" {{-- optional --}}
    :helper="__('translation.form.helper.property')" {{-- optional --}}
    :value="old('property', isset($resource) ? $resource->property : null)" {{-- optional --}}
    size="md" {{-- optional, by default 'md' --}}
    :required="true" {{-- optional, by default false --}}
    :disabled="false" {{-- optional, by default false --}}
    :min="0" {{-- optional --}}
    :max="0" {{-- optional --}}
    :step="0" {{-- optional --}}
/>
```

> Validation errors and `old()` re-population are resolved from `name` (converted to dot notation), not `id`. When using nested names like `translations[fr][name]`, make sure your FormRequest validates the matching dotted key (`translations.fr.name`).

### Textarea

```html
<x-form.inputs.textarea
    id="property"
    :label="__('translation.form.property')"
    :placeholder="__('translation.form.placeholder.property')" {{-- optional --}}
    :helper="__('translation.form.helper.property')" {{-- optional --}}
    :value="old('property', isset($resource) ? $resource->property : null)"
    size="md" {{-- optional, by default 'md' --}}
    :required="true" {{-- optional, by default true --}}
    :disabled="false" {{-- optional, by default false --}}
    :rows="10" {{-- optional, by default 10 --}}
/>
```

> There is currently **no built-in WYSIWYG component** in this package. If a project needs rich-text editing, it's implemented at the project level (e.g. with TipTap) — it doesn't come from `gingerminds-core`.

### Select

Renders a Bootstrap `<select>` wired to Select2. Options are passed as the slot content (plain `<option>` tags).

```html
<x-form.inputs.select
    id="property"
    :label="__('translation.form.placeholder.property')" {{-- optional --}}
    size="md"
    :helper="__('translation.form.helper.property')" {{-- optional --}}
    :required="true" {{-- optional, by default true --}}
    :disabled="false" {{-- optional, by default false --}}
    :multiple="true" {{-- optional, by default false --}}
    :search="true" {{-- optional, by default false. Enables Select2's search box --}}
    ajax-url="{{ route('your-package.your-resource.search') }}" {{-- optional. Loads options asynchronously instead of the slot content, and implies :search --}}
>
    <option value="1">Option 1</option>
</x-form.inputs.select>
```

If you need a "search a model" select backed by Livewire instead of a plain AJAX endpoint, the package ships a `SelectModel` Livewire component — see the `select-model` [filter](../partials/filters.md#select-model-filter) for its reference usage (list filters only for now).

### Toggle

A styled checkbox switch. It always submits a value (`0` when unchecked, `1` when checked) thanks to a hidden `0` input rendered alongside the checkbox, so you don't need a `required` rule or a default-value fallback in your FormRequest.

```html
<x-form.inputs.toggle
    id="property"
    :label="__('translation.form.placeholder.property')"
    :helper="__('translation.form.helper.property')" {{-- optional --}}
    :checked="old('property', isset($resource) ? $resource->property : false)"
/>
```

### The size system

Fields are laid out on Bootstrap's 12-column grid. Most components accept a `size` attribute to control their width:

1. `tiny`: 2 columns
2. `sm`: 4 columns
3. `md`: 6 columns
4. `lg`: 8 columns
5. `xl`: 12 columns

> `textarea` doesn't accept `tiny` (falls back to `md`), and `toggle` doesn't have a `size` prop at all.

## See also

- [Layouts](layouts.md) — the `crud.form` / `crud.form-tabs` layouts these fields are meant to live in.
- [Filters](../partials/filters.md) — the list-filter equivalents of these components (`date`, `number`, `boolean`, `select`, `select-model`).
