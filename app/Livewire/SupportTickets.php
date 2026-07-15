<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Ticket;
use App\Models\ArchivoAdjunto;
use App\Models\TipoTicket;
use App\Models\Departamento;
use App\Models\Sector;
use App\Models\Maquina;
use App\Models\Prioridad;
use App\Models\EstadoTicket;
use App\Models\Equipment;
use Illuminate\Support\Facades\Auth;

class SupportTickets extends Component
{
    use WithFileUploads;

    public $title;
    public $description;
    public $tipo_ticket_id = '';
    public $prioridad_id = '';
    public $attachments = [];
    
    public $isCreating = false;

    // Equipos
    public $mostrarModalEquipo = false;
    public $ticketIdEquipo = null;
    public $equipoSeleccionado = '';

    public function rules()
    {
        $rules = [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'tipo_ticket_id' => 'required|exists:tipo_tickets,id',
            'attachments' => 'max:2',
            'attachments.*' => 'nullable|image|max:5120',
        ];

        if (Auth::user()?->role === 'admin' || Auth::user()?->role === 'agente') {
            $rules['prioridad_id'] = 'required|exists:prioridads,id';
        }

        return $rules;
    }

    public function render()
    {
        $user = Auth::user();
        
        $query = Ticket::with(['tipoTicket', 'estado', 'prioridad', 'creador', 'agente', 'departamento', 'sector', 'maquina'])
                       ->whereNotIn('estado_id', [3, 4])
                       ->orderBy('created_at', 'desc');

        if ($user->role === 'admin' || $user->role === 'agente') {
            $tickets = $query->get();
        } else {
            $tickets = $query->where('usuario_creador_id', $user->id)->get();
        }

        $tipos = TipoTicket::all();
        $prioridades = Prioridad::all();
        $estados = EstadoTicket::all();
        $equiposDisponibles = Equipment::where('stock', '>', 0)->get();

        return view('livewire.support-tickets', compact('tickets', 'tipos', 'prioridades', 'estados', 'equiposDisponibles'));
    }

    public function showCreateForm()
    {
        $this->reset(['title', 'description', 'tipo_ticket_id', 'prioridad_id', 'attachments']);
        $this->isCreating = true;
    }

    public function cancelCreate()
    {
        $this->isCreating = false;
    }

    public function removeAttachment($index)
    {
        array_splice($this->attachments, $index, 1);
    }

    public function saveTicket()
    {
        $this->validate();

        // Prevención de duplicados (Idempotencia)
        $recentTicket = Ticket::where('usuario_creador_id', Auth::id())
            ->where('titulo', $this->title)
            ->where('descripcion', $this->description)
            ->where('created_at', '>=', now()->subMinutes(2))
            ->first();

        if ($recentTicket) {
            $this->attachments = [];
            $this->isCreating = false;
            session()->flash('message', 'Este ticket ya fue generado recientemente (se evitó duplicado).');
            return;
        }

        // Assuming status 1 is "Abierto" as seeded.
        $estadoAbierto = EstadoTicket::where('nombre', 'Abierto')->first();

        $prioridadId = $this->prioridad_id;
        if (empty($prioridadId)) {
            $prioridadDefault = Prioridad::where('nombre', 'Baja')->first() ?? Prioridad::first();
            $prioridadId = $prioridadDefault ? $prioridadDefault->id : 1;
        }

        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            $ticket = Ticket::create([
                'titulo' => $this->title,
                'descripcion' => $this->description,
                'tipo_ticket_id' => $this->tipo_ticket_id,
                'estado_id' => $estadoAbierto->id ?? 1,
                'prioridad_id' => $prioridadId,
                'usuario_creador_id' => Auth::id(),
                'agente_asignado_id' => null,
                'departamento_id' => Auth::user()->departamento_id,
                'sector_id' => null,
                'maquina_id' => null,
            ]);

            if (!empty($this->attachments)) {
                foreach ($this->attachments as $file) {
                    $path = $file->store('tickets', 'public');

                    ArchivoAdjunto::create([
                        'ticket_id' => $ticket->id,
                        'nombre_archivo' => $file->getClientOriginalName(),
                        'ruta_archivo' => $path,
                        'visible_operadores' => true,
                    ]);
                }
            }

            \Illuminate\Support\Facades\DB::commit();
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            \Illuminate\Support\Facades\Log::error('Error saving ticket in SupportTickets: ' . $e->getMessage());
            session()->flash('message', 'Ocurrió un error al crear el ticket.');
            return;
        }

        $this->attachments = [];
        $this->isCreating = false;
        session()->flash('message', 'Ticket aperturado exitosamente.');
    }

    public function changeStatus($ticketId, $statusId)
    {
        $user = Auth::user();
        if ($user->role === 'admin' || $user->role === 'agente') {
            $ticket = Ticket::find($ticketId);
            if ($ticket) {
                $ticket->update(['estado_id' => $statusId]);
                session()->flash('message', 'Estado del ticket actualizado.');
            }
        }
    }

    public function tomarTicket($ticketId)
    {
        $user = Auth::user();
        if ($user->role === 'agente' || $user->role === 'admin') {
            $ticket = Ticket::find($ticketId);
            if ($ticket && !$ticket->agente_asignado_id) {
                // Determine 'En Proceso' ID (assuming 2 for seeded data or dynamically find)
                $enProceso = EstadoTicket::where('nombre', 'En Progreso')->first();
                $ticket->update([
                    'agente_asignado_id' => $user->id,
                    'estado_id' => $enProceso->id ?? $ticket->estado_id
                ]);
                session()->flash('message', 'Acabas de tomar este ticket. Su estado cambió a En Progreso.');
            }
        }
    }

    public function abrirModalEquipo($ticketId)
    {
        $this->ticketIdEquipo = $ticketId;
        $this->mostrarModalEquipo = true;
    }

    public function cerrarModalEquipo()
    {
        $this->mostrarModalEquipo = false;
        $this->ticketIdEquipo = null;
        $this->equipoSeleccionado = '';
    }

    public function entregarEquipo()
    {
        if ($this->ticketIdEquipo && $this->equipoSeleccionado) {
            $equipo = Equipment::find($this->equipoSeleccionado);
            if ($equipo && $equipo->stock > 0) {
                $equipo->decrement('stock');
                session()->flash('message', 'Equipo ' . $equipo->name . ' entregado y descontado del inventario exitosamente.');
                $this->cerrarModalEquipo();
            }
        }
    }
}
