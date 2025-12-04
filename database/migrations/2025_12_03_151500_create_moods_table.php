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
        Schema::create('moods', function (Blueprint $table) {
            $table->id();
<<<<<<< HEAD
<<<<<<< HEAD
            $table->string('mood_name');
=======
            $table->string('category');
            $table->string('intensity');
>>>>>>> 681b617 (create schema database)
=======
            $table->string('mood_name');
>>>>>>> 7139098 (Add models and migrations for AiRespons, LogActivity, Mood, UserPreference; remove Tells and update moods structure)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('moods');
    }
};
