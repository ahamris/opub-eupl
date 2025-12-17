<?php

namespace App\Helpers;

use App\Models\Admin\AdminThemeSetting;

class ThemeHelper
{
    public static function getThemeCss(): string
    {
        $settings = AdminThemeSetting::getSettings();
        $baseColor = $settings->base_color;
        $accentColor = $settings->accent_color;

        // Handle "base" accent color - special case with zinc-800
        $isBaseColor = $accentColor === 'base';

        // Her renk için doğru shade'leri belirle
        $colorShades = $isBaseColor ? [
            'accent' => '800',
            'content_light' => '800',
            'content_dark' => 'white',
            'foreground_light' => 'white',
            'foreground_dark' => 'zinc-800',
        ] : self::getColorShades($accentColor);

        $css = '<style id="theme-variables">'."\n";

        // Base color mapping (zinc -> selected base color) - @theme block
        if ($baseColor !== 'zinc') {
            $css .= '@theme {'."\n";
            $shades = [50, 100, 200, 300, 400, 500, 600, 700, 800, 900, 950];
            foreach ($shades as $shade) {
                $css .= "    --color-zinc-{$shade}: var(--color-{$baseColor}-{$shade});\n";
            }
            $css .= '}'."\n\n";
        }

        // Accent color variables - @theme block (compile-time)
        $css .= '@theme {'."\n";
        if ($isBaseColor) {
            $css .= "    --color-accent: var(--color-zinc-800);\n";
            $css .= "    --color-accent-content: var(--color-zinc-800);\n";
            $css .= "    --color-accent-foreground: var(--color-white);\n";
        } else {
            $css .= "    --color-accent: var(--color-{$accentColor}-{$colorShades['accent']});\n";
            $css .= "    --color-accent-content: var(--color-{$accentColor}-{$colorShades['content_light']});\n";
            $foregroundLight = $colorShades['foreground_light'] === 'white'
                ? 'var(--color-white)'
                : "var(--color-{$colorShades['foreground_light']})";
            $css .= "    --color-accent-foreground: {$foregroundLight};\n";
        }
        $css .= '}'."\n\n";

        // Runtime overrides - :root for light mode (immediate effect)
        $css .= ':root {'."\n";

        // Base color mapping (zinc -> selected base color) - Runtime override
        if ($baseColor !== 'zinc') {
            $shades = [50, 100, 200, 300, 400, 500, 600, 700, 800, 900, 950];
            foreach ($shades as $shade) {
                $css .= "    --color-zinc-{$shade}: var(--color-{$baseColor}-{$shade});\n";
            }
        }

        // Accent color variables
        if ($isBaseColor) {
            $css .= "    --color-accent: var(--color-zinc-800);\n";
            $css .= "    --color-accent-content: var(--color-zinc-800);\n";
            $css .= "    --color-accent-foreground: var(--color-white);\n";
        } else {
            $css .= "    --color-accent: var(--color-{$accentColor}-{$colorShades['accent']});\n";
            $css .= "    --color-accent-content: var(--color-{$accentColor}-{$colorShades['content_light']});\n";
            $foregroundLight = $colorShades['foreground_light'] === 'white'
                ? 'var(--color-white)'
                : "var(--color-{$colorShades['foreground_light']})";
            $css .= "    --color-accent-foreground: {$foregroundLight};\n";
        }
        $css .= '}'."\n\n";

        // Dark mode overrides - @layer theme for base, .dark for others
        if ($isBaseColor) {
            $css .= '@layer theme {'."\n";
            $css .= '    .dark {'."\n";
            // Base color mapping for dark mode (zinc -> selected base color)
            if ($baseColor !== 'zinc') {
                $shades = [50, 100, 200, 300, 400, 500, 600, 700, 800, 900, 950];
                foreach ($shades as $shade) {
                    $css .= "        --color-zinc-{$shade}: var(--color-{$baseColor}-{$shade});\n";
                }
            }
            $css .= "        --color-accent: var(--color-white);\n";
            $css .= "        --color-accent-content: var(--color-white);\n";
            $css .= "        --color-accent-foreground: var(--color-zinc-950);\n";
            $css .= '    }'."\n";
            $css .= '}'."\n";
        } else {
            $css .= '.dark {'."\n";
            // Base color mapping for dark mode (zinc -> selected base color)
            if ($baseColor !== 'zinc') {
                $shades = [50, 100, 200, 300, 400, 500, 600, 700, 800, 900, 950];
                foreach ($shades as $shade) {
                    $css .= "    --color-zinc-{$shade}: var(--color-{$baseColor}-{$shade});\n";
                }
            }
            $css .= "    --color-accent: var(--color-{$accentColor}-{$colorShades['accent']});\n";
            $css .= "    --color-accent-content: var(--color-{$accentColor}-{$colorShades['content_dark']});\n";
            $foregroundDark = $colorShades['foreground_dark'] === 'white'
                ? 'var(--color-white)'
                : "var(--color-{$colorShades['foreground_dark']})";
            $css .= "    --color-accent-foreground: {$foregroundDark};\n";
            $css .= '}'."\n";
        }

        $css .= '</style>'."\n";

        return $css;
    }

