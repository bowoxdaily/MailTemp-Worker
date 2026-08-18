<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('emails', function (Blueprint $table) {
            $table->id();
            $table->foreignId('temporary_email_id')->constrained()->cascadeOnDelete();
            $table->string('from_address');
            $table->string('from_name')->nullable();
            $table->string('subject');
            $table->longText('body_html')->nullable();
            $table->longText('body_text')->nullable();
            $table->unsignedInteger('size_bytes')->default(0);
            $table->boolean('is_read')->default(false);
            $table->timestamp('received_at');
            $table->timestamps();

            $table->index('received_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('emails');
    }
};
