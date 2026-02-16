<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Visita extends Model
{
    use HasFactory;

    protected $table = 'visitas';
    protected $primaryKey = 'id';

    protected $fillable = [
        'convenio_id',
        'fecha',
        'observaciones'
    ];

    protected $casts = [
        'fecha' => 'datetime',
    ];

    public function convenio()
    {
        return $this->belongsTo(Convenio::class);
    }
}
