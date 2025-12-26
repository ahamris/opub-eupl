<?php

namespace App\View\Components\Front;

use App\Models\StaticPage;
use Illuminate\View\Component;
use Illuminate\View\View;

class GdprBanner extends Component
{
    public string $settingsLabel;

    public string $settingsUrl;

    public string $introTitle;

    public string $introSummary;

    public string $preferencesTitle;

    public string $preferencesSummary;

    public array $categories;

    public function __construct(
        ?string $settingsLabel = null,
        ?string $settingsUrl = null,
        ?string $introTitle = null,
        ?string $introSummary = null,
        ?string $preferencesTitle = null,
        ?string $preferencesSummary = null,
        ?array $categories = null,
    ) {
        // Check if banner is enabled
        $bannerEnabled = get_setting('cookie_banner_enabled', '1') == '1';
        
        // Only load settings if banner is enabled
        if (!$bannerEnabled) {
            $this->settingsLabel = '';
            $this->settingsUrl = '';
            $this->introTitle = '';
            $this->introSummary = '';
            $this->preferencesTitle = '';
            $this->preferencesSummary = '';
            $this->categories = [];
            return;
        }

        $this->settingsLabel = $settingsLabel ?? get_setting('cookie_settings_label', 'Cookie policy');
        
        // Get settings URL - check if it's a page selection
        $settingsPageType = get_setting('cookie_settings_page_type', 'custom');
        if ($settingsPageType === 'static') {
            $pageId = get_setting('cookie_settings_page_id');
            if ($pageId) {
                $static = StaticPage::find($pageId);
                $this->settingsUrl = $settingsUrl ?? ($static ? route('page.show', $static->slug) : get_setting('cookie_settings_url', 'javascript:void(0)'));
            } else {
                $this->settingsUrl = $settingsUrl ?? get_setting('cookie_settings_url', 'javascript:void(0)');
            }
        } else {
            $this->settingsUrl = $settingsUrl ?? get_setting('cookie_settings_url', 'javascript:void(0)');
        }

        $this->introTitle = $introTitle ?? get_setting('cookie_intro_title', 'We use cookies');
        $this->introSummary = $introSummary
            ?? get_setting(
                'cookie_intro_summary',
                'In addition to functional cookies we also place analytics and marketing cookies to understand usage, show relevant content and offer support. Only essential cookies are enabled by default.'
            );

        $this->preferencesTitle = $preferencesTitle
            ?? get_setting('cookie_preferences_title', 'Manage cookie preferences');

        $this->preferencesSummary = $preferencesSummary
            ?? get_setting(
                'cookie_preferences_summary',
                'Configure your cookie preferences below. Need more information? Read our policy.'
            );

        $this->categories = $categories ?? $this->defaultCategories();
    }

    public function render(): View
    {
        return view('components.front.gdpr-banner', [
            'settingsLabel' => $this->settingsLabel,
            'settingsUrl' => $this->settingsUrl,
            'introTitle' => $this->introTitle,
            'introSummary' => $this->introSummary,
            'preferencesTitle' => $this->preferencesTitle,
            'preferencesSummary' => $this->preferencesSummary,
            'categories' => $this->categories,
        ]);
    }

    protected function defaultCategories(): array
    {
        return [
            [
                'key' => 'functional',
                'label' => get_setting('cookie_category_functional_label', 'Functional cookies'),
                'description' => get_setting('cookie_category_functional_description', 'Required for core functionality of the website.'),
                'enabled' => true,
                'locked' => true,
            ],
            [
                'key' => 'analytics',
                'label' => get_setting('cookie_category_analytics_label', 'Analytics cookies'),
                'description' => get_setting('cookie_category_analytics_description', 'Help us measure usage and improve the experience.'),
                'enabled' => false,
                'locked' => false,
            ],
            [
                'key' => 'marketing',
                'label' => get_setting('cookie_category_marketing_label', 'Marketing cookies'),
                'description' => get_setting('cookie_category_marketing_description', 'Enable personalised content and external integrations.'),
                'enabled' => false,
                'locked' => false,
            ],
        ];
    }
}
