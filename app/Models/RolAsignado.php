<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RolAsignado extends Model
{
    use HasFactory;
    protected $table = 'roles_asignados';
    protected $primaryKey = ['id_rol', 'dni'];
    public $incrementing = false;
    protected $keyType = ['unsignedBigInteger', 'string'];
    protected $fillable = [
        'id_rol',
        'dni'
    ];
}
