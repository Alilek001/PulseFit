<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. ROUTINES TABLE
        Schema::create('routines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('training_days'); 
            $table->boolean('is_premium')->default(false);
            $table->timestamps();
        });

        // 2. EXERCISES TABLE
        Schema::create('exercises', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('muscle_group'); 
            $table->text('description');
            $table->string('video_url')->nullable();
            $table->boolean('is_premium')->default(false);
            $table->timestamps();
        });

        // 3. RECIPES TABLE
        Schema::create('recipes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('calories');
            $table->integer('proteins'); 
            $table->integer('carbs'); 
            $table->integer('fats'); 
            $table->text('description')->nullable();
            $table->boolean('is_premium')->default(false);
            $table->timestamps();
        });

        // 4. PROGRESS TABLE
        Schema::create('progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('weight', 5, 2); 
            $table->decimal('body_fat', 4, 1)->nullable(); 
            $table->date('date');
            $table->timestamps();
        });

        // 5. ROUTINE_EXERCISE PIVOT TABLE
        Schema::create('routine_exercise', function (Blueprint $table) {
            $table->id();
            $table->foreignId('routine_id')->constrained()->onDelete('cascade');
            $table->foreignId('exercise_id')->constrained()->onDelete('cascade');
            $table->integer('sets')->default(4);
            $table->integer('reps')->default(12);
            $table->string('notes')->nullable(); 
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('routine_exercise');
        Schema::dropIfExists('progress');
        Schema::dropIfExists('recipes');
        Schema::dropIfExists('exercises');
        Schema::dropIfExists('routines');
    }
};
