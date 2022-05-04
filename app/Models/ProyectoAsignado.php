<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProyectoAsignado extends Model
{
    use HasFactory;
    protected $table = 'proyectos_asignados';
    protected $primaryKey = ['id_proyecto', 'dni'];
    public $incrementing = false;
    protected $keyType = ['unsignedBigInteger', 'string'];
    protected $fillable = [
        'id_proyecto',
        'dni'
    ];
}
