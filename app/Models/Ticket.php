<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\ArchivoAdjunto;

class Ticket extends Model
{
    protected $guarded = [];

    // Relaciones
    public function tipoTicket(): BelongsTo
    {
        return $this->belongsTo(TipoTicket::class, 'tipo_ticket_id');
    }

    public function estado(): BelongsTo
    {
        return $this->belongsTo(EstadoTicket::class, 'estado_id');
    }

    public function prioridad(): BelongsTo
    {
        return $this->belongsTo(Prioridad::class, 'prioridad_id');
    }

    public function creador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_creador_id');
    }

    public function agente(): BelongsTo
    {
        return $this->belongsTo(User::class, 'agente_asignado_id');
    }

    public function departamento(): BelongsTo
    {
        return $this->belongsTo(Departamento::class, 'departamento_id');
    }

    public function sector(): BelongsTo
    {
        return $this->belongsTo(Sector::class, 'sector_id');
    }

    public function maquina(): BelongsTo
    {
        return $this->belongsTo(Maquina::class, 'maquina_id');
    }

    public function archivosAdjuntos(): HasMany
    {
        return $this->hasMany(ArchivoAdjunto::class);
    }
}
