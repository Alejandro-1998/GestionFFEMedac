<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegistroActividad extends Model
{
    protected $table = 'registros_actividad';

    protected $fillable = ['descripcion', 'sujeto_type', 'sujeto_id', 'evento'];

    public function sujeto()
    {
        return $this->morphTo();
    }
}
