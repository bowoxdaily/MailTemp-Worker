<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('settings')->insert([
            [
                'key' => 'app_name',
                'value' => 'EmailTemp',
                'description' => 'Application name used in branding',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'app_logo_url',
                'value' => '',
                'description' => 'Branding Logo URL (Leave empty to use default SVG icon)',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'footer_copyright',
                'value' => '© 2026 EmailTemp. All rights reserved.',
                'description' => 'Footer copyright text',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    public function down(): void
    {
        DB::table('settings')
            ->whereIn('key', ['app_name', 'app_logo_url', 'footer_copyright'])
            ->delete();
    }
};
