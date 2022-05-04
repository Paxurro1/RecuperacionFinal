<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TareaAsignada extends Model
{
    use HasFactory;
    protected $table = 'tareas_asignadas';
    protected $primaryKey = ['id_tarea', 'dni'];
    public $incrementing = false;
    protected $keyType = ['unsignedBigInteger', 'string'];
    protected $fillable = [
        'id_tarea',
        'dni'
    ];
}
