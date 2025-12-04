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
        Schema::create('log_activities', function (Blueprint $table) {
            $table->id();
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> 7139098 (Add models and migrations for AiRespons, LogActivity, Mood, UserPreference; remove Tells and update moods structure)
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('action');
            $table->text('details')->nullable();
            $table->ipAddress('ip_address')->nullable();
            $table->string('user_agent')->nullable();
<<<<<<< HEAD
=======
            $table->string('activity_type');
            $table->text('description')->nullable();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
>>>>>>> 681b617 (create schema database)
=======
>>>>>>> 7139098 (Add models and migrations for AiRespons, LogActivity, Mood, UserPreference; remove Tells and update moods structure)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_activities');
    }
};
