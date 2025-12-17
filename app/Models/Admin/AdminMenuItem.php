<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Log;

class AdminMenuItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'admin_menu_id',
        'parent_id',
        'item_type',
        'label',
        'slug',
        'route_name',
        'route_parameters',
        'url',
        'icon',
        'badge_text',
        'badge_color',
        'active_pattern',
        'target',
        'position',
        'is_active',
        'options',
    ];

    protected function casts(): array
    {
        return [
            'route_parameters' => 'array',
            'options' => 'array',
            'position' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('position')->orderBy('id');
    }

    public function menu(): BelongsTo
    {
        return $this->belongsTo(AdminMenu::class, 'admin_menu_id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id')
            ->ordered();
    }

    public function childrenRecursive(): HasMany
    {
        return $this->children()->with('childrenRecursive');
    }

    public function isCurrentlyActive(): bool
    {
        if (! $this->is_active) {
            return false;
        }

        if ($this->active_pattern) {
            return request()->routeIs($this->active_pattern);
        }

        if ($this->route_name) {
            return request()->routeIs($this->route_name)
                || request()->routeIs($this->route_name.'.*');
        }

        return false;
    }

    public function shouldExpand(): bool
    {
        if ($this->isCurrentlyActive()) {
            return true;
        }

        if (! $this->relationLoaded('children')) {
            return false;
        }

        return $this->children->contains(function (self $child) {
            return $child->shouldExpand();
        });
    }

    public function resolvedRouteParameters(): array
    {
        return $this->route_parameters ?? [];
    }

    public function getResolvedBadgeTextAttribute(): ?string
    {
        // Eğer options içinde badge_callback varsa, dinamik hesapla
        $options = $this->options ?? [];

        if (isset($options['badge_callback']) && is_string($options['badge_callback'])) {
            // Callback string olarak tanımlanmış (örn: 'App\\Models\\Order::count')
            try {
                $callback = $options['badge_callback'];
                if (str_contains($callback, '::')) {
                    // Static method call: 'App\\Models\\Order::count'
                    [$class, $method] = explode('::', $callback, 2);
                    if (class_exists($class) && method_exists($class, $method)) {
                        $result = $class::$method();

                        return $result !== null ? (string) $result : null;
                    }
                } elseif (function_exists($callback)) {
                    // Function call
                    $result = $callback($this);

                    return $result !== null ? (string) $result : null;
                }
            } catch (\Exception $e) {
                // Callback hatası - statik badge_text kullan
            }
        }

        if (isset($options['badge_query'])) {
            // badge_query ile veritabanı sorgusu yap
            // Örnek: ['model' => 'App\\Models\\User', 'query' => 'count()']
            $query = $options['badge_query'];

            if (isset($query['model']) && ! empty($query['query']) && class_exists($query['model'])) {
                try {
                    $modelClass = $query['model'];
                    $queryString = trim($query['query']);

                    // Güvenlik: Sadece Model class'larına izin ver
                    if (! is_subclass_of($modelClass, \Illuminate\Database\Eloquent\Model::class)) {
                        return $this->badge_text;
                    }

                    // Whitelist yaklaşımı: Sadece read-only method'lara izin ver
                    $allowedMethods = [
                        // Aggregation methods
                        'count', 'sum', 'avg', 'max', 'min',
                        // Query builder methods (read-only)
                        'where', 'whereIn', 'whereNotIn', 'whereNull', 'whereNotNull',
                        'whereBetween', 'whereNotBetween', 'whereDate', 'whereTime', 'whereDay',
                        'whereMonth', 'whereYear', 'whereColumn', 'whereExists', 'whereNotExists',
                        'orWhere', 'orWhereIn', 'orWhereNotIn', 'orWhereNull', 'orWhereNotNull',
                        // Ordering and limiting (read-only)
                        'orderBy', 'orderByDesc', 'latest', 'oldest', 'inRandomOrder',
                        'limit', 'take', 'skip', 'offset',
                        // Grouping (read-only)
                        'groupBy', 'having', 'havingRaw',
                        // Select (read-only)
                        'select', 'addSelect', 'distinct',
                        // Joins (read-only)
                        'join', 'leftJoin', 'rightJoin', 'crossJoin',
                        // Other read-only methods
                        'first', 'find', 'findOrFail', 'get', 'pluck', 'value', 'exists', 'doesntExist',
                    ];

                    // Yazma işlemlerini engelle (blacklist)
                    $forbiddenMethods = [
                        'delete', 'update', 'save', 'create', 'insert', 'upsert',
                        'forceDelete', 'restore', 'increment', 'decrement',
                        'touch', 'push', 'sync', 'attach', 'detach',
                    ];

                    // Query string'i parse et ve method'ları kontrol et
                    // Method chain'i -> ile ayır
                    $tokens = preg_split('/->/', $queryString);

                    foreach ($tokens as $token) {
                        // Method adını çıkar (parantez içindeki parametreleri kaldır)
                        $method = preg_replace('/\(.*\)/', '', trim($token));
                        $method = trim($method);

                        // Boş token'ları atla
                        if (empty($method)) {
                            continue;
                        }

                        // Blacklist kontrolü (yazma işlemleri)
                        if (in_array($method, $forbiddenMethods, true)) {
                            if (config('app.debug')) {
                                Log::warning('Badge query contains forbidden method: '.$method, [
                                    'model' => $modelClass,
                                    'query' => $queryString,
                                ]);
                            }

                            return $this->badge_text;
                        }

                        // Whitelist kontrolü (sadece izin verilen method'lar)
                        if (! in_array($method, $allowedMethods, true)) {
                            if (config('app.debug')) {
                                Log::warning('Badge query contains non-whitelisted method: '.$method, [
                                    'model' => $modelClass,
                                    'query' => $queryString,
                                ]);
                            }

                            return $this->badge_text;
                        }
                    }

                    // Ek güvenlik: Tehlikeli karakterleri filtrele
                    $allowedChars = '/^[a-zA-Z0-9_()->\'\",\s:>-]+$/';
                    if (! preg_match($allowedChars, $queryString)) {
                        if (config('app.debug')) {
                            Log::warning('Badge query contains invalid characters', [
                                'model' => $modelClass,
                                'query' => $queryString,
                            ]);
                        }

                        return $this->badge_text;
                    }

                    // Model::{query} şeklinde çalıştır
                    // Örnek: User::count()
                    // Örnek: User::where('status', 'pending')->count()
                    $code = $modelClass.'::'.$queryString;

                    // eval() kullan - güvenlik kontrolleri yapıldı
                    // Whitelist ve blacklist kontrolleri ile sadece güvenli method'lara izin veriliyor
                    $result = eval("return {$code};");

                    return $result !== null ? (string) $result : null;
                } catch (\Exception $e) {
                    // Query hatası - statik badge_text kullan
                    // Log hatayı (production'da)
                    if (config('app.debug')) {
                        Log::error('Badge query error: '.$e->getMessage(), [
                            'model' => $query['model'] ?? null,
                            'query' => $query['query'] ?? null,
                        ]);
                    }

                    return $this->badge_text;
                }
            }
        }

        // Statik badge_text kullan (cache'e dahil değil, her render'da okunur)
        return $this->badge_text;
    }
}
