<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DummyTicketsSeeder extends Seeder
{
    public function run()
    {
        $tipos = [1, 2, 3, 4, 5];
        $estados = [1, 2, 3, 4]; // 1: Abierto, 2: En proceso, 3: Resuelto, 4: Cerrado
        $prioridades = [1, 2, 3];
        $users = [1, 3, 2, 4];
        $plantas = [1, 2];

        $tickets = [];
        $now = Carbon::now();

        // 35 tickets with realistic distribution
        for ($i = 0; $i < 35; $i++) {
            // Skew towards recent dates
            $daysAgo = rand(1, 100) > 70 ? rand(0, 30) : rand(31, 180);
            $created_at = $now->copy()->subDays($daysAgo)->subHours(rand(0, 23));
            
            // Bias status: mostly solved/closed, some new/in-progress
            $randStatus = rand(1, 100);
            if ($randStatus < 20) $estado_id = 1;
            elseif ($randStatus < 40) $estado_id = 2;
            elseif ($randStatus < 70) $estado_id = 3;
            else $estado_id = 4;

            $tipo_id = $tipos[array_rand($tipos)];
            
            $tickets[] = [
                'titulo' => 'Incidencia automática #' . rand(1000, 9999),
                'descripcion' => 'Revisión periódica o falla reportada por el sistema. Creada para demostración analítica.',
                'tipo_ticket_id' => $tipo_id,
                'estado_id' => $estado_id,
                'prioridad_id' => $prioridades[array_rand($prioridades)],
                'usuario_creador_id' => $users[array_rand($users)],
                'agente_asignado_id' => in_array($estado_id, [2, 3, 4]) ? $users[array_rand($users)] : null,
                'created_at' => $created_at,
                'updated_at' => $created_at->copy()->addHours(rand(1, 72)),
                'planta' => $plantas[array_rand($plantas)],
                'tiempo_resolucion' => in_array($estado_id, [3, 4]) ? rand(120, 2880) : null, // 2 to 48 hours
            ];
        }

        DB::table('tickets')->insert($tickets);
    }
}
