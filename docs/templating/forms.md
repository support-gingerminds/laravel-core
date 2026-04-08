# Forms Templating Documentation

## Form Fields configurations

### Basic

You can use the following configuration to integrate basic form fields such as text, number, email, etc.
To do so, include this in your form configuration:

```html
<x-form.inputs.basic
    id="property"
    :label="__('translation.form.property')"
    :placeholder="__('translation.form.placeholder.property')" {{-- optional --}}
    :helper="__('translation.form.helper.property')" {{-- optional --}}
    :value="old('property', isset($resource) ? $resource->property : null)" {{-- optional --}}
    size="md" {{-- optional, by default 'md' --}}
    :required="true" {{-- optional, by default true --}}
    :disabled="false" {{-- optional, by default false --}}
    :min="0" {{-- optional --}}
    :max="0" {{-- optional --}}
    :step="0" {{-- optional --}}
/>
```

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

### Wysiwyg

```html
<x-form.inputs.wysiwyg
    id="property"
    :label="__('translation.form.property')"
    :helper="__('translation.form.helper.property')" {{-- optional --}}
    :value="old('property', isset($resource) ? $resource->property : null)"
    size="md" {{-- optional, by default 'md' --}}
    :required="true" {{-- optional, by default true --}}
    :disabled="false" {{-- optional, by default false --}}
    :rows="10" {{-- optional, by default 10 --}}
/>
```

Warning, the wysiwyg need to install script so use `make install-node` command to install it.

### Select

```html
<x-form.inputs.select
    id="property"
    :label="__('translation.form.placeholder.property')" {{-- optional --}}
    size="md"
    :helper="__('translation.form.helper.property')" {{-- optional --}}
    :required="true" {{-- optional, by default true --}}
    :disabled="false" {{-- optional, by default false --}}
    :multiple="true" {{-- optional, by default false --}}
    :search="true" {{-- optional, by default false --}}
>
    {{-- Define options here --}}
</x-form.inputs.select>
```

### Toggle

```html
<x-form.inputs.toggle
    id="property"
    :label="__('translation.form.placeholder.property')"
    :helper="__('translation.form.helper.property')" {{-- optional --}}
    :checked="old('property', isset($resource) ? $resource->property : false)"
/>
```

### The size system
We use bootstrap 5 with 12 columns grid system.

In all theses components you can choose the size of the input field using the `size` attribute. Available sizes are:

1. `tiny`: 2 columns
2. `sm`: 4 columns
3. `md`: 6 columns
4. `lg`: 8 columns
5. `xl`: 12 columns
