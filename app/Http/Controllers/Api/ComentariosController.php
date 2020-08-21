<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ComentariosModel;
use Illuminate\Http\Request;

class ComentariosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return ComentariosModel::paginate(30);
    }
    public function busqueda($search)
    {
        return ComentariosModel::where('sku', '=', $search)->get();
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $comentario = new ComentariosModel();
        $comentario->sku = $request->sku;
        $comentario->correo = $request->correo;
        $comentario->comentario = $request->comentario;
        $comentario->cal = $request->cal;
        if ($comentario->save()) {
            return response()->json(['message' => 'Comentario Agregado'], 201);
        } else {
            return response()->json(['message' => 'Comentario no se pudo agregar, inténtelo más tarde'], 405);
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
        return ComentariosModel::find($id);
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $comentario = ComentariosModel::find($id);

        if ($comentario->delete()) {
            return response()->json(['message' => 'Comentario Eliminado'], 201);
        } else {
            return response()->json(['message' => 'Comentario no se pudo eliminar, inténtelo más tarde'], 405);
        }

    }
}
