<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('temporary_emails', function (Blueprint $table) {
            $table->id();
            $table->string('email_address')->unique();
            $table->foreignId('domain_id')->constrained()->cascadeOnDelete();
            $table->string('session_id')->index();
            $table->timestamp('expires_at')->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('temporary_emails');
    }
};
