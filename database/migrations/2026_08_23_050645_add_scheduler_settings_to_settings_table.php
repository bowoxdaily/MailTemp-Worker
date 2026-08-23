<?php

use App\Models\Setting;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Setting::create([
            'key' => 'cleanup_interval_minutes',
            'value' => '1',
            'description' => 'Email cleanup interval (minutes)',
        ]);
    }

    public function down(): void
    {
        Setting::where('key', 'cleanup_interval_minutes')->delete();
    }
};
