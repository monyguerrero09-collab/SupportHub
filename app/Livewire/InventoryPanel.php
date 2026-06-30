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

    // Edit properties
    public $showingEditModal = false;
    public $editEquipmentId = null;
    public $editName;
    public $editType;
    public $editModel;
    public $editBarcode;
    public $editStatus;

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
        ]);

        $equipment = Equipment::find($this->editEquipmentId);
        if ($equipment) {
            $equipment->update([
                'name' => $this->editName,
                'type' => $this->editType,
                'model' => $this->editModel,
                'barcode' => $this->editBarcode,
                'status' => $this->editStatus,
            ]);
            $this->showingEditModal = false;
            $this->dispatch('notify', 'Equipo actualizado con éxito');
        }
    }

    public function deleteEquipment($id)
    {
        $equipment = Equipment::find($id);
        if ($equipment) {
            $equipment->delete();
            $this->dispatch('notify', 'Equipo eliminado del inventario');
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
    }

    public function downloadPdf($equipmentId)
    {
        $equipment = Equipment::find($equipmentId);
        if ($equipment && $equipment->pdf_path) {
            return \Storage::disk('public')->download($equipment->pdf_path, 'Carta_Responsiva_' . ($equipment->barcode ?? $equipment->name) . '.pdf');
        }
    }

    public function render()
    {
        $inventory = Equipment::with('maquina')->latest()->get();
        
        $pantallaCount = Equipment::where('type', 'Pantalla')->count();
        $cpuCount = Equipment::where('type', 'CPU')->count();
        $impresoraCount = Equipment::where('type', 'Impresora')->count();

        return view('livewire.inventory-panel', [
            'inventory' => $inventory,
            'pantallaCount' => $pantallaCount,
            'cpuCount' => $cpuCount,
            'impresoraCount' => $impresoraCount,
        ]);
    }
}
