<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tarea extends Model
{
    use HasFactory;
    protected $table = 'tareas';
    protected $fillable = [
        'descripcion',
        'dificultad',
        'estimacion',
        'f_comienzo',
        'f_fin',
        'porcentaje',
        'id_proyecto'
    ];
}
