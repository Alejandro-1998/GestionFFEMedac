<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Modulo extends Model
{
    use HasFactory;

    protected $table = 'modulos';

    protected $fillable = [
        'nombre',
        'curso_academico_id',
    ];

    public function cursosAcademicos()
    {
        return $this->belongsToMany(CursoAcademico::class, 'curso_academico_modulo');
    }

    public function cursos()
    {
        return $this->hasMany(Curso::class);
    }

    public function empresas()
    {
        return $this->belongsToMany(Empresa::class, 'empresa_modulo');
    }

    public function empleados()
    {
        return $this->belongsToMany(Empleado::class, 'empleado_modulo');
    }
}
