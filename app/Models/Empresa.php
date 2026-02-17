<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\RegistrarActividad;

class Empresa extends Model
{
    use HasFactory, RegistrarActividad;

    protected $table = 'empresas';
    protected $primaryKey = 'id';

    protected $fillable = ['nombre', 'email', 'telefono', 'direccion', 'nif', 'convenio_path'];

    public function sedes()
    {
        return $this->hasMany(Sede::class);
    }

    public function empleados()
    {
        return $this->hasMany(Empleado::class);
    }

    public function convenios()
    {
        return $this->hasMany(Convenio::class);
    }

    public function cursos()
    {
        return $this->belongsToMany(CursoAcademico::class, 'curso_academico_empresa');
    }

    public function modulos()
    {
        return $this->belongsToMany(Modulo::class, 'empresa_modulo');
    }
}
