<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ConfiguracionModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ConfiguracionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return ConfiguracionModel::all();
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
        $imagenes = $request->allFiles();
        foreach ($imagenes['name'] as $img) {
            $nombre = Str::random(2) . time() . $img->getClientOriginalName();
            $img->move('assets/storage/banner/', $nombre);
        }
        return response()->json(['message' => 'archivo subido con exito', 'name' => $nombre], 200, [], JSON_UNESCAPED_SLASHES);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        $configuracion = ConfiguracionModel::find(1);
        $configuracion->paypal_sandbox = $request->paypal_sandbox;
        $configuracion->paypal_production = $request->paypal_production;
        $configuracion->paypal_env = $request->paypal_env;
        $configuracion->beneficiario = $request->beneficiario;
        $configuracion->banco = $request->banco;
        $configuracion->clabe = $request->clabe;
        $configuracion->concepto = $request->concepto;
        $configuracion->referencia = $request->referencia;
        $configuracion->envio = $request->envio;
        $configuracion->mprod = $request->mprod;
        $configuracion->slider = $request->slider;
        if ($configuracion->update()) {
            return response()->json(['message' => 'Actualizado'], 201);
        } else {
            return response()->json(['message' => 'No se pudo actualizar, inténtelo más tarde'], 405);
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
        //
    }
}
