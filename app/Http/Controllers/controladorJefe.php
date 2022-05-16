<?php

namespace App\Http\Controllers;

use App\Models\Proyecto;
use App\Models\ProyectoAsignado;
use App\Models\Usuario;
use Exception;
use Illuminate\Http\Request;

class controladorJefe extends Controller
{
    public function getProyectosJefe(string $dni)
    {
        $proyectos = Proyecto::join('usuarios', 'usuarios.dni', '=', 'proyectos.dni_jefe')
        ->where('proyectos.dni_jefe', $dni)
            ->select(['usuarios.nombre', 'proyectos.dni_jefe', 'proyectos.id', 'proyectos.nombre'])
            ->get();
        if ($proyectos) {
            return response()->json($proyectos, 200);
        } else {
            return response()->json(['mensaje' => 'Error al obtener los proyectos.'], 402);
        }
    }

    public function getProyectoConUsuariosJefe(int $id)
    {
        // error_log($id);
        $proyecto = Proyecto::where('proyectos.id', '=', $id)
            ->select(['proyectos.id', 'proyectos.nombre', 'proyectos.dni_jefe'])
            ->get();
        foreach ($proyecto as $p) {
            $trabajadores = Usuario::join('proyectos_asignados', 'proyectos_asignados.dni', '=', 'usuarios.dni')
                ->where('proyectos_asignados.id_proyecto', '=', $id)
                ->select(['usuarios.nombre', 'usuarios.dni'])
                ->get();
            $p->trabajadores = $trabajadores;
        }
        // error_log($proyecto);
        if ($proyecto) {
            return response()->json($proyecto, 200);
        } else {
            return response()->json(['mensaje' => 'Error al obtener el proyecto.'], 402);
        }
    }

    public function getTrabajadoresJefe(int $id)
    {
        $trabajadoresEnProyecto = Usuario::join('proyectos_asignados', 'proyectos_asignados.dni', '=', 'usuarios.dni')
            ->where('proyectos_asignados.id_proyecto', '=', $id)
            ->pluck('usuarios.dni')
            ->toArray();
        // error_log('eyyy');
        $trabajadores = Usuario::join('roles_asignados', 'roles_asignados.dni', '=', 'usuarios.dni')
            ->where('roles_asignados.id_rol', '=', 3)
            ->whereNotIn('usuarios.dni', $trabajadoresEnProyecto)
            ->select(['usuarios.nombre', 'usuarios.dni'])
            ->get();
        // error_log($trabajadores);
        if ($trabajadores) {
            return response()->json($trabajadores, 200);
        } else {
            return response()->json(['mensaje' => 'Error al obtener los trabajadores.'], 402);
        }
    }

    public function actualizarTrabajadoresJefe(Request $request)
    {
        try {
            $proyecto = $request->get('proyectos')[0];
            // error_log(print_r($proyecto['trabajadores'], true));
            ProyectoAsignado::where('id_proyecto', $proyecto['id'])->delete();
            $trabajadores = $proyecto['trabajadores'];
            foreach ($trabajadores as $trabajador) {
                ProyectoAsignado::create([
                    'id_proyecto' => $proyecto['id'],
                    'dni' => $trabajador['dni']
                ]);
            }
            return response()->json(['mensaje' => 'Actualizado con exito'], 200);
        } catch (Exception $th) {
            return response()->json(['mensaje' => $th->getMessage()], 400);
        }
    }
}
