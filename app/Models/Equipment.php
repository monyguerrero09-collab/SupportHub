<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Equipment extends Model
{
    protected $guarded = [];

    public function maquina()
    {
        return $this->belongsTo(Maquina::class, 'maquina_id');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
