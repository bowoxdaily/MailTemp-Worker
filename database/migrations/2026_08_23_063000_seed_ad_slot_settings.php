<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $now = now();

        DB::table('settings')->insertOrIgnore([
            ['key' => 'ad_header', 'value' => '', 'description' => 'Ad script displayed in the header area', 'created_at' => $now, 'updated_at' => $now],
            ['key' => 'ad_generator', 'value' => '', 'description' => 'Ad script displayed between the generator and inbox', 'created_at' => $now, 'updated_at' => $now],
            ['key' => 'ad_inbox', 'value' => '', 'description' => 'Ad script displayed below the inbox', 'created_at' => $now, 'updated_at' => $now],
            ['key' => 'ad_footer', 'value' => '', 'description' => 'Ad script displayed in the footer area', 'created_at' => $now, 'updated_at' => $now],
        ]);
    }

    public function down(): void
    {
        DB::table('settings')->whereIn('key', [
            'ad_header',
            'ad_generator',
            'ad_inbox',
            'ad_footer',
        ])->delete();
    }
};
