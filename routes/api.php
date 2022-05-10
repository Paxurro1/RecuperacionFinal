<?php

use App\Http\Controllers\controladorAdministrador;
use App\Http\Controllers\controladorUsuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group(['middleware' => ['Cors']], function () {
    //Login
    Route::post('/login', [controladorUsuario::class, 'login']);

    // Admin
    Route::get('/getUsuarios', [controladorAdministrador::class, 'getUsuarios']);
    Route::delete('/borrarUsuario/{dni}', [controladorAdministrador::class, 'borrarUsuario']);
    Route::post('/addUsuario', [controladorAdministrador::class, 'addUsuario']);
    Route::post('/editarUsuario', [controladorAdministrador::class, 'editarUsuario']);
});
