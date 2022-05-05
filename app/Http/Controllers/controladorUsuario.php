<?php

namespace App\Http\Controllers;

use App\Models\RolAsignado;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class controladorUsuario extends Controller
{
    public function login(Request $request)
    {
        //Extraigo los campos
        $email = $request->get('email');
        $pass = $request->get('pass');
        //Hago la query
        $usuario = DB::table('usuarios')->where('email', $email)->first();

        if ($usuario) {
            $ckPass = Hash::check($pass, $usuario->pass);

            if ($ckPass) {
                $usuario = $this->getDatosUsuario($usuario);
                error_log(print_r($usuario, true));
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
}
