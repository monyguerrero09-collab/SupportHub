<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Equipment;
use App\Models\ArchivoAdjunto;
use App\Models\Documento;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class DocumentViewer extends Component
{
    use WithFileUploads;

    protected $listeners = [
        'select-document' => 'selectDocument',
    ];

    // Search and filters
    public $search = '';
    public $filterSource = 'all'; // all, tickets, inventory, general
    public $filterType = 'all';   // all, pdf, image, word, text, other

    // Viewer selection state
    public $selectedDocId = null; // e.g., 'eq_1', 'tk_5', 'gen_3'
    public $selectedDoc = null;
    public $textContent = null;

    // Upload general document properties
    public $generalFile;
    public $generalFileName = '';
    public $generalCategory = 'Manual'; // Manual, Política, Guía, General
    public $generalEquipmentId = null;
    public $generalArea = null;
    public $generalDescription = '';
    public $generalAuthor = '';
    public $generalLicense = 'no-especificada';
    public $generalVisibleOperadores = false; // Control de visibilidad para usuarios
    public $generalDestinatarioId = null; // Dirigido a usuario específico
    public $showingUploadModal = false;

    protected $rules = [
        'generalFile' => 'required|file|max:51200', // max 50MB para soportar videos
        'generalFileName' => 'required|string|max:255',
        'generalCategory' => 'required|string|in:Manual,Política,Guía,General',
        'generalEquipmentId' => 'nullable|exists:equipment,id',
        'generalArea' => 'nullable|string',
        'generalDescription' => 'nullable|string|max:1000',
        'generalAuthor' => 'required|string|max:255',
        'generalLicense' => 'required|string|max:50',
        'generalVisibleOperadores' => 'boolean',
        'generalDestinatarioId' => 'nullable',
    ];

    protected $validationAttributes = [
        'generalFile' => 'archivo',
        'generalFileName' => 'nombre del documento',
        'generalCategory' => 'categoría',
        'generalAuthor' => 'autor',
        'generalLicense' => 'licencia',
    ];

    public function mount()
    {
        // Restrict access: only admin, agente, and user roles
        if (!in_array(auth()->user()->role, ['admin', 'agente', 'user'])) {
            abort(403, 'No autorizado.');
        }

        // Set default filter source for normal user
        if (auth()->user()->role === 'user') {
            $this->filterSource = 'all';
        }

        // Prefill author name
        $this->generalAuthor = auth()->user()->name;
    }

    public function selectDocument($uniqueId)
    {
        $this->selectedDocId = $uniqueId;
        $this->textContent = null;

        $docs = $this->getDocuments();
        $this->selectedDoc = collect($docs)->firstWhere('id', $uniqueId);

        if ($this->selectedDoc) {
            $path = $this->selectedDoc['ruta_real'];
            $exists = Storage::disk('public')->exists($path);
            $this->selectedDoc['existe_fisicamente'] = $exists;

            if ($exists && $this->selectedDoc['tipo'] === 'text') {
                try {
                    $fileSize = Storage::disk('public')->size($path);
                    $maxSize = 250 * 1024; // 250 KB limit for preview
                    if ($fileSize > $maxSize) {
                        $stream = Storage::disk('public')->readStream($path);
                        $this->textContent = stream_get_contents($stream, $maxSize) . "\n\n... [CONTENIDO TRUNCADO. El archivo es demasiado grande (aprox. " . round($fileSize / 1024, 1) . " KB) para mostrarse completo. Por favor descárgalo para verlo completo.] ...";
                        if (is_resource($stream)) {
                            fclose($stream);
                        }
                    } else {
                        $this->textContent = Storage::disk('public')->get($path);
                    }
                } catch (\Exception $e) {
                    $this->textContent = "Error al intentar leer el archivo de texto: " . $e->getMessage();
                }
            }
        }
    }

    public function closeViewer()
    {
        $this->selectedDocId = null;
        $this->selectedDoc = null;
        $this->textContent = null;
    }

    public function downloadDocument($uniqueId)
    {
        $docs = $this->getDocuments();
        $doc = collect($docs)->firstWhere('id', $uniqueId);

        if ($doc && $doc['ruta_real']) {
            if (Storage::disk('public')->exists($doc['ruta_real'])) {
                return Storage::disk('public')->download($doc['ruta_real'], $doc['nombre']);
            }
        }

        $this->dispatch('notify', 'El archivo no está disponible para descarga.');
    }

    public function deleteDocument($uniqueId)
    {
        // Only admin can delete files
        if (auth()->user()->role !== 'admin') {
            $this->dispatch('notify', 'Solo los administradores pueden eliminar documentos.');
            return;
        }

        $parts = explode('_', $uniqueId);
        $source = $parts[0] ?? '';
        $id = $parts[1] ?? null;

        if ($source === 'gen' && $id) {
            $doc = Documento::find($id);
            if ($doc) {
                // Delete physical file
                if (Storage::disk('public')->exists($doc->ruta_archivo)) {
                    Storage::disk('public')->delete($doc->ruta_archivo);
                }
                $doc->delete();
                $this->dispatch('notify', 'Documento eliminado con éxito');
                if ($this->selectedDocId === $uniqueId) {
                    $this->closeViewer();
                }
                return;
            }
        } elseif ($source === 'eq' && $id) {
            $equipment = Equipment::find($id);
            if ($equipment && $equipment->pdf_path) {
                if (Storage::disk('public')->exists($equipment->pdf_path)) {
                    Storage::disk('public')->delete($equipment->pdf_path);
                }
                $equipment->update(['pdf_path' => null]);
                $this->dispatch('notify', 'Responsiva eliminada con éxito');
                if ($this->selectedDocId === $uniqueId) {
                    $this->closeViewer();
                }
                return;
            }
        } elseif ($source === 'tk' && $id) {
            $adjunto = ArchivoAdjunto::find($id);
            if ($adjunto) {
                if (Storage::disk('public')->exists($adjunto->ruta_archivo)) {
                    Storage::disk('public')->delete($adjunto->ruta_archivo);
                }
                $adjunto->delete();
                $this->dispatch('notify', 'Archivo adjunto del ticket eliminado');
                if ($this->selectedDocId === $uniqueId) {
                    $this->closeViewer();
                }
                return;
            }
        }

        $this->dispatch('notify', 'No se pudo eliminar el documento.');
    }

    public function uploadGeneralFile()
    {
        // Restrict upload access: only admin and agente
        if (!in_array(auth()->user()->role, ['admin', 'agente'])) {
            abort(403, 'No autorizado.');
        }

        $this->validate();

        $path = $this->generalFile->store('general_documents', 'public');

        $doc = Documento::create([
            'nombre' => $this->generalFileName,
            'nombre_archivo' => $this->generalFile->getClientOriginalName(),
            'ruta_archivo' => $path,
            'tipo_archivo' => $this->generalFile->getMimeType() ?? $this->generalFile->getClientOriginalExtension(),
            'tamaño' => $this->generalFile->getSize(),
            'categoria' => $this->generalCategory,
            'usuario_id' => Auth::id(),
            'equipment_id' => $this->generalEquipmentId ?: null,
            'area' => $this->generalArea ?: null,
            'descripcion' => $this->generalDescription ?: null,
            'autor' => $this->generalAuthor ?: null,
            'licencia' => $this->generalLicense ?: null,
            'visible_operadores' => $this->generalVisibleOperadores ? 1 : 0,
            'destinatario_id' => $this->generalDestinatarioId ?: null,
        ]);

        $this->showingUploadModal = false;
        $this->reset(['generalFile', 'generalFileName', 'generalCategory', 'generalEquipmentId', 'generalArea', 'generalDescription', 'generalLicense', 'generalVisibleOperadores', 'generalDestinatarioId']);
        $this->generalAuthor = auth()->user()->name;
        
        $this->selectDocument('gen_' . $doc->id);
        
        $this->dispatch('notify', 'Documento general guardado exitosamente');
    }

    public function getDocuments()
    {
        $allDocs = [];

        // 1. Load Equipment PDFs (Responsivas)
        if ($this->filterSource === 'all' || $this->filterSource === 'inventory') {
            $equipmentsQuery = Equipment::whereNotNull('pdf_path');
            if (auth()->user()->role === 'user') {
                $equipmentsQuery->whereNotNull('maquina_id');
            }
            $equipments = $equipmentsQuery->get();
            foreach ($equipments as $item) {
                $ext = 'pdf';
                $allDocs[] = [
                    'id' => 'eq_' . $item->id,
                    'nombre' => 'Responsiva_' . ($item->barcode ?? $item->name) . '.pdf',
                    'nombre_original' => 'Carta Responsiva / Contrato',
                    'ruta_real' => $item->pdf_path,
                    'tipo' => 'pdf',
                    'ext' => $ext,
                    'tamaño' => 0, // Not stored in DB, can mock or read dynamically
                    'origen' => 'inventario',
                    'entidad_asociada' => $item->name . ' (' . ($item->barcode ?? 'S/N') . ')',
                    'autor' => 'Sistema / Inventario',
                    'fecha' => $item->updated_at,
                ];
            }
        }

        // 2. Load Ticket Attachments
        if ($this->filterSource === 'all' || $this->filterSource === 'tickets') {
            $attachmentsQuery = ArchivoAdjunto::with('ticket');
            if (auth()->user()->role === 'user') {
                // Users can only see tickets they created AND that are visible_operadores=true
                $attachmentsQuery->whereHas('ticket', function ($q) {
                    $q->where('usuario_creador_id', auth()->id());
                })->where('visible_operadores', true);
            }
            $attachments = $attachmentsQuery->get();
            foreach ($attachments as $item) {
                $ext = strtolower(pathinfo($item->nombre_archivo, PATHINFO_EXTENSION));
                $allDocs[] = [
                    'id' => 'tk_' . $item->id,
                    'nombre' => $item->nombre_archivo,
                    'nombre_original' => $item->nombre_archivo,
                    'ruta_real' => $item->ruta_archivo,
                    'tipo' => $this->determineFileType($ext),
                    'ext' => $ext,
                    'tamaño' => 0, // Mocked or read
                    'origen' => 'tickets',
                    'entidad_asociada' => 'Ticket #' . $item->ticket_id . ': ' . ($item->ticket->titulo ?? 'Sin título'),
                    'autor' => $item->ticket->creador->name ?? 'Usuario',
                    'fecha' => $item->created_at,
                ];
            }
        }

        // 3. Load General Documents
        if ($this->filterSource === 'all' || $this->filterSource === 'general') {
            $generalsQuery = Documento::with(['usuario', 'equipment', 'destinatario']);
            // Users can only see documents explicitly marked visible_operadores=true and directed to them or to everyone
            if (auth()->user()->role === 'user') {
                $generalsQuery->where('visible_operadores', true)
                              ->where(function($q) {
                                  $q->whereNull('destinatario_id')
                                    ->orWhere('destinatario_id', auth()->id());
                              });
            }
            $generals = $generalsQuery->get();
            foreach ($generals as $item) {
                $ext = strtolower(pathinfo($item->nombre_archivo, PATHINFO_EXTENSION));
                
                // Construct detailed metadata string
                $assoc = $item->categoria;
                if ($item->equipment) {
                    $assoc .= ' • ' . $item->equipment->name;
                }
                if ($item->destinatario) {
                    $assoc .= ' • Dirigido a: ' . $item->destinatario->name;
                }
                if ($item->area) {
                    // Look up Area name dynamically
                    $areaNames = [
                        'sec' => 'Seguridad TI',
                        'red' => 'Redes/WiFi',
                        'serv' => 'Archivos',
                        'software' => 'Software/Licencias',
                        'print' => 'Impresión',
                        'equipos' => 'Equipos'
                    ];
                    $areaName = $areaNames[$item->area] ?? ucfirst($item->area);
                    $assoc .= ' • Área: ' . $areaName;
                }

                $allDocs[] = [
                    'id' => 'gen_' . $item->id,
                    'nombre' => $item->nombre,
                    'nombre_original' => $item->nombre_archivo,
                    'ruta_real' => $item->ruta_archivo,
                    'tipo' => $this->determineFileType($ext),
                    'ext' => $ext,
                    'tamaño' => $item->tamaño,
                    'origen' => 'general',
                    'entidad_asociada' => $assoc,
                    'autor' => $item->autor ?: ($item->usuario->name ?? 'Admin'),
                    'fecha' => $item->created_at,
                    'equipment' => $item->equipment ? $item->equipment->name : null,
                    'area_key' => $item->area,
                    'descripcion' => $item->descripcion,
                    'licencia' => $item->licencia,
                    'visible_ops' => (bool) $item->visible_operadores,
                ];
            }
        }

        // Apply filters & search
        return collect($allDocs)
            ->filter(function ($doc) {
                // Search term match
                if ($this->search) {
                    $query = mb_strtolower($this->search);
                    $nameMatch = Str::contains(mb_strtolower($doc['nombre']), $query);
                    $entityMatch = Str::contains(mb_strtolower($doc['entidad_asociada']), $query);
                    $authorMatch = Str::contains(mb_strtolower($doc['autor']), $query);
                    if (!$nameMatch && !$entityMatch && !$authorMatch) {
                        return false;
                    }
                }

                // File type filter
                if ($this->filterType !== 'all') {
                    if ($doc['tipo'] !== $this->filterType) {
                        return false;
                    }
                }

                return true;
            })
            ->sortByDesc('fecha')
            ->values()
            ->all();
    }

    private function determineFileType($ext)
    {
        if ($ext === 'pdf') {
            return 'pdf';
        }
        if (in_array($ext, ['png', 'jpg', 'jpeg', 'gif', 'svg', 'webp'])) {
            return 'image';
        }
        if (in_array($ext, ['doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'odt'])) {
            return 'word';
        }
        if (in_array($ext, ['txt', 'log', 'csv', 'ini', 'json', 'xml', 'md', 'sql', 'html', 'css', 'js', 'php', 'py', 'sh', 'bat', 'yml', 'yaml', 'env'])) {
            return 'text';
        }
        if (in_array($ext, ['mp4', 'webm', 'ogg', 'mov', 'avi', 'mkv'])) {
            return 'video';
        }
        return 'other';
    }

    public function render()
    {
        return view('livewire.document-viewer', [
            'documents' => $this->getDocuments(),
            'equipments' => Equipment::all(),
            'usuariosList' => User::orderBy('nombre_completo')->get(),
            'areasList' => [
                ['id' => 'sec', 'name' => 'Seguridad TI'],
                ['id' => 'red', 'name' => 'Redes/WiFi'],
                ['id' => 'serv', 'name' => 'Archivos'],
                ['id' => 'software', 'name' => 'Software y Licencias'],
                ['id' => 'print', 'name' => 'Impresión'],
                ['id' => 'equipos', 'name' => 'Equipos']
            ]
        ]);
    }
}
