<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // General settings
        Setting::set('site_title', 'Open Public', 'general');
        Setting::set('logo', '', 'general');
        Setting::set('favicon', '', 'general');

        // SMTP settings
        Setting::set('smtp_host', config('mail.mailers.smtp.host', ''), 'smtp');
        Setting::set('smtp_port', config('mail.mailers.smtp.port', '587'), 'smtp');
        Setting::set('smtp_username', config('mail.mailers.smtp.username', ''), 'smtp');
        Setting::set('smtp_password', config('mail.mailers.smtp.password', ''), 'smtp');
        Setting::set('smtp_encryption', config('mail.mailers.smtp.encryption', 'tls'), 'smtp');
        Setting::set('smtp_from_address', config('mail.from.address', ''), 'smtp');
        Setting::set('smtp_from_name', config('mail.from.name', ''), 'smtp');
    }
}
