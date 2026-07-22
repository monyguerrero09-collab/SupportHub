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
use Livewire\Attributes\Url;
use DB;
use Illuminate\Support\Facades\Storage;

#[Layout('layouts.blank')]
class SupportHub extends Component
{
    use WithFileUploads;

    #[Url]
    public $activeTab = 'inicio'; // inicio, tickets, inventory, map, statistics, users, gestion_archivos
    public $ticketFiles = [];
    public $userProfileEmail = '';
    public $selectedEquipmentId = null;
    public $showingDetail = false;
    
    // Filters for Metrics
    public $dateFilter = '';
    public $statusFilter = 'Todos';
    public $plantaFilter = '';
    public $userFilter = '';
    public $searchUser = '';
    public $aTicketHoraVisita = '';
    public $aTicketTiempoResolucion = '';
    public $aTicketFiles = [];

    // Modal states
    public $showingAddEquipment = false;
    public $showingNewTicket = false;
    public $showingUserModal = false;

    // Modales de Finalización y Cancelación
    public $mostrarModalFinalizar = false;
    public $mostrarModalCancelar = false;
    public $causasSolucion = [];
    public $motivosCancelacion = [];
    public $causaSolucionId = null;
    public $motivoCancelacionId = null;
    public $detallesResolucion = '';
    public $archivoResolucion = null;
    public $visibleAlUsuario = false;

    // Form fields for New Ticket
    public $ticketCategory = null;
    public $ticketSubcategory = null;
    public $ticketDescription;
    public $ticketPriority = 1;
    public $ticketLocation;
    public $ticketAvailableTime;
    public $ticketSectorId = null;
    public $ticketPlanta = null;
    
    // User Modal interaction
    public $userComment = '';
    public $selectedTicketId = null;

    // Form fields para las pestañas Causas y Motivos
    public $tabSelectedTicketId = null;
    public $tabTicketModel = null;
    
    // Admin Ticket Modal properties
    public $showingAdminTicket = false;
    


    public $aTicketId = null;
    public $aTicketPriority = null;
    public $aTicketStatus = null;
    public $notificationsList = [];
    public $notifCount = 0;
    public $adminTicketModel = null;
    public $aTicketAgent = null;
    public $aTicketKoment = '';

    public $SECTORS = [
        ['id' => 'fg-sd', 'name' => 'Service Desk Grupo FG', 'icon' => '🎧']
    ];

    public $AREAS = [
        ['id' => 'abas', 'name' => 'ABAS', 'services' => 1, 'icon' => 'FileText'],
        ['id' => 'sec', 'name' => 'Seguridad TI', 'services' => 5, 'icon' => 'ShieldCheck'],
        ['id' => 'red', 'name' => 'Redes/WiFi', 'services' => 3, 'icon' => 'Globe'],
        ['id' => 'serv', 'name' => 'Archivos', 'services' => 1, 'icon' => 'HardDrive'],
        ['id' => 'software', 'name' => 'Software y Licencias', 'services' => 4, 'icon' => 'Cpu'],
        ['id' => 'print', 'name' => 'Impresión', 'services' => 3, 'icon' => 'Printer'],
        ['id' => 'equipos', 'name' => 'Equipos', 'services' => 2, 'icon' => 'Monitor'],
    ];

    public $SERVICES_BY_AREA = [
        'abas' => [
            ['id' => 'abas_serv', 'name' => 'Plataforma ABAS', 'description' => 'Gestión de accesos, impresión y otras incidencias de ABAS.', 'icon' => 'FileText'],
        ],
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
        ],
        'vig_camara' => [
            ['id' => 'vig_camara_serv', 'name' => 'Cámara de Seguridad', 'description' => 'Incidencias con cámaras y monitoreo.', 'icon' => 'Video'],
        ],
        'vig_equipo' => [
            ['id' => 'vig_equipo_serv', 'name' => 'Equipo de Grabación / NVR', 'description' => 'Servidores de video y grabadoras.', 'icon' => 'Monitor'],
        ],
        'vig_wifi' => [
            ['id' => 'vig_wifi_serv', 'name' => 'Conexión de Vigilancia', 'description' => 'Enlace inalámbrico o cableado de seguridad.', 'icon' => 'Globe'],
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
        'abas_serv' => [['id' => 'abas1', 'name' => 'No me deja entrar'], ['id' => 'abas2', 'name' => 'No deja imprimir'], ['id' => 'abas3', 'name' => 'Otro']],
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
        'vig_camara_serv' => [
            ['id' => 'vc1', 'name' => 'La cámara no funciona'],
            ['id' => 'vc2', 'name' => 'Se trabó'],
            ['id' => 'vc3', 'name' => 'No graba'],
            ['id' => 'vc4', 'name' => 'Pantalla negra'],
            ['id' => 'vc5', 'name' => 'Otro']
        ],
        'vig_equipo_serv' => [
            ['id' => 've1', 'name' => 'No enciende'],
            ['id' => 've2', 'name' => 'Está lento'],
            ['id' => 've3', 'name' => 'Problemas con la pantalla'],
            ['id' => 've4', 'name' => 'No funciona'],
            ['id' => 've5', 'name' => 'Otro']
        ],
        'vig_wifi_serv' => [
            ['id' => 'vw1', 'name' => 'No hay internet'],
            ['id' => 'vw2', 'name' => 'Internet lento'],
            ['id' => 'vw3', 'name' => 'Se cae la conexión'],
            ['id' => 'vw4', 'name' => 'Otro']
        ]
    ];

