<?php

$targetFile = 'c:\\Users\\monyg\\OneDrive\\Documentos\\ticket_plataforma\\app\\Livewire\\SupportHub.php';
$c = file_get_contents($targetFile);

// Check if already injected
if (strpos($c, 'public $simDesc = \'\';') !== false) {
    echo "Already injected backend logic.\n";
    exit(0);
}

$backendLogic = <<<'PHP'
    public $simDesc = '';
    public $simPlant = '1';
    public $simArea = 'Sistemas / TI';
    public $simPriority = 'Media';
    public $simStatus = 'Abierto';

    public function crearTicketSimulador()
    {
        $this->validate([
            'simDesc' => 'required|string',
            'simPlant' => 'required|string',
            'simArea' => 'required|string',
            'simPriority' => 'required|string',
            'simStatus' => 'required|string',
        ]);

        $prioridadId = match($this->simPriority) {
            'Baja' => 1,
            'Media' => 2,
            'Alta' => 3,
            default => 2,
        };

        $estadoId = match($this->simStatus) {
            'Abierto' => 1,
            'En Proceso' => 2,
            'Resuelto' => 3,
            default => 1,
        };
        
        $userId = \Auth::id() ?? \App\Models\User::first()->id;

        $ticket = Ticket::create([
            'titulo' => '[' . mb_strtoupper($this->simArea) . '] Registro Rápido',
            'descripcion' => $this->simDesc,
            'prioridad_id' => $prioridadId,
            'estado_id' => $estadoId,
            'planta' => $this->simPlant,
            'usuario_creador_id' => $userId,
            'tipo_ticket_id' => 1,
        ]);
        
        if ($estadoId == 3) {
            $ticket->update(['agente_asignado_id' => $userId]);
        }

        $this->reset(['simDesc']);
        $this->dispatch('notify', 'Ticket del simulador generado y guardado en la base de datos.');
        $this->dispatch('ticket-created');
    }
PHP;

// Insert before the last closing brace
$insertPos = strpos($c, 'public function render()');
if ($insertPos !== false) {
    $c = substr_replace($c, $backendLogic . "\n\n    ", $insertPos, 0);
    file_put_contents($targetFile, $c);
    echo "Injected backend logic successfully.\n";
} else {
    echo "Could not find render function.\n";
    exit(1);
}
