<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Maquina;
use App\Models\Ticket;
use App\Models\Equipment;
use Livewire\Attributes\Layout;

#[Layout('layouts.blank')]
class StationMap extends Component
{
    public $selectedStationId = null;
    public $activePlant = 1;
    public $scale = 1;

    protected $listeners = ['setSelectedStation' => 'setSelectedStation'];

    public function setSelectedStation($id)
    {
        $this->selectedStationId = $id;
    }

    public function switchPlant($plantId)
    {
        $this->activePlant = $plantId;
        $this->selectedStationId = null;
    }

    public function updateScale($val)
    {
        $this->scale = max(0.4, min(2, $this->scale + $val));
    }

    public function updatePosition($id, $x, $y)
    {
        if (in_array(auth()->user()->role ?? '', ['admin', 'agente'])) {
            $maquina = Maquina::find($id);
            if ($maquina) {
                $maquina->update(['pos_x' => $x, 'pos_y' => $y]);
            }
        }
    }

    public function deleteStation($id)
    {
        if (in_array(auth()->user()->role ?? '', ['admin', 'agente'])) {
            $maquina = Maquina::find($id);
            if ($maquina) {
                // Reset position so it hides from map (or delete entirely)
                $maquina->update(['pos_x' => null, 'pos_y' => null]);
            }
        }
        if ($this->selectedStationId == $id) {
            $this->selectedStationId = null;
        }
    }

    public function render()
    {
        $stations = Maquina::all();
        $tickets = Ticket::all();
        $inventory = Equipment::all();

        $stockCount = $inventory->where('status', 'in-stock')->count();
        $deployedCount = $inventory->where('status', 'deployed')->count();

        $processedStations = collect();
        
        // Asumimos 23 estaciones por planta por ahora
        for ($i = 1; $i <= 46; $i++) {
            $plant = ($i <= 23) ? 1 : 2;
            $station = $stations->firstWhere('id', $i);
            
            $status = 'empty';
            $stationInventory = collect();
            $name = 'Estación ' . str_pad($i, 2, '0', STR_PAD_LEFT);
            $dbId = $i;

            if ($station) {
                $name = $station->nombre;
                $stationInventory = $inventory->where('maquina_id', $i);
                $stationTickets = $tickets->where('maquina_id', $i)->whereIn('estado_id', [1, 2]);

                if ($stationInventory->isNotEmpty()) {
                    $status = ($stationTickets->isNotEmpty()) ? 'error' : 'ok';
                }
            }

            $processedStations->push([
                'id' => "ST-" . $i, 
                'db_id' => $dbId,
                'name' => $name,
                'status' => $status,
                'equipment' => $stationInventory,
                'pos_x' => $station ? $station->pos_x : null,
                'pos_y' => $station ? $station->pos_y : null,
                'planta' => $plant,
            ]);
        }

        $selectedStation = $this->selectedStationId 
            ? $processedStations->firstWhere('db_id', $this->selectedStationId) 
            : null;

        return view('livewire.station-map', [
            'stations' => $processedStations,
            'selectedStation' => $selectedStation,
            'stockCount' => $stockCount,
            'deployedCount' => $deployedCount
        ]);
    }
}
