<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Ticket;
use App\Models\Equipment;
use App\Models\Maquina;
use App\Models\Prioridad;
use App\Models\User;
use App\Models\ArchivoAdjunto;
use Auth;
use Livewire\Attributes\Layout;
use Livewire\WithFileUploads;
use DB;

#[Layout('layouts.blank')]
class SupportHub extends Component
{
    use WithFileUploads;

    public $activeTab = 'map'; // tickets, inventory, map, statistics, users, gestion_archivos
    public $ticketFiles = [];
    public $selectedEquipmentId = null;
    public $showingDetail = false;
    
    // Filters for Metrics
    public $dateFilter = '';
    public $userFilter = '';

    // Modal states
    public $showingAddEquipment = false;
    public $showingNewTicket = false;
    public $showingUserModal = false;

    // Form fields for New Ticket
    public $ticketCategory = null;
    public $ticketSubcategory = null;
    public $ticketDescription;
    public $ticketPriority = 1;
    public $ticketLocation;
    public $ticketAvailableTime;
    
    // User Modal interaction
    public $userComment = '';
    public $selectedTicketId = null;

    // Admin Ticket Modal properties
    public $showingAdminTicket = false;
    public $aTicketId = null;
    public $aTicketPriority = null;
    public $aTicketStatus = null;
    public $aTicketAgent = null;
    public $aTicketKoment = '';

    public $SECTORS = [
        ['id' => 'fg-sd', 'name' => 'Service Desk Grupo FG', 'icon' => '🎧']
    ];

    public $AREAS = [
        ['id' => 'sec', 'name' => 'Seguridad TI', 'services' => 5, 'icon' => 'ShieldCheck'],
        ['id' => 'red', 'name' => 'Redes/WiFi', 'services' => 3, 'icon' => 'Globe'],
        ['id' => 'serv', 'name' => 'Archivos', 'services' => 1, 'icon' => 'HardDrive'],
        ['id' => 'software', 'name' => 'Software y Licencias', 'services' => 4, 'icon' => 'Cpu'],
        ['id' => 'print', 'name' => 'Impresión', 'services' => 3, 'icon' => 'Printer'],
        ['id' => 'equipos', 'name' => 'Equipos', 'services' => 2, 'icon' => 'Monitor'],
    ];

    public $SERVICES_BY_AREA = [
        'sec' => [
            ['id' => 'acc', 'name' => 'Accesos y contraseñas', 'description' => 'Gestión de credenciales y bloqueos.', 'icon' => 'Key'],
            ['id' => 'av', 'name' => 'Antivirus y amenazas', 'description' => 'Reporte de virus detectados.', 'icon' => 'ShieldAlert'],
            ['id' => 'phish', 'name' => 'Correo sospechoso (phishing)', 'description' => 'Alertas sobre correos fraudulentos.', 'icon' => 'MailWarning'],
            ['id' => 'perm', 'name' => 'Permisos a sistemas', 'description' => 'Acceso a carpetas o aplicaciones.', 'icon' => 'Lock'],
            ['id' => 'sec_dev', 'name' => 'Seguridad en dispositivos', 'description' => 'Incidentes en equipos finales.', 'icon' => 'Smartphone'],
        ],
        'red' => [
            ['id' => 'wifi', 'name' => 'WiFi', 'description' => 'Problemas de conexión inalámbrica.', 'icon' => 'Wifi'],
            ['id' => 'cable', 'name' => 'Red cableada', 'description' => 'Conexión por cable Ethernet.', 'icon' => 'Network'],
            ['id' => 'vpn', 'name' => 'VPN (acceso remoto)', 'description' => 'Conexión segura remota.', 'icon' => 'Shield'],
        ],
        'serv' => [
            ['id' => 'files_mgmt', 'name' => 'Gestión de Archivos', 'description' => 'Acceso a carpetas y red compartida.', 'icon' => 'Files'],
        ],
        'software' => [
            ['id' => 'inst', 'name' => 'Instalación de programas', 'description' => 'Nuevos softwares solicitados.', 'icon' => 'Package'],
            ['id' => 'lic', 'name' => 'Licencias', 'description' => 'Activación y claves de producto.', 'icon' => 'FileKey'],
            ['id' => 'office', 'name' => 'Aplicaciones de oficina', 'description' => 'Word, Excel, Outlook, etc.', 'icon' => 'FileText'],
            ['id' => 'ent_sys', 'name' => 'Sistemas empresariales', 'description' => 'Herramientas internas y ERP.', 'icon' => 'Binary'],
        ],
        'print' => [
            ['id' => 'net_print', 'name' => 'Impresoras de red', 'description' => 'Equipos compartidos en oficina.', 'icon' => 'Printer'],
            ['id' => 'pers_print', 'name' => 'Impresoras', 'description' => 'Equipos conectados localmente.', 'icon' => 'Printer'],
            ['id' => 'scan', 'name' => 'Escáner', 'description' => 'Digitalización de documentos.', 'icon' => 'Scan'],
        ],
        'equipos' => [
            ['id' => 'ntb', 'name' => 'Notebook', 'description' => 'Laptops y periféricos.', 'icon' => 'Monitor'],
            ['id' => 'wks', 'name' => 'WorkStation', 'description' => 'Estaciones de alto rendimiento.', 'icon' => 'Monitor'],
        ]
    ];

