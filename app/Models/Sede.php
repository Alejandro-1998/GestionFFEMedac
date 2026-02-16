<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\RegistrarActividad;

class Sede extends Model
{
    use HasFactory, RegistrarActividad;

    protected $table = 'sedes';
    protected $primaryKey = 'id';

    protected $fillable = [
        'empresa_id',
        'nombre',
        'ubicacion',
        'direccion',
        'telefono'
    ];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function empleados()
    {
        return $this->hasMany(Empleado::class);
    }

    public function convenios()
    {
        return $this->hasMany(Convenio::class);
    }
}
