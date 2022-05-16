<?php

namespace App\Http\Controllers;

use App\Models\Proyecto;
use App\Models\RolAsignado;
use App\Models\Tarea;
use App\Models\Usuario;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class controladorUsuario extends Controller
{
    public function login(Request $request)
    {
        $email = $request->get('email');
        $pass = $request->get('pass');
        $usuario = DB::table('usuarios')->where('email', $email)->first();

        if ($usuario) {
            $ckPass = Hash::check($pass, $usuario->pass);

            if ($ckPass) {
                $usuario = $this->getDatosUsuario($usuario);
                // error_log(print_r($usuario, true));
                return response()->json($usuario, 200);
            } else {
                return response()->json(['mensaje' => 'Datos de inicio de sesión incorrectos'], 403);
            }
        } else {
            return response()->json(['mensaje' => 'Datos de inicio de sesión incorrectos'], 403);
        }
    }

    public static function getDatosUsuario($usuario)
    {
        $usuario = Usuario::where('email', '=', $usuario->email)
            ->select(['email', 'nombre', 'apellidos', 'dni'])
            ->first();
        $roles = RolAsignado::where('dni', '=', $usuario->dni)
            ->select('id_rol')
            ->get();
        $usuario->roles = $roles;
        return $usuario;
    }

    public function editarPerfil(Request $request)
    {
        try {
            $email = $request->get('email');
            // error_log($email);
            $nombre = $request->get('nombre');
            $apellidos = $request->get('apellidos');
            $dni = $request->get('dni');
            $dniAntiguo = $request->get('dniAntiguo');
            Usuario::where('dni', $dniAntiguo)
                ->update(['dni' => $dni, 'email' => $email, 'nombre' => $nombre, 'apellidos' => $apellidos]);
            $usuario = DB::table('usuarios')->where('email', $email)->first();

            $usuario = $this->getDatosUsuario($usuario);
            // error_log(print_r($usuario, true));
            return response()->json($usuario, 200);
        } catch (Exception $th) {
            return response()->json(['mensaje' => $th->getMessage()], 400);
        }
    }

    public function cambiarPass(Request $request)
    {
        try {
            // error_log('ey');
            $dniAntiguo = $request->get('dniAntiguo');
            // error_log($dniAntiguo);
            $pass = $request->get('pass');
            // error_log($pass);
            Usuario::where('dni', $dniAntiguo)
                ->update(['pass' => Hash::make($pass)]);
            return response()->json(['mensaje' => 'Se ha cambiado la contraseña'], 200);
        } catch (Exception $th) {
            return response()->json(['mensaje' => $th->getMessage()], 400);
        }
    }

    public function getProyectosUsuario(string $dni)
    {
        // error_log('ey');
        $proyectos = Proyecto::join('proyectos_asignados', 'proyectos_asignados.id_proyecto', '=', 'proyectos.id')
            ->join('usuarios', 'usuarios.dni', '=', 'proyectos_asignados.dni')
            ->where('proyectos_asignados.dni', $dni)
            ->select(['usuarios.nombre', 'proyectos.dni_jefe', 'proyectos.id', 'proyectos.nombre'])
            ->get();
        if ($proyectos) {
            return response()->json($proyectos, 200);
        } else {
            return response()->json(['mensaje' => 'Error al obtener los proyectos.'], 402);
        }
    }

    public function getHacer(int $id, string $dni)
    {
        $hacer = Tarea::join('tareas_asignadas', 'tareas_asignadas.id_tarea', '=', 'tareas.id')
        ->join('usuarios', 'usuarios.dni', '=', 'tareas_asignadas.dni')
        ->where([['tareas_asignadas.dni', $dni], ['tareas.id_proyecto', $id], ['tareas.estado', 1]])
        ->select(['tareas.id', 'tareas.descripcion', 'tareas.dificultad', 'tareas.estimacion', 'tareas.estado', 'tareas.f_comienzo', 'tareas.f_fin', 'tareas.porcentaje'])
        ->get();
        // error_log(print_r($hacer, true));
        return response()->json($hacer, 200);
    }

    public function getHaciendo(int $id, string $dni)
    {
        $haciendo = Tarea::join('tareas_asignadas', 'tareas_asignadas.id_tarea', '=', 'tareas.id')
        ->join('usuarios', 'usuarios.dni', '=', 'tareas_asignadas.dni')
        ->where([['tareas_asignadas.dni', $dni], ['tareas.id_proyecto', $id], ['tareas.estado', 2]])
        ->select(['tareas.id', 'tareas.descripcion', 'tareas.dificultad', 'tareas.estimacion', 'tareas.estado', 'tareas.f_comienzo', 'tareas.f_fin', 'tareas.porcentaje'])
        ->get();
        // error_log(print_r($haciendo, true));
        return response()->json($haciendo, 200);
    }

    public function getHecho(int $id, string $dni)
    {
        $hecho = Tarea::join('tareas_asignadas', 'tareas_asignadas.id_tarea', '=', 'tareas.id')
        ->join('usuarios', 'usuarios.dni', '=', 'tareas_asignadas.dni')
        ->where([['tareas_asignadas.dni', $dni], ['tareas.id_proyecto', $id], ['tareas.estado', 3]])
        ->select(['tareas.id', 'tareas.descripcion', 'tareas.dificultad', 'tareas.estimacion', 'tareas.estado', 'tareas.f_comienzo', 'tareas.f_fin', 'tareas.porcentaje'])
        ->get();
        // error_log(print_r($hecho, true));
        return response()->json($hecho, 200);
    }

    public function actualizarTareasUsuario(Request $request)
    {
        try {
            $hacer = $request->get('hacer');
            $haciendo = $request->get('haciendo');
            $hecho = $request->get('hecho');
            foreach ($hacer as $tarea) {
                Tarea::where('id', $tarea['id'])
                ->update(['estado' => 1]);
            }
            foreach ($haciendo as $tarea) {
                Tarea::where('id', $tarea['id'])
                ->update(['estado' => 2, 'porcentaje' => $tarea['porcentaje']]);
            }
            foreach ($hecho as $tarea) {
                Tarea::where('id', $tarea['id'])
                ->update(['estado' => 3]);
            }
            return response()->json(['mensaje' => 'Tareas actualizadas'], 200);
        } catch (Exception $th) {
            return response()->json(['mensaje' => $th->getMessage()], 400);
        }
    }
}