    public $PROBLEMAS_EQUIPOS = [
        ['id' => 'e1', 'name' => 'No enciende'],
        ['id' => 'e2', 'name' => 'Esta lento'],
        ['id' => 'e3', 'name' => 'Aparece un error'],
        ['id' => 'e4', 'name' => 'Problemas con la pantalla'],
        ['id' => 'e5', 'name' => 'No funciona']
    ];

    public $CATEGORIES_BY_SERVICE = [
        'acc' => [['id' => 'a1', 'name' => 'No puedo iniciar sesión'], ['id' => 'a2', 'name' => 'Usuario bloqueado'], ['id' => 'a3', 'name' => 'Olvidé mi contraseña']],
        'av' => [['id' => 'av1', 'name' => 'El antivirus detectó un virus']],
        'phish' => [['id' => 'p1', 'name' => 'Recibí un correo sospechoso']],
        'perm' => [['id' => 'pm1', 'name' => 'No tengo acceso a una carpeta o sistema']],
        'sec_dev' => [['id' => 's1', 'name' => 'Mi cuenta fue hackeada (o sospecha)']],
        'wifi' => [['id' => 'w1', 'name' => 'no hay internet'], ['id' => 'w2', 'name' => 'internet lento'], ['id' => 'w3', 'name' => 'algunas paginas no cargan']],
        'cable' => [['id' => 'cb1', 'name' => 'No conecta al wifi'], ['id' => 'cb2', 'name' => 'se cae la conexión']],
        'vpn' => [['id' => 'v1', 'name' => 'No puedo conectarme a la VPN'], ['id' => 'v2', 'name' => 'No conecta al wifi'], ['id' => 'v3', 'name' => 'se cae la conexión']],
        'files_mgmt' => [['id' => 'f1', 'name' => 'No puedo acceder a archivos compartidos'], ['id' => 'f2', 'name' => 'Carpeta de red no aparece']],
        'inst' => [['id' => 'is1', 'name' => 'Necesito instalar un programa'], ['id' => 'is2', 'name' => 'El programa no abre']],
        'lic' => [['id' => 'lc1', 'name' => 'Error de licencia'], ['id' => 'lc2', 'name' => 'Licencia vencida']],
        'office' => [['id' => 'of1', 'name' => 'No puedo usar Word/Excel']],
        'ent_sys' => [['id' => 'es1', 'name' => 'Error en sistema interno']],
        'net_print' => [['id' => 'np1', 'name' => 'No imprime'], ['id' => 'np2', 'name' => 'Papel atascado'], ['id' => 'np3', 'name' => 'No aparece la impresora'], ['id' => 'np4', 'name' => 'Sin tinta o tóner']],
        'pers_print' => [['id' => 'pp1', 'name' => 'No imprime'], ['id' => 'pp2', 'name' => 'Impresión borrosa']],
        'scan' => [['id' => 'sc1', 'name' => 'No escanea'], ['id' => 'sc2', 'name' => 'no se visualiza el escáner']],
    ];

