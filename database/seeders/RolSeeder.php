<?php

namespace Database\Seeders;

use App\Models\Rol;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Rol::create([
            'descripcion' => 'Administrador'
        ]);
        Rol::create([
            'descripcion' => 'Jefe de equipo'
        ]);
        Rol::create([
            'descripcion' => 'Usuario'
        ]);
    }
}
