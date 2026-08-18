<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('description')->nullable();
            $table->timestamps();
        });

        // Seed default settings
        DB::table('settings')->insert([
            ['key' => 'cloudflare_worker_secret', 'value' => Str::random(64), 'description' => 'Secret for Cloudflare Worker authentication', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'rate_limit_generate', 'value' => '10', 'description' => 'Max email generates per IP per hour', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'default_expiry_minutes', 'value' => '10', 'description' => 'Default temporary email expiry in minutes', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'max_email_size_bytes', 'value' => '10485760', 'description' => 'Max incoming email size (10MB)', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'max_attachment_size_bytes', 'value' => '5242880', 'description' => 'Max attachment size (5MB)', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'polling_interval_seconds', 'value' => '5', 'description' => 'Frontend inbox polling interval', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
