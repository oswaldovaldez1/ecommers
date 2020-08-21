<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProvedorModel;
use Illuminate\Http\Request;

class ProvedorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return ProvedorModel::paginate(20);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function allProvedor()
    {
        return ProvedorModel::all();
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
        $provedor = new ProvedorModel();
        $provedor->provedor = $request->provedor;
        $provedor->porcentaje = $request->porcentaje;
        $provedor->descuento = $request->descuento;

        if ($provedor->save()) {

            return response()->json(['message' => 'Provedor Creado'], 201);
        } else {
            return response()->json(['message' => 'Provedor no se pudo crear, inténtelo más tarde'], 405);
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
        $provedor = ProvedorModel::find($id);
        if ($provedor === null) {
            return response()->json(['message' => 'Provedor no encontrado'], 404);
        } else {
            return $provedor;
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
        $provedor = ProvedorModel::find($id);
        $provedor->provedor = $request->provedor;
        $provedor->porcentaje = $request->porcentaje;
        $provedor->descuento = $request->descuento;

        if ($provedor->update()) {

            return response()->json(['message' => 'Provedor Actualizado'], 201);
        } else {
            return response()->json(['message' => 'Provedor no se pudo actualizar, inténtelo más tarde'], 405);
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
        $provedor = ProvedorModel::find($id);
        if ($provedor->delete()) {
            return response()->json(['message' => 'Provedor Eliminado'], 201);
        } else {
            return response()->json(['message' => 'Provedor no se pudo eliminar, inténtelo más tarde'], 405);
        }
    }
    public function busqueda($search)
    {
        $respuesta = ProvedorModel::where('provedor', 'like', '%' . $search . '%')
            ->get()->toArray();

        return response()->json($respuesta);

    }
}
