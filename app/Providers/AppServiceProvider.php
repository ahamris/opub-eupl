<?php

namespace App\Providers;

use App\Models\Setting;
use App\Services\Typesense\TypesenseSearchService;
use App\View\Components\Navigation\Breadcrumbs;
use App\View\Components\UI\Accordion;
use App\View\Components\UI\AccordionItem;
use App\View\Components\UI\Alert;
use App\View\Components\UI\Avatar;
use App\View\Components\UI\Badge;
use App\View\Components\UI\Button;
use App\View\Components\UI\Card;
use App\View\Components\UI\Checkbox;
use App\View\Components\UI\ColorPicker;
use App\View\Components\UI\DatePicker;
use App\View\Components\UI\Divider;
use App\View\Components\UI\Dropdown;
use App\View\Components\UI\Input;
use App\View\Components\UI\Modal;
use App\View\Components\UI\Pagination;
use App\View\Components\UI\Radio;
use App\View\Components\UI\Select;
use App\View\Components\UI\TagInput;
use App\View\Components\UI\Textarea;
use App\View\Components\UI\Toast;
use App\View\Components\UI\Toggle;
use App\View\Components\UI\Tooltip;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register TypesenseSearchService as singleton for connection reuse
        $this->app->singleton(TypesenseSearchService::class, function ($app) {
            return new TypesenseSearchService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Configure mail settings from database
        $this->configureMailFromDatabase();

        // Form Components
        Blade::component(Button::class, 'button');
        Blade::component(Button::class, 'ui.button');
        Blade::component(Input::class, 'input');
        Blade::component(Input::class, 'ui.input');
        Blade::component(Textarea::class, 'textarea');
        Blade::component(Textarea::class, 'ui.textarea');
        Blade::component(Select::class, 'select');
        Blade::component(Select::class, 'ui.select');
        Blade::component(DatePicker::class, 'datepicker');
        Blade::component(DatePicker::class, 'ui.datepicker');
        Blade::component(ColorPicker::class, 'colorpicker');
        Blade::component(ColorPicker::class, 'ui.colorpicker');
        Blade::component(Checkbox::class, 'checkbox');
        Blade::component(Checkbox::class, 'ui.checkbox');
        Blade::component(Toggle::class, 'toggle');
        Blade::component(Toggle::class, 'ui.toggle');
        Blade::component(Radio::class, 'radio');
        Blade::component(Radio::class, 'ui.radio');
        Blade::component(TagInput::class, 'tag-input');
        Blade::component(TagInput::class, 'ui.tag-input');
        Blade::component(Dropdown::class, 'ui.dropdown');

        // Layout Components
        Blade::component(Card::class, 'ui.card');
        Blade::component(Modal::class, 'ui.modal');
        Blade::component(Accordion::class, 'accordion');
        Blade::component(Accordion::class, 'ui.accordion');
        Blade::component(AccordionItem::class, 'accordion.item');
        Blade::component(AccordionItem::class, 'ui.accordion-item');
        Blade::component(Divider::class, 'divider');
        Blade::component(Divider::class, 'ui.divider');

        // Feedback Components
        Blade::component(Alert::class, 'alert');
        Blade::component(Alert::class, 'ui.alert');
        Blade::component(Badge::class, 'badge');
        Blade::component(Badge::class, 'ui.badge');
        Blade::component(Avatar::class, 'avatar');
        Blade::component(Avatar::class, 'ui.avatar');
        Blade::component(Tooltip::class, 'ui.tooltip');
        Blade::component(Toast::class, 'ui.toast');

        // Navigation Components
        Blade::component(Pagination::class, 'ui.pagination');
        Blade::component(Breadcrumbs::class, 'navigation.breadcrumbs');
    }

    /**
     * Configure mail settings from database settings.
     */
    protected function configureMailFromDatabase(): void
    {
        // Check if settings table exists (might not exist during migrations)
        if (!Schema::hasTable('settings')) {
            return;
        }

        try {
            // Get SMTP settings from database
            $smtpHost = Setting::get('smtp_host');
            $smtpPort = Setting::get('smtp_port');
            $smtpUsername = Setting::get('smtp_username');
            $smtpPassword = Setting::get('smtp_password');
            $smtpEncryption = Setting::get('smtp_encryption');
            $fromAddress = Setting::get('smtp_from_address');
            $fromName = Setting::get('smtp_from_name');

            // Only override config if database values exist and are not empty
            if (!empty($smtpHost)) {
                config(['mail.mailers.smtp.host' => $smtpHost]);
            }

            if (!empty($smtpPort)) {
                config(['mail.mailers.smtp.port' => (int) $smtpPort]);
            }

            if ($smtpUsername !== null) {
                // Username can be empty string for some SMTP servers
                config(['mail.mailers.smtp.username' => $smtpUsername]);
            }

            if ($smtpPassword !== null) {
                // Password can be empty string for some SMTP servers
                config(['mail.mailers.smtp.password' => $smtpPassword]);
            }

            if (!empty($smtpEncryption)) {
                config(['mail.mailers.smtp.encryption' => $smtpEncryption]);
            }

            if (!empty($fromAddress)) {
                config(['mail.from.address' => $fromAddress]);
            }

            if (!empty($fromName)) {
                config(['mail.from.name' => $fromName]);
            }
        } catch (\Exception $e) {
            // Silently fail if settings can't be loaded (e.g., during migrations)
            // Fall back to config file values
        }
    }
}
