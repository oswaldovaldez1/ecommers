<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UserModel;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return UserModel::paginate(20);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'firstname' => 'required|string',
            'email' => 'required|string|email|unique:user',
            'password' => 'required|string',
        ]);

        $user = new UserModel();
        $user->firstname = $request->firstname;
        $user->secondname = $request->secondname;
        $user->lastname = $request->lastname;
        $user->phone = $request->phone;
        $user->api_token = '';
        $user->remember_token = '';
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->id_rol = $request->rol + 1;
        $user->tipo = $request->rol;

        if ($user->save()) {
            return response()->json(['message' => 'Usuario Agregado'], 201);
        } else {
            return response()->json(['message' => 'El usuario no se pudo agregar,inténtelo más tarde'], 405);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = UserModel::find($id);
        if ($user === null) {
            return response()->json(['message' => 'usuario no encontrada'], 404);
        } else {
            return $user;
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'firstname' => 'required|string',
        ]);

        $user = UserModel::find($id);
        $user->firstname = $request->firstname;
        $user->secondname = $request->secondname;
        $user->lastname = $request->lastname;
        $user->phone = $request->phone;
        if (strlen($request->password) > 0) {
            $user->password = bcrypt($request->password);
        }
        $user->id_rol = $request->rol + 1;
        $user->tipo = $request->rol;

        if ($user->update()) {
            return response()->json(['message' => 'Usuario Actualizado'], 201);
        } else {
            return response()->json(['message' => 'El usuario no se pudo actualizar,inténtelo más tarde'], 4005);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = UserModel::find($id);
        if ($user->delete()) {
            return response()->json(['message' => 'Usuario Eliminado'], 201);
        } else {
            return response()->json(['message' => 'El usuario no se pudo eliminar,inténtelo más tarde'], 4005);
        }
    }
    public function busqueda($search)
    {
        $respuesta = UserModel::where('firstname', 'like', '%' . $search . '%')
            ->orWhere('secondname', 'like', '%' . $search . '%')
            ->orWhere('lastname', 'like', '%' . $search . '%')
            ->orWhere('email', 'like', '%' . $search . '%')
            ->get()->toArray();

        return response()->json($respuesta);

    }
}
