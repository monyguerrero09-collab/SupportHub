<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistorialTicket extends Model
{
    protected $guarded = [];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function causaSolucion()
    {
        return $this->belongsTo(CausaSolucion::class);
    }

    public function motivoCancelacion()
    {
        return $this->belongsTo(MotivoCancelacion::class);
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}
