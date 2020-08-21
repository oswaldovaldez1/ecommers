<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProductGalleryModel;
use App\Models\ProductModel;
use App\Models\ProvedorModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //dd(ProductModel::paginate(30));
        return ProductModel::paginate(30);
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
    public function removeImg($id, $idpr)
    {
        $product = ProductModel::find($idpr);
        $productGallery = ProductGalleryModel::find($id);
        $image_path = 'assets/storage/product/' . $product->sku . '/' . $productGallery->image;
//\File::deleteDirectory('assets/storage/product/' . $product->sku . '/');

        if (\File::exists($image_path)) {
            //\File::delete($image_path);
            unlink($image_path);
        }
        $productGallery->delete();
        return response()->json('archivo eliminado');

    }
    public function getImgs($id)
    {
        $product = ProductModel::find($id);
        $productGallery = ProductGalleryModel::where('id_product', '=', $id);
        $urls = array();
        if ($productGallery->count() > 0) {
            foreach ($productGallery->get() as $img) {
                $url = '/assets/storage/product/' . $product->sku . '/' . $img->image;
                array_push($urls, ["id" => $img->id, "url" => $url]);
            }
            return response()->json($urls);
        }
        //return ($productGallery->get());
        //   foreach
        //$url = Storage::url('file.jpg');

    }
    public function upImg(Request $request)
    {
        //return $request->file("files");
        $imagenes = $request->allFiles();
        $product = ProductModel::find($request->header('id'));
        foreach ($imagenes['name'] as $img) {
            $nombre = Str::random(2) . time() . $img->getClientOriginalName();
            $img->move('assets/storage/product/' . $product->sku, $nombre);

            $productGallery = new ProductGalleryModel();
            $productGallery->id_product = $product->id;
            $productGallery->image = $nombre;
            $productGallery->save();

        }
        return response()->json(['message' => 'archivo subido con exito'], 200, [], JSON_UNESCAPED_SLASHES);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //return $request;
        $product = new ProductModel();

        $product->title = $request->title;
        $product->sku = $request->sku;
        $product->description = $request->description;
        $product->category = $request->category;
        $product->price = 0;
        $product->previus_price = $request->previus_price;
        $product->status = $request->status;
        $product->url = $request->url;
        $product->stock = 0;
        $product->size = 0;
        $product->trademark = $request->trademark;
        $product->id_provedor = $request->id_provedor;
        //return $product;

        $guardado = $product->save();
        if ($guardado) {

            // $productGallery = new ProductGalleryModel();
            // $productGallery->id_product = $product->id;
            // $productGallery->image = '';
            // $productGallery->save();
            return response()->json(['message' => 'Producto Agregado', 'product' => $product], 201);
        } else {
            return response()->json(['message' => 'No se pudo agregar el producto,inténtelo más tarde'], 405);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postProduct(Request $request)
    {
        $product = null;
        $existe = ProductModel::where('sku', '=', $request->sku)->exists();
        if ($existe) {
            $product = ProductModel::find(ProductModel::where('sku', '=', $request->sku)->get()[0]->id
            );
        } else {
            $product = new ProductModel();
        }

        $idprovedor = ProvedorModel::where('provedor', $request->id_provedor)->get()[0]->id;
        $product->title = $request->title;
        if (!$existe) {
            $product->sku = $request->sku;
        }
        $product->description = $request->description;
        $product->category = $request->category;
        $product->price = $request->price;
        $product->previus_price = $request->previus_price;
        $product->status = $request->status;
        $product->url = $request->url;
        $product->stock = 0;
        $product->size = 0;
        $product->trademark = $request->trademark;
        $product->id_provedor = $idprovedor;

        $guardado = null;
        if (!$existe) {
            $guardado = $product->save();
        } else {
            $guardado = $product->update();
        }
        if ($guardado) {
            return response()->json(['message' => 'Producto Agregado'], 201);
        } else {
            return response()->json(['message' => 'No se pudo agregar el producto,inténtelo más tarde'], 405);
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
        return ProductModel::find($id);
        // $product = ProductModel::where('sku', $id)->first();
        // $gallery = ProductGalleryModel::where('id_product', $product->id)->first();

        // //dd($product->sku);
        // return response()->json([
        //     "id" => $product->sku,
        //     "Nombre" => "",
        //     "Marca" => $product->trademark,
        //     "Descripcion" => $product->description,
        //     "Descripcion_completa" => "",
        //     "Imagen" => "",
        //     "Propietario" => "",
        //     "Imgs" => [],
        //     "Categorias" => [],
        // ]);
        /*
    return response()->json([
    "id" => $paso->Codigo,
    "Nombre" => $paso->Nombre,
    "Marca" => $paso->Marca,
    "Descripcion" => $paso->Descripcion_corta,
    "Descripcion_completa" => $paso->Descripcion,
    "Imagen" => $paso->Imagen_principal,
    "Propietario" => $paso->Empresa_propietaria,
    "Imgs" => $paso->Imagenes_secundarias,
    "Categorias" => $paso->Categorias,
    ]);
     */
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
        $product = ProductModel::find($id);
        $product->title = $request->title;
        $product->sku = $request->sku;
        $product->description = $request->description;
        $product->category = $request->category;
        $product->price = $request->price;
        $product->previus_price = $request->previus_price;
        $product->stock = $request->stock;
        $product->size = $request->size;
        $product->url = $request->url;

        $product->status = $request->status;
        $product->trademark = $request->trademark;

        // $actulizado = $product->update();
        // $productGallery = new ProductGalleryModel();
        // $productGallery->id_product = $product->id;
        // $productGallery->image = '';
        // $actualizadoImg = $productGallery->save();

        //if ($actulizado || $actualizadoImg) {
        if ($product->update()) {
            return response()->json(['message' => 'Producto Actualizada'], 201);
        } else {
            return response()->json(['message' => 'Producto no se pudo actualizar, inténtelo más tarde'], 405);
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
        $product = ProductModel::find($id);
        \File::deleteDirectory('assets/storage/product/' . $product->sku . '/');

        if ($product->delete()) {
            return response()->json(['message' => 'Producto Eliminado'], 201);
        } else {
            return response()->json(['message' => 'Producto no se pudo eliminar, inténtelo más tarde'], 405);
        }
    }
    public function search($busqueda)
    {

        $datos = ProductModel::where('description', 'like', '%' . $busqueda . '%');
        return response()->json($datos->get()->toArray());
    }
    public function busqueda($search)
    {
        $respuesta = ProductModel::where('description', 'like', '%' . $search . '%')
            ->orWhere('sku', 'like', '%' . $search . '%')
            ->orWhere('title', 'like', '%' . $search . '%')
            ->get()->toArray();

        return response()->json($respuesta);

    }
}
