<?php

namespace Database\Seeders;

use App\Models\Proyecto;
use App\Models\ProyectoAsignado;
use App\Models\RolAsignado;
use App\Models\Tarea;
use App\Models\TareaAsignada;
use App\Models\Usuario;
use Database\Factories\ProyectoAsignadoFactory;
use Database\Factories\ProyectoFactory;
use Database\Factories\RolAsigFactory;
use Database\Factories\RolAsignadoFactory;
use Database\Factories\TareaAsignadaFactory;
use Database\Factories\TareaFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 0; $i < 200; $i++) {
            $usuario = Usuario::factory()->create();
            $dni = $usuario->dni;
            RolAsignadoFactory::$DNI = $dni;
            RolAsignadoFactory::$ROL = 3;
            RolAsignado::factory()->create();
            if ($i % 10 == 0) {
                RolAsignadoFactory::$ROL = 2;
                RolAsignado::factory()->create();
                ProyectoFactory::$DNI = $dni;
                $proyecto = Proyecto::factory()->create();
            }
            ProyectoAsignadoFactory::$DNI = $dni;
            ProyectoAsignadoFactory::$ID = $proyecto->id;
            ProyectoAsignado::factory()->create();
            if ($i % 2 == 0) {
                TareaFactory::$ID = $proyecto->id;
                $tarea = Tarea::factory()->create();
                TareaAsignadaFactory::$ID = $tarea->id;
                TareaAsignadaFactory::$DNI = $dni;
                TareaAsignada::factory()->create();
            }
            if ($i % 30 == 0 || $i % 55 == 0) {
                RolAsignadoFactory::$ROL = 1;
                RolAsignado::factory()->create();
            }
        }
    }
}