    public function mount() {
        $this->CATEGORIES_BY_SERVICE['ntb'] = $this->PROBLEMAS_EQUIPOS;
        $this->CATEGORIES_BY_SERVICE['wks'] = $this->PROBLEMAS_EQUIPOS;

        // Load database sectors if they exist, otherwise fallback to static
        if (\Schema::hasTable('sectors')) {
            \App\Models\Sector::firstOrCreate(['nombre' => 'Vigilancia']);
            $dbSectors = \App\Models\Sector::all();
            if ($dbSectors->isNotEmpty()) {
                $this->SECTORS = $dbSectors->map(fn($s) => [
                    'id' => $s->id,
                    'name' => $s->nombre,
                    'icon' => $s->nombre === 'Vigilancia' ? '📹' : '🏢'
                ])->toArray();
            }
        }

        if (!request()->has('activeTab')) {
            $this->activeTab = 'inicio';
        }

        if (Auth::check()) {
            $this->userProfileEmail = Auth::user()->email;
            
            $userRole = Auth::user()->role;
            $dismissedIds = \Illuminate\Support\Facades\Cache::get('dismissed_notifications_' . Auth::id(), []);
            if ($userRole === 'user') {
                $this->notifCount = Ticket::where('usuario_creador_id', Auth::id())
                    ->whereNotIn('id', $dismissedIds)
                    ->count();
            } else {
                $this->notifCount = Ticket::whereNotIn('id', $dismissedIds)->count();
            }
        }

        $this->causasSolucion = \App\Models\CausaSolucion::orderBy('nombre')->get();
        $this->motivosCancelacion = \App\Models\MotivoCancelacion::orderBy('nombre')->get();
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
    public $userTelefono;
    public $userPassword;
    public $userRole = 'user';
    public $userCodigoAcceso;
    public $selectedUserId = null;

    protected $listeners = [
        'openEquipmentDetail' => 'openEquipmentDetail',
        'openAddEquipment' => 'openAddEquipment',
        'openNewTicket' => 'openNewTicket',
        'refresh-inventory' => '$refresh',
        'viewDocument' => 'handleViewDocument'
    ];

    public function handleViewDocument($uniqueId)
    {
        $this->activeTab = 'gestion_archivos';
        $this->dispatch('select-document', uniqueId: $uniqueId)->to(DocumentViewer::class);
    }

    public function viewAttachment($id)
    {
        $this->showingDetail = false;
        $this->handleViewDocument('tk_' . $id);
    }

    public function setTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function refreshMetrics()
    {
        $this->dispatch('notify', 'Métricas actualizadas desde la base de datos');
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
        $ticket = Ticket::with(['creador', 'sector', 'archivosAdjuntos'])->find($id);
        if ($ticket) {
            $this->adminTicketModel = $ticket;
            $this->aTicketId = $id;
            $this->aTicketPriority = $ticket->prioridad_id;
            $this->aTicketStatus = $ticket->estado_id;
            $this->aTicketAgent = $ticket->agente_asignado_id;
            $this->aTicketHoraVisita = $ticket->hora_visita;
            $this->aTicketTiempoResolucion = $ticket->tiempo_resolucion;
            $this->aTicketKoment = '';
            $this->aTicketFiles = [];
            $this->showingAdminTicket = true;
        }
    }


    public function viewNotificationTicket($id)
    {
        if (Auth::check() && in_array(Auth::user()->role, ['admin', 'agente'])) {
            $this->activeTab = 'tickets';
            $this->viewAdminTicket($id);
        } else {
            $this->activeTab = 'mis_tickets';
            $this->viewUserTicket($id);
        }
    }

    public function dismissNotification($id)
    {
        $dismissed = \Illuminate\Support\Facades\Cache::get('dismissed_notifications_' . Auth::id(), []);
        $dismissed[] = (int)$id;
        \Illuminate\Support\Facades\Cache::forever('dismissed_notifications_' . Auth::id(), array_unique($dismissed));
        $this->notifCount = max(0, $this->notifCount - 1);
    }

    public function clearAllNotifications()
    {
        $dismissed = \Illuminate\Support\Facades\Cache::get('dismissed_notifications_' . Auth::id(), []);
        foreach ($this->notificationsList as $notif) {
            $dismissed[] = (int)$notif['id'];
        }
        \Illuminate\Support\Facades\Cache::forever('dismissed_notifications_' . Auth::id(), array_unique($dismissed));
        $this->notificationsList = [];
        $this->notifCount = 0;
    }

    public function updatedTabSelectedTicketId($value)
    {
        if ($value) {
            $this->tabTicketModel = Ticket::with(['creador', 'estado', 'prioridad', 'sector'])->find($value);
        } else {
            $this->tabTicketModel = null;
        }
    }

    public function updatedActiveTab($value)
    {
        if ($value === 'causas') {
            $this->causasSolucion = \App\Models\CausaSolucion::orderBy('nombre')->get();
            $this->tabSelectedTicketId = null;
            $this->tabTicketModel = null;
            $this->reset(['causaSolucionId', 'detallesResolucion', 'archivoResolucion']);
        } elseif ($value === 'motivos') {
            $this->motivosCancelacion = \App\Models\MotivoCancelacion::orderBy('nombre')->get();
            $this->tabSelectedTicketId = null;
            $this->tabTicketModel = null;
            $this->reset(['motivoCancelacionId', 'detallesResolucion', 'archivoResolucion', 'visibleAlUsuario']);
        }
    }

    public function guardarFinalizacionDesdeTab()
    {
        $this->validate([
            'tabSelectedTicketId' => 'required',
            'causaSolucionId' => 'required',
            'detallesResolucion' => 'required'
        ]);

        $ticket = Ticket::find($this->tabSelectedTicketId);
        if (!$ticket) return;

        $path = null;
        if ($this->archivoResolucion) {
            $path = $this->archivoResolucion->store('resoluciones', 'public');
        }

        $estadoId = \App\Models\EstadoTicket::where('nombre', 'Completado')->value('id') ?? 3;
        $ticket->update(['estado_id' => $estadoId]);

        \App\Models\HistorialTicket::create([
            'ticket_id' => $ticket->id,
            'usuario_id' => \Auth::id(),
            'accion' => 'Ticket Finalizado',
            'causa_solucion_id' => $this->causaSolucionId,
            'detalles' => $this->detallesResolucion,
            'adjunto_path' => $path,
            'visible_para_usuario' => true,
        ]);

        $this->dispatch('ticket-updated');
        $this->dispatch('notify', 'Ticket Finalizado Exitosamente');
        $this->tabSelectedTicketId = null;
        $this->tabTicketModel = null;
        $this->reset(['causaSolucionId', 'detallesResolucion', 'archivoResolucion']);
    }

    public function guardarCancelacionDesdeTab()
    {
        $this->validate([
            'tabSelectedTicketId' => 'required',
            'motivoCancelacionId' => 'required',
            'detallesResolucion' => 'required'
        ]);

        $ticket = Ticket::find($this->tabSelectedTicketId);
        if (!$ticket) return;

        $path = null;
        if ($this->archivoResolucion) {
            $path = $this->archivoResolucion->store('resoluciones', 'public');
        }

        $estadoId = \App\Models\EstadoTicket::where('nombre', 'Cancelado')->value('id') ?? 4;
        $ticket->update(['estado_id' => $estadoId]);

        \App\Models\HistorialTicket::create([
            'ticket_id' => $ticket->id,
            'usuario_id' => \Auth::id(),
            'accion' => 'Ticket Cancelado',
            'motivo_cancelacion_id' => $this->motivoCancelacionId,
            'detalles' => $this->detallesResolucion,
            'adjunto_path' => $path,
            'visible_para_usuario' => $this->visibleAlUsuario,
        ]);

        $this->dispatch('ticket-updated');
        $this->dispatch('notify', 'Ticket Cancelado Exitosamente');
        $this->tabSelectedTicketId = null;
        $this->tabTicketModel = null;
        $this->reset(['motivoCancelacionId', 'detallesResolucion', 'archivoResolucion', 'visibleAlUsuario']);
    }

    public function updateAdminTicket()
    {
        $ticket = Ticket::find($this->aTicketId);
        if ($ticket) {
            $oldStatusId = $ticket->estado_id;
            $oldHoraVisita = $ticket->hora_visita;

            $ticket->update([
                'prioridad_id' => $this->aTicketPriority,
                'estado_id' => $this->aTicketStatus,
                'agente_asignado_id' => $this->aTicketAgent ?: null,
                'hora_visita' => $this->aTicketHoraVisita ?: null,
                'tiempo_resolucion' => $this->aTicketTiempoResolucion ?: null,
            ]);

            // Save files uploaded in the management modal
            if (!empty($this->aTicketFiles)) {
                foreach ($this->aTicketFiles as $file) {
                    $path = $file->store('ticket_attachments', 'public');
                    ArchivoAdjunto::create([
                        'ticket_id' => $ticket->id,
                        'nombre_archivo' => $file->getClientOriginalName(),
                        'ruta_archivo' => $path,
                        'visible_operadores' => true,
                    ]);
                }
            }
            $this->aTicketFiles = [];

            $visitaText = $this->aTicketHoraVisita ? "\n\nHora de visita programada: " . $this->aTicketHoraVisita : "";
            $resolucionText = $this->aTicketTiempoResolucion ? "\n\nTiempo estimado de resolución: " . $this->aTicketTiempoResolucion . " minutos" : "";
            $extraText = $visitaText . $resolucionText;

            // Add note/comment if written
            if (!empty($this->aTicketKoment)) {
                $ticket->comentarios()->create([
                    'mensaje' => $this->aTicketKoment,
                    'usuario_id' => Auth::id(),
                    'es_cliente' => false,
                ]);

                // Notificación al usuario eliminada por solicitud (WhatsApp solo para admins/agentes)
            } elseif ($oldStatusId != $this->aTicketStatus) {
                // Notificación automática de cambio de estado eliminada
            } elseif ($oldHoraVisita != $this->aTicketHoraVisita) {
                // Notificación de cambio de hora de visita eliminada
            }

            $this->showingAdminTicket = false;
            $this->dispatch('notify', 'Ticket actualizado exitosamente');
        }
    }
    
    public function abrirModalFinalizar($id)
    {
        $this->aTicketId = $id;
        $this->mostrarModalFinalizar = true;
        $this->mostrarModalCancelar = false;
        $this->causasSolucion = \App\Models\CausaSolucion::orderBy('nombre')->get();
        $this->reset(['causaSolucionId', 'detallesResolucion', 'archivoResolucion']);
    }

    public function abrirModalCancelar($id)
    {
        $this->aTicketId = $id;
        $this->mostrarModalCancelar = true;
        $this->mostrarModalFinalizar = false;
        $this->visibleAlUsuario = false;
        $this->motivosCancelacion = \App\Models\MotivoCancelacion::orderBy('nombre')->get();
        $this->reset(['motivoCancelacionId', 'detallesResolucion', 'archivoResolucion']);
    }

    public function cerrarModalResolucion()
    {
        $this->mostrarModalFinalizar = false;
        $this->mostrarModalCancelar = false;
    }

    public function guardarFinalizacion()
    {
        $this->validate([
            'causaSolucionId' => 'required',
            'detallesResolucion' => 'required'
        ]);

        $ticket = Ticket::find($this->aTicketId);
        if (!$ticket) return;

        $path = null;
        if ($this->archivoResolucion) {
            $path = $this->archivoResolucion->store('resoluciones', 'public');
        }

        $estadoId = \App\Models\EstadoTicket::where('nombre', 'Completado')->value('id') ?? 3;
        $ticket->update(['estado_id' => $estadoId]);

        \App\Models\HistorialTicket::create([
            'ticket_id' => $ticket->id,
            'usuario_id' => Auth::id(),
            'accion' => 'Ticket Finalizado',
            'causa_solucion_id' => $this->causaSolucionId,
            'detalles' => $this->detallesResolucion,
            'adjunto_path' => $path,
            'visible_para_usuario' => true,
        ]);

        $this->dispatch('ticket-updated');
        $this->dispatch('notify', 'Ticket Finalizado Exitosamente');
        $this->cerrarModalResolucion();
        $this->showingAdminTicket = false;
        
        // Refrescar lista
        if (in_array(auth()->user()->role, ['admin', 'agente'])) {
            // Recargar datos si es necesario
        }
    }

    public function guardarCancelacion()
    {
        $this->validate([
            'motivoCancelacionId' => 'required',
            'detallesResolucion' => 'required'
        ]);

        $ticket = Ticket::find($this->aTicketId);
        if (!$ticket) return;

        $path = null;
        if ($this->archivoResolucion) {
            $path = $this->archivoResolucion->store('resoluciones', 'public');
        }

        $estadoId = \App\Models\EstadoTicket::where('nombre', 'Cancelado')->value('id') ?? 4;
        $ticket->update(['estado_id' => $estadoId]);

        \App\Models\HistorialTicket::create([
            'ticket_id' => $ticket->id,
            'usuario_id' => Auth::id(),
            'accion' => 'Ticket Cancelado',
            'motivo_cancelacion_id' => $this->motivoCancelacionId,
            'detalles' => $this->detallesResolucion,
            'adjunto_path' => $path,
            'visible_para_usuario' => $this->visibleAlUsuario,
        ]);

        $this->dispatch('ticket-updated');
        $this->dispatch('notify', 'Ticket Cancelado Exitosamente');
        $this->cerrarModalResolucion();
        $this->showingAdminTicket = false;
    }

    public function downloadAttachmentPath($path)
    {
        if (Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->download($path);
        }
        $this->dispatch('notify', 'Archivo no encontrado');
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

    public function openNewTicket($stationId = null)
    {
        $this->reset(['ticketCategory', 'ticketSubcategory', 'ticketDescription', 'ticketAvailableTime', 'ticketPriority', 'ticketFiles']);
        if ($stationId) {
            $this->ticketLocation = $stationId;
        }
        $this->activeTab = 'generar_ticket';
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

        $maquinaId = null;
        $status = 'in-stock';
        $installedAt = null;

        if (is_numeric($this->equipmentPhysLocation)) {
            $maquinaId = (int)$this->equipmentPhysLocation;
            $status = 'deployed';
            $installedAt = now();
        }

        Equipment::create([
            'name' => $this->equipmentName,
            'type' => $this->equipmentCategory,
            'model' => $this->equipmentModel,
            'barcode' => $this->equipmentBarcode,
            'status' => $status,
            'maquina_id' => $maquinaId,
            'installed_at' => $installedAt,
        ]);

        $this->showingAddEquipment = false;
        $this->dispatch('notify', 'Hardware registrado con éxito');
        $this->dispatch('refresh-inventory');
    }

    public function openUserModal($id = null)
    {
        if ($id && Auth::user()->role !== 'admin') {
            $this->dispatch('notify', 'No tienes permisos para editar usuarios');
            return;
        }
        $this->reset(['userName', 'userEmail', 'userTelefono', 'userPassword', 'userRole', 'userCodigoAcceso', 'selectedUserId']);
        if ($id) {
            $user = User::find($id);
            $this->selectedUserId = $id;
            $this->userName = $user->nombre_completo;
            $this->userEmail = $user->email;
            $this->userTelefono = $user->telefono;
            $this->userRole = $user->role ?? 'user';
            $this->userCodigoAcceso = $user->codigo_acceso;
        }
        $this->showingUserModal = true;
    }

    public function saveUser()
    {
        if ($this->selectedUserId && Auth::user()->role !== 'admin') {
            $this->dispatch('notify', 'No tienes permisos para editar usuarios');
            return;
        }

        // Validation rules: codigo_acceso must be unique (except for the user being updated)
        $rules = [
            'userName' => 'required|string|max:255',
            'userTelefono' => 'nullable|string|max:20',
            'userRole' => 'required|in:admin,agente,user',
            'userCodigoAcceso' => 'required|string|max:50|unique:usuarios,codigo_acceso,' . ($this->selectedUserId ?? 'NULL'),
        ];
        $this->validate($rules);

        $rolNombre = match($this->userRole) {
            'admin' => 'Admin',
            'agente' => 'Agente TI',
            'user' => 'Usuario',
            default => 'Usuario',
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
            'telefono' => $this->userTelefono,
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
        if (Auth::user()->role !== 'admin') {
            $this->dispatch('notify', 'No tienes permisos para eliminar usuarios');
            return;
        }

        if (Auth::id() == $id) {
            $this->dispatch('notify', 'No puedes eliminar tu propio usuario');
            return;
        }

        $user = User::find($id);
        if ($user) {
            $user->delete();

            // Clear active database sessions for this user
            \Illuminate\Support\Facades\DB::table('sessions')->where('user_id', $id)->delete();

            // Clear their notifications cache and flush caching systems
            \Illuminate\Support\Facades\Cache::forget('dismissed_notifications_' . $id);
            \Illuminate\Support\Facades\Cache::flush();

            $this->dispatch('notify', 'Usuario eliminado con éxito de la base de datos y sesiones/caché depuradas');
        }
    }

    public function updateProfileEmail()
    {
        $this->validate([
            'userProfileEmail' => 'required|email|unique:usuarios,email,' . Auth::id(),
        ]);

        $user = Auth::user();
        $user->email = $this->userProfileEmail;
        $user->save();

        session()->flash('profile_success', '¡Correo electrónico actualizado correctamente!');
    }

    public function createIntuitiveTicket($data)
    {
        $this->ticketSectorId = $data['sectorId'] ?? null;
        $this->ticketCategory = $data['category'] ?? null;
        $this->ticketSubcategory = $data['subcategory'] ?? null;
        $this->ticketDescription = $data['description'] ?? null;
        $this->ticketPlanta = $data['planta'] ?? null;
        $this->ticketPriority = $data['priority'] ?? 2;
        $this->ticketLocation = $data['location'] ?? null;

        $this->createTicket();
    }

    public function createManualTicket($data)
    {
        $this->ticketSectorId = $data['sectorId'] ?? null;
        $this->ticketCategory = 'SOPORTE';
        $this->ticketSubcategory = $data['subcategory'] ?? null;
        $this->ticketDescription = $data['description'] ?? null;
        $this->ticketPlanta = $data['planta'] ?? null;
        $this->ticketPriority = 2;
        $this->ticketLocation = $data['location'] ?? null;

        $this->createTicket();
    }

    public function createTicket()
    {
        $this->validate([
            'ticketCategory' => 'required',
            'ticketSubcategory' => 'required',
            'ticketDescription' => 'required',
            'ticketPlanta' => 'required',
        ], [
            'ticketCategory.required' => 'Por favor selecciona un área',
            'ticketSubcategory.required' => 'Por favor selecciona la incidencia',
            'ticketPlanta.required' => 'Por favor selecciona una planta'
        ]);

        // Archivos sin límite de peso ni formato (gestionado por php.ini y livewire global config)

        $fullTitle = '[' . mb_strtoupper($this->ticketCategory) . '] ' . mb_strtoupper($this->ticketSubcategory);
        
        $finalDescription = $this->ticketDescription;
        if (!empty($this->ticketAvailableTime)) {
            $finalDescription .= "\n\n[Horario Disponible Indicado por el Usuario]:\n" . $this->ticketAvailableTime;
        }

        $sectorId = $this->ticketSectorId;
        if (empty($sectorId) && !empty($this->ticketLocation)) {
            $machine = Maquina::find($this->ticketLocation);
            if ($machine) {
                $sectorId = $machine->sector_id;
            }
        }

        $ticket = Ticket::create([
            'titulo' => $fullTitle,
            'descripcion' => $finalDescription,
            'prioridad_id' => $this->ticketPriority,
            'maquina_id' => $this->ticketLocation ?: null,
            'sector_id' => $sectorId ?: null,
            'estado_id' => 1,
            'usuario_creador_id' => Auth::id(),
            'tipo_ticket_id' => 1,
            'planta' => $this->ticketPlanta,
        ]);

        // Guardar archivos adjuntos
        if (!empty($this->ticketFiles)) {
            foreach ($this->ticketFiles as $file) {
                $path = $file->store('ticket_attachments', 'public');
                ArchivoAdjunto::create([
                    'ticket_id' => $ticket->id,
                    'nombre_archivo' => $file->getClientOriginalName(),
                    'ruta_archivo' => $path,
                    'visible_operadores' => true,
                ]);
            }
        }

        // Notify Admins and Agents via WhatsApp (Node.js microservice)
        try {
            $adminsAndAgents = \App\Models\User::whereHas('rol', function ($q) {
                $q->whereIn('nombre', ['Admin', 'Agente TI']);
            })->whereNotNull('telefono')->get();

            \Illuminate\Support\Facades\Log::info('--- PRUEBA WHATSAPP ---');
            \Illuminate\Support\Facades\Log::info('Usuarios encontrados con teléfono: ' . $adminsAndAgents->count());

            $msgForStaff = "NUEVO TICKET REQUERIDO 🚨\n\nTicket ID: #{$ticket->id}\nDe: " . (Auth::user()->nombre_completo ?? 'Usuario') . "\nCategoría: {$ticket->titulo}\n\nIngresa al sistema para revisar los detalles.";

            foreach ($adminsAndAgents as $notifyUser) {
                if (!empty($notifyUser->telefono)) {
                    \Illuminate\Support\Facades\Log::info('Enviando a: ' . $notifyUser->telefono);

                    $response = \Illuminate\Support\Facades\Http::post('http://127.0.0.1:3000/api/send-notification', [
                        'token' => 'OMEGA123456',
                        'phone' => $notifyUser->telefono,
                        'message' => $msgForStaff
                    ]);

                    \Illuminate\Support\Facades\Log::info('Respuesta Node: ' . $response->body());
                }
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error sending whatsapp notification: ' . $e->getMessage());
        }

        $this->showingNewTicket = false;
        $this->reset(['ticketCategory', 'ticketSubcategory', 'ticketDescription', 'ticketAvailableTime', 'ticketPriority', 'ticketLocation', 'ticketFiles', 'ticketSectorId']);
        $this->dispatch('ticket-created');
        
        
        
        $this->dispatch('notify', 'Ticket generado exitosamente');
    }

    public function removeTicketFile($index)
    {
        if (isset($this->ticketFiles[$index])) {
            array_splice($this->ticketFiles, $index, 1);
        }
    }

    public function removeAdminTicketFile($index)
    {
        if (isset($this->aTicketFiles[$index])) {
            array_splice($this->aTicketFiles, $index, 1);
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

    public function deleteTicket($ticketId)
    {
        $ticket = Ticket::find($ticketId);
        if ($ticket) {
            if (Auth::user()->role === 'user' && $ticket->usuario_creador_id !== Auth::id()) {
                $this->dispatch('notify', 'No tienes permiso para eliminar este ticket.');
                return;
            }

            // Eliminar archivos del almacenamiento
            $adjuntos = \App\Models\ArchivoAdjunto::where('ticket_id', $ticket->id)->get();
            foreach ($adjuntos as $adjunto) {
                if ($adjunto->ruta_archivo) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($adjunto->ruta_archivo);
                }
            }
            \App\Models\ArchivoAdjunto::where('ticket_id', $ticket->id)->delete();
            
            // Eliminar comentarios asociados
            $ticket->comentarios()->delete();
            
            // Eliminar cualquier historial previo
            \App\Models\HistorialTicket::where('ticket_id', $ticket->id)->delete();

            // Eliminar ticket definitivamente
            $ticket->delete();
            if (method_exists($ticket, 'forceDelete')) {
                $ticket->forceDelete();
            }

            $this->dispatch('notify', 'Ticket eliminado permanentemente.');
        }
    }

    public function render()
    {
        // Dynamic Notification List Query
        if (Auth::check()) {
            $userRole = Auth::user()->role;
            $dismissedIds = \Illuminate\Support\Facades\Cache::get('dismissed_notifications_' . Auth::id(), []);
            if ($userRole === 'user') {
                $notifTickets = Ticket::where('usuario_creador_id', Auth::id())
                    ->whereNotIn('id', $dismissedIds)
                    ->with(['estado'])
                    ->latest()
                    ->take(5)
                    ->get();
                $this->notificationsList = $notifTickets->map(fn($t) => [
                    'id' => $t->id,
                    'icon' => $t->estado_id == 3 ? '✅' : ($t->estado_id == 4 ? '🔒' : '🎫'),
                    'title' => 'Ticket #' . str_pad($t->id, 5, '0', STR_PAD_LEFT) . ' ' . ($t->estado?->nombre ?? 'Abierto'),
                    'msg' => $t->titulo ?: $t->descripcion,
                    'time' => $t->created_at->diffForHumans(),
                    'read' => false
                ])->toArray();
            } else {
                $notifTickets = Ticket::whereNotIn('id', $dismissedIds)
                    ->with(['creador', 'estado'])
                    ->latest()
                    ->take(5)
                    ->get();
                $this->notificationsList = $notifTickets->map(fn($t) => [
                    'id' => $t->id,
                    'icon' => '🎫',
                    'title' => 'Nuevo Ticket #' . str_pad($t->id, 5, '0', STR_PAD_LEFT),
                    'msg' => 'De ' . ($t->creador?->nombre_completo ?? 'Sistema') . ' - ' . ($t->titulo ?: $t->descripcion),
                    'time' => $t->created_at->diffForHumans(),
                    'read' => false
                ])->toArray();
            }
            $currentCount = count($this->notificationsList);
            if ($currentCount > $this->notifCount) {
                $this->dispatch('play-notification-sound');
            }
            $this->notifCount = $currentCount;
        } else {
            $this->notificationsList = [];
            $this->notifCount = 0;
        }

        $ticketsQuery = Ticket::with(['estado', 'prioridad', 'creador', 'agente'])->latest();
        $ticketsQuery->whereNotIn('estado_id', [3, 4]);
        if ($this->statusFilter !== 'Todos') {
            $ticketsQuery->whereHas('estado', function($q) {
                if ($this->statusFilter === 'Abierto') {
                    $q->where('nombre', 'Abierto');
                } elseif ($this->statusFilter === 'En Proceso') {
                    $q->where('nombre', 'En Progreso');
                } elseif ($this->statusFilter === 'Resuelto') {
                    $q->where('nombre', 'Resuelto');
                } elseif ($this->statusFilter === 'Cerrado') {
                    $q->where('nombre', 'Cerrado');
                }
            });
        }
        if ($this->dateFilter) {
            $ticketsQuery->whereDate('created_at', $this->dateFilter);
        }
        if ($this->plantaFilter) {
            $ticketsQuery->where('planta', $this->plantaFilter);
        }
        $tickets = $ticketsQuery->get();

        // Historial tickets: only resolved (3) and closed (4) tickets for admin/agente
        $historialTickets = [];
        if (Auth::check() && in_array(Auth::user()->role, ['admin', 'agente'])) {
            $historialQuery = Ticket::with(['estado', 'prioridad', 'creador', 'agente'])
                ->whereIn('estado_id', [3, 4, 5])
                ->latest();
            
            if ($this->dateFilter) {
                $historialQuery->whereDate('created_at', $this->dateFilter);
            }
            if ($this->plantaFilter) {
                $historialQuery->where('planta', $this->plantaFilter);
            }
            $historialTickets = $historialQuery->get();
        }
        
        $usersQuery = User::orderBy('nombre_completo', 'asc');
        if (!empty($this->searchUser)) {
            $usersQuery->where(function($q) {
                $q->where('nombre_completo', 'like', '%' . $this->searchUser . '%')
                  ->orWhere('email', 'like', '%' . $this->searchUser . '%')
                  ->orWhere('codigo_acceso', 'like', '%' . $this->searchUser . '%');
            });
        }
        $users = $usersQuery->get();
        
        $stockCount = Equipment::where('status', 'in-stock')->count();
        $deployedCount = Equipment::where('status', 'deployed')->count();

        // Metrics logic
        $allTicketsQuery = Ticket::query();
        if ($this->dateFilter) $allTicketsQuery->whereDate('created_at', $this->dateFilter);
        if ($this->userFilter) $allTicketsQuery->whereHas('creador', fn($q) => $q->where('name', 'like', "%{$this->userFilter}%"));
        
        $filteredTickets = $allTicketsQuery->get();
        $total = $filteredTickets->count();

        $stats = [
            'total'       => $total,
            'overdue'     => $filteredTickets->where('created_at', '<', now()->subDays(2))->count(),
            'dueToday'    => $filteredTickets->whereBetween('created_at', [now()->startOfDay(), now()->endOfDay()])->count(),
            'closedToday' => $filteredTickets->whereIn('estado_id', [3, 4])->whereBetween('updated_at', [now()->startOfDay(), now()->endOfDay()])->count(),
            'open'        => $filteredTickets->where('estado_id', 1)->count(),
            'hold'        => $filteredTickets->where('estado_id', 2)->count(),
            'unassigned'  => $filteredTickets->whereNull('agente_asignado_id')->count(),
            'assigned'    => $filteredTickets->whereNotNull('agente_asignado_id')->count(),
            'canceled'    => $filteredTickets->where('estado_id', 5)->count(), // Suponiendo 5 como Cancelado
            'inv_total'   => Equipment::count(),
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
        $trendClosedData = [];
        $trendCanceledData = [];
        for ($i = 6; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $months->push($month->format('M'));
            $trendData[] = Ticket::whereYear('created_at', $month->year)
                                  ->whereMonth('created_at', $month->month)
                                  ->count();
            $trendClosedData[] = Ticket::whereYear('updated_at', $month->year)
                                        ->whereMonth('updated_at', $month->month)
                                        ->whereIn('estado_id', [3, 4])
                                        ->count();
            $trendCanceledData[] = Ticket::whereYear('updated_at', $month->year)
                                          ->whereMonth('updated_at', $month->month)
                                          ->where('estado_id', 5)
                                          ->count();
        }

        // Category breakdown from department relationship (Areas)
        $categoryData = $filteredTickets->map(function($t) {
            return $t->departamento ? $t->departamento->nombre : 'Sin Área';
        })->countBy()->sortDesc()->take(6);

        // Planta counts (1 vs 2)
        $plantaCounts = [
            'Planta 1' => $filteredTickets->where('planta', 1)->count(),
            'Planta 2' => $filteredTickets->where('planta', 2)->count(),
        ];

        // Incidencias más frecuentes (subcategory)
        $frequentIncidents = $filteredTickets->map(function($t) {
            preg_match('/\]\s*(.+)$/i', $t->titulo, $m);
            return isset($m[1]) ? trim($m[1]) : $t->titulo;
        })->countBy()->sortDesc()->take(6);

        // SLA compliance: tickets resolved within 2 days
        $resolvedTickets = $filteredTickets->whereIn('estado_id', [3, 4]);
        $slaOk = $resolvedTickets->filter(function($t) {
            return $t->created_at->diffInDays($t->updated_at) <= 2;
        })->count();
        $slaPercent = $resolvedTickets->count() > 0
            ? round(($slaOk / $resolvedTickets->count()) * 100)
            : ($total > 0 ? 75 : 94);

        $last30DaysTickets = Ticket::where('created_at', '>=', now()->subDays(30))->get();
        $last30DaysTotal = $last30DaysTickets->count();
        $last30DaysClosed = $last30DaysTickets->whereIn('estado_id', [3, 4])->count();
        $last30DaysRate = $last30DaysTotal > 0 ? round(($last30DaysClosed / $last30DaysTotal) * 100) : 94;

        return view('livewire.support-hub', [
            'tickets'          => $tickets,
            'historialTickets' => $historialTickets,
            'users'            => $users,
            'agentes'          => User::whereHas('rol', function ($q) {
                                      $q->whereIn('nombre', ['Admin', 'Agente TI']);
                                  })->get(),
            'estadosLocales'   => \App\Models\EstadoTicket::whereIn('id', [1, 2])->get(),
            'stockCount'       => $stockCount,
            'deployedCount'    => $deployedCount,
            'stats'            => $stats,
            'priorityCounts'   => $priorityCounts,
            'statusCounts'     => $statusCounts,
            'estadoNames'      => $estadoNames,
            'trendMonths'      => $months,
            'trendData'        => $trendData,
            'trendClosedData'  => $trendClosedData,
            'trendCanceledData'=> $trendCanceledData,
            'last30DaysTotal'  => $last30DaysTotal,
            'last30DaysClosed' => $last30DaysClosed,
            'last30DaysRate'   => $last30DaysRate,
            'categoryData'     => $categoryData,
            'plantaCounts'     => $plantaCounts,
            'frequentIncidents'=> $frequentIncidents,
            'slaPercent'       => $slaPercent,
            'maquinas'         => Maquina::all(),
            'prioridades'      => Prioridad::all(),
            'causasSolucion'   => \App\Models\CausaSolucion::all(),
            'motivosCancelacion'=> \App\Models\MotivoCancelacion::all(),
            'selectedEquipment'=> $this->selectedEquipmentId ? Equipment::find($this->selectedEquipmentId) : null
        ]);
    }
}
