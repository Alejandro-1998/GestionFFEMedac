<?php

namespace App\Traits;

use App\Models\RegistroActividad;

trait RegistrarActividad
{
    public static function bootRegistrarActividad()
    {
        static::created(function ($model) {
            $model->registrarActividad('creado');
        });

        static::updated(function ($model) {
            $model->registrarActividad('actualizado');
        });

        static::deleted(function ($model) {
            $model->registrarActividad('eliminado');
        });
    }

    public function registrarActividad($evento)
    {
        RegistroActividad::create([
            'descripcion' => class_basename($this) . " fue {$evento}",
            'sujeto_type' => get_class($this),
            'sujeto_id' => $this->id,
            'evento' => $evento,
        ]);
    }
}
