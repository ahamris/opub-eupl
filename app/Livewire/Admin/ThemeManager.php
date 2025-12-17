<?php

namespace App\Livewire\Admin;

use App\Helpers\ThemeHelper;
use App\Models\Admin\AdminThemeSetting;
use Livewire\Component;

class ThemeManager extends Component
{
    public string $baseColor = 'zinc';

    public string $accentColor = 'indigo';

    public function mount(): void
    {
        $settings = AdminThemeSetting::getSettings();
        $this->baseColor = $settings->base_color;
        $this->accentColor = $settings->accent_color;
    }

    public function save(): void
    {
        $this->validate([
            'baseColor' => ['required', 'string', 'in:'.implode(',', array_keys(ThemeHelper::getAvailableBaseColors()))],
            'accentColor' => ['required', 'string', 'in:'.implode(',', array_keys(ThemeHelper::getAvailableAccentColors()))],
        ]);

        // Save "base" as-is - ThemeHelper will handle it
        // Cache will be automatically cleared by model's booted() method
        $settings = AdminThemeSetting::getSettings();
        $settings->update([
            'base_color' => $this->baseColor,
            'accent_color' => $this->accentColor,
        ]);

        $this->dispatch('notify', type: 'success', message: 'Theme generated successfully!');
        $this->dispatch('theme-updated');
    }

    public function getAvailableBaseColorsProperty(): array
    {
        return ThemeHelper::getAvailableBaseColors();
    }

    public function getAvailableAccentColorsProperty(): array
    {
        return ThemeHelper::getAvailableAccentColors();
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        return view('livewire.admin.theme-manager');
    }
}
