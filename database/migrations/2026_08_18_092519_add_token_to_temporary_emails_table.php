<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('temporary_emails', function (Blueprint $table) {
            $table->string('token', 64)->unique()->after('session_id');
            $table->enum('status', ['active', 'expired', 'deleted'])->default('active')->after('token');
        });
    }

    public function down(): void
    {
        Schema::table('temporary_emails', function (Blueprint $table) {
            $table->dropColumn(['token', 'status']);
        });
    }
};
