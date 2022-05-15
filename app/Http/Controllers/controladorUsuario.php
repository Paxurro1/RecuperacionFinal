<?php

namespace App\Http\Controllers;

use App\Models\RolAsignado;
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
}