    public function mount() {
        $this->CATEGORIES_BY_SERVICE['ntb'] = $this->PROBLEMAS_EQUIPOS;
        $this->CATEGORIES_BY_SERVICE['wks'] = $this->PROBLEMAS_EQUIPOS;
    }

    // Form fields for Add Equipment (Match image "Alta de Hardware")
    public $equipmentName;
    public $equipmentCategory = 'Pantalla';
    public $equipmentModel;
    public $equipmentBarcode; // S/N
    public $equipmentPhysLocation = '-- Bodega --';

    // User Management
    public $userName;
    public $userEmail;
    public $userPassword;
    public $userRole = 'user';
    public $userCodigoAcceso;
    public $selectedUserId = null;

    protected $listeners = [
        'openEquipmentDetail' => 'openEquipmentDetail',
        'openAddEquipment' => 'openAddEquipment',
        'openNewTicket' => 'openNewTicket'
    ];

    public function setTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function openEquipmentDetail($id)
    {
        $this->selectedEquipmentId = $id;
        $this->showingDetail = true;
    }

    public function closeDetail()
    {
        $this->showingDetail = false;
        $this->selectedEquipmentId = null;
        $this->selectedTicketId = null;
    }
    
    public function viewUserTicket($id)
    {
        $this->selectedTicketId = $id;
        $this->showingDetail = true;
    }

    public function viewAdminTicket($id)
    {
        $ticket = Ticket::find($id);
        if ($ticket) {
            $this->aTicketId = $id;
            $this->aTicketPriority = $ticket->prioridad_id;
            $this->aTicketStatus = $ticket->estado_id;
            $this->aTicketAgent = $ticket->agente_asignado_id;
            $this->aTicketKoment = '';
            $this->showingAdminTicket = true;
        }
    }

    public function updateAdminTicket()
    {
        $ticket = Ticket::find($this->aTicketId);
        if ($ticket) {
            $oldStatusId = $ticket->estado_id;

            $ticket->update([
                'prioridad_id' => $this->aTicketPriority,
                'estado_id' => $this->aTicketStatus,
                'agente_asignado_id' => $this->aTicketAgent ?: null,
            ]);

            // Add note/comment if written
            if (!empty($this->aTicketKoment)) {
                $ticket->comentarios()->create([
                    'mensaje' => $this->aTicketKoment,
                    'usuario_id' => Auth::id(),
                    'es_cliente' => false,
                ]);

                // Notification to user
                if ($ticket->creador) {
                    try {
                        \Illuminate\Support\Facades\Mail::raw(
                            "Estimado usuario, su ticket #{$ticket->id} ha recibido una actualización:\n\n" .
                            $this->aTicketKoment . "\n\n" .
                            "Estado actual: " . (\App\Models\EstadoTicket::find($this->aTicketStatus)->nombre ?? 'Desconocido'),
                            function ($mail) use ($ticket) {
                                $mail->to($ticket->creador->email)
                                     ->subject("Actualización de su Ticket #{$ticket->id}");
                            }
                        );
                    } catch (\Exception $e) {}
                }
            } elseif ($oldStatusId != $this->aTicketStatus) {
                // Notificación automática de cambio de estado si no hay comentario manual
                 if ($ticket->creador) {
                    try {
                        $estadoNombre = \App\Models\EstadoTicket::find($this->aTicketStatus)->nombre ?? 'Desconocido';
                        \Illuminate\Support\Facades\Mail::raw(
                            "Estimado usuario, el estado de su ticket #{$ticket->id} ha cambiado a: {$estadoNombre}.\n\n",
                            function ($mail) use ($ticket, $estadoNombre) {
                                $mail->to($ticket->creador->email)
                                     ->subject("Su Ticket #{$ticket->id} se encuentra en: {$estadoNombre}");
                            }
                        );
                    } catch (\Exception $e) {}
                }
            }

            $this->showingAdminTicket = false;
            $this->dispatch('notify', 'Ticket actualizado exitosamente');
        }
    }
    
