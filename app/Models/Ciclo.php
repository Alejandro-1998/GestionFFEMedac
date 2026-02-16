<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ciclo extends Model
{
    use HasFactory;

    protected $table = 'ciclos';

    protected $fillable = ['nombre'];

    public function cursos()
    {
        return $this->hasMany(CursoAcademico::class);
    }

    public function empresas()
    {
        return $this->belongsToMany(Empresa::class, 'ciclo_empresa');
    }
}
