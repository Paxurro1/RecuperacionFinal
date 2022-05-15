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
    // Gestión de usuarios
    Route::get('/getUsuarios', [controladorAdministrador::class, 'getUsuarios']);
    Route::delete('/borrarUsuario/{dni}', [controladorAdministrador::class, 'borrarUsuario']);
    Route::post('/addUsuario', [controladorAdministrador::class, 'addUsuario']);
    Route::post('/editarUsuario', [controladorAdministrador::class, 'editarUsuario']);
    // Gestión de proyectos
    Route::get('/getJefes', [controladorAdministrador::class, 'getJefes']);
    Route::get('/getProyectos', [controladorAdministrador::class, 'getProyectos']);
    Route::post('/actualizarProyectos', [controladorAdministrador::class, 'actualizarProyectos']);
    Route::get('/getProyectoConUsuarios/{id}', [controladorAdministrador::class, 'getProyectoConUsuarios']);
    Route::get('/getTrabajadores/{id}', [controladorAdministrador::class, 'getTrabajadores']);
    Route::post('/actualizarTrabajadores', [controladorAdministrador::class, 'actualizarTrabajadores']);
    // Gestión de tareas
    Route::get('/getTareas/{id}', [controladorAdministrador::class, 'getTareas']);
    Route::post('/addTarea', [controladorAdministrador::class, 'addTarea']);
    Route::delete('/borrarTarea/{id}', [controladorAdministrador::class, 'borrarTarea']);
    Route::post('/editarTarea', [controladorAdministrador::class, 'editarTarea']);
    // Asignar tareas
    Route::get('/getUsuariosProyecto/{id}', [controladorAdministrador::class, 'getUsuariosProyecto']);
    Route::get('/getUsuarioConTareas/{id}/{dni}', [controladorAdministrador::class, 'getUsuarioConTareas']);
    Route::get('/getTareasSinAsignar/{id}/{dni}', [controladorAdministrador::class, 'getTareasSinAsignar']);
});
