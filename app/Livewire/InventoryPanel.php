<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Equipment;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;

#[Layout('layouts.blank')]
class InventoryPanel extends Component
{
    use WithFileUploads;

    public $pdfFiles = [];

    // Navigation sub-tab
    public $subTab = 'bodega'; // 'bodega', 'equipos', 'assignments', 'logs', 'scanner'
    public $searchTerm = '';

    // Plant Filter
    public $globalPlantFilter = 'Todas'; // 'Todas', 'Planta 1', 'Planta 2'

    // Scanner functionality
    public $scannerActivePlant = 'Planta 1';
    public $scannerMode = 'add'; // 'add', 'deduct', 'verify'
    public $scannedBarcode = '';
    public $scanSoundEnabled = true;
    public $lastScanFeedback = null;
    
    // Scanner Staging
    public $stagedBarcode = null;
    public $stagedName = '';
    public $stagedModel = '';
    public $stagedDescription = '';
    public $stagedType = 'Equipos';
    public $stagedQty = 1;
    public $stagedIsNew = false;
    public $stagedMinStock = 5;
    public $stagedMaxStock = 25;

    // React style stock filters
    public $selectedCategory = 'Todas';
    public $selectedStatus = 'Todos';
    public $categories = ['Equipos', 'Accesorios', 'Componentes', 'Redes y Cables', 'Licencias/Software'];

    // Modals
    public $showAddMaterialModal = false;
    public $showAssignItemModal = false;

    // Add Material form
    public $newMaterialCategory = 'Equipos';
    public $newMaterialCustomCategory = '';
    public $newMaterialModel = '';
    public $newMaterialQuantity = 10;
    public $newMaterialNotes = '';
    public $newMaterialAcquisitionDate;
    public $newMaterialMin = 5;
    public $newMaterialMax = 25;

    // Assign Item form
    public $newAssignmentStockKey = '';
    public $newAssignmentTargetType = 'Estación';
    public $newAssignmentTargetId = '';
    public $newAssignmentQuantity = 1;
    public $newAssignmentNotes = '';
    public $newAssignmentDate;

    // Edit properties (original modal)
    public $showingEditModal = false;
    public $editEquipmentId = null;
    public $editName;
    public $editType;
    public $editModel;
    public $editBarcode;
    public $editStatus;
    public $editMaquinaId = null;

    // Edit Assignment properties
    public $showEditAssignmentModal = false;
    public $editAssignmentId = null;
    public $editAssignmentTargetType = 'Estación';
    public $editAssignmentTargetId = '';
    public $editAssignmentDate;
    public $editAssignmentNotes = '';

    // Edit properties (React style Group Edit)
    public $showEditMaterialModal = false;
    public $editOldType = '';
    public $editOldModel = '';
    public $editNewType = '';
    public $editNewModel = '';
    public $editNewQuantity = 0;
    public $editNewMin = 5;
    public $editNewMax = 25;

    protected $listeners = [
        'refresh-inventory' => '$refresh',
    ];

    public function mount()
    {
        $this->newMaterialCategory = 'Equipos';
        $this->newMaterialAcquisitionDate = now()->format('Y-m-d');
        $this->newAssignmentDate = now()->format('Y-m-d');
    }

    public function getStockStatus($quantity, $min, $max)
    {
        $q = (int)$quantity;
        $mn = (int)$min;
        $mx = (int)$max;

        if ($q <= $mn) {
            return [
                'code' => 'red',
                'label' => 'Muy Poco - ¡Hay que pedir!',
                'bgClass' => 'bg-rose-500',
                'textClass' => 'text-rose-400',
                'badgeBg' => 'bg-rose-950/50 text-rose-300 border-rose-500/20',
                'borderClass' => 'border-rose-900/50'
            ];
        }

        $yellowRangeLimit = $mn + (($mx - $mn) * 0.35);
        if ($q <= $yellowRangeLimit) {
            return [
                'code' => 'yellow',
                'label' => 'Bajo - No requiere pedir aún',
                'bgClass' => 'bg-amber-500',
                'textClass' => 'text-amber-400',
                'badgeBg' => 'bg-amber-950/50 text-amber-300 border-amber-500/20',
                'borderClass' => 'border-amber-900/50'
            ];
        }

        return [
            'code' => 'green',
            'label' => 'Abastecido',
            'bgClass' => 'bg-emerald-500',
            'textClass' => 'text-emerald-400',
            'badgeBg' => 'bg-emerald-950/50 text-emerald-300 border-emerald-500/20',
            'borderClass' => 'border-emerald-900/50'
        ];
    }

