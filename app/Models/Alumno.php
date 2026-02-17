<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alumno extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre_completo',
        'dni',
        'dni_encriptado',
        'nota_1',
        'nota_2',
        'nota_3',
        'nota_4',
        'nota_5',
        'nota_6',
        'nota_7',
        'nota_8',
        'nota_media',
        'empresa_id',
        'curso_id',
        'curso_academico_id',
        'email',
    ];

    public function cursoAcademico()
    {
        return $this->belongsTo(CursoAcademico::class);
    }

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::saving(function ($alumno) {
            // Auto-calculate nota_media
            $grades = collect([
                $alumno->nota_1,
                $alumno->nota_2,
                $alumno->nota_3,
                $alumno->nota_4,
                $alumno->nota_5,
                $alumno->nota_6,
                $alumno->nota_7,
                $alumno->nota_8,
            ])->filter(function ($grade) {
                return !is_null($grade) && $grade !== '';
            });

            if ($grades->isNotEmpty()) {
                $alumno->nota_media = round($grades->avg(), 2);
            } else {
                $alumno->nota_media = null;
            }

            if ($alumno->isDirty('dni') && $alumno->dni) {
                $dni = $alumno->dni;
                // Masking logic: 31017272R -> 31**72**R
                // Indices 0,1 kept. 2,3 masked. 4,5 kept. 6,7 masked. 8 kept.
                
                if (strlen($dni) >= 9) {
                    $masked = substr($dni, 0, 2) . '**' . substr($dni, 4, 2) . '**' . substr($dni, 8);
                    $alumno->dni_encriptado = $masked;
                } else {
                    // Fallback for short DNIs, just store as is or simple mask? 
                    // Assuming valid DNI length usually, but let's be safe.
                    $alumno->dni_encriptado = $dni;
                }
            }
        });
    }

    public function convenios()
    {
        return $this->hasMany(Convenio::class);
    }

    public function empresa()
    {
        return $this->hasOneThrough(Empresa::class, Convenio::class, 'alumno_id', 'id', 'id', 'empresa_id');
    }

    public function curso()
    {
        return $this->belongsTo(Curso::class, 'curso_id');
    }
}
