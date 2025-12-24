<?php

namespace Database\Seeders;

use App\Models\Admin\AdminMenu;
use App\Models\Admin\AdminMenuItem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AdminMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function () {
            $menu = AdminMenu::updateOrCreate(
                ['slug' => 'admin-main'],
                [
                    'name' => 'Admin Sidebar',
                    'description' => 'Admin panel standard menu',
                    'position' => 0,
                    'is_active' => true,
                ]
            );

            AdminMenuItem::where('admin_menu_id', $menu->id)->delete();

            $position = 0;

            $pagesSection = AdminMenuItem::create([
                'admin_menu_id' => $menu->id,
                'item_type' => 'section',
                'label' => 'PAGES',
                'slug' => Str::slug('PAGES'),
                'position' => $position++,
                'is_active' => true,
            ]);

            AdminMenuItem::create([
                'admin_menu_id' => $menu->id,
                'parent_id' => $pagesSection->id,
                'item_type' => 'link',
                'label' => 'Dashboard',
                'slug' => 'dashboard',
                'route_name' => 'admin.home',
                'icon' => 'chart-line',
                'position' => 0,
                'is_active' => true,
            ]);


            AdminMenuItem::create([
                'admin_menu_id' => $menu->id,
                'parent_id' => $pagesSection->id,
                'item_type' => 'link',
                'label' => 'Users',
                'slug' => 'users',
                'route_name' => 'admin.users.index',
                'icon' => 'users',
                'position' => 2,
                'is_active' => true,
            ]);

            AdminMenuItem::create([
                'admin_menu_id' => $menu->id,
                'parent_id' => $pagesSection->id,
                'item_type' => 'link',
                'label' => 'Contact Messages',
                'slug' => 'contact-messages',
                'route_name' => 'admin.contact-submissions.index',
                'icon' => 'envelope',
                'position' => 3,
                'is_active' => true,
            ]);

            // Content Section
            $contentSection = AdminMenuItem::create([
                'admin_menu_id' => $menu->id,
                'item_type' => 'section',
                'label' => 'CONTENT',
                'slug' => Str::slug('CONTENT'),
                'position' => $position++,
                'is_active' => true,
            ]);

            AdminMenuItem::create([
                'admin_menu_id' => $menu->id,
                'parent_id' => $contentSection->id,
                'item_type' => 'link',
                'label' => 'Blog Categories',
                'slug' => 'blog-categories',
                'route_name' => 'admin.content.blog-category.index',
                'icon' => 'folder',
                'position' => 0,
                'is_active' => true,
            ]);

            AdminMenuItem::create([
                'admin_menu_id' => $menu->id,
                'parent_id' => $contentSection->id,
                'item_type' => 'link',
                'label' => 'Blogs',
                'slug' => 'blogs',
                'route_name' => 'admin.content.blog.index',
                'icon' => 'newspaper',
                'position' => 1,
                'is_active' => true,
            ]);

            AdminMenuItem::create([
                'admin_menu_id' => $menu->id,
                'parent_id' => $contentSection->id,
                'item_type' => 'link',
                'label' => 'Static Pages',
                'slug' => 'static-pages',
                'route_name' => 'admin.content.static-page.index',
                'icon' => 'file-lines',
                'position' => 2,
                'is_active' => true,
            ]);

            $systemSection = AdminMenuItem::create([
                'admin_menu_id' => $menu->id,
                'item_type' => 'section',
                'label' => 'SYSTEM',
                'slug' => Str::slug('SYSTEM'),
                'position' => $position++,
                'is_active' => true,
            ]);

            // Menu Settings
            AdminMenuItem::create([
                'admin_menu_id' => $menu->id,
                'parent_id' => $systemSection->id,
                'item_type' => 'link',
                'label' => 'Menu Settings',
                'slug' => 'menu-settings',
                'route_name' => 'admin.settings.menu',
                'icon' => 'bars-progress',
                'position' => 0,
                'is_active' => true,
            ]);

            // Theme Settings
            AdminMenuItem::create([
                'admin_menu_id' => $menu->id,
                'parent_id' => $systemSection->id,
                'item_type' => 'link',
                'label' => 'Theme Settings',
                'slug' => 'theme-settings',
                'route_name' => 'admin.settings.theme',
                'icon' => 'palette',
                'position' => 1,
                'is_active' => true,
            ]);

            // Settings Section
            $settingsSection = AdminMenuItem::create([
                'admin_menu_id' => $menu->id,
                'item_type' => 'section',
                'label' => 'SETTINGS',
                'slug' => Str::slug('SETTINGS'),
                'position' => $position++,
                'is_active' => true,
            ]);

            // Site Settings (Dropdown/Parent)
            $siteSettings = AdminMenuItem::create([
                'admin_menu_id' => $menu->id,
                'parent_id' => $settingsSection->id,
                'item_type' => 'link',
                'label' => 'Site Settings',
                'slug' => 'site-settings',
                'route_name' => null, // Default route, will be overridden by children
                'icon' => 'gear',
                'position' => 0,
                'is_active' => true,
            ]);

            // Header Menu (Submenu)
            AdminMenuItem::create([
                'admin_menu_id' => $menu->id,
                'parent_id' => $siteSettings->id,
                'item_type' => 'link',
                'label' => 'Header Menu',
                'slug' => 'header-menu-settings',
                'route_name' => 'admin.settings.header-menu',
                'icon' => 'bars',
                'position' => 0,
                'is_active' => true,
            ]);

            // Footer Menu (Submenu)
            AdminMenuItem::create([
                'admin_menu_id' => $menu->id,
                'parent_id' => $siteSettings->id,
                'item_type' => 'link',
                'label' => 'Footer Menu',
                'slug' => 'footer-menu-settings',
                'route_name' => 'admin.settings.footer-menu',
                'icon' => 'list',
                'position' => 1,
                'is_active' => true,
            ]);
        });
    }

    protected function createChildren(AdminMenuItem $parent, array $children): void
    {
        foreach ($children as $index => $child) {
            AdminMenuItem::create([
                'admin_menu_id' => $parent->admin_menu_id,
                'parent_id' => $parent->id,
                'item_type' => 'link',
                'label' => $child['label'],
                'slug' => Str::slug($child['label']),
                'route_name' => $child['route'] ?? null,
                'icon' => $child['icon'] ?? null,
                'active_pattern' => $child['pattern'] ?? null,
                'badge_text' => $child['badge'] ?? null,
                'badge_color' => $child['badge_color'] ?? null,
                'position' => $index,
                'is_active' => true,
            ]);
        }
    }
}