    public function addMaterial()
    {
        $category = $this->newMaterialCategory === 'Otro' ? $this->newMaterialCustomCategory : $this->newMaterialCategory;
        
        $this->validate([
            'newMaterialModel' => 'required|string|max:255',
            'newMaterialQuantity' => 'required|integer|min:0',
            'newMaterialMin' => 'required|integer|min:0',
            'newMaterialMax' => 'required|integer|min:1|gt:newMaterialMin',
        ]);

        if (!$category) {
            $this->addError('newMaterialCustomCategory', 'La categoría es requerida.');
            return;
        }

        for ($i = 0; $i < $this->newMaterialQuantity; $i++) {
            $barcode = strtoupper(substr($category, 0, 3)) . '-' . strtoupper(substr($this->newMaterialModel, 0, 3)) . '-' . rand(1000, 9999);
            
            Equipment::create([
                'name' => $this->newMaterialModel,
                'type' => $category,
                'model' => $this->newMaterialModel,
                'barcode' => $barcode,
                'status' => 'in-stock',
                'description' => $this->newMaterialNotes ?: 'Ingreso manual',
                'min_stock' => $this->newMaterialMin,
                'max_stock' => $this->newMaterialMax,
                'planta' => $this->globalPlantFilter === 'Todas' ? 'Planta 1' : $this->globalPlantFilter,
                'created_at' => $this->newMaterialAcquisitionDate ?: now(),
            ]);
        }

        // Log movement
        \App\Models\InventoryMovement::create([
            'action' => 'Ingreso',
            'details' => "Se registró manualmente el equipo \"{$this->newMaterialModel}\" con {$this->newMaterialQuantity} unidades.",
        ]);

        $this->showAddMaterialModal = false;
        $this->reset(['newMaterialModel', 'newMaterialQuantity', 'newMaterialMin', 'newMaterialMax', 'newMaterialNotes']);
        $this->newMaterialCategory = 'Equipos';
        $this->newMaterialAcquisitionDate = now()->format('Y-m-d');
        $this->dispatch('notify', 'Nuevo equipo registrado en el stock.');
        $this->dispatch('refresh-inventory');
    }

    public function openEditMaterial($type, $model)
    {
        $this->editOldType = $type;
        $this->editOldModel = $model;
        
        $items = Equipment::where('type', $type)
            ->where('model', $model)
            ->where('status', 'in-stock')
            ->get();
            
        $first = $items->first();
        
        $this->editNewType = $type;
        $this->editNewModel = $model;
        $this->editNewQuantity = $items->count();
        $this->editNewMin = $first ? $first->min_stock : 5;
        $this->editNewMax = $first ? $first->max_stock : 25;
        
        $this->showEditMaterialModal = true;
    }

    public function saveEditMaterial()
    {
        $this->validate([
            'editNewModel' => 'required|string|max:255',
            'editNewType' => 'required|string',
            'editNewQuantity' => 'required|integer|min:0',
            'editNewMin' => 'required|integer|min:0',
            'editNewMax' => 'required|integer|min:1|gt:editNewMin',
        ]);

        $itemsQuery = Equipment::where('type', $this->editOldType)
            ->where('model', $this->editOldModel);
            
        $allGroupItems = $itemsQuery->get();
        $inStockItems = $allGroupItems->where('status', 'in-stock');

        // Update basic fields for ALL items in that group
        foreach ($allGroupItems as $item) {
            $item->update([
                'name' => $this->editNewModel,
                'type' => $this->editNewType,
                'model' => $this->editNewModel,
                'min_stock' => $this->editNewMin,
                'max_stock' => $this->editNewMax,
            ]);
        }

        // Adjust quantity of in-stock items
        $currentInStockCount = $inStockItems->count();
        $diff = $this->editNewQuantity - $currentInStockCount;

        if ($diff > 0) {
            for ($i = 0; $i < $diff; $i++) {
                $barcode = strtoupper(substr($this->editNewType, 0, 3)) . '-' . strtoupper(substr($this->editNewModel, 0, 3)) . '-' . rand(1000, 9999);
                Equipment::create([
                    'name' => $this->editNewModel,
                    'type' => $this->editNewType,
                    'model' => $this->editNewModel,
                    'barcode' => $barcode,
                    'status' => 'in-stock',
                    'description' => 'Ajuste de stock manual',
                    'min_stock' => $this->editNewMin,
                    'max_stock' => $this->editNewMax,
                    'planta' => $this->globalPlantFilter === 'Todas' ? 'Planta 1' : $this->globalPlantFilter,
                ]);
            }
            \App\Models\InventoryMovement::create([
                'action' => 'Ajuste',
                'details' => "Se actualizó el stock de \"{$this->editNewModel}\" a {$this->editNewQuantity} unidades (antes: {$currentInStockCount}).",
            ]);
        } elseif ($diff < 0) {
            $toRemove = abs($diff);
            $itemsToRemove = $inStockItems->take($toRemove);
            foreach ($itemsToRemove as $item) {
                $item->delete();
            }
            \App\Models\InventoryMovement::create([
                'action' => 'Ajuste',
                'details' => "Se actualizó el stock de \"{$this->editNewModel}\" a {$this->editNewQuantity} unidades (antes: {$currentInStockCount}).",
            ]);
        } else {
            \App\Models\InventoryMovement::create([
                'action' => 'Ajuste',
                'details' => "Se modificaron los parámetros de \"{$this->editNewModel}\" (Mín: {$this->editNewMin}, Máx: {$this->editNewMax}).",
            ]);
        }

        $this->showEditMaterialModal = false;
        $this->dispatch('notify', 'Equipo actualizado correctamente.');
        $this->dispatch('refresh-inventory');
    }

