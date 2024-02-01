<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers;
use App\Http\Controllers\Auth;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;

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

// Funciones dentro del middleware para retornar la info como json
Route::group(['middleware' => ['return.json']], function () {
	/* Authentication Routes */	
	Route::post('login', [Auth\AuthController::class, 'login'])->name('api.login');
	Route::post('test', [PostController::class, 'test'])->name('api.post.test');

	// Se acceden a estas rutas solo si el usuario ha iniciado sesión
	Route::middleware('auth:api')->group(function () {
		//Authentication routes
		Route::post('logout', [Auth\AuthController::class, 'logout'])->name('api.logout');
		Route::get('user/info', [UserController::class, 'info'])->name('api.user.info');
		Route::get('users', [UserController::class, 'list'])->name('api.users.list');
		Route::get('user/data/{id}', [UserController::class, 'data'])->name('api.users.data');
		Route::post('user/save', [UserController::class, 'save'])->name('api.users.save'); // Puede guardar y actualizar

		// Se necesita ser administrador para eliminar el usuario
		Route::group(['middleware' => ['role:admin']], function() { 
			Route::delete('user/delete/{id}', [UserController::class, 'delete'])->name('api.user.delete');
		});

		Route::post('posts/index', [PostController::class, 'index'])->name('api.posts.index');
		Route::post('post/save', [PostController::class, 'save'])->name('api.post.save'); // Puede guardar y actualizar
		Route::post('post/store', [PostController::class, 'store'])->name('api.post.store');
		Route::get('post/show', [PostController::class, 'show'])->name('api.post.show');
		Route::post('post/update', [PostController::class, 'update'])->name('api.post.update');
		Route::get('post/data/{id}', [PostController::class, 'data'])->name('api.post.data');

		// Puede ser administrador o empleado para eliminar el post
		Route::group(['middleware' => ['role:admin|employee']], function() { 
			Route::delete('post/destroy/{id}', [PostController::class, 'destroy'])->name('api.post.destroy');
		});

		Route::get('roles', [UserController::class, 'getRoles'])->name('api.roles.get');
	});
});

Route::post('test', [PostController::class, 'test'])->name('api.post.test');

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
