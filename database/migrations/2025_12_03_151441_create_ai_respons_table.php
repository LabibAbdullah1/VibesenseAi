<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ai_respons', function (Blueprint $table) {
            $table->id();
<<<<<<< HEAD
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->text('user_message');
            $table->foreignId('mood_id')->nullable()->constrained('moods')->onDelete('set null');
            $table->text('ai_respon');
            $table->text('motivation')->nullable();
            $table->integer('processing_time_ms')->nullable();
=======
            $table->foreignId('tell_id')->constrained('tells')->cascadeOnDelete();
            $table->text('response_text');
>>>>>>> 681b617 (create schema database)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_respons');
    }
};
