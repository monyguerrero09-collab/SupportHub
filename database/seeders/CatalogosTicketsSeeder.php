<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CausaSolucion;
use App\Models\MotivoCancelacion;

class CatalogosTicketsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $causas = [
            'Reparación de Hardware',
            'Configuración de Software',
            'Soporte a Usuario',
            'Actualización de Sistema',
            'Mantenimiento Preventivo',
            'Cambio de Refacciones',
            'Otro'
        ];

        foreach ($causas as $causa) {
            CausaSolucion::firstOrCreate(['nombre' => $causa]);
        }

        $motivos = [
            'Duplicado',
            'Falta de Información',
            'Cancelado por el Usuario',
            'Problema Resuelto por el Usuario',
            'Fuera de Alcance',
            'Otro'
        ];

        foreach ($motivos as $motivo) {
            MotivoCancelacion::firstOrCreate(['nombre' => $motivo]);
        }
    }
}