    public function incrementStock($type, $model, $planta = null)
    {
        $targetPlanta = $planta ?: ($this->globalPlantFilter === 'Todas' ? 'Planta 1' : $this->globalPlantFilter);
        $barcode = strtoupper(substr($type, 0, 3)) . '-' . strtoupper(substr($model, 0, 3)) . '-' . rand(1000, 9999);
        
        $first = Equipment::where('type', $type)->where('model', $model)->first();
        $min = $first ? $first->min_stock : 5;
        $max = $first ? $first->max_stock : 25;

        Equipment::create([
            'name' => $model,
            'type' => $type,
            'model' => $model,
            'barcode' => $barcode,
            'status' => 'in-stock',
            'description' => 'Ajuste rápido (+1)',
            'min_stock' => $min,
            'max_stock' => $max,
            'planta' => $targetPlanta,
        ]);

        $newQty = Equipment::where('type', $type)->where('model', $model)->where('status', 'in-stock')->count();
        \App\Models\InventoryMovement::create([
            'action' => 'Ajuste',
            'details' => "Se incrementó el stock de \"{$model}\" (+1 en {$targetPlanta}). Stock actual total: {$newQty}.",
        ]);

        $this->dispatch('notify', 'Stock incrementado.');
        $this->dispatch('refresh-inventory');
    }

    public function decrementStock($type, $model, $planta = null)
    {
        $targetPlanta = $planta ?: ($this->globalPlantFilter === 'Todas' ? 'Planta 1' : $this->globalPlantFilter);
        
        $item = Equipment::where('type', $type)
            ->where('model', $model)
            ->where('planta', $targetPlanta)
            ->where('status', 'in-stock')
            ->first();

        if ($item) {
            $item->delete();
            $newQty = Equipment::where('type', $type)->where('model', $model)->where('status', 'in-stock')->count();
            \App\Models\InventoryMovement::create([
                'action' => 'Ajuste',
                'details' => "Se redujo el stock de \"{$model}\" (-1 en {$targetPlanta}). Stock actual total: {$newQty}.",
            ]);
            $this->dispatch('notify', 'Stock reducido.');
        } else {
            $this->dispatch('notify', "No hay unidades disponibles de ese modelo en {$targetPlanta}.");
        }
        $this->dispatch('refresh-inventory');
    }

    public function deleteStockGroup($type, $model)
    {
        $itemsQuery = Equipment::where('type', $type)->where('model', $model)->where('status', 'in-stock');
        if ($this->globalPlantFilter !== 'Todas') {
            $itemsQuery->where('planta', $this->globalPlantFilter);
        }
        
        $items = $itemsQuery->get();
        foreach ($items as $item) {
            $item->delete();
        }
        
        \App\Models\InventoryMovement::create([
            'action' => 'Eliminación',
            'details' => "Se eliminó el equipo \"{$model}\" del stock de bodega.",
        ]);

        $this->dispatch('notify', "Se ha eliminado \"{$model}\".");
        $this->dispatch('refresh-inventory');
    }

