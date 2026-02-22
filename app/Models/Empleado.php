<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Empleado extends Model
{
    use HasFactory;

    protected $table = 'empleados';
    protected $primaryKey = 'id';

    protected $fillable = [
        'empresa_id',
        'sede_id',
        'dni_pasaporte',
        'nombre',
        'apellido',
        'apellido2',
        'email',
        'fecha_nacimiento',
        'cargo',
        'telefono_responsable_laboral',
        'activo'
    ];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function sede()
    {
        return $this->belongsTo(Sede::class);
    }

    public function convenios()
    {
        return $this->hasMany(Convenio::class, 'empleado_id');
    }

    public function modulos()
    {
        return $this->belongsToMany(Modulo::class, 'empleado_modulo');
    }
}
