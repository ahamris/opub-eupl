<?php

namespace App\Livewire\Admin;

use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Route;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Table extends Component
{
    use WithPagination;

    // Component Configuration Props
    public ?string $modelClass = null; // Resolved model class (public for Livewire serialization)

    public ?string $originalResource = null; // Store original resource for re-resolution after Livewire serialization

    protected array $resources = [];

    public string $resource; // Resource key (e.g., 'users', 'orders') - deprecated, use model prop instead

    public array $columns = []; // Column definitions

    public array $searchFields = []; // Fields to search in

    public array $sortableFields = []; // Fields that can be sorted

    public bool $showActions = true; // Show actions column

    public array $actions = ['view', 'edit', 'delete']; // Available actions

    public ?string $routePrefix = null; // Route prefix

    public string $searchPlaceholder = 'Search...';

    public int $paginate = 10;

    public bool $showCheckbox = true; // Show checkbox column

    public bool $showBulkDelete = true; // Show bulk delete button

    protected $providedItems = null; // External items (Paginator or Collection)

    public bool $isExternal = false; // Track if items are external

    public ?string $customActionsView = null; // Custom actions view path

    #[Url(as: 'search')]
    public string $search = '';

    #[Url(as: 'sort')]
    public string $sortField = 'created_at';

    #[Url(as: 'direction')]
    public string $sortDirection = 'desc';

    public array $selected = [];

    public bool $selectAll = false;

    public function mount(
        string|Model $resource,
        array $columns,
        $items = null,
        array $searchFields = [],
        array $sortableFields = [],
        bool $showActions = true,
        array $actions = ['view', 'edit', 'delete'],
        ?string $routePrefix = null,
        string $searchPlaceholder = 'Search...',
        int $paginate = 10,
        bool $showCheckbox = true,
        bool $showBulkDelete = true,
        ?string $customActionsView = null,
    ): void {
        // Store original resource for re-resolution after Livewire serialization
        if ($resource instanceof Model) {
            $this->originalResource = $resource::class;
        } elseif (is_string($resource)) {
            $this->originalResource = $resource;
        }

        // Resolve model class from resource
        if ($resource instanceof Model) {
            // Direct model instance provided
            $this->modelClass = $resource::class;
            $this->resource = $this->resolveResourceKeyFromModel($resource::class);
        } elseif (class_exists($resource)) {
            // Model class string provided (e.g., \App\Models\Product::class)
            $this->modelClass = $resource;
            $this->resource = $this->resolveResourceKeyFromModel($resource);
        } elseif (array_key_exists($resource, $this->resources)) {
            // Legacy: Resource key provided (e.g., 'users', 'orders')
            $this->resource = $resource;
            $this->modelClass = $this->resources[$resource];
        } else {
            // Try convention-based auto-resolve: App\Models\{Resource}
            // 'products' -> 'App\Models\Product'
            // 'orders' -> 'App\Models\Order'
            $singular = str($resource)->singular()->toString();
            $modelName = str($singular)->camel()->ucfirst()->toString();
            $modelClass = "\\App\\Models\\{$modelName}";

            if (class_exists($modelClass) && is_subclass_of($modelClass, Model::class)) {
                $this->modelClass = $modelClass;
                $this->resource = $resource;
            } else {
                abort(403, "Invalid resource specified: {$resource}. Provide a model class (e.g., \\App\\Models\\Product::class) or register it in \$resources array.");
            }
        }

        $this->providedItems = $items;
        $this->isExternal = ! is_null($items);

        // Normalize columns: support both simple string array and detailed array
        $this->columns = $this->normalizeColumns($columns);

        // Auto-extract search and sortable fields if not provided
        $this->searchFields = $searchFields ?: $this->extractSearchableFields($this->columns);
        $this->sortableFields = $sortableFields ?: $this->extractSortableFields($this->columns);
        $this->showActions = $showActions;
        $this->actions = $actions;
        $this->routePrefix = $routePrefix;
        $this->searchPlaceholder = $searchPlaceholder;
        $this->paginate = $paginate;
        $this->showCheckbox = $showCheckbox;
        $this->showBulkDelete = $showBulkDelete;
        $this->customActionsView = $customActionsView;

        // Set default sort field if not provided
        if (! in_array($this->sortField, $this->sortableFields)) {
            $this->sortField = $this->sortableFields[0] ?? 'id';
        }
    }

    protected function getModelInstance(): Model
    {
        // Re-resolve model class if not set (e.g. on subsequent requests after Livewire serialization)
        if (empty($this->modelClass)) {
            // First priority: Use originalResource (most reliable - stored during mount)
            if (!empty($this->originalResource) && is_string($this->originalResource) && class_exists($this->originalResource) && is_subclass_of($this->originalResource, Model::class)) {
                $this->modelClass = $this->originalResource;
            }
            // Second: Try if resource is already a model class string
            elseif (!empty($this->resource) && is_string($this->resource) && class_exists($this->resource) && is_subclass_of($this->resource, Model::class)) {
                $this->modelClass = $this->resource;
            }
            // Third: Try resources array (legacy)
            elseif (!empty($this->resource) && array_key_exists($this->resource, $this->resources)) {
                $this->modelClass = $this->resources[$this->resource];
            }
            // Fourth: Try convention-based auto-resolve: App\Models\{Resource}
            elseif (!empty($this->resource)) {
                $singular = str($this->resource)->singular()->toString();
                $modelName = str($singular)->camel()->ucfirst()->toString();
                $modelClass = "\\App\\Models\\{$modelName}";

                if (class_exists($modelClass) && is_subclass_of($modelClass, Model::class)) {
                    $this->modelClass = $modelClass;
                } else {
                    \Log::error('getModelInstance: Could not resolve model class', [
                        'originalResource' => $this->originalResource ?? 'not set',
                        'resource' => $this->resource ?? 'not set',
                        'attempted_class' => $modelClass,
                    ]);
                    abort(403, 'Invalid resource.');
                }
            } else {
                \Log::error('getModelInstance: No resource or modelClass available', [
                    'originalResource' => $this->originalResource ?? 'not set',
                    'resource' => $this->resource ?? 'not set',
                    'modelClass' => $this->modelClass ?? 'not set',
                ]);
                abort(403, 'Invalid resource.');
            }
        }

        if (empty($this->modelClass) || !class_exists($this->modelClass)) {
            \Log::error('getModelInstance: modelClass is invalid', [
                'modelClass' => $this->modelClass ?? 'not set',
            ]);
            abort(403, 'Invalid resource.');
        }

        return new $this->modelClass;
    }

    protected function resolveResourceKeyFromModel(string $modelClass): string
    {
        // Try to find in resources array first
        $key = array_search($modelClass, $this->resources, true);
        if ($key !== false) {
            return $key;
        }

        // Generate resource key from model class name
        // \App\Models\Product -> product
        // \App\Models\Order -> order
        $className = class_basename($modelClass);

        return str($className)->lower()->plural()->toString();
    }

    /**
     * Normalize columns array to support both simple and detailed formats.
     *
     * Simple format: ['id', 'name', 'email']
     * Key-value format: ['id' => 'ID', 'name' => 'Name'] (legacy)
     * Detailed format: [['key' => 'id', 'label' => 'ID', 'sortable' => true], ...]
     * Mixed format: ['id', ['key' => 'is_active', 'type' => 'toggle'], 'email']
     */
    protected function normalizeColumns(array $columns): array
    {
        $normalized = [];

        // Check if it's key-value format (legacy): ['field' => 'label', ...]
        $isKeyValueFormat = false;
        foreach ($columns as $key => $value) {
            if (is_string($key) && (is_string($value) || is_array($value))) {
                $isKeyValueFormat = true;
                break;
            }
        }

        if ($isKeyValueFormat) {
            // Legacy key-value format: ['field' => 'label'] or ['field' => ['label' => '...', 'sortable' => true]]
            foreach ($columns as $field => $column) {
                if (is_string($column)) {
                    // Simple: ['id' => 'ID']
                    $normalized[] = [
                        'key' => $field,
                        'label' => $column,
                        'sortable' => true,
                    ];
                } elseif (is_array($column)) {
                    // Detailed: ['id' => ['label' => 'ID', 'sortable' => true]]
                    $normalized[] = [
                        'key' => $field,
                        'label' => $column['label'] ?? str($field)->replace('_', ' ')->ucfirst()->toString(),
                        'sortable' => $column['sortable'] ?? true,
                        'type' => $column['type'] ?? null,
                        'format' => $column['format'] ?? null,
                    ];
                }
            }
        } else {
            // New format: ['id', 'name'] or [['key' => 'id', 'label' => 'ID'], ...]
            foreach ($columns as $index => $column) {
                if (is_string($column)) {
                    // Simple string format: 'id' -> ['key' => 'id', 'label' => 'ID', 'sortable' => true]
                    $normalized[] = [
                        'key' => $column,
                        'label' => str($column)->replace('_', ' ')->ucfirst()->toString(),
                        'sortable' => true,
                    ];
                } elseif (is_array($column)) {
                    // Already in detailed format, ensure 'key' exists
                    if (! isset($column['key'])) {
                        $column['key'] = $index;
                    }
                    // Set default label if not provided
                    if (! isset($column['label'])) {
                        $column['label'] = str($column['key'])->replace('_', ' ')->ucfirst()->toString();
                    }
                    // Default sortable to true if not specified
                    if (! isset($column['sortable'])) {
                        $column['sortable'] = true;
                    }
                    $normalized[] = $column;
                }
            }
        }

        return $normalized;
    }

    /**
     * Extract searchable fields from normalized columns.
     */
    protected function extractSearchableFields(array $columns): array
    {
        $fields = [];
        foreach ($columns as $column) {
            $key = is_array($column) ? ($column['key'] ?? null) : $column;
            $type = is_array($column) ? ($column['type'] ?? null) : null;
            // Exclude toggle and custom type columns from search
            if ($key && $type !== 'toggle' && $type !== 'custom') {
                $fields[] = $key;
            }
        }

        return $fields;
    }

    /**
     * Extract sortable fields from normalized columns.
     */
    protected function extractSortableFields(array $columns): array
    {
        $fields = [];
        foreach ($columns as $column) {
            $key = is_array($column) ? ($column['key'] ?? null) : $column;
            $sortable = is_array($column) ? ($column['sortable'] ?? true) : true;
            if ($key && $sortable) {
                $fields[] = $key;
            }
        }

        return $fields;
    }

    #[Computed]
    public function items()
    {
        // If items are passed from parent (Controller mode), use them directly
        if ($this->providedItems) {
            return $this->providedItems;
        }

        $query = $this->getModelInstance()->query();

        // Eager load relationships dynamically based on column keys
        // Supports nested relations: 'user.role.name' -> loads 'user.role'
        $relationships = [];
        foreach ($this->columns as $column) {
            $key = is_array($column) ? ($column['key'] ?? null) : null;

            // If key contains a dot (e.g., category.name, user.role.name)
            if ($key && str($key)->contains('.')) {
                // Get the relation path excluding the last part
                // Example: 'user.role.name' -> 'user.role'
                // Example: 'user.name' -> 'user'
                $relationPath = str($key)->beforeLast('.')->toString();
                $relationships[] = $relationPath;
            }
        }

        // Load unique relationships
        if (! blank($relationships)) {
            $query->with(array_unique($relationships));
        }

        // Apply search
        if ($this->search && ! blank($this->searchFields)) {
            $query->where(function (Builder $q) {
                foreach ($this->searchFields as $index => $field) {
                    // Check if field is a relationship field (e.g., 'user.name')
                    if (str($field)->contains('.')) {
                        $parts = explode('.', $field, 2);
                        if (count($parts) === 2) {
                            [$relation, $relationField] = $parts;
                            if ($index === 0) {
                                $q->whereHas($relation, function ($subQuery) use ($relationField) {
                                    $subQuery->where($relationField, 'like', "%{$this->search}%");
                                });
                            } else {
                                $q->orWhereHas($relation, function ($subQuery) use ($relationField) {
                                    $subQuery->where($relationField, 'like', "%{$this->search}%");
                                });
                            }
                        }
                    } else {
                        // Direct field search
                        if ($index === 0) {
                            $q->where($field, 'like', "%{$this->search}%");
                        } else {
                            $q->orWhere($field, 'like', "%{$this->search}%");
                        }
                    }
                }
            });
        }

        // Apply sorting
        if (in_array($this->sortField, $this->sortableFields)) {
            // Handle relationship sorting
            if (str($this->sortField)->contains('.')) {
                $parts = explode('.', $this->sortField, 2);
                if (count($parts) === 2) {
                    [$relation, $column] = $parts;
                    $modelTable = $this->getModelInstance()->getTable();
                    $relationTable = $relation === 'user' ? 'users' : $relation.'s';
                    $foreignKey = $relation === 'user' ? 'user_id' : $relation.'_id';
                    $query->join($relationTable, $modelTable.'.'.$foreignKey, '=', $relationTable.'.id')
                        ->orderBy($relationTable.'.'.$column, $this->sortDirection)
                        ->select($modelTable.'.*');
                } else {
                    $query->orderBy($this->sortField, $this->sortDirection);
                }
            } else {
                $query->orderBy($this->sortField, $this->sortDirection);
            }
        }

        return $query->paginate($this->paginate);
    }

    public function sortBy(string $field): void
    {
        if (! in_array($field, $this->sortableFields)) {
            return;
        }

        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }

        $this->resetPage();
    }

    public function updatedSelectAll(bool $value): void
    {
        if ($value) {
            $this->selected = $this->items->pluck('id')->toArray();
        } else {
            $this->selected = [];
        }
    }

    public function updatedSelected(): void
    {
        $this->selectAll = count($this->selected) > 0 && count($this->selected) === $this->items->count();
    }

    public function deleteSelected(): void
    {
        if (blank($this->selected) || ! $this->showBulkDelete) {
            return;
        }

        $modelInstance = $this->getModelInstance();

        // Get items to delete - we'll delete them one by one to trigger model events
        // This ensures observers, deleting/deleted events, and related cleanup run properly
        $items = $modelInstance->query()->whereIn('id', $this->selected)->get();

        $count = 0;
        foreach ($items as $item) {
            $item->delete(); // Triggers model events (deleting, deleted) and observers
            $count++;
        }

        $this->selected = [];
        $this->selectAll = false;

        $modelName = class_basename($this->modelClass);
        $message = "{$count} ".str($modelName)->lower()->toString().($count > 1 ? 's' : '').' deleted successfully.';
        $this->dispatch('notify', type: 'success', message: $message);
    }

    public function delete(int $id): void
    {
        try {
            if (! in_array('delete', $this->actions)) {
                \Log::warning('Delete action rejected: Action not in allowed list', [
                    'id' => $id,
                    'model' => $this->modelClass ?? 'unknown',
                    'allowed_actions' => $this->actions,
                ]);
                return;
            }

            $modelInstance = $this->getModelInstance();
            $item = $modelInstance->query()->findOrFail($id);
            $itemName = $item->name ?? $item->email ?? $item->title ?? "#{$id}";
            $modelName = class_basename($this->modelClass);

            $item->delete();
            $this->selected = array_filter($this->selected, fn ($item) => $item !== $id);

            $message = str($modelName)->ucfirst()->toString()." '{$itemName}' deleted successfully.";
            $this->dispatch('notify', type: 'success', message: $message);
        } catch (\Exception $e) {
            \Log::error('Delete action error', [
                'id' => $id,
                'model' => $this->modelClass ?? 'unknown',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }

    public function getRoute(string $action, int $id): ?string
    {
        if (! $this->routePrefix) {
            return null;
        }

        $routeName = "{$this->routePrefix}.{$action}";

        if (! Route::has($routeName)) {
            return null;
        }

        return route($routeName, $id);
    }

    public function view(int $id): ?string
    {
        // For file models, return Storage URL instead of route
        $modelInstance = $this->getModelInstance();
        $item = $modelInstance->query()->findOrFail($id);

        if (isset($item->file_path) && $item->file_path) {
            return \Illuminate\Support\Facades\Storage::url($item->file_path);
        }

        return $this->getRoute('show', $id);
    }

    public function edit(int $id): ?string
    {
        return $this->getRoute('edit', $id);
    }

    public function deleteRoute(int $id): ?string
    {
        return $this->getRoute('destroy', $id);
    }

    public function download(int $id): ?string
    {
        $modelInstance = $this->getModelInstance();
        $item = $modelInstance->query()->findOrFail($id);

        // Check if item has file_path (for file models)
        if (isset($item->file_path) && $item->file_path) {
            return \Illuminate\Support\Facades\Storage::url($item->file_path);
        }

        return null;
    }

    public function getColumnLabel(string $field): string
    {
        // Columns are now always normalized to array format
        foreach ($this->columns as $column) {
            if (($column['key'] ?? null) === $field) {
                return $column['label'] ?? str($field)->replace('_', ' ')->ucfirst()->toString();
            }
        }

        return str($field)->replace('_', ' ')->ucfirst()->toString();
    }

    public function isColumnSortable(string $field): bool
    {
        // Columns are now always normalized to array format
        foreach ($this->columns as $column) {
            if (($column['key'] ?? null) === $field) {
                return $column['sortable'] ?? false;
            }
        }

        return in_array($field, $this->sortableFields);
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function toggleField(int $id, string $field): void
    {
        try {
            // Security: Only allow toggling fields that are explicitly defined as toggle type in columns
            $columnType = $this->getColumnType($field);
            if ($columnType !== 'toggle') {
                \Log::warning('Toggle field rejected: Invalid column type', [
                    'field' => $field,
                    'column_type' => $columnType,
                    'model' => $this->modelClass ?? 'unknown',
                    'columns' => $this->columns,
                ]);
                abort(403, 'Invalid field for toggle operation.');
            }

            // Security: Validate field exists in columns to prevent mass assignment
            $allowedFields = [];
            foreach ($this->columns as $column) {
                if (($column['type'] ?? null) === 'toggle') {
                    $allowedFields[] = $column['key'] ?? null;
                }
            }

            if (! in_array($field, $allowedFields, true)) {
                \Log::warning('Toggle field rejected: Field not in allowed list', [
                    'field' => $field,
                    'allowed_fields' => $allowedFields,
                    'model' => $this->modelClass ?? 'unknown',
                ]);
                abort(403, 'Field not allowed for toggle operation.');
            }

            $modelInstance = $this->getModelInstance();
            $item = $modelInstance->query()->findOrFail($id);

            // Security: Ensure field exists on model
            // Check if field exists in attributes or is a cast/accessor
            $hasField = array_key_exists($field, $item->getAttributes()) 
                || (method_exists($item, 'hasAttribute') && $item->hasAttribute($field))
                || (method_exists($item, 'hasCast') && $item->hasCast($field))
                || property_exists($item, $field);
            
            if (! $hasField) {
                \Log::warning('Toggle field rejected: Field not found on model', [
                    'field' => $field,
                    'model' => $this->modelClass ?? 'unknown',
                    'item_id' => $id,
                    'attributes' => array_keys($item->getAttributes()),
                    'casts' => method_exists($item, 'getCasts') ? array_keys($item->getCasts()) : [],
                ]);
                abort(404, 'Field not found on model.');
            }

            $oldValue = $item->$field;
            $newValue = ! $oldValue;

            // Security: Use fillable/guarded protection - only update the specific allowed field
            $item->update([$field => $newValue]);

            // Get field label for toast message
            $fieldLabel = $this->getColumnLabel($field);
            $modelName = class_basename($this->modelClass);
            $itemName = $item->name ?? $item->email ?? "#{$item->id}";

            $status = $newValue ? 'activated' : 'deactivated';
            $variant = $newValue ? 'success' : 'warning';
            $message = str($modelName)->ucfirst()->toString()." '{$itemName}' {$status} successfully.";

            $this->dispatch('notify', type: $variant, message: $message);
        } catch (\Exception $e) {
            \Log::error('Toggle field error', [
                'field' => $field,
                'id' => $id,
                'model' => $this->modelClass ?? 'unknown',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }

    public function getColumnType(string $field): ?string
    {
        foreach ($this->columns as $column) {
            // Columns are now always normalized to array format
            $columnField = $column['key'] ?? null;
            if ($columnField === $field && isset($column['type'])) {
                return $column['type'];
            }
        }

        return null;
    }

    public function render(): View
    {
        return view('livewire.admin.table');
    }
}