    public function assignItem()
    {
        $this->validate([
            'newAssignmentStockKey' => 'required',
            'newAssignmentTargetId' => 'required',
            'newAssignmentQuantity' => 'required|integer|min:1',
        ]);

        $parts = explode('|', $this->newAssignmentStockKey);
        if (count($parts) !== 2) {
            return;
        }
        $type = $parts[0];
        $model = $parts[1];

        // Find available items
        $availableItems = Equipment::where('type', $type)
            ->where('model', $model)
            ->where('status', 'in-stock')
            ->get();

        if ($availableItems->count() < $this->newAssignmentQuantity) {
            $this->addError('newAssignmentQuantity', "No hay suficiente stock. Disponibles: " . $availableItems->count());
            return;
        }

        $assignedItems = $availableItems->take($this->newAssignmentQuantity);
        
        $targetName = '';
        if ($this->newAssignmentTargetType === 'Estación') {
            $maquina = \App\Models\Maquina::find($this->newAssignmentTargetId);
            $targetName = $maquina ? $maquina->nombre : 'Estación';
            
            foreach ($assignedItems as $item) {
                $item->update([
                    'status' => 'deployed',
                    'maquina_id' => $this->newAssignmentTargetId,
                    'installed_at' => $this->newAssignmentDate ?: now(),
                    'description' => $this->newAssignmentNotes ?: 'Asignación a estación'
                ]);
            }
        } else {
            $user = \App\Models\User::find($this->newAssignmentTargetId);
            $targetName = $user ? $user->name : 'Usuario';
            
            foreach ($assignedItems as $item) {
                $item->update([
                    'status' => 'deployed',
                    'user_id' => $this->newAssignmentTargetId,
                    'installed_at' => $this->newAssignmentDate ?: now(),
                    'description' => $this->newAssignmentNotes ?: 'Asignación a usuario'
                ]);
            }
        }

        // Log movement
        \App\Models\InventoryMovement::create([
            'action' => 'Asignación',
            'details' => "Se asignó {$this->newAssignmentQuantity}x {$type} ({$model}) a {$this->newAssignmentTargetType}: \"{$targetName}\".",
        ]);

        $this->showAssignItemModal = false;
        $this->reset(['newAssignmentStockKey', 'newAssignmentTargetId', 'newAssignmentQuantity', 'newAssignmentNotes']);
        $this->newAssignmentDate = now()->format('Y-m-d');
        $this->dispatch('notify', "Asignación exitosa para {$targetName}.");
        $this->dispatch('refresh-inventory');
    }

    public function returnItem($id)
    {
        $equipment = Equipment::find($id);
        if ($equipment && $equipment->status === 'deployed') {
            $targetType = $equipment->maquina_id ? 'Estación' : 'Usuario';
            $targetName = '';
            if ($equipment->maquina_id) {
                $targetName = $equipment->maquina ? $equipment->maquina->nombre : 'Estación';
            } else if ($equipment->user_id) {
                $targetName = $equipment->usuario ? $equipment->usuario->name : 'Usuario';
            }

            $equipment->update([
                'status' => 'in-stock',
                'maquina_id' => null,
                'user_id' => null,
                'installed_at' => null,
                'description' => 'Retornado de asignación'
            ]);

            // Log movement
            \App\Models\InventoryMovement::create([
                'action' => 'Retorno',
                'details' => "Retornado 1x {$equipment->type} ({$equipment->model}) desde {$targetType}: \"{$targetName}\" a la Bodega.",
            ]);

            $this->dispatch('notify', 'Artículo retornado a bodega con éxito.');
            $this->dispatch('refresh-inventory');
        }
    }

    public function openAssignFor($type, $model)
    {
        $this->newAssignmentStockKey = $type . '|' . $model;
        $this->showAssignItemModal = true;
    }

    public function changeEditAssignmentTargetType($type)
    {
        $this->editAssignmentTargetType = $type;
        $this->editAssignmentTargetId = '';
    }

    public function changeNewAssignmentTargetType($type)
    {
        $this->newAssignmentTargetType = $type;
        $this->newAssignmentTargetId = '';
    }

    public function openEditAssignment($id)
    {
        $equipment = Equipment::find($id);
        if ($equipment && $equipment->status === 'deployed') {
            $this->editAssignmentId = $id;
            $this->editAssignmentTargetType = $equipment->maquina_id ? 'Estación' : 'Usuario';
            $this->editAssignmentTargetId = $equipment->maquina_id ?: $equipment->user_id;
            $this->editAssignmentDate = $equipment->installed_at ? \Carbon\Carbon::parse($equipment->installed_at)->format('Y-m-d') : now()->format('Y-m-d');
            $this->editAssignmentNotes = $equipment->description;
            $this->showEditAssignmentModal = true;
        }
    }

