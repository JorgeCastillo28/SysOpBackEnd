<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Post;

class PostController extends Controller
{
    /* Función para obtener el listado de Posts */
    public function index(Request $request)
    {
        $currentUser = Auth::user();
        $user_id = isset($request->user_id) ? $request->user_id : false;

        $data = Post::select(['posts.id', 'posts.title', 'posts.introduction', 'posts.subtitle', 'posts.content', 'users.name AS user_name'])
            ->leftJoin('users', 'users.id', 'posts.created_by');

        if ($user_id):
            $data->where('posts.created_by', $user_id);
        endif;

    	return response(['error' => false, 'message' => "Lista de Posts", 'data' => $data->get()]);
    }

    /* Función para obtener la información del Post */
    public function data(Request $request)
    {
        $post = Post::findOrFail($request->id);
        return response(['error' => false, 'message' => "Datos del Post.", 'data' => $post], 200);
    }

    /* Función para guardar y actualizar la información del Post */
    public function save(Request $request)
    {
        $this->validate($request, [
            'post_id'       => 'nullable',
            'title'         => 'required|string',
            'introduction'  => 'required|string',
            'subtitle'      => 'required|string',
            'content'       => 'required|string'
        ]);

        $currentUser        = Auth::user();
        $post_id            = intval($request->post_id);

        if (!$post_id):
            $post = new Post();
            $post->created_by = $currentUser->id;
            $msg = "Se ha creado el Post <strong>".$post->title."</strong> correctamente.";
        else:
            $post = Post::findOrFail($request->post_id);
            $msg = "Se ha actualizado el Post <strong>".$post->title."</strong> correctamente.";
        endif;

        $post->title        = $request->title;
        $post->introduction = $request->introduction;
        $post->subtitle     = $request->subtitle;
        $post->content      = $request->content;
        $post->updated_by   = $currentUser->id;
        $post->save();

        return response([
            'error'     => false,
            'data'      => $post,
            'message'   => $msg
        ]);
    }

    /* Función solo para crear un Post */
    public function store(Request $request)
    {
    	$this->validate($request, [
            'title'         => 'required|string',
            'introduction'  => 'required|string',
            'subtitle'      => 'required|string',
            'content'       => 'required|string'
        ]);

        $currentUser        = Auth::user();

        $post               = new Post();
        $post->title        = $request->title;
        $post->introduction = $request->introduction;
        $post->subtitle     = $request->subtitle;
        $post->content      = $request->content;
        $post->created_by   = Auth::user()->id;
        $post->updated_by   = Auth::user()->id;
        $post->save();

        return response([
            'error'     => false,
            'data'      => $post,
            'message'   => "El usuario ".$post->creator->name." ha creado el Post <strong>".$post->title."</strong>"
        ]);
    }

    /* Función para obtener el Post */
    public function show(Request $request)
    {
    	$post = Post::findOrFail($request->post_id);

        return response([
            'error'     => false,
            'data'      => $post,
            'message'   => "Datos del Post."
        ]);
    }

    /* Función solo para actualizar el Post */
    public function update(Request $request)
    {
    	$this->validate($request, [
            'post_id'       => 'required|integer|exists:posts,id',
            'title'         => 'required|string',
            'introduction'  => 'required|string',
            'subtitle'      => 'required|string',
            'content'       => 'required|string'
        ]);

        $currentUser        = Auth::user();

        $post               = Post::findOrFail($request->post_id);
        $post->title        = $request->title;
        $post->introduction = $request->introduction;
        $post->subtitle     = $request->subtitle;
        $post->content      = $request->content;
        $post->updated_by   = Auth::user()->id;
        $post->save();

        return response([
            'error'     => false,
            'data'      => $post,
            'message'   => "El usuario ".$post->creator()->name." ha actualizado el Post <strong>".$post->title."</strong>"
        ]);
    }

    /**
    * Remove the specified resource from storage.
    * Eliminar el post con el Id proporcionado
    *
    * @param  \App\Models\Post  $post
    * @return \Illuminate\Http\Response
    */
    public function destroy(Request $request)
    {
    	$post = Post::findOrFail($request->id);
        $post->save();
        $post->delete();

        return response([
            'error'     => false,
            'data'      => $post,
            'message'   => "Se ha eliminado el post ".$post->title." correctamente."
        ]);
    }
}
