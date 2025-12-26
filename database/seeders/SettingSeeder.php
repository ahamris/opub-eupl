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
        Setting::setValue('site_title', 'Open Public', 'general');
        Setting::setValue('logo', '', 'general');
        Setting::setValue('favicon', '', 'general');

        // SMTP settings
        Setting::setValue('smtp_host', config('mail.mailers.smtp.host', ''), 'smtp');
        Setting::setValue('smtp_port', config('mail.mailers.smtp.port', '587'), 'smtp');
        Setting::setValue('smtp_username', config('mail.mailers.smtp.username', ''), 'smtp');
        Setting::setValue('smtp_password', config('mail.mailers.smtp.password', ''), 'smtp');
        Setting::setValue('smtp_encryption', config('mail.mailers.smtp.encryption', 'tls'), 'smtp');
        Setting::setValue('smtp_from_address', config('mail.from.address', ''), 'smtp');
        Setting::setValue('smtp_from_name', config('mail.from.name', ''), 'smtp');
    }
}