    public function saveEditAssignment()
    {
        $this->validate([
            'editAssignmentTargetId' => 'required',
            'editAssignmentDate' => 'required|date',
        ]);

        $equipment = Equipment::find($this->editAssignmentId);
        if ($equipment && $equipment->status === 'deployed') {
            $oldTarget = $equipment->maquina_id ? "Estación: " . ($equipment->maquina ? $equipment->maquina->nombre : '') : "Usuario: " . ($equipment->usuario ? $equipment->usuario->name : '');
            
            if ($this->editAssignmentTargetType === 'Estación') {
                $maquina = \App\Models\Maquina::find($this->editAssignmentTargetId);
                $newTargetName = $maquina ? $maquina->nombre : 'Estación';
                
                $equipment->update([
                    'maquina_id' => $this->editAssignmentTargetId,
                    'user_id' => null,
                    'installed_at' => $this->editAssignmentDate,
                    'description' => $this->editAssignmentNotes
                ]);
            } else {
                $user = \App\Models\User::find($this->editAssignmentTargetId);
                $newTargetName = $user ? $user->name : 'Usuario';
                
                $equipment->update([
                    'maquina_id' => null,
                    'user_id' => $this->editAssignmentTargetId,
                    'installed_at' => $this->editAssignmentDate,
                    'description' => $this->editAssignmentNotes
                ]);
            }

            $newTarget = $this->editAssignmentTargetType . ": " . $newTargetName;

            // Log movement
            \App\Models\InventoryMovement::create([
                'action' => 'Ajuste',
                'details' => "Se editó la asignación de 1x {$equipment->type} ({$equipment->model}). De [{$oldTarget}] a [{$newTarget}].",
            ]);

            $this->showEditAssignmentModal = false;
            $this->dispatch('notify', 'Asignación actualizada con éxito.');
            $this->dispatch('refresh-inventory');
        }
    }

    public function editEquipment($id)
    {
        $equipment = Equipment::find($id);
        if ($equipment) {
            $this->editEquipmentId = $id;
            $this->editName = $equipment->name;
            $this->editType = $equipment->type;
            $this->editModel = $equipment->model;
            $this->editBarcode = $equipment->barcode;
            $this->editStatus = $equipment->status;
            $this->editMaquinaId = $equipment->maquina_id;
            $this->showingEditModal = true;
        }
    }

    public function updateEquipment()
    {
        $this->validate([
            'editName' => 'required|string|max:255',
            'editType' => 'required|string',
            'editModel' => 'required|string|max:255',
            'editBarcode' => 'required|string|max:255',
            'editStatus' => 'required|string',
            'editMaquinaId' => 'nullable',
        ]);

        $equipment = Equipment::find($this->editEquipmentId);
        if ($equipment) {
            $maquinaId = $this->editMaquinaId ?: null;
            $newStatus = $this->editStatus;
            $installedAt = $equipment->installed_at;

            if ($maquinaId) {
                $newStatus = 'deployed';
                $installedAt = $installedAt ?: now();
            } else {
                if ($newStatus === 'deployed') {
                    $newStatus = 'in-stock';
                }
                $installedAt = null;
            }

            $equipment->update([
                'name' => $this->editName,
                'type' => $this->editType,
                'model' => $this->editModel,
                'barcode' => $this->editBarcode,
                'status' => $newStatus,
                'maquina_id' => $maquinaId,
                'installed_at' => $installedAt,
            ]);
            
            $this->showingEditModal = false;
            $this->dispatch('notify', 'Equipo actualizado con éxito');
            $this->dispatch('refresh-inventory');
        }
    }

    public function deleteEquipment($id)
    {
        $equipment = Equipment::find($id);
        if ($equipment) {
            $equipment->delete();
            $this->dispatch('notify', 'Equipo eliminado del inventario');
            $this->dispatch('refresh-inventory');
        }
    }

    public function updatedPdfFiles($value, $key)
    {
        $this->validate([
            "pdfFiles.{$key}" => 'mimes:pdf|max:10240', // max 10MB
        ]);

        $equipment = Equipment::find($key);
        if ($equipment && isset($this->pdfFiles[$key])) {
            // Remove old pdf if exists
            if ($equipment->pdf_path && \Storage::disk('public')->exists($equipment->pdf_path)) {
                \Storage::disk('public')->delete($equipment->pdf_path);
            }

            $path = $this->pdfFiles[$key]->store('equipment_pdfs', 'public');
            $equipment->update([
                'pdf_path' => $path
            ]);
        }
        
        $this->reset('pdfFiles');
        
        $this->dispatch('notify', 'Documento guardado correctamente');
        $this->dispatch('refresh-inventory');
    }

