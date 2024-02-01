<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\User;

/* Controlador para la autenticación de usuario */

class AuthController extends Controller
{
    // Función para el inicio de sesión
    public function login(Request $request)
    {
        // Validar que reciba el username y password
        $this->validate($request, [
            'username' => 'required',
            'password' => 'required'
        ]);

    	if (!$request->username OR !$request->password)
            return response(['message' => 'Solicitud no válida. Por favor ingrese un nombre de usuario y contraseña.'], 422);

        $user = User::where('username', $request->username)->first();

        // Si no se encuentra ese usuario en la BD devolver un mensaje de error
        if (!$user)
            return response(['message' => 'Verifica tu nombre de usuario o contraseña.'], 422);

        if (config('app.env') == 'local'):
            $client = new \GuzzleHttp\Client(['verify' => false]);
        else:
            $client = new \GuzzleHttp\Client;
        endif;

        try {

            // Iniciar sesión con las credenciales del usuario y del cliente de passport para obtener los tokens de acceso

            $response = $client->post(env('PASSPORT_ENDPOINT'), [
                'form_params' => [
                    'grant_type' => 'password',
                    'client_id' => env('PASSPORT_CLIENT_ID'),
                    'client_secret' => env('PASSPORT_CLIENT_SECRET'),
                    'username' => $user->username,
                    'password' => $request->password
                ]
            ]);

            return response( $response->getBody(), 200);

        } catch (\GuzzleHttp\Exception\BadResponseException $e) {

            if ($e->getCode() === 400) {
                return response(['message' => 'Solicitud no válida. Por favor ingrese un nombre de usuario y contraseña.' ], $e->getCode());

            } else if ($e->getCode() === 401) {
                return response(['message' => 'Tus credenciales son incorrectas. Inténtalo de nuevo.'], $e->getCode());
            }

            return response(['message' => 'El servidor presenta un problema.'], $e->getCode());
        }
    }

    // Función para obtener un nuevo access_token utilizando el refresh_token en caso de que haya expirado el token actual
    public function refresh(Request $request)
    {
        if (config('app.env') == 'local'):
            $client = new \GuzzleHttp\Client(['verify' => false]);
        else:
            $client = new \GuzzleHttp\Client;
        endif;

        try {

            $response = $client->post(env('PASSPORT_ENDPOINT'), [
                'form_params' => [
                    'grant_type' => 'refresh_token',
                    'client_id' => env('PASSPORT_CLIENT_ID'),
                    'client_secret' => env('PASSPORT_CLIENT_SECRET'),
                    'refresh_token' => $request->refresh_token,
                    'scope' => ''
                ]
            ]);

            return response( $response->getBody(), 200);

        } catch (\GuzzleHttp\Exception\BadResponseException $e) {
            return response([ 'errors' => ['El servidor presenta un problema.']], $e->getCode());
        }
    }

    // Función para cerrar sesión
    public function logout()
    {
        auth()->user()->tokens->each(function ($token, $key) {
            $token->delete();
        });

        return response(['message' => 'Se ha cerrado sesión exitosamente'], 200);
    }
}
