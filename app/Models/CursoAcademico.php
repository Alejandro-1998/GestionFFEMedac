<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CursoAcademico extends Model
{
    use HasFactory;

    protected $table = 'cursos_academicos';
    protected $primaryKey = 'id';

    protected $fillable = [
        'anyo',
        'actual',
    ];

    public function modulos()
    {
        return $this->belongsToMany(Modulo::class, 'curso_academico_modulo');
    }



    public function convenios()
    {
        return $this->hasMany(Convenio::class, 'curso_academico_id');
    }

    public function alumnos()
    {
        return $this->hasMany(Alumno::class, 'curso_academico_id');
    }

    public function profesores()
    {
        return $this->belongsToMany(User::class, 'curso_academico_user', 'curso_academico_id', 'user_id')->where('rol', 'profesor');
    }
}