    public function downloadPdf($equipmentId)
    {
        $equipment = Equipment::find($equipmentId);
        if ($equipment && $equipment->pdf_path) {
            return \Storage::disk('public')->download($equipment->pdf_path, 'Carta_Responsiva_' . ($equipment->barcode ?? $equipment->name) . '.pdf');
        }
    }

    public function processScan()
    {
        $code = trim($this->scannedBarcode);
        if (!$code) return;

        $this->scannedBarcode = ''; // Reset input

        $itemTpl = Equipment::where('barcode', $code)->orWhere('model', $code)->first();
        $modelName = $itemTpl ? $itemTpl->model : '';

        // Si es modo Verify, mostramos la info de inmediato (no hay staging)
        if ($this->scannerMode === 'verify') {
            if (!$itemTpl) {
                $this->showScanFeedback("Código No Encontrado", "El código '{$code}' no existe.", "rose");
                if ($this->scanSoundEnabled) $this->dispatch('play-sound', ['type' => 'error']);
                return;
            }
            
            $p1 = Equipment::where(function($q) use ($code, $modelName) {
                    $q->where('barcode', $code)->orWhere('model', $modelName);
                })->where('planta', 'Planta 1')->where('status', 'in-stock')->count();
            $p2 = Equipment::where(function($q) use ($code, $modelName) {
                    $q->where('barcode', $code)->orWhere('model', $modelName);
                })->where('planta', 'Planta 2')->where('status', 'in-stock')->count();
            $total = $p1 + $p2;
            
            \App\Models\InventoryMovement::create([
                'action' => 'Verificación',
                'details' => "Escáner Auto: Verificación de [{$code}]",
            ]);

            $this->showScanFeedback("Info Multi-Planta", "{$modelName} | P1: {$p1} | P2: {$p2} | Total: {$total}", "blue");
            if ($this->scanSoundEnabled) $this->dispatch('play-sound', ['type' => 'info']);
            return;
        }

        // Para Entrada o Salida, entramos al Staging
        $this->stagedBarcode = $code;
        $this->stagedQty = 1;

        if ($itemTpl) {
            // Producto existe
            $this->stagedIsNew = false;
            $this->stagedName = $itemTpl->name;
            $this->stagedModel = $itemTpl->model;
            $this->stagedDescription = $itemTpl->description ?? '';
            $this->stagedType = $itemTpl->type;
            $this->stagedMinStock = $itemTpl->min_stock;
            $this->stagedMaxStock = $itemTpl->max_stock;
            
            if ($this->scanSoundEnabled) $this->dispatch('play-sound', ['type' => 'info']);
        } else {
            // Producto nuevo
            $this->stagedIsNew = true;
            $this->stagedName = '';
            $this->stagedModel = '';
            $this->stagedDescription = '';
            $this->stagedType = 'Equipos';
            $this->stagedMinStock = 5;
            $this->stagedMaxStock = 25;
            
            if ($this->scanSoundEnabled) $this->dispatch('play-sound', ['type' => 'info']);
        }
    }

    public function cancelScan()
    {
        $this->stagedBarcode = null;
        $this->stagedName = '';
        $this->stagedModel = '';
        $this->stagedDescription = '';
        $this->stagedQty = 1;
    }

