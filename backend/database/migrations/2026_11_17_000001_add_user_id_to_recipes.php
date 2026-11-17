<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // user_id null = receta global (creada por un admin); con valor = receta privada de ese usuario.
    public function up(): void
    {
        Schema::table('recipes', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('id')->constrained()->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('recipes', function (Blueprint $table) {
            $table->dropConstrainedForeignId('user_id');
        });
    }
};
