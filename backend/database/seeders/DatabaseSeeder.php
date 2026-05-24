<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Exercise;
use App\Models\Recipe;
use App\Models\Progress;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $password = Hash::make('password123');

        // Usuario Básico
        $user = User::firstOrCreate(['email' => 'user@pulsefit.com'], [
            'name' => 'Adrián García',
            'password' => $password,
            'role' => 'user',
            'weight' => 75.0,
            'height' => 178,
            'goal' => 'maintain',
        ]);

        // Usuario Premium
        $premium = User::firstOrCreate(['email' => 'premium@pulsefit.com'], [
            'name' => 'Sofía Martínez',
            'password' => $password,
            'role' => 'premium',
            'weight' => 62.5,
            'height' => 165,
            'goal' => 'lose_weight',
        ]);

        // Usuario Admin
        $admin = User::firstOrCreate(['email' => 'admin@pulsefit.com'], [
            'name' => 'Carlos López',
            'password' => $password,
            'role' => 'admin',
            'weight' => 82.0,
            'height' => 180,
            'goal' => 'gain_muscle',
        ]);

        // Ejercicios Base
        $exercises = [
            ['name' => 'Press de Banca', 'muscle_group' => 'Pecho', 'description' => 'Acuéstese en el banco y empuje la barra.', 'is_premium' => false],
            ['name' => 'Aperturas Inclinadas', 'muscle_group' => 'Pecho', 'description' => 'Aperturas con mancuernas en banco inclinado.', 'is_premium' => true],
            ['name' => 'Sentadillas', 'muscle_group' => 'Piernas', 'description' => 'Flexione las rodillas manteniendo la espalda recta.', 'is_premium' => false],
            ['name' => 'Prensa de Piernas', 'muscle_group' => 'Piernas', 'description' => 'Empuje el peso usando las piernas en la máquina.', 'is_premium' => true],
            ['name' => 'Dominadas', 'muscle_group' => 'Espalda', 'description' => 'Cuelgue de la barra y tire hasta que la barbilla pase la barra.', 'is_premium' => false],
            ['name' => 'Curl de Bíceps', 'muscle_group' => 'Bíceps', 'description' => 'Flexione los codos levantando las mancuernas.', 'is_premium' => false],
        ];
        foreach ($exercises as $ex) {
            Exercise::create($ex);
        }

        // Recetas Base
        $recipes = [
            ['name' => 'Batido Proteico Básico', 'calories' => 350, 'proteins' => 30, 'carbs' => 40, 'fats' => 5, 'description' => 'Avena, proteína de suero, leche y plátano.', 'is_premium' => false],
            ['name' => 'Avena Nocturna con Chía', 'calories' => 420, 'proteins' => 15, 'carbs' => 55, 'fats' => 12, 'description' => 'Mezcla avena, leche de almendras y semillas de chía. Deja reposar.', 'is_premium' => false],
            ['name' => 'Pollo Teriyaki Premium Élite', 'calories' => 550, 'proteins' => 45, 'carbs' => 60, 'fats' => 10, 'description' => 'Pechuga de pollo al horno con salsa teriyaki casera y arroz jazmín. Macros perfectos para volumen.', 'is_premium' => true],
            ['name' => 'Salmón Glaseado Keto', 'calories' => 600, 'proteins' => 40, 'carbs' => 5, 'fats' => 45, 'description' => 'Corte de salmón noruego al horno con espárragos.', 'is_premium' => true],
        ];
        foreach ($recipes as $rec) {
            Recipe::create($rec);
        }

        // Historial de progreso para Adrián (para que el gráfico SVG tenga datos al iniciar)
        Progress::create(['user_id' => $user->id, 'weight' => 78.0, 'body_fat' => 16.5, 'date' => now()->subDays(30)]);
        Progress::create(['user_id' => $user->id, 'weight' => 76.5, 'body_fat' => 15.0, 'date' => now()->subDays(15)]);
        Progress::create(['user_id' => $user->id, 'weight' => 75.0, 'body_fat' => 14.5, 'date' => now()]);
    }
}
