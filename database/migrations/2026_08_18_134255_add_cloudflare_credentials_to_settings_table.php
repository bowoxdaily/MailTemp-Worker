<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('settings')->insert([
            [
                'key' => 'cloudflare_api_token',
                'value' => '',
                'description' => 'Cloudflare API Token for Worker Deployment',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'cloudflare_account_id',
                'value' => '',
                'description' => 'Cloudflare Account ID',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('settings')
            ->whereIn('key', ['cloudflare_api_token', 'cloudflare_account_id'])
            ->delete();
    }
};