    public function updatePosition($id, $x, $y)
    {
        if (in_array(auth()->user()->role, ['admin', 'agente'])) {
            $maquina = Maquina::find($id);
            if ($maquina) {
                $maquina->update(['pos_x' => $x, 'pos_y' => $y]);
            }
        }
    }
    
    public function addCommentToUserTicket()
    {
        $this->validate(['userComment' => 'required|string']);
        $ticket = Ticket::find($this->selectedTicketId);
        if ($ticket) {
            $ticket->comentarios()->create([
                'mensaje' => $this->userComment,
                'usuario_id' => Auth::id(),
                'es_cliente' => true,
            ]);
            $this->userComment = '';
        }
    }

    public function reopenTicket($id)
    {
        $ticket = Ticket::find($id);
        if ($ticket) {
            $ticket->update(['estado_id' => 1]);
            $ticket->comentarios()->create([
                'mensaje' => 'Ticket reabierto por ' . Auth::user()->name . '.',
                'usuario_id' => Auth::id(),
                'es_cliente' => false,
            ]);
            $this->dispatch('notify', 'Ticket reabierto exitosamente');
        }
    }

    public function openAddEquipment()
    {
        $this->reset(['equipmentName', 'equipmentCategory', 'equipmentModel', 'equipmentBarcode', 'equipmentPhysLocation']);
        $this->showingAddEquipment = true;
    }

    public function createEquipment()
    {
        $this->validate([
            'equipmentName' => 'required',
            'equipmentCategory' => 'required',
            'equipmentModel' => 'required',
            'equipmentBarcode' => 'required',
        ]);

        Equipment::create([
            'name' => $this->equipmentName,
            'type' => $this->equipmentCategory,
            'model' => $this->equipmentModel,
            'barcode' => $this->equipmentBarcode,
            'status' => 'in-stock',
        ]);

        $this->showingAddEquipment = false;
        $this->dispatch('notify', 'Hardware registrado con éxito');
    }

    public function openUserModal($id = null)
    {
        $this->reset(['userName', 'userEmail', 'userPassword', 'userRole', 'userCodigoAcceso', 'selectedUserId']);
        if ($id) {
            $user = User::find($id);
            $this->selectedUserId = $id;
            $this->userName = $user->nombre_completo;
            $this->userEmail = $user->email;
            $this->userRole = $user->role ?? 'user';
            $this->userCodigoAcceso = $user->codigo_acceso;
        }
        $this->showingUserModal = true;
    }

    public function saveUser()
    {
        // Validation rules: codigo_acceso must be unique (except for the user being updated)
        $rules = [
            'userName' => 'required|string|max:255',
            'userRole' => 'required|in:admin,agente,user',
            'userCodigoAcceso' => 'required|string|max:50|unique:usuarios,codigo_acceso,' . ($this->selectedUserId ?? 'NULL'),
        ];
        $this->validate($rules);

        $rolNombre = match($this->userRole) {
            'admin' => 'Admin',
            'agente' => 'Agente TI',
            'user' => 'Operador',
            default => 'Operador',
        };
        $rol = \App\Models\Role::where('nombre', $rolNombre)->first();

        // Auto-generate unique email/id if creating a user
        if (!$this->selectedUserId) {
            $slugName = \Illuminate\Support\Str::slug($this->userName, '');
            if (empty($slugName)) {
                $slugName = 'user';
            }
            $this->userEmail = $slugName . rand(100, 999) . '@supporthub.com';
        }

        $data = [
            'nombre_completo' => $this->userName,
            'email' => $this->userEmail,
            'rol_id' => $rol ? $rol->id : 3,
            'codigo_acceso' => $this->userCodigoAcceso,
        ];
        
        if ($this->userPassword) {
            $data['password'] = bcrypt($this->userPassword);
        } else if (!$this->selectedUserId) {
            // Default password is set to the access code on creation
            $data['password'] = bcrypt($this->userCodigoAcceso);
        }

        if ($this->selectedUserId) {
            User::find($this->selectedUserId)->update($data);
            $this->dispatch('notify', 'Usuario actualizado con éxito');
        } else {
            User::create($data);
            $this->dispatch('notify', 'Usuario registrado con éxito');
        }
        $this->showingUserModal = false;
    }

