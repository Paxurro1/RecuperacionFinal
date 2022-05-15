<?php

namespace App\Http\Controllers;

use App\Models\Proyecto;
use App\Models\ProyectoAsignado;
use App\Models\RolAsignado;
use App\Models\Tarea;
use App\Models\TareaAsignada;
use App\Models\Usuario;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class controladorAdministrador extends Controller
{
    public function getUsuarios()
    {
        $usuarios = Usuario::orderBy('nombre', 'asc')->get();
        if ($usuarios) {
            foreach ($usuarios as $u) {
                $roles = RolAsignado::where('dni', '=', $u->dni)
                    ->select('id_rol')
                    ->get();
                $u->roles = $roles;
            }
            return response()->json($usuarios, 200);
        } else {
            return response()->json(['mensaje' => 'Error al obtener los usuarios.'], 402);
        }
    }

    public function borrarUsuario(string $dni)
    {
        // error_log($dni);
        $usuario = Usuario::where('dni', '=', $dni)->get();
        if ($usuario) {
            Usuario::where('dni', '=', $dni)->delete();
            return response()->json(['mensaje' => 'Se ha eliminado el usuario'], 200);
        } else {
            return response()->json(['mensaje' => 'No se pudo eliminar el usuario'], 400);
        }
        // error_log(print_r($usuario, true));
    }

    public function addUsuario(Request $request)
    {
        $roles = $request->get('roles');
        // error_log(print_r($request->get('email'), true));
        $usuario_email = DB::table('usuarios')->where('email', $request->get('email'))->first();
        $usuario_dni = DB::table('usuarios')->where('dni', $request->get('dni'))->first();
        // error_log(print_r($usuario, true));
        if (!$usuario_email && !$usuario_dni) {
            // error_log(print_r('entra', true));
            Usuario::create([
                'dni' => $request->get('dni'),
                'email' => $request->get('email'),
                'nombre' => $request->get('nombre'),
                'apellidos' => $request->get('apellidos'),
                'pass' => Hash::make($request->get('pass'))
            ]);
            foreach ($roles as $r) {
                // error_log($r);
                RolAsignado::create(['id_rol' => $r, 'dni' => $request->get('dni')]);
            }
            return response()->json(['mensaje' => 'Se ha registrado el usuario'], 200);
        } else {
            return response()->json(['mensaje' => 'No se pudo registrar el usuario'], 400);
        }
    }

    public function editarUsuario(Request $request)
    {
        try {
            $email = $request->get('email');
            $nombre = $request->get('nombre');
            $apellidos = $request->get('apellidos');
            $dni = $request->get('dni');
            $roles = $request->get('roles');
            $dniAntiguo = $request->get('dniAntiguo');
            Usuario::where('dni', $dniAntiguo)
                ->update(['dni' => $dni, 'email' => $email, 'nombre' => $nombre, 'apellidos' => $apellidos]);
            RolAsignado::where('dni', '=', $dniAntiguo)->delete();
            RolAsignado::where('dni', '=', $dni)->delete();
            foreach ($roles as $r) {
                error_log($dni);
                error_log($r);
                RolAsignado::create(['id_rol' => $r, 'dni' => $dni]);
                error_log('sigo');
            }
            return response()->json(['mensaje' => 'Usuario actualizado'], 200);
        } catch (Exception $th) {
            return response()->json(['mensaje' => $th->getMessage()], 400);
        }
    }

    public function getJefes()
    {
        $jefes = Usuario::join('roles_asignados', 'roles_asignados.dni', '=', 'usuarios.dni')
            ->where('roles_asignados.id_rol', 2)
            ->get();
        if ($jefes) {
            return response()->json($jefes, 200);
        } else {
            return response()->json(['mensaje' => 'Error al obtener los jefes.'], 402);
        }
    }

    public function getProyectos()
    {
        $proyectos = Proyecto::join('usuarios', 'usuarios.dni', '=', 'proyectos.dni_jefe')
            ->select(['usuarios.nombre', 'proyectos.dni_jefe', 'proyectos.id', 'proyectos.nombre'])
            ->get();
        if ($proyectos) {
            return response()->json($proyectos, 200);
        } else {
            return response()->json(['mensaje' => 'Error al obtener los proyectos.'], 402);
        }
    }

    public function actualizarProyectos(Request $request)
    {
        // error_log(print_r($request->get('proyectos'), true));
        try {
            $proyectos = $request->get('proyectos');
            foreach ($proyectos as $p) {
                // error_log(print_r($p['id'], true));
                Proyecto::where('id', $p['id'])
                    ->update(['nombre' => $p['nombre'], 'dni_jefe' => $p['dni_jefe']]);
            }
            return response()->json(['mensaje' => 'Proyectos actualizados'], 200);
        } catch (Exception $th) {
            return response()->json(['mensaje' => 'Error al actualizar los proyectos'], 400);
        }
    }

    public function getProyectoConUsuarios(int $id)
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

    public function getTrabajadores(int $id)
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

    public function actualizarTrabajadores(Request $request)
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

    public function getTareas(int $id)
    {
        // error_log($id);
        $tareas = Tarea::where('id_proyecto', $id)
            ->select(['id', 'descripcion', 'dificultad', 'estimacion', 'estado', 'f_comienzo', 'f_fin', 'porcentaje'])
            ->get();
        if ($tareas) {
            return response()->json($tareas, 200);
        } else {
            return response()->json(['mensaje' => 'Error al obtener las tareas.'], 402);
        }
    }

    public function addTarea(Request $request)
    {
        try {
            Tarea::create([
                'descripcion' => $request->get('descripcion'),
                'dificultad' => $request->get('dificultad'),
                'estimacion' => $request->get('estimacion'),
                'estado' => 1,
                'f_comienzo' => $request->get('f_comienzo'),
                'f_fin' => $request->get('f_fin'),
                'porcentaje' => 0,
                'id_proyecto' => $request->get('id_proyecto')
            ]);
            return response()->json(['mensaje' => 'Tarea registrada'], 200);
        } catch (Exception $th) {
            return response()->json(['mensaje' => $th->getMessage()], 400);
        }
    }

    public function borrarTarea(string $id)
    {
        $tarea = Tarea::where('id', '=', $id)->get();
        if ($tarea) {
            Tarea::where('id', '=', $id)->delete();
            return response()->json(['mensaje' => 'Se ha eliminado la tarea'], 200);
        } else {
            return response()->json(['mensaje' => 'No se pudo eliminar la tarea'], 400);
        }
    }

    public function editarTarea(Request $request)
    {
        try {
            Tarea::where('id', $request->get('id'))
                ->update([
                    'descripcion' => $request->get('descripcion'),
                    'dificultad' => $request->get('dificultad'),
                    'estimacion' => $request->get('estimacion'),
                    'estado' => $request->get('estado'),
                    'f_comienzo' => $request->get('f_comienzo'),
                    'f_fin' => $request->get('f_fin'),
                    'porcentaje' => $request->get('porcentaje')
                ]);

            return response()->json(['mensaje' => 'Tarea actualizadoa'], 200);
        } catch (Exception $th) {
            return response()->json(['mensaje' => $th->getMessage()], 400);
        }
    }

    public function getUsuariosProyecto(int $id)
    {
        $usuarios = Usuario::join('proyectos_asignados', 'proyectos_asignados.dni', '=', 'usuarios.dni')
            ->where('proyectos_asignados.id_proyecto', '=', $id)
            ->get();
        if ($usuarios) {
            return response()->json($usuarios, 200);
        } else {
            return response()->json(['mensaje' => 'Error al obtener los usuarios.'], 402);
        }
    }

    public function getUsuarioConTareas(int $id, string $dni)
    {
        // error_log($dni);
        $trabajadores = Usuario::where('dni', '=', $dni)
            ->select(['dni', 'email', 'nombre', 'apellidos'])
            ->get();
        // error_log($trabajadores);
        foreach ($trabajadores as $t) {
            $tareas = Tarea::join('tareas_asignadas', 'tareas_asignadas.id_tarea', '=', 'tareas.id')
                ->join('usuarios', 'usuarios.dni', '=', 'tareas_asignadas.dni')
                ->where([['tareas.id_proyecto', '=', $id], ['tareas_asignadas.dni', $dni]])
                ->select(['tareas.descripcion', 'tareas.id'])
                ->get();
            $t->tareas = $tareas;
        }
        // error_log($trabajadores);
        if ($trabajadores) {
            return response()->json($trabajadores, 200);
        } else {
            return response()->json(['mensaje' => 'Error al obtener los usuarios.'], 402);
        }
    }

    public function getTareasSinAsignar(int $id, string $dni)
    {
        // error_log($id);
        $tareasAsignadas = Tarea::join('tareas_asignadas', 'tareas_asignadas.id_tarea', '=', 'tareas.id')
            ->join('usuarios', 'usuarios.dni', '=', 'tareas_asignadas.dni')
            ->where([['tareas.id_proyecto', '=', $id], ['tareas_asignadas.dni', $dni]])
            ->pluck('tareas.id')
            ->toArray();
        // error_log(print_r($tareasAsignadas, true));
        $tareas = Tarea::join('proyectos', 'proyectos.id', '=', 'tareas.id_proyecto')
            ->where([['tareas.id_proyecto', '=', $id]])
            ->whereNotIn('tareas.id', $tareasAsignadas)
            ->select(['tareas.descripcion', 'tareas.id'])
            ->get();
        // error_log(print_r($tareas, true));
        if ($tareas) {
            return response()->json($tareas, 200);
        } else {
            return response()->json(['mensaje' => 'Error al obtener las tareas.'], 402);
        }
    }

    public function actualizarTareas(Request $request)
    {
        try {
            $tareasSolas = $request->get('tareasSolas');
            // error_log(print_r($request['tareasSolas'], true));
            $trabajador = $request->get('trabajadores')[0];
            // error_log(print_r($trabajador['tareas'][0]['id'], true));
            foreach ($tareasSolas as $t) {
                TareaAsignada::where([['id_tarea', $t['id']], ['dni', $trabajador['dni']]])->delete();
            }
            foreach ($trabajador['tareas'] as $t) {
                // error_log(print_r($t['id'], true));
                // error_log(print_r($trabajador['dni'], true));
                TareaAsignada::where([['id_tarea', $t['id']], ['dni', $trabajador['dni']]])->delete();
                TareaAsignada::create([
                    'id_tarea' => $t['id'],
                    'dni' => $trabajador['dni']
                ]);
            }
        } catch (Exception $th) {
            return response()->json(['mensaje' => $th->getMessage()], 400);
        }
    }

    public function addProyecto(Request $request)
    {
        try {
            Proyecto::create([
                'nombre' => $request->get('nombre'),
                'dni_jefe' => $request->get('jefe'),
            ]);
            return response()->json(['mensaje' => 'Proyecto registrado'], 200);
        } catch (Exception $th) {
            return response()->json(['mensaje' => $th->getMessage()], 400);
        }
    }

    public function borrarProyecto(string $id)
    {
        $proyecto = Proyecto::where('id', '=', $id)->get();
        if ($proyecto) {
            proyecto::where('id', '=', $id)->delete();
            return response()->json(['mensaje' => 'Se ha eliminado el proyecto'], 200);
        } else {
            return response()->json(['mensaje' => 'No se pudo eliminar el proyecto'], 400);
        }
    }
}
