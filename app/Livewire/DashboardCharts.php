<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardCharts extends Component
{
    public function render()
    {
        $user = Auth::user();
        
        // Solo administradores y agentes pueden ver las métricas completas
        if (!$user || ($user->role !== 'admin' && $user->role !== 'agente')) {
            return view('livewire.dashboard-charts', [
                'hasAccess' => false
            ]);
        }

        $query = Ticket::with(['tipoTicket', 'estado', 'prioridad']);
        
        // Si es agente, tal vez quiera ver sus propias métricas o las generales.
        // Por ahora, le mostramos los tickets que tienen asignados (si es agente).
        if ($user->role === 'agente') {
            $query->where('agente_asignado_id', $user->id);
        }
        
        $tickets = $query->get();

        // 1. Incidentes más frecuentes (Por Tipo)
        $frecuentes = $tickets->groupBy(function($t) {
            return $t->tipoTicket->nombre ?? 'Sin Tipo';
        })->map->count()->sortDesc()->take(5);

        // Incidentes MENOS frecuentes (Por Tipo)
        $menosFrecuentes = $tickets->groupBy(function($t) {
            return $t->tipoTicket->nombre ?? 'Sin Tipo';
        })->map->count()->sort()->take(5);

        // 2. Tickets resueltos (General)
        $resueltos = $tickets->filter(function($t) {
            return $t->estado && $t->estado->nombre === 'Resuelto';
        });

        // Agrupados mensualmente
        $mensual = $resueltos->groupBy(function($t) {
            return Carbon::parse($t->updated_at)->format('M Y');
        })->map->count();

        // Agrupados semanalmente
        $semanal = $resueltos->groupBy(function($t) {
            return 'Semana ' . Carbon::parse($t->updated_at)->format('W');
        })->map->count();

        // 3. Eficiencia / Estado Actual (Avance)
        $estados = $tickets->groupBy(function($t) {
            return $t->estado->nombre ?? 'Sin Estado';
        })->map->count();

        return view('livewire.dashboard-charts', [
            'hasAccess' => true,
            'frecuentesLabels' => $frecuentes->keys(),
            'frecuentesData' => $frecuentes->values(),
            
            'menosFrecuentesLabels' => $menosFrecuentes->keys(),
            'menosFrecuentesData' => $menosFrecuentes->values(),
            
            'mensualLabels' => $mensual->keys(),
            'mensualData' => $mensual->values(),
            
            'semanalLabels' => $semanal->keys(),
            'semanalData' => $semanal->values(),

            'estadosLabels' => $estados->keys(),
            'estadosData' => $estados->values(),
            
            'totalTickets' => $tickets->count(),
            'totalResueltos' => $resueltos->count(),
        ]);
    }
}
