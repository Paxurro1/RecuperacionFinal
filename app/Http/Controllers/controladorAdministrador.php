<?php

namespace App\Http\Controllers;

use App\Models\RolAsignado;
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
                error_log($r);
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
            $pass = Hash::make($request->get('pass'));
            $roles = $request->get('roles');
            $dniAntiguo = $request->get('dniAntiguo');
            Usuario::where('dni', $dniAntiguo)
                ->update(['dni' => $dni, 'email' => $email, 'nombre' => $nombre, 'apellidos' => $apellidos, 'pass' => $pass]);
            RolAsignado::where('dni', '=', $dniAntiguo)->delete();
            foreach ($roles as $r) {
                RolAsignado::create(['id_rol' => $r, 'dni' => $dni]);
            }
            return response()->json(['mensaje' => 'Usuario actualizado'], 200);
        } catch (Exception $th) {
            return response()->json(['mensaje' => 'El dni o el email ya está registrado'], 400);
        }
    }
}
