<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

use App\Models\User;
use App\Models\Role;
use App\Mail\WelcomeEmail;

use Hash;

class UserController extends Controller
{
    /* Función para obtener la información del usuario logueado */
    public function info (Request $request)
    {
    	$user = $request->user();
    	$user->roles;

    	return response(['error' => false, 'message' => "Datos del usuario", 'data' => $user], 200);
    }

    /* Función para obtener el listado de usuarios */
    public function list(Request $request)
    {
    	$data = User::select(['id', 'username', 'name', 'email', 'phone', 'birthdate', 'created_at'])->get();

    	return response(['error' => false, 'message' => "Listado de usuarios", 'data' => $data], 200);
    }

    /* Función para obtener los roles de usuario */
    public function getRoles(Request $request)
    {
        $roles = Role::all();
        return response(['error' => false, 'message' => "Roles de Usuario.", 'data' => $roles], 200);
    }

    /* Función para obtener la información del usuario con el Id proporcionado */
    public function data(Request $request)
    {
        $user = User::findOrFail($request->id);
        $user->roles;
        return response(['error' => false, 'message' => "Datos del Usuario.", 'data' => $user], 200);
    }

    /* Función para guardar y actualizar la información del usuario */
    public function save(Request $request)
    {
        $this->validate($request, [
            // 'username' => 'nullable|string|min:5|max:36|unique:users|regex:/^[0-9A-Za-z.\-_]+(?<![_.])$/',
            'user_id'   => 'nullable',
            'username'  => 'required|string|max:36|regex:/^[0-9A-Za-z.\-_]+(?<![_.])$/',
            'name'      => 'required|string|max:50',
            'email'     => 'required|email',
            'phone'     => 'nullable|string|max:14',
            'password'  => 'nullable|min:3|max:30'
        ]);

        $currentUser = Auth::user();
        $user_id = intval($request->user_id);

        if (!$user_id):
            $user = new User();
            $user->created_by = $currentUser->id;
            $msg = "Se ha creado el usuario <strong>".$request->name."</strong> correctamente.";
        else:
            $user = User::findOrFail($user_id);
            $msg = "Se ha actualizado el usuario <strong>".$request->name."</strong> correctamente.";
        endif;

        $user->username   = $request->username;
        $user->name       = $request->name;
        $user->email      = $request->email;
        $user->phone      = $request->phone;
        $user->birthdate  = date('Y-m-d', strtotime(str_replace('/', '-', $request->birthdate)));
        $user->updated_by = $currentUser->id;

        // Actualizar la contraseña solo si se recibe
        if ($request->password):
            $user->password  = Hash::make($request->password);
        endif;

        $user->save();

        // Agregar rol de usuario tipo empleado si el usuario logueado es un administrador
        if ($currentUser->hasRole('admin')):
            if (isset($request->role_id)):
                $roles          = Role::whereIn('id', array($request->role_id))->get();
                $update_roles   = $user->roles()->sync($roles);
            endif;

            // Mandar email de bienvenida para el usuario registrado
            if (!$user_id):
                $recipients = array($user->email);

                $mail = Mail::to($recipients);
                $mail->queue(new WelcomeEmail($user));
            endif;
        endif;

        return response(['error' => false, 'message' => $msg, 'data' => $user], 200);
    }

    /**
    * Remove the specified resource from storage.
    * Eliminar el usuario con el Id proporcionado
    *
    * @param  \App\Models\User  $user
    * @return \Illuminate\Http\Response
    */
    public function delete(Request $request)
    {
        $user = User::findOrFail($request->id);

        $user->updated_by = $request->user()->id;
        $user->save();
        $user->delete();
            
        return response([
            'error' => false,
            'data'  => $user,
            'message' => "Se ha eliminado el usuario <strong>".$user->name."</strong> correctamente."
        ]);
    }
}