    /**
     * Her renk için doğru shade'leri döndürür
     */
    private static function getColorShades(string $color): array
    {
        return match ($color) {
            // Base color (gray) - 500 (light), 400 (dark)
            'gray' => [
                'accent' => '500',
                'content_light' => '500',
                'content_dark' => '400',
                'foreground_light' => 'white',
                'foreground_dark' => 'white',
            ],
            // Base colors (grayscale) - zinc-800 kullanır
            'slate', 'zinc', 'neutral', 'stone' => [
                'accent' => '800',
                'content_light' => '800',
                'content_dark' => '800',
                'foreground_light' => 'white',
                'foreground_dark' => 'zinc-800',
            ],
            // Red - 500 (light), 400 (dark content)
            'red' => [
                'accent' => '500',
                'content_light' => '600',
                'content_dark' => '400',
                'foreground_light' => 'white',
                'foreground_dark' => 'white',
            ],
            // Orange - 500 (light), 400 (dark content), dark foreground 950
            'orange' => [
                'accent' => '500',
                'content_light' => '500',
                'content_dark' => '400',
                'foreground_light' => 'white',
                'foreground_dark' => 'orange-950',
            ],
            // Amber - 400 (light), 400 (dark content), foreground 950
            'amber' => [
                'accent' => '400',
                'content_light' => '400',
                'content_dark' => '400',
                'foreground_light' => 'amber-950',
                'foreground_dark' => 'amber-950',
            ],
            // Yellow - 400 (light), 400 (dark content), foreground 950
            'yellow' => [
                'accent' => '400',
                'content_light' => '400',
                'content_dark' => '400',
                'foreground_light' => 'yellow-950',
                'foreground_dark' => 'yellow-950',
            ],
            // Lime - 400 (light), 400 (dark content), foreground 900/950
            'lime' => [
                'accent' => '400',
                'content_light' => '400',
                'content_dark' => '400',
                'foreground_light' => 'lime-900',
                'foreground_dark' => 'lime-950',
            ],
            // Green - 600 (light), 400 (dark content)
            'green' => [
                'accent' => '600',
                'content_light' => '600',
                'content_dark' => '400',
                'foreground_light' => 'white',
                'foreground_dark' => 'white',
            ],
            // Emerald - 600 (light), 400 (dark content)
            'emerald' => [
                'accent' => '600',
                'content_light' => '600',
                'content_dark' => '400',
                'foreground_light' => 'white',
                'foreground_dark' => 'white',
            ],
            // Teal - 600 (light), 400 (dark content)
            'teal' => [
                'accent' => '600',
                'content_light' => '600',
                'content_dark' => '400',
                'foreground_light' => 'white',
                'foreground_dark' => 'white',
            ],
            // Cyan - 600 (light), 400 (dark content)
            'cyan' => [
                'accent' => '600',
                'content_light' => '600',
                'content_dark' => '400',
                'foreground_light' => 'white',
                'foreground_dark' => 'white',
            ],
            // Sky - 600 (light), 400 (dark content)
            'sky' => [
                'accent' => '600',
                'content_light' => '600',
                'content_dark' => '400',
                'foreground_light' => 'white',
                'foreground_dark' => 'white',
            ],
            // Blue - 500 (light), 400 (dark content)
            'blue' => [
                'accent' => '500',
                'content_light' => '600',
                'content_dark' => '400',
                'foreground_light' => 'white',
                'foreground_dark' => 'white',
            ],
            // Indigo - 500 (light), 300 (dark content)
            'indigo' => [
                'accent' => '500',
                'content_light' => '600',
                'content_dark' => '300',
                'foreground_light' => 'white',
                'foreground_dark' => 'white',
            ],
            // Violet - 500 (light), 400 (dark content)
            'violet' => [
                'accent' => '500',
                'content_light' => '500',
                'content_dark' => '400',
                'foreground_light' => 'white',
                'foreground_dark' => 'white',
            ],
            // Purple - 500 (light), 300 (dark content)
            'purple' => [
                'accent' => '500',
                'content_light' => '500',
                'content_dark' => '300',
                'foreground_light' => 'white',
                'foreground_dark' => 'white',
            ],
            // Fuchsia - 600 (light), 400 (dark content)
            'fuchsia' => [
                'accent' => '600',
                'content_light' => '600',
                'content_dark' => '400',
                'foreground_light' => 'white',
                'foreground_dark' => 'white',
            ],
            // Pink - 600 (light), 400 (dark content)
            'pink' => [
                'accent' => '600',
                'content_light' => '600',
                'content_dark' => '400',
                'foreground_light' => 'white',
                'foreground_dark' => 'white',
            ],
            // Rose - 500 (light), 400 (dark content)
            'rose' => [
                'accent' => '500',
                'content_light' => '500',
                'content_dark' => '400',
                'foreground_light' => 'white',
                'foreground_dark' => 'white',
            ],
            default => [
                'accent' => '500',
                'content_light' => '500',
                'content_dark' => '400',
                'foreground_light' => 'white',
                'foreground_dark' => 'white',
            ],
        };
    }

    public static function getAvailableBaseColors(): array
    {
        return [
            'slate' => 'Slate',
            'gray' => 'Gray',
            'zinc' => 'Zinc',
            'neutral' => 'Neutral',
            'stone' => 'Stone',
        ];
    }

    public static function getAvailableAccentColors(): array
    {
        return [
            'base' => 'Base',
            'red' => 'Red',
            'orange' => 'Orange',
            'amber' => 'Amber',
            'yellow' => 'Yellow',
            'lime' => 'Lime',
            'green' => 'Green',
            'emerald' => 'Emerald',
            'teal' => 'Teal',
            'cyan' => 'Cyan',
            'sky' => 'Sky',
            'blue' => 'Blue',
            'indigo' => 'Indigo',
            'violet' => 'Violet',
            'purple' => 'Purple',
            'fuchsia' => 'Fuchsia',
            'pink' => 'Pink',
            'rose' => 'Rose',
        ];
    }
}
