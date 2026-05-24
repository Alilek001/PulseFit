<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Exercise;
use App\Models\Recipe;
use App\Models\Routine;
use App\Models\Progress;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $password = Hash::make('password123');

        $user = User::firstOrCreate(['email' => 'user@pulsefit.com'], [
            'name' => 'Adrián García',
            'password' => $password,
            'role' => 'user',
            'weight' => 75.0,
            'height' => 178,
            'goal' => 'maintain',
        ]);

        $premium = User::firstOrCreate(['email' => 'premium@pulsefit.com'], [
            'name' => 'Sofía Martínez',
            'password' => $password,
            'role' => 'premium',
            'weight' => 62.5,
            'height' => 165,
            'goal' => 'lose_weight',
        ]);

        User::firstOrCreate(['email' => 'admin@pulsefit.com'], [
            'name' => 'Carlos López',
            'password' => $password,
            'role' => 'admin',
            'weight' => 82.0,
            'height' => 180,
            'goal' => 'gain_muscle',
        ]);

        // Ejercicios
        $exerciseData = [
            ['name' => 'Press de Banca', 'muscle_group' => 'Pecho', 'description' => 'Acuéstese en el banco y empuje la barra.', 'is_premium' => false],
            ['name' => 'Aperturas Inclinadas', 'muscle_group' => 'Pecho', 'description' => 'Aperturas con mancuernas en banco inclinado.', 'is_premium' => true],
            ['name' => 'Sentadillas', 'muscle_group' => 'Piernas', 'description' => 'Flexione las rodillas manteniendo la espalda recta.', 'is_premium' => false],
            ['name' => 'Prensa de Piernas', 'muscle_group' => 'Piernas', 'description' => 'Empuje el peso usando las piernas en la máquina.', 'is_premium' => true],
            ['name' => 'Dominadas', 'muscle_group' => 'Espalda', 'description' => 'Cuelgue de la barra y tire hasta que la barbilla pase la barra.', 'is_premium' => false],
            ['name' => 'Curl de Bíceps', 'muscle_group' => 'Bíceps', 'description' => 'Flexione los codos levantando las mancuernas.', 'is_premium' => false],
            ['name' => 'Press Militar', 'muscle_group' => 'Hombros', 'description' => 'Press con barra por encima de la cabeza de pie.', 'is_premium' => false],
            ['name' => 'Elevaciones Laterales', 'muscle_group' => 'Hombros', 'description' => 'Eleva mancuernas a los lados hasta la altura del hombro.', 'is_premium' => false],
            ['name' => 'Pajaros Posteriores', 'muscle_group' => 'Hombros', 'description' => 'Elevaciones posteriores con mancuernas inclinado.', 'is_premium' => true],
            ['name' => 'Plancha', 'muscle_group' => 'Core', 'description' => 'Mantén el cuerpo recto apoyado en antebrazos y pies.', 'is_premium' => false],
            ['name' => 'Crunch Abdominal', 'muscle_group' => 'Core', 'description' => 'Contrae el abdomen llevando hombros hacia las rodillas.', 'is_premium' => false],
            ['name' => 'Rueda Abdominal', 'muscle_group' => 'Core', 'description' => 'Rueda hacia adelante manteniendo el core contraido.', 'is_premium' => true],
            ['name' => 'Carrera Continua', 'muscle_group' => 'Cardio', 'description' => 'Trote a ritmo constante durante el tiempo indicado.', 'is_premium' => false],
            ['name' => 'HIIT en Bicicleta', 'muscle_group' => 'Cardio', 'description' => 'Intervalos de alta intensidad en bicicleta estatica.', 'is_premium' => true],
            ['name' => 'Cuerda de Saltar', 'muscle_group' => 'Cardio', 'description' => 'Saltos continuos con cuerda a ritmo sostenido.', 'is_premium' => false],
            ['name' => 'Curl de Martillo', 'muscle_group' => 'Brazos', 'description' => 'Curl con agarre neutro para biceps y braquial.', 'is_premium' => false],
            ['name' => 'Fondos en Paralelas', 'muscle_group' => 'Brazos', 'description' => 'Dips en paralelas para triceps.', 'is_premium' => false],
            ['name' => 'Press Frances', 'muscle_group' => 'Brazos', 'description' => 'Extension de triceps con barra EZ tumbado.', 'is_premium' => true],
        ];

        $exercises = [];
        foreach ($exerciseData as $ex) {
            $exercises[$ex['name']] = Exercise::firstOrCreate(['name' => $ex['name']], $ex);
        }

        // Recetas
        $recipeData = [
            ['name' => 'Batido Proteico Básico', 'calories' => 350, 'proteins' => 30, 'carbs' => 40, 'fats' => 5, 'is_premium' => false,
             'description' => 'Avena, proteína de suero, leche y plátano.',
             'image_url' => 'https://imgs.search.brave.com/hHDhhJsWpn-PViNvOvdve-xlu226nESoabIGxpEQx_w/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9oaXBz/LmhlYXJzdGFwcHMu/Y29tL2htZy1wcm9k/L2ltYWdlcy9tZWpv/cmVzLWJhdGlkb3Mt/cHJvdGVpbmFzLTE2/NDAwMDUxNDMuanBl/Zz9jcm9wPTF4dzow/Ljg0Mzc1eGg7MCww/LjE3NXhoJnJlc2l6/ZT05ODA6Kg'],
            ['name' => 'Avena Nocturna con Chía', 'calories' => 420, 'proteins' => 15, 'carbs' => 55, 'fats' => 12, 'is_premium' => false,
             'description' => 'Mezcla avena, leche de almendras y semillas de chía. Deja reposar.',
             'image_url' => 'https://imgs.search.brave.com/32ug2-nU8k6ZLMk75gO6kesxA2mOIknF3liaXpHkcks/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly93d3cu/Z2FzdHJvbGFid2Vi/LmNvbS91L2ZvdG9n/cmFmaWFzL20vMjAy/NS8xMC8zMC9mODQ4/eDQ3Ny04NDg5NV8x/NDI2OThfNTA1MC5w/bmc'],
            ['name' => 'Pollo Teriyaki Premium Élite', 'calories' => 550, 'proteins' => 45, 'carbs' => 60, 'fats' => 10, 'is_premium' => true,
             'description' => 'Pechuga de pollo al horno con salsa teriyaki casera y arroz jazmín. Macros perfectos para volumen.',
             'image_url' => 'https://imgs.search.brave.com/oIp1ZnhqktoIS1chKsMVBLI6qJ5L3lR_68YY9PHgUJo/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9pbWFn/LmJvbnZpdmV1ci5j/b20vYW5hZGlyLXNl/c2Ftby1hbC1wb2xs/by10ZXJpeWFraS5q/cGc'],
            ['name' => 'Salmón Glaseado Keto', 'calories' => 600, 'proteins' => 40, 'carbs' => 5, 'fats' => 45, 'is_premium' => true,
             'description' => 'Corte de salmón noruego al horno con espárragos.',
             'image_url' => 'https://imgs.search.brave.com/ttb16bc-JlKQKWZUFadq6kHBoatrQJGnhKY_Pq8uKEw/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9pbWFn/LmJvbnZpdmV1ci5j/b20vc2FsbW9uLWds/YXNlYWRvLWNvbi1t/aWVsLWNhc2Vyby5q/cGc'],
        ];

        foreach ($recipeData as $rec) {
            Recipe::firstOrCreate(['name' => $rec['name']], $rec);
        }

        // Rutinas globales con ejercicios
        $routines = [
            [
                'name' => 'Rutina Inicial de Piernas', 'tier' => 'basic', 'is_premium' => false,
                'training_days' => '2', 'description' => null, 'user_id' => null,
                'exercises' => [
                    ['name' => 'Sentadillas', 'sets' => 3, 'reps' => 12, 'notes' => null],
                ],
            ],
            [
                'name' => 'Rutina Inicial de Core', 'tier' => 'basic', 'is_premium' => false,
                'training_days' => '3', 'description' => null, 'user_id' => null,
                'exercises' => [
                    ['name' => 'Plancha', 'sets' => 3, 'reps' => 12, 'notes' => null],
                    ['name' => 'Crunch Abdominal', 'sets' => 3, 'reps' => 12, 'notes' => null],
                ],
            ],
            [
                'name' => 'Hipertrofia de Pecho Avanzada', 'tier' => 'basic', 'is_premium' => true,
                'training_days' => '3', 'description' => null, 'user_id' => null,
                'exercises' => [
                    ['name' => 'Press de Banca', 'sets' => 4, 'reps' => 10, 'notes' => 'Técnica estricta. Progresión lineal.'],
                    ['name' => 'Aperturas Inclinadas', 'sets' => 4, 'reps' => 10, 'notes' => 'Técnica estricta. Progresión lineal.'],
                ],
            ],
            [
                'name' => 'Fuerza y Potencia de Piernas', 'tier' => 'basic', 'is_premium' => true,
                'training_days' => '2', 'description' => null, 'user_id' => null,
                'exercises' => [
                    ['name' => 'Sentadillas', 'sets' => 4, 'reps' => 10, 'notes' => 'Técnica estricta. Progresión lineal.'],
                    ['name' => 'Prensa de Piernas', 'sets' => 4, 'reps' => 10, 'notes' => 'Técnica estricta. Progresión lineal.'],
                ],
            ],
            [
                'name' => 'Full Body Élite', 'tier' => 'basic', 'is_premium' => true,
                'training_days' => '4', 'description' => null, 'user_id' => null,
                'exercises' => [
                    ['name' => 'Press de Banca', 'sets' => 4, 'reps' => 10, 'notes' => 'Técnica estricta. Progresión lineal.'],
                    ['name' => 'Dominadas', 'sets' => 4, 'reps' => 10, 'notes' => 'Técnica estricta. Progresión lineal.'],
                    ['name' => 'Sentadillas', 'sets' => 4, 'reps' => 10, 'notes' => 'Técnica estricta. Progresión lineal.'],
                    ['name' => 'Press Militar', 'sets' => 4, 'reps' => 10, 'notes' => 'Técnica estricta. Progresión lineal.'],
                    ['name' => 'Plancha', 'sets' => 4, 'reps' => 10, 'notes' => 'Técnica estricta. Progresión lineal.'],
                ],
            ],
            [
                'name' => 'Cardio HIIT Avanzado', 'tier' => 'basic', 'is_premium' => true,
                'training_days' => '3', 'description' => null, 'user_id' => null,
                'exercises' => [
                    ['name' => 'HIIT en Bicicleta', 'sets' => 4, 'reps' => 10, 'notes' => 'Técnica estricta. Progresión lineal.'],
                    ['name' => 'Cuerda de Saltar', 'sets' => 4, 'reps' => 10, 'notes' => 'Técnica estricta. Progresión lineal.'],
                    ['name' => 'Carrera Continua', 'sets' => 4, 'reps' => 10, 'notes' => 'Técnica estricta. Progresión lineal.'],
                ],
            ],
            [
                'name' => 'Destruye piernas', 'tier' => 'basic', 'is_premium' => true,
                'training_days' => '1', 'description' => null, 'user_id' => null,
                'exercises' => [
                    ['name' => 'Prensa de Piernas', 'sets' => 3, 'reps' => 12, 'notes' => 'Piernas colocadas en la parte mas central de la plataforma'],
                ],
            ],
            [
                'name' => 'Rutina Élite de Brazos', 'tier' => 'basic', 'is_premium' => true,
                'training_days' => '2', 'description' => null, 'user_id' => null,
                'exercises' => [
                    ['name' => 'Fondos en Paralelas', 'sets' => 3, 'reps' => 12, 'notes' => null],
                    ['name' => 'Press Frances', 'sets' => 3, 'reps' => 12, 'notes' => null],
                ],
            ],
            [
                'name' => 'Rutina gitana de piernas', 'tier' => 'elite', 'is_premium' => true,
                'training_days' => '1', 'description' => 'Rutina destroza piernas', 'user_id' => null,
                'exercises' => [
                    ['name' => 'Prensa de Piernas', 'sets' => 2, 'reps' => 8, 'notes' => 'Colocación de piernas en la parte central de la plataforma'],
                ],
            ],
        ];

        foreach ($routines as $routineData) {
            $exList = $routineData['exercises'];
            unset($routineData['exercises']);

            $existing = Routine::where('name', $routineData['name'])->whereNull('user_id')->first();
            if ($existing) continue;

            $routine = Routine::create($routineData);
            foreach ($exList as $ex) {
                if (isset($exercises[$ex['name']])) {
                    $routine->exercises()->attach($exercises[$ex['name']]->id, [
                        'sets' => $ex['sets'],
                        'reps' => $ex['reps'],
                        'notes' => $ex['notes'],
                    ]);
                }
            }
        }

        // Historial de progreso para Adrián
        if ($user->wasRecentlyCreated) {
            Progress::create(['user_id' => $user->id, 'weight' => 78.0, 'body_fat' => 16.5, 'date' => now()->subDays(30)]);
            Progress::create(['user_id' => $user->id, 'weight' => 76.5, 'body_fat' => 15.0, 'date' => now()->subDays(15)]);
            Progress::create(['user_id' => $user->id, 'weight' => 75.0, 'body_fat' => 14.5, 'date' => now()]);
        }
    }
}