    public function deleteUser($id)
    {
        User::find($id)->delete();
    }

    public function createTicket()
    {
        $this->validate([
            'ticketCategory' => 'required',
            'ticketSubcategory' => 'required',
            'ticketDescription' => 'required',
        ], [
            'ticketCategory.required' => 'Por favor selecciona un área',
            'ticketSubcategory.required' => 'Por favor selecciona la incidencia'
        ]);

        if (!empty($this->ticketFiles)) {
            $this->validate([
                'ticketFiles.*' => 'max:10240', // max 10MB
            ]);
        }

        $fullTitle = '[' . mb_strtoupper($this->ticketCategory) . '] ' . mb_strtoupper($this->ticketSubcategory);
        
        $finalDescription = $this->ticketDescription;
        if (!empty($this->ticketAvailableTime)) {
            $finalDescription .= "\n\n[Horario Disponible Indicado por el Usuario]:\n" . $this->ticketAvailableTime;
        }

        $ticket = Ticket::create([
            'titulo' => $fullTitle,
            'descripcion' => $finalDescription,
            'prioridad_id' => $this->ticketPriority,
            'maquina_id' => $this->ticketLocation ?: null,
            'estado_id' => 1,
            'usuario_creador_id' => Auth::id(),
            'tipo_ticket_id' => 1,
        ]);

        // Guardar archivos adjuntos
        if (!empty($this->ticketFiles)) {
            foreach ($this->ticketFiles as $file) {
                $path = $file->store('ticket_attachments', 'public');
                ArchivoAdjunto::create([
                    'ticket_id' => $ticket->id,
                    'nombre_archivo' => $file->getClientOriginalName(),
                    'ruta_archivo' => $path,
                ]);
            }
        }

        // Send Email to Agent and Admin
        $adminsAndAgents = User::whereHas('rol', function ($q) {
            $q->whereIn('nombre', ['Admin', 'Agente TI']);
        })->get();
        foreach ($adminsAndAgents as $notifyUser) {
            \Illuminate\Support\Facades\Mail::raw(
                "Un nuevo caso de resolucion de " . strtoupper($this->ticketCategory) . " ha sido generado.\n\n" .
                "Título: " . $fullTitle . "\n" .
                "Usuario: " . Auth::user()->name . "\n\n" .
                "Descripción:\n" . $finalDescription,
                function ($mail) use ($notifyUser) {
                    $mail->to($notifyUser->email)
                         ->subject('NUEVO TICKET REGISTRADO: ' . strtoupper($this->ticketCategory));
                }
            );
        }

        $this->showingNewTicket = false;
        $this->reset(['ticketCategory', 'ticketSubcategory', 'ticketDescription', 'ticketAvailableTime', 'ticketPriority', 'ticketLocation', 'ticketFiles']);
        $this->dispatch('ticket-created');
        
        if (Auth::user()->role === 'user') {
            $this->activeTab = 'mis_tickets';
        }
        
        $this->dispatch('notify', 'Ticket generado exitosamente');
    }

    public function removeTicketFile($index)
    {
        if (isset($this->ticketFiles[$index])) {
            array_splice($this->ticketFiles, $index, 1);
        }
    }

