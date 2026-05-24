<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add tier and description to routines
        Schema::table('routines', function (Blueprint $table) {
            $table->string('tier')->default('basic')->after('name'); // basic | elite
            $table->text('description')->nullable()->after('tier');
        });

        // Weekly plan per user
        Schema::create('weekly_plan_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('routine_id')->constrained()->onDelete('cascade');
            $table->tinyInteger('day_of_week'); // 0=Mon ... 6=Sun
            $table->timestamps();
            $table->unique(['user_id', 'day_of_week']);
        });

        // Upgrade requests
        Schema::create('upgrade_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('status')->default('pending'); // pending | approved | rejected
            $table->text('message')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('upgrade_requests');
        Schema::dropIfExists('weekly_plan_items');
    }
};
