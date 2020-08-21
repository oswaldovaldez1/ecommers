<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CategoryModel;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return CategoryModel::orderBy('mainid', 'ASC')
            ->orderBy('id', 'ASC')->paginate(20);
    }

    public function getMenuCategories()
    {
        $categorias = CategoryModel::whereColumn('id', 'mainid')->get();

        //dd($categorias);
        $arrayCategorias = [];

        foreach ($categorias as $categoria) {

            $arreglo = [
                'id' => $categoria->id,
                'mainid' => $categoria->mainid,
                'titulo' => $categoria->name,
                'categoria' => $categoria->slug,
                'status' => $categoria->status,
                'subnivel' => $this->getSubCategorie($categoria->id, 2),
            ];
            array_push($arrayCategorias, $arreglo);
        }
        return response()->json($arrayCategorias);
    }
    private function getSubCategorie($id, $nivel)
    {
        $arraySubCategorias = [];
        if ($nivel > 0) {
            $subCategorias = CategoryModel::where('mainid', '=', $id)
                ->where('id', '<>', $id)
                ->get();
            foreach ($subCategorias as $subCategoria) {

                $arreglo = [
                    'id' => $subCategoria->id,
                    'mainid' => $subCategoria->mainid,
                    'titulo' => $subCategoria->name,
                    'categoria' => $subCategoria->slug,
                    'status' => $subCategoria->status,
                    'subnivel' => $this->getSubCategorie($subCategoria->id, $nivel - 1),
                ];
                array_push($arraySubCategorias, $arreglo);
            }
        }
        return $arraySubCategorias;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function allCategory()
    {
        return CategoryModel::all();
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
        $categoria = new CategoryModel();
        $categoria->name = $request->name;
        $categoria->mainid = $request->mainid;
        $categoria->slug = $request->slug;
        $categoria->role = '';
        $categoria->status = $request->status;

        if ($categoria->save()) {
            if ($categoria->mainid === '0') {
                $categoria->mainid = $categoria->id;
                $categoria->update();
            }
            return response()->json(['message' => 'Categoria Creada'], 201);
        } else {
            return response()->json(['message' => 'Categoria no se pudo crear, inténtelo más tarde'], 405);
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

        $categoria = CategoryModel::find($id);
        if ($categoria === null) {
            return response()->json(['message' => 'categoria no encontrada'], 404);
        } else {
            return $categoria;
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
        $categoria = CategoryModel::find($id);
        $categoria->name = $request->name;
        $categoria->mainid = $request->mainid;
        $categoria->slug = $request->slug;
        $categoria->role = '';
        $categoria->status = $request->status;

        if ($categoria->update()) {
            return response()->json(['message' => 'Categoria Actualizada'], 201);
        } else {
            return response()->json(['message' => 'Categoria no se pudo actualizar, inténtelo más tarde'], 405);
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
        $categoria = CategoryModel::find($id);
        if ($categoria->delete()) {
            return response()->json(['message' => 'Categoria Eliminada'], 201);
        } else {
            return response()->json(['message' => 'Categoria no se pudo eliminar, inténtelo más tarde'], 405);
        }
    }
    public function busqueda($search)
    {
        $respuesta = CategoryModel::where('name', 'like', '%' . $search . '%')
            ->get()->toArray();

        return response()->json($respuesta);
    }
}