    public function commitScan()
    {
        $code = $this->stagedBarcode;
        $qty = (int) $this->stagedQty;
        
        if (!$code || $qty <= 0 || trim($this->stagedName) === '') {
            $this->showScanFeedback("Datos Inválidos", "Revisa la cantidad y el nombre del producto.", "rose");
            if ($this->scanSoundEnabled) $this->dispatch('play-sound', ['type' => 'error']);
            return;
        }

        if ($this->scannerMode === 'add') {
            for ($i = 0; $i < $qty; $i++) {
                Equipment::create([
                    'name' => trim($this->stagedName),
                    'type' => $this->stagedType,
                    'model' => trim($this->stagedModel),
                    'barcode' => $code,
                    'status' => 'in-stock',
                    'description' => trim($this->stagedDescription) ?: 'Ingreso por escáner',
                    'min_stock' => $this->stagedMinStock,
                    'max_stock' => $this->stagedMaxStock,
                    'planta' => $this->scannerActivePlant,
                ]);
            }

            Equipment::where('barcode', $code)->update([
                'name' => trim($this->stagedName),
                'type' => $this->stagedType,
                'model' => trim($this->stagedModel),
                'description' => trim($this->stagedDescription) ?: 'Ingreso por escáner',
                'min_stock' => $this->stagedMinStock,
                'max_stock' => $this->stagedMaxStock,
            ]);

            $modelName = trim($this->stagedName);
            $newQty = Equipment::where(function($q) use ($code, $modelName) {
                $q->where('barcode', $code)->orWhere('model', $modelName);
            })->where('planta', $this->scannerActivePlant)->where('status', 'in-stock')->count();
            
            \App\Models\InventoryMovement::create([
                'action' => 'Ingreso',
                'details' => "Escáner Auto: Entrada +{$qty} de [{$code}] en {$this->scannerActivePlant}.",
            ]);

            $this->showScanFeedback("Stock Incrementado (+{$qty} en {$this->scannerActivePlant})", "{$modelName} -> Stock: {$newQty}", "emerald");
            if ($this->scanSoundEnabled) $this->dispatch('play-sound', ['type' => 'success']);
            
        } elseif ($this->scannerMode === 'deduct') {
            $modelName = trim($this->stagedName);
            
            Equipment::where('barcode', $code)->update([
                'name' => trim($this->stagedName),
                'type' => $this->stagedType,
                'model' => trim($this->stagedModel),
                'description' => trim($this->stagedDescription) ?: 'Salida por escáner',
            ]);

            $itemsToRemove = Equipment::where(function($q) use ($code, $modelName) {
                    $q->where('barcode', $code)->orWhere('model', $modelName);
                })
                ->where('planta', $this->scannerActivePlant)
                ->where('status', 'in-stock')
                ->limit($qty)
                ->get();

            $removedCount = $itemsToRemove->count();

            if ($removedCount > 0) {
                foreach ($itemsToRemove as $item) {
                    $item->delete();
                }

                $newQty = Equipment::where(function($q) use ($code, $modelName) {
                    $q->where('barcode', $code)->orWhere('model', $modelName);
                })->where('planta', $this->scannerActivePlant)->where('status', 'in-stock')->count();

                \App\Models\InventoryMovement::create([
                    'action' => 'Salida',
                    'details' => "Escáner Auto: Salida -{$removedCount} de [{$code}] en {$this->scannerActivePlant}.",
                ]);

                $this->showScanFeedback("Stock Reducido (-{$removedCount} en {$this->scannerActivePlant})", "{$modelName} -> Stock: {$newQty}", "amber");
                if ($this->scanSoundEnabled) $this->dispatch('play-sound', ['type' => 'success']);
            } else {
                $this->showScanFeedback("Sin Stock en {$this->scannerActivePlant}", "No hay unidades en esta planta.", "rose");
                if ($this->scanSoundEnabled) $this->dispatch('play-sound', ['type' => 'error']);
            }
        }

        $this->cancelScan();
        $this->dispatch('refresh-inventory');
    }

    public function showScanFeedback($title, $detail, $color)
    {
        $this->lastScanFeedback = [
            'title' => $title,
            'detail' => $detail,
            'color' => $color,
            'time' => now()->format('H:i:s'),
        ];
    }

