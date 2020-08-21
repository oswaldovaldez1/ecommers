<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MarcasModel;
use Illuminate\Http\Request;

class MarcasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return MarcasModel::paginate(20);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function allMarcas()
    {
        return MarcasModel::all();
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
        $marca = new MarcasModel();
        $marca->nombre = $request->nombre;
        $marca->porcentaje = $request->porcentaje;
        $marca->provedores = $request->provedores;
        $marca->tag = $request->tag;

        if ($marca->save()) {
            return response()->json(['message' => 'Marca Creada'], 201);
        } else {
            return response()->json(['message' => 'La marca no se pudo crear, inténtelo más tarde'], 405);
        }
    }

    public function busqueda($search)
    {
        $respuesta = marcasModel::where('nombre', 'like', '%' . $search . '%')->get()->toArray();

        return response()->json($respuesta);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $marca = MarcasModel::find($id);
        if ($marca === null) {
            return response()->json(['message' => 'marca no encontrada'], 404);
        } else {
            return $marca;
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
        $marca = MarcasModel::find($id);
        $marca->nombre = $request->nombre;
        $marca->porcentaje = $request->porcentaje;
        $marca->provedores = $request->provedores;

        $marca->tag = $request->tag;

        if ($marca->update()) {
            return response()->json(['message' => 'Marca Actualizada'], 201);
        } else {
            return response()->json(['message' => 'La marca no se pudo actualizar, inténtelo más tarde'], 405);
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
        $marca = MarcasModel::find($id);
        if ($marca->delete()) {
            return response()->json(['message' => 'Marca Eliminada'], 201);
        } else {
            return response()->json(['message' => 'Marca no se pudo eliminar, inténtelo más tarde'], 405);
        }
    }
}
