<?php

namespace App\Livewire\Admin;

use App\Models\Admin\AdminMenu;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Livewire\Attributes\On;
use Livewire\Component;

class Sidebar extends Component
{
    #[On('refresh-sidebar')]
    public function refresh(): void
    {
        // Cache'i temizle ve component'i yeniden render et
        Cache::forget('admin-menu:sidebar');
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        // Cache süresiz - sadece MenuManager'da menü değiştiğinde cache temizleniyor
        // NOT: Badge değerleri cache'e dahil değil, her render'da dinamik olarak hesaplanıyor
        $menu = cache()->rememberForever('admin-menu:sidebar', function () {
            return AdminMenu::query()
                ->active()
                ->where('slug', 'admin-main')
                ->with(['items' => fn ($query) => $query->ordered()->with('childrenRecursive')])
                ->first();
        });

        $filterActiveItems = static function (Collection $items) use (&$filterActiveItems) {
            return $items
                ->filter(fn ($item) => $item->is_active)
                ->map(function ($item) use (&$filterActiveItems) {
                    if ($item->relationLoaded('childrenRecursive')) {
                        $filteredChildren = $filterActiveItems($item->childrenRecursive);

                        $item->setRelation('childrenRecursive', $filteredChildren);
                        $item->setRelation('children', $filteredChildren);
                    }

                    return $item;
                })
                ->values();
        };

        $menuItems = $menu
            ? $filterActiveItems($menu->items)
            : collect();

        return view('livewire.admin.sidebar', [
            'menu' => $menu,
            'menuItems' => $menuItems,
        ]);
    }
}
