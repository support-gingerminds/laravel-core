# Sortable Resources

"Sortable" covers two unrelated features in this package. Pick the right one for your use case.

## 1. Column sorting (`SortableModelInterface`)

Lets users click a column header to sort the list by `?sortBy=property&sort=asc|desc`. It's a marker interface — no method to implement.

```php
use Gingerminds\LaravelCore\Models\SortableModelInterface;

class Product extends Model implements ResourceModelInterface, SortableModelInterface
{
    // ...
}
```

`AbstractRepository::initGetQueryBuilder()` checks for this interface and, when both `sort` and `sortBy` are present in the request, applies `applySort()` (which also handles sorting through `BelongsTo` relations). The `crud.list` layout already renders clickable headers via `<x-list.table_row_header>` when a column defines a `property` — see [Layouts](templating/layouts.md#crudlist).

## 2. Drag & drop reordering (`crud.list-tree` layout)

This is a UI/JS pattern for **hierarchical or manually-ordered** resources (categories, menus, etc.) — not a model interface. It combines the `crud.list-tree` [layout](templating/layouts.md#crudlist-tree), Sortable.js, and a small controller endpoint.

### The view

```blade
@extends('gingerminds-core::layouts.crud.list-tree')

@section('tree')
    @include('your-package::pages.your_resource.partials.tree', [
        'treeItems' => $rootItems,
        'depth' => 0,
    ])
@endsection

@push('scripts')
    <script>
        window.treeReorderUrl = "{{ route('your-package.your_resource.reorder') }}";
    </script>
@endpush
```

### The recursive tree partial

Each nesting level needs a `.sortable-level` wrapper (holding `data-parent-id`) around `.sortable-item` rows (each holding `data-item-id`), and recurses into children:

```blade
@php $depth = $depth ?? 0; @endphp

<div class="sortable-level" data-parent-id="{{ $treeItems->first()?->parent_id ?? '' }}">
    @foreach($treeItems as $item)
        <div class="sortable-item" data-item-id="{{ $item->id }}">
            {{-- row content --}}

            @if($item->children->isNotEmpty())
                @include('your-package::pages.your_resource.partials.tree', [
                    'treeItems' => $item->children,
                    'depth' => $depth + 1,
                ])
            @endif
        </div>
    @endforeach
</div>
```

The layout's built-in script (`crud/list-tree.blade.php`) initializes one Sortable.js instance per `.sortable-level` and, on drop, `POST`s to `window.treeReorderUrl` with:

```json
{ "ids": [3, 1, 2], "parent_id": 5 }
```

— the ordered ids of that level's direct children, and the parent id of that level (`null` for the root level).

### The controller endpoint

```php
public function reorder(ReorderRequest $request, ParentModel $parent): JsonResponse
{
    $this->authorize('update', $parent);

    foreach ($request->input('ids') as $position => $id) {
        // adjust the "position" column to whatever your model uses
        $this->repository->find($id)->update(['position' => $position]);
    }

    return response()->json(['success' => true]);
}
```

Each drag only reorders **one level at a time** — you don't need to handle re-parenting unless you explicitly support dragging items between levels.

A flat (non-tree) variant of the same idea is used for simple manual ordering, e.g. reordering products inside a range: see `ProductRangeController::reorderProducts()` / `updateProductsOrder()` in the main project for a pivot-table example (`updateExistingPivot($id, ['sort_order' => $position])`).

## See also

- [Layouts](templating/layouts.md) — `crud.list` vs `crud.list-tree`.
- [Resource Model](ResourceModel.md) — optional model interfaces.
