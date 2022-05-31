<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiasMaximos extends Model
{
    use HasFactory;
    protected $table = 'dias_maximos';
    protected $fillable = [
        'dias'
    ];
}