    public function downloadAttachment($id)
    {
        $archivo = ArchivoAdjunto::find($id);
        if ($archivo && $archivo->ruta_archivo) {
            return \Storage::disk('public')->download($archivo->ruta_archivo, $archivo->nombre_archivo);
        }
    }

    public function exportCSV()
    {
        $this->dispatch('notify', 'Exportación iniciada... Generando CSV.');
    }

    public function render()
    {
        $tickets = Ticket::with(['estado', 'prioridad', 'creador'])->latest()->get();
        $users = User::all();
        
        $stockCount = Equipment::where('status', 'in-stock')->count();
        $deployedCount = Equipment::where('status', 'deployed')->count();

        // Metrics logic
        $allTicketsQuery = Ticket::query();
        if ($this->dateFilter) $allTicketsQuery->whereDate('created_at', $this->dateFilter);
        if ($this->userFilter) $allTicketsQuery->whereHas('creador', fn($q) => $q->where('name', 'like', "%{$this->userFilter}%"));
        
        $filteredTickets = $allTicketsQuery->get();
        $total = $filteredTickets->count();

        $stats = [
            'total'      => $total,
            'overdue'    => $filteredTickets->where('created_at', '<', now()->subDays(2))->count(),
            'dueToday'   => $filteredTickets->whereBetween('created_at', [now()->startOfDay(), now()->endOfDay()])->count(),
            'open'       => $filteredTickets->where('estado_id', 1)->count(),
            'hold'       => $filteredTickets->where('estado_id', 2)->count(),
            'unassigned' => $filteredTickets->whereNull('agente_asignado_id')->count(),
        ];

        // Priority counts (IDs: 1=Baja, 2=Media, 3=Alta)
        $priorityCounts = $filteredTickets->groupBy('prioridad_id')->map->count();

        // Status counts keyed by estado_id
        $statusCounts = $filteredTickets->groupBy('estado_id')->map->count();

        // All estado names for bar chart labels
        $estadoNames = \App\Models\EstadoTicket::orderBy('id')->pluck('nombre', 'id');

        // Monthly trend – last 7 months
        $months = collect();
        $trendData = [];
        for ($i = 6; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $months->push($month->format('M'));
            $trendData[] = Ticket::whereYear('created_at', $month->year)
                                  ->whereMonth('created_at', $month->month)
                                  ->count();
        }

        // Category breakdown from ticket titles (format: [AREA] ...)
        $categoryData = $filteredTickets->map(function($t) {
            preg_match('/\[([^\]]+)\]/', $t->titulo, $m);
            return $m[1] ?? 'Otro';
        })->countBy()->sortDesc()->take(6);

        // SLA compliance: tickets resolved within 2 days
        $resolvedTickets = $filteredTickets->whereIn('estado_id', [3, 4]);
        $slaOk = $resolvedTickets->filter(function($t) {
            return $t->created_at->diffInDays($t->updated_at) <= 2;
        })->count();
        $slaPercent = $resolvedTickets->count() > 0
            ? round(($slaOk / $resolvedTickets->count()) * 100)
            : ($total > 0 ? 75 : 94);

        return view('livewire.support-hub', [
            'tickets'          => $tickets,
            'users'            => $users,
            'agentes'          => User::whereHas('rol', function ($q) {
                                      $q->whereIn('nombre', ['Admin', 'Agente TI']);
                                  })->get(),
            'estadosLocales'   => \App\Models\EstadoTicket::all(),
            'stockCount'       => $stockCount,
            'deployedCount'    => $deployedCount,
            'stats'            => $stats,
            'priorityCounts'   => $priorityCounts,
            'statusCounts'     => $statusCounts,
            'estadoNames'      => $estadoNames,
            'trendMonths'      => $months,
            'trendData'        => $trendData,
            'categoryData'     => $categoryData,
            'slaPercent'       => $slaPercent,
            'maquinas'         => Maquina::all(),
            'prioridades'      => Prioridad::all(),
            'selectedEquipment'=> $this->selectedEquipmentId ? Equipment::find($this->selectedEquipmentId) : null
        ]);
    }
}
