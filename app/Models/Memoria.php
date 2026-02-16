<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Memoria extends Model
{
    use HasFactory;

    protected $table = 'convenios';
    protected $primaryKey = 'id';

    protected $fillable = [
        'convenio_id',
        'fecha',
        'horas',
        'actividad',
        'aprobado'
    ];

    public function convenio()
    {
        return $this->belongsTo(Convenio::class);
    }
}
