<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\RegistrarActividad;
use Carbon\Carbon;

class Convenio extends Model
{
    use HasFactory, RegistrarActividad;

    protected $table = 'convenios';
    protected $primaryKey = 'id';

    protected $fillable = [
        'alumno_id',
        'profesor_id',
        'empresa_id',
        'empleado_id',
        'curso_academico_id',
        'sede_id',
        'total_horas',
        'fecha_inicio',
        'fecha_fin',
        'estado'
    ];

    public function alumno()
    {
        return $this->belongsTo(Alumno::class, 'alumno_id');
    }

    public function profesor()
    {
        return $this->belongsTo(User::class, 'profesor_id');
    }

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function sede()
    {
        return $this->belongsTo(Sede::class);
    }

    public function tutorLaboral()
    {
        return $this->belongsTo(Empleado::class, 'empleado_id');
    }

    public function curso()
    {
        return $this->belongsTo(CursoAcademico::class, 'curso_academico_id');
    }
    public function getEstadoAttribute(): string
    {
        $raw = $this->attributes['estado'] ?? null;

        if ($raw === 'cancelada') return 'cancelada';

        $hoy    = Carbon::today();
        $inicio = $this->fecha_inicio ? Carbon::parse($this->fecha_inicio) : null;
        $fin    = $this->fecha_fin    ? Carbon::parse($this->fecha_fin)    : null;

        if (!$inicio)          return 'asignada';
        if ($hoy->lt($inicio)) return 'asignada';
        if (!$fin || $hoy->lte($fin)) return 'en_proceso';
        return 'finalizada';
    }
}