    public function render()
    {
        // 1. Grouped Stock Query
        $stockQuery = Equipment::where('status', 'in-stock');
        
        // Search Term Filter
        if ($this->searchTerm) {
            $stockQuery->where(function ($q) {
                $q->where('type', 'like', '%' . $this->searchTerm . '%')
                  ->orWhere('model', 'like', '%' . $this->searchTerm . '%');
            });
        }

        // Category Filter
        if ($this->selectedCategory !== 'Todas') {
            $stockQuery->where('type', $this->selectedCategory);
        }
        
        if ($this->globalPlantFilter !== 'Todas') {
            $stockQuery->where('planta', $this->globalPlantFilter);
        }

        $stockGroupedRaw = $stockQuery
            ->selectRaw('type, model, count(*) as quantity, 
                         SUM(CASE WHEN planta = "Planta 1" THEN 1 ELSE 0 END) as stockP1, 
                         SUM(CASE WHEN planta = "Planta 2" THEN 1 ELSE 0 END) as stockP2,
                         MIN(created_at) as date_added, MIN(description) as notes, MAX(min_stock) as min_stock, MAX(max_stock) as max_stock')
            ->groupBy('type', 'model')
            ->get();

        // Map status info
        $stockGrouped = $stockGroupedRaw->map(function ($item) {
            $item->status_info = $this->getStockStatus($item->quantity, $item->min_stock, $item->max_stock);
            return $item;
        });

        // Semáforo Status Filter
        if ($this->selectedStatus !== 'Todos') {
            $stockGrouped = $stockGrouped->filter(function ($item) {
                return $item->status_info['code'] === $this->selectedStatus;
            });
        }

        // 2. Active Assignments Query
        $assignmentsQuery = Equipment::with(['maquina', 'usuario'])->where('status', 'deployed');
        if ($this->searchTerm) {
            $assignmentsQuery->where(function ($q) {
                $q->where('type', 'like', '%' . $this->searchTerm . '%')
                  ->orWhere('model', 'like', '%' . $this->searchTerm . '%')
                  ->orWhereHas('maquina', function ($mq) {
                      $mq->where('nombre', 'like', '%' . $this->searchTerm . '%');
                  })
                  ->orWhereHas('usuario', function ($u) {
                      $u->where('name', 'like', '%' . $this->searchTerm . '%');
                  });
            });
        }
        $assignments = $assignmentsQuery->latest('installed_at')->get();

        // 3. Movements History
        $movements = \App\Models\InventoryMovement::latest()->take(10)->get();

        // 4. Original List (Equipos Serializados)
        $inventoryQuery = Equipment::with('maquina');
        if ($this->searchTerm) {
            $inventoryQuery->where(function ($q) {
                $q->where('name', 'like', '%' . $this->searchTerm . '%')
                  ->orWhere('type', 'like', '%' . $this->searchTerm . '%')
                  ->orWhere('model', 'like', '%' . $this->searchTerm . '%')
                  ->orWhere('barcode', 'like', '%' . $this->searchTerm . '%');
            });
        }
        $inventory = $inventoryQuery->latest()->get();

        // Compute summary metrics for all items in stock
        $allGroupedQuery = Equipment::where('status', 'in-stock');
        if ($this->globalPlantFilter !== 'Todas') {
            $allGroupedQuery->where('planta', $this->globalPlantFilter);
        }
        
        $allGrouped = $allGroupedQuery
            ->selectRaw('type, model, count(*) as quantity, MAX(min_stock) as min_stock, MAX(max_stock) as max_stock')
            ->groupBy('type', 'model')
            ->get();

        $summary = [
            'total' => $allGrouped->count(),
            'green' => 0,
            'yellow' => 0,
            'red' => 0,
        ];

        foreach ($allGrouped as $item) {
            $status = $this->getStockStatus($item->quantity, $item->min_stock, $item->max_stock);
            if ($status['code'] === 'green') $summary['green']++;
            elseif ($status['code'] === 'yellow') $summary['yellow']++;
            elseif ($status['code'] === 'red') $summary['red']++;
        }

        $pantallaCountQ = Equipment::where('type', 'Pantalla')->where('status', 'in-stock');
        $cpuCountQ = Equipment::where('type', 'CPU')->where('status', 'in-stock');
        $impresoraCountQ = Equipment::where('type', 'Impresora')->where('status', 'in-stock');
        $totalInStockQ = Equipment::where('status', 'in-stock');
        
        if ($this->globalPlantFilter !== 'Todas') {
            $pantallaCountQ->where('planta', $this->globalPlantFilter);
            $cpuCountQ->where('planta', $this->globalPlantFilter);
            $impresoraCountQ->where('planta', $this->globalPlantFilter);
            $totalInStockQ->where('planta', $this->globalPlantFilter);
        }

        $pantallaCount = $pantallaCountQ->count();
        $cpuCount = $cpuCountQ->count();
        $impresoraCount = $impresoraCountQ->count();
        $totalInStock = $totalInStockQ->count();

        // Low stock count (Rojo state)
        $lowStockAlerts = $summary['red'];

        $maquinas = \App\Models\Maquina::orderBy('nombre')->get();
        $usuarios = \App\Models\User::orderBy('name')->get();

        return view('livewire.inventory-panel', [
            'inventory' => $inventory,
            'stockGrouped' => $stockGrouped,
            'assignments' => $assignments,
            'movements' => $movements,
            'summary' => $summary,
            'pantallaCount' => $pantallaCount,
            'cpuCount' => $cpuCount,
            'impresoraCount' => $impresoraCount,
            'totalInStock' => $totalInStock,
            'totalInWarehouse' => $totalInStock,
            'totalAssignedActive' => Equipment::where('status', 'deployed')->count(),
            'totalAssignedReturned' => \App\Models\InventoryMovement::where('action', 'Retorno')->count(),
            'lowStockAlerts' => $lowStockAlerts,
            'maquinas' => $maquinas,
            'usuarios' => $usuarios,
        ]);
    }
}
