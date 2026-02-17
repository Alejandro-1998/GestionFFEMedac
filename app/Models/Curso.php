<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Curso extends Model
{
    use HasFactory;

    protected $table = 'cursos';

    protected $fillable = [
        'nombre',
        'modulo_id',
    ];

    public function modulo()
    {
        return $this->belongsTo(Modulo::class);
    }

    public function alumnos()
    {
        return $this->hasMany(Alumno::class);
    }
    
    public function getAnyoAttribute()
    {
        // Deprecated: ambiguous in Many-to-Many
        return $this->modulo->cursosAcademicos->first()->anyo ?? null;
    }
    
    public function getTitulacionAttribute()
    {
        return $this->modulo->nombre ?? null;
    }
}
