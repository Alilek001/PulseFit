<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('workout_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('routine_id')->nullable()->constrained()->nullOnDelete();
            $table->string('routine_name');
            $table->integer('duration_minutes')->default(30);
            $table->text('notes')->nullable();
            $table->date('logged_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('workout_logs');
    }
};
