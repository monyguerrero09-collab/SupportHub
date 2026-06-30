<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Maquina extends Model
{
    protected $fillable = ['nombre', 'descripcion', 'external_id', 'sector_id', 'pos_x', 'pos_y', 'status'];

    public function equipment()
    {
        return $this->hasMany(Equipment::class, 'maquina_id');
    }

    public function sector()
    {
        return $this->belongsTo(Sector::class);
    }
}
