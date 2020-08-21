<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CategoryModel;
use App\Models\Gonher;
use App\Models\ProductGalleryModel;
use App\Models\ProductModel;
use App\Models\ProductOrderModel;
use App\Models\ProvedorModel;
use App\Models\ConfiguracionModel;
use App\Models\UserModel;
use App\Models\Order;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Spatie\Sitemap\SitemapGenerator;

class webController extends Controller
{
    private function getAllUser()
    {

        return UserModel::where('tipo', '=', 2)->count();
    }
    private function getAllCompras()
    {
        return Order::orderBy('status')->select(DB::raw('status,count(status) as total'))->groupBy('status')->get();
    }
    private function getOrdenByMonth()
    {
        return Order::orderBy('Mes', 'desc')->select(DB::raw('count(*) as contador, sum(pay_amount) as total, MONTHNAME(created_at) AS Mes'))->where('status', '<>', 'pendiente')->where('status', '<>', 'cancelado')->groupBy('Mes')->skip(0)->take(12)->get();
    }
    public function dashboard()
    {
        DB::statement("SET lc_time_names = 'es_ES'");
        return response()->json([
            'totalUsuarios' => $this->getAllUser(),
            'totalComprasMes' => $this->getOrdenByMonth(),
            'totalCompras' => $this->getAllCompras()
        ]);
    }
    public function sitemap()
    {
        SitemapGenerator::create('https://lanesa1957.com')

            ->writeToFile(public_path('sitemap.xml'));
    }
    public function getslider()
    {
        $conf = ConfiguracionModel::find(1);
        $conf = json_decode($conf->mprod, true);
        $local = array();
        $api = array();

        foreach ($conf as $config) {
            if ($config['ubicacion'] === 'api') {
                array_push($api, $config['id']);
            } else {
                array_push($local, $config['id']);
            }
        }

        $instrumentos = Gonher::whereIN("sku", $api)->get();



        $productos = [];
        foreach ($instrumentos as $instrumento) {
            $totalvendido = ProductOrderModel::where('sku', 'like', $instrumento->sku)->count();
            array_push($productos, [
                "id" => $instrumento->sku,
                "Nombre" => $instrumento->titulo,
                "Marca" => $instrumento->marca,
                "Descripcion" => $instrumento->descripcion_corta,
                "Descripcion_completa" => $instrumento->descripcion_completa,
                "Imagen" => $instrumento->imagen,
                "Propietario" => $instrumento->propietario,
                "Imgs" => json_decode($instrumento->imgs, true),
                "Categorias" => json_decode($instrumento->categorias, true),
                "Precio" => $this->getCosto($instrumento->propietario, $instrumento->precio_dis), //$instrumento->precio,
                "Precio_distribuidor" => $instrumento->precio_dis,
                "Precio_con_descuentos" => $instrumento->precio_desc,
                "Disponible" => $instrumento->disponible,
                "vendido" => $totalvendido,
                "ubicacion" => 'api',
            ]);
        }
        $instrumentos = ProductModel::whereIN("sku", $local)->get();




        foreach ($instrumentos as $instrumento) {
            $totalvendido = ProductOrderModel::where('sku', 'like', $instrumento->sku)->count();
            array_push($productos, [
                "id" => $instrumento->sku,
                "Nombre" => $instrumento->titulo,
                "Marca" => $instrumento->marca,
                "Descripcion" => $instrumento->descripcion_corta,
                "Descripcion_completa" => $instrumento->descripcion_completa,
                "Imagen" => $instrumento->imagen,
                "Propietario" => $instrumento->propietario,
                "Imgs" => json_decode($instrumento->imgs, true),
                "Categorias" => json_decode($instrumento->categorias, true),
                "Precio" => $this->getCosto($instrumento->propietario, $instrumento->precio_dis), //$instrumento->precio,
                "Precio_distribuidor" => $instrumento->precio_dis,
                "Precio_con_descuentos" => $instrumento->precio_desc,
                "Disponible" => $instrumento->disponible,
                "vendido" => $totalvendido,
                "ubicacion" => 'api',
            ]);
        }

        return response()->json(["datos" => $productos, "total" => count($productos)], 200);
    }
    public function getvalores(Request $request)
    {
        return response()->json(['datos' => 'ok']);
    }
    public function getData()
    {
        $skus = Gonher::select('sku')->where('propietario', "not like", '')->get()->toArray();
        $sk = [];
        foreach ($skus as $sku) {
            //dd($sku['sku']);
            array_push($sk, $sku['sku']);
        }
        return response()->json(['datos' => $sk, 'ubicacion' => 'api']);
    }
    public function getBusqueda($busqueda)
    {

        if (!Gonher::where('sku', '=', $busqueda)
            ->where('updated_at', '<', date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - 1, date("Y"))))->exists()) {
            // dd(date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - 1, date("Y"))));
            return response()->json(['agregado' => 'Si', 'consulta' => $busqueda]);
        }

        /*if (Gonher::where('sku', '=', $busqueda)->exists()) {
        return response()->json(['agregado' => 'Si anterior', 'consulta' => $busqueda]);
        }*/

        $datos = $this->price($busqueda);

        if ($datos === null) {
            return response()->json(['agregado' => 'No', 'consulta' => $busqueda]);
        }

        if (!property_exists($datos, 'Disponible')) {
            return response()->json(['agregado' => 'No', 'consulta' => $busqueda]);
        }
        $paso = $this->getProducto($busqueda);
        if ($paso == null) {
            return response()->json(['agregado' => 'No', 'consulta' => $busqueda]);
        }
        //dd($paso);
        if (Gonher::where('sku', '=', $busqueda)->exists()) {
            $gonher = Gonher::find(Gonher::where('sku', '=', $busqueda)->first()->id);

            $gonher->titulo = $paso->Nombre;
            $gonher->marca = $paso->Marca;
            $gonher->descripcion_corta = $paso->Descripcion_corta;
            $gonher->descripcion_completa = $paso->Descripcion;
            $gonher->imagen = $paso->Imagen_principal;
            $gonher->imgs = json_encode($paso->Imagenes_secundarias, JSON_UNESCAPED_SLASHES);
            $gonher->categorias = json_encode($paso->Categorias, JSON_UNESCAPED_SLASHES);
            $gonher->precio = $datos->Precio;
            $gonher->precio_dis = $datos->Precio_distribuidor;
            $gonher->precio_desc = $datos->Precio_con_descuentos;
            $gonher->disponible = $datos->Disponible === 'No' ? 'N' : 'S';
            $gonher->propietario = property_exists($paso, 'Empresa_propietaria') ? $paso->Empresa_propietaria : '';
            $gonher->descripcion_corta = $paso->Descripcion_corta;
            $gonher->descripcion_completa = $paso->Descripcion;
            $gonher->updated_at = Carbon::now();
            $gonher->update();
        } else {
            $gonher = new Gonher();
            $gonher->sku = $busqueda;
            $gonher->titulo = $paso->Nombre;
            $gonher->marca = $paso->Marca;
            $gonher->descripcion_corta = $paso->Descripcion_corta;
            $gonher->descripcion_completa = property_exists($paso, 'Descripcion') ? $paso->Descripcion : "";
            $gonher->imagen = property_exists($paso, 'Imagen_principal') ? $paso->Imagen_principal : "";
            $gonher->imgs = json_encode($paso->Imagenes_secundarias, JSON_UNESCAPED_SLASHES);
            $gonher->categorias = json_encode($paso->Categorias, JSON_UNESCAPED_SLASHES);
            $gonher->precio = $datos->Precio;
            $gonher->precio_dis = $datos->Precio_distribuidor;
            $gonher->precio_desc = $datos->Precio_con_descuentos;
            $gonher->disponible = $datos->Disponible === 'No' ? 'N' : 'S';
            $gonher->save();
        }
        return response()->json(['agregado' => 'Si', 'consulta' => $busqueda]);
    }

    public function getarticulos($busqueda)
    {
        $busqueda = str_replace('-', '%', $busqueda);

        $url = 'http://www.grupogonher.mx/apis/ProdListadoArticulos.aspx?&Tkn=6E94C0CEEFBE41F882F943BD92783B0B&Auth=8C64F710366F47B2AA981B19C8051234&filtro=' . $busqueda;
        $cliente = new Client();
        $response = $cliente->request('GET', $url);
        $skus = (string) $response->getBody();

        if (strrpos($skus, "FAIL") > 0) {
            return response()->json(['datos' => [], 'ubicacion' => 'api', 'consulta' => $busqueda]);
        }
        $articulos = json_decode($skus);

        //return response()->json(['datos' => count($articulos->Articulos), 'consulta' => $busqueda]);
        return response()->json(['datos' => $articulos->Articulos, 'ubicacion' => 'api', 'consulta' => $busqueda]);
    }
    public function getarticulo1($busqueda)
    {

        $datos = $this->price($busqueda);

        if ($datos === null) {
            //return response()->json(['datos' => 'no']);
        }
        if (!property_exists($datos, 'Disponible')) {
            return response()->json(['datos' => 'no']);
        }
        if ($datos->Disponible === 'No') {
            //return response()->json(['datos' => 'no']);
        }
        $paso = $this->getProducto($busqueda);

        if ($paso == null) {
            return response()->json(['datos' => 'no']);
        }

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
            "Precio" => $this->getCosto($paso->Empresa_propietaria, $datos->Precio_distribuidor), //$datos->Precio,
            "Precio_distribuidor" => $datos->Precio_distribuidor,
            "Precio_con_descuentos" => $datos->Precio_con_descuentos,
            "Disponible" => $datos->Disponible,
        ], 200);
    }

    private function getProducto($busqueda)
    {
        $url = 'http://www.grupogonher.mx/apis/ProdDetalle.aspx?&Tkn=6E94C0CEEFBE41F882F943BD92783B0B&Auth=8C64F710366F47B2AA981B19C8051234&articulo=' . $busqueda;
        $cliente = new Client();
        $response = $cliente->request('GET', $url);
        $algo = (string) $response->getBody();
        $algo = str_replace("\\\"", "'", $algo);
        $algo = str_replace("	", "", $algo);
        $algo = preg_replace("/\r|\n/", "", $algo);

        $paso = json_decode($algo);
        return $paso;
    }
    private function price($busqueda)
    {
        $cliente = new Client();
        $url = "http://www.grupogonher.mx/apis/ProdPreciosExisten.aspx?&Tkn=6E94C0CEEFBE41F882F943BD92783B0B&Auth=8C64F710366F47B2AA981B19C8051234&Articulo=" . $busqueda;
        $respuesta = $cliente->request('GET', $url);
        $respuesta = (string) $respuesta->getbody();
        return json_decode($respuesta);
    }
    public function getHead($busqueda)
    {

        //$url = 'http://201.107.4.57/Archivo/Articulo/' . $busqueda;
        $url = str_replace('-', '/', $busqueda);

        try {

            $cliente = new Client();
            $response = $cliente->get($url, ['http_errors' => false]);

            if ($response->getStatusCode() === 200) {

                $contents = file_get_contents($url);
                header('Content-type: image/jpeg');
                echo $contents;
            } else {
                header('Content-type: image/jpeg');
                echo Storage::get('public/default/noproduct.jpg');
                //$contents =     file_get_contents(url('/') . '/img/noproduct.jpg');

            }
        } catch (Exception $e) {
        }
    }

    /**nuevas funciones finales*/
    public function searchproduct($busqueda, $index)
    {

        $productos = [];
        $count = 0;
        $instrumentos = null;
        $busqueda = str_replace('-', '%', $busqueda);
        $instrumentos = Gonher::where('titulo', 'like', '%' . $busqueda . '%')
            ->orWhere('descripcion_corta', 'like', '%' . $busqueda . '%')
            ->orWhere('descripcion_completa', 'like', '%' . $busqueda . '%')
            ->where('disponible', '=', 'S')
            ->skip(($index - 1) * 15)->take(15)->get();
        if ($index === '1') {
            $count = Gonher::where('titulo', 'like', '%' . $busqueda . '%')
                ->orWhere('descripcion_corta', 'like', '%' . $busqueda . '%')
                ->orWhere('descripcion_completa', 'like', '%' . $busqueda . '%')
                ->where('disponible', '=', 'S')
                ->count();
        }

        $products = ProductModel::select('product.*', 'provedor.provedor', 'provedor.porcentaje')
            ->join('provedor', 'product.id_provedor', '=', 'provedor.id')
            ->where('title', 'like', '%' . $busqueda . '%')
            ->orWhere('description', 'like', '%' . $busqueda . '%')
            ->orWhere('trademark', 'like', '%' . $busqueda . '%')
            ->orWhere('sku', 'like', '%' . $busqueda . '%')
            ->where('status', 'like', '1')
            ->skip(($index - 1) * 15)->take(15)->get();
        $counts = ProductModel::select('product.*', 'provedor.provedor', 'provedor.porcentaje')
            ->join('provedor', 'product.id_provedor', '=', 'provedor.id')
            ->where('title', 'like', '%' . $busqueda . '%')
            ->orWhere('description', 'like', '%' . $busqueda . '%')
            ->orWhere('trademark', 'like', '%' . $busqueda . '%')
            ->orWhere('sku', 'like', '%' . $busqueda . '%')
            ->where('status', 'like', '1')
            ->count();

        // if ($instrumentos === null) {
        //     return response()->json(["datos" => [], "total" => $count], 200);
        // }

        foreach ($instrumentos as $instrumento) {
            $totalvendido = ProductOrderModel::where('sku', 'like', $instrumento->sku)->count();

            array_push($productos, [
                "id" => $instrumento->sku,
                "Nombre" => $instrumento->titulo,
                "Marca" => $instrumento->marca,
                "Descripcion" => $instrumento->descripcion_corta,
                "Descripcion_completa" => $instrumento->descripcion_completa,
                "Imagen" => $instrumento->imagen,
                "Propietario" => $instrumento->propietario,
                "Imgs" => json_decode($instrumento->imgs, true),
                "Categorias" => json_decode($instrumento->categorias, true),
                "Precio" => $this->getCosto($instrumento->propietario, $instrumento->precio_dis), //$instrumento->precio,
                "Precio_distribuidor" => $instrumento->precio_dis,
                "Precio_con_descuentos" => $instrumento->precio_desc,
                "Disponible" => $instrumento->disponible,
                "vendido" => $totalvendido,
                "url" => '',
                "ubicacion" => 'api',
            ]);
        }

        foreach ($products as $instrumento) {
            $categoria = CategoryModel::where("ID", '=', $instrumento->category)->get();
            $prImage = ProductGalleryModel::where('id_product', '=', $instrumento->id)->get();
            $imgpr = "";
            $imgspr = array();

            if ($prImage->count() > 0) {
                $x = -1;
                foreach ($prImage as $img) {
                    $x++;
                    if ($x === 0) {
                        $imgpr = '/assets/storage/product/' . $instrumento->sku . '/' . $img->image;
                        continue;
                    }

                    array_push($imgspr, '/assets/storage/product/' . $instrumento->sku . '/' . $img->image);
                    //$imgspr .= '"/assets/storage/product/' . $producto->sku . '/' . $img->image . '",';
                }
                //unset($imgspr[0]);
                //$imgspr .= "]";
            } else {
                $imgpr = '/assets/noproduct.jpg';
            }

            //print_r($imgspr);
            //print_r(json_decode($imgspr, true));exit;
            $totalvendido = ProductOrderModel::where('sku', 'like', $instrumento->sku)->count();

            array_push($productos, [
                "id" => $instrumento->sku,
                "Nombre" => $instrumento->title . ' MOD. ' . $instrumento->sku,
                "Marca" => $instrumento->trademark,
                "Descripcion" => $instrumento->description,
                "Descripcion_completa" => $instrumento->description,
                "Imagen" => $imgpr,
                "Propietario" => $instrumento->provedor,
                "Imgs" => $imgspr,
                "Categorias" => $categoria->count() === 0 ? [] : [
                    "ID" => $categoria[0]->name, "ID" => $categoria[0]->id,
                    "PADREID" => $categoria[0]->mainid
                ],
                "Precio" => $this->getCosto($instrumento->provedor, $instrumento->previus_price), //$precio,
                "Precio_distribuidor" => (float) $instrumento->previus_price,
                "Precio_con_descuentos" => (float) $instrumento->previus_price,
                "Disponible" => 'S',
                "ubicacion" => 'local',
                "vendido" => $totalvendido,
                "url" => $instrumento->url,
            ]);
        }

        return response()->json(["datos" => $productos, "total" => $count + $counts], 200);
    }
    public function getByCategory($category, $index)
    {
        $productos = [];
        $count = 0;
        $instrumentos = null;

        switch ($category) {
            case "Guitarra-Acústicas": {
                    //1032
                    $instrumentos = Gonher::where('categorias', 'like', '%1032%')->where('Disponible', '=', 'S')->skip(($index - 1) * 15)->take(15)->get();

                    if ($index === '1') {
                        $count = Gonher::where('categorias', 'like', '%1032%')->where('Disponible', '=', 'S')->count();
                    }
                    break;
                }
            case "Guitarra-Eléctricas": {
                    //788  785
                    $instrumentos = Gonher::where('categorias', 'like', '%788%')->where('categorias', 'like', '%785%')->where('Disponible', '=', 'S')->skip(($index - 1) * 15)->take(15)->get();

                    if ($index === '1') {
                        $count = Gonher::where('categorias', 'like', '%788%')->where('categorias', 'like', '%785%')->where('Disponible', '=', 'S')->count();
                    }
                    break;
                }
            case "Guitarra-Electroacústica": {
                    //806
                    $instrumentos = Gonher::where('categorias', 'like', '%806%')->where('Disponible', '=', 'S')->skip(($index - 1) * 15)->take(15)->get();

                    if ($index === '1') {
                        $count = Gonher::where('categorias', 'like', '%806%')->where('Disponible', '=', 'S')->count();
                    }
                    break;
                }
            case "Guitarra-Amplificadores": {
                    //911
                    $instrumentos = Gonher::where('categorias', 'like', '%911%')->where('Disponible', '=', 'S')->skip(($index - 1) * 15)->take(15)->get();

                    if ($index === '1') {
                        $count = Gonher::where('categorias', 'like', '%911%')->where('Disponible', '=', 'S')->count();
                    }
                    break;
                }
            case "Guitarra-Efecto": {
                    //770
                    $instrumentos = Gonher::where('categorias', 'like', '%770%')->where('Disponible', '=', 'S')->skip(($index - 1) * 15)->take(15)->get();

                    if ($index === '1') {
                        $count = Gonher::where('categorias', 'like', '%770%')->where('Disponible', '=', 'S')->count();
                    }
                    break;
                }
            case "Guitarra-Accesorios": {
                    //755 883
                    $instrumentos = Gonher::where('categorias', 'like', '%755%')->where('categorias', 'like', '%883%')->where('Disponible', '=', 'S')->skip(($index - 1) * 15)->take(15)->get();

                    if ($index === '1') {
                        $count = Gonher::where('categorias', 'like', '%755%')->where('categorias', 'like', '%883%')->where('Disponible', '=', 'S')->count();
                    }
                    break;
                }
            case "Bajo-Eléctrico": {
                    //904 905
                    $instrumentos = Gonher::where('categorias', 'like', '%904%')->where('categorias', 'like', '%905%')->where('Disponible', '=', 'S')->skip(($index - 1) * 15)->take(15)->get();

                    if ($index === '1') {
                        $count = Gonher::where('categorias', 'like', '%904%')->where('categorias', 'like', '%905%')->where('Disponible', '=', 'S')->count();
                    }
                    break;
                }
            case "Bajo-Electroacústico": {
                    //904 1069  E/ACUST
                    $instrumentos = Gonher::where('categorias', 'like', '%904%')->where('categorias', 'like', '%1069%')->where('titulo', 'like', '%E/ACUST%')->where('Disponible', '=', 'S')->skip(($index - 1) * 15)->take(15)->get();

                    if ($index === '1') {
                        $count = Gonher::where('categorias', 'like', '%904%')->where('categorias', 'like', '%1069%')->where('titulo', 'like', '%E/ACUST%')->where('Disponible', '=', 'S')->count();
                    }
                    break;
                }
            case "Bajo-Amplificadores": {
                    //851
                    $instrumentos = Gonher::where('categorias', 'like', '%851%')->where('Disponible', '=', 'S')->skip(($index - 1) * 15)->take(15)->get();

                    if ($index === '1') {
                        $count = Gonher::where('categorias', 'like', '%851%')->where('Disponible', '=', 'S')->count();
                    }
                    break;
                }
            case "Bajo-Efectos": {
                    //754
                    $instrumentos = Gonher::where('categorias', 'like', '%754%')->where('Disponible', '=', 'S')->skip(($index - 1) * 15)->take(15)->get();

                    if ($index === '1') {
                        $count = Gonher::where('categorias', 'like', '%754%')->where('Disponible', '=', 'S')->count();
                    }
                    break;
                }
            case "Bajo-Accesorios": {
                    // 755 767
                    $instrumentos = Gonher::where('categorias', 'like', '%755%')->where('categorias', 'like', '%767%')->where('Disponible', '=', 'S')->skip(($index - 1) * 15)->take(15)->get();

                    if ($index === '1') {
                        $count = Gonher::where('categorias', 'like', '%755%')->where('categorias', 'like', '%767%')->where('Disponible', '=', 'S')->count();
                    }
                    break;
                }
            case "Bateria-Acústicas": {
                    //829
                    $instrumentos = Gonher::where('categorias', 'like', '%829%')->where('Disponible', '=', 'S')->skip(($index - 1) * 15)->take(15)->get();

                    if ($index === '1') {
                        $count = Gonher::where('categorias', 'like', '%829%')->where('Disponible', '=', 'S')->count();
                    }
                    break;
                }
            case "Bateria-Eléctricas": {
                    //989
                    $instrumentos = Gonher::where('categorias', 'like', '%989%')->where('Disponible', '=', 'S')->skip(($index - 1) * 15)->take(15)->get();

                    if ($index === '1') {
                        $count = Gonher::where('categorias', 'like', '%989%')->where('Disponible', '=', 'S')->count();
                    }
                    break;
                }
            case "Marcha-y-Orquesta": {
                    //1063 o 1019
                    $instrumentos = Gonher::where('categorias', 'like', '%1063%')->orWhere('categorias', 'like', '%1019%')->where('Disponible', '=', 'S')->skip(($index - 1) * 15)->take(15)->get();

                    if ($index === '1') {
                        $count = Gonher::where('categorias', 'like', '%1063%')->orWhere('categorias', 'like', '%1019%')->where('Disponible', '=', 'S')->count();
                    }
                    break;
                }
            case "Tarolas": {
                    //
                    break;
                }
            case "Percusiones-mayores": {
                    // PERCUSIONES MAYORES 771
                    $instrumentos = Gonher::where('categorias', 'like', '%771%')->where('Disponible', '=', 'S')->skip(($index - 1) * 15)->take(15)->get();

                    if ($index === '1') {
                        $count = Gonher::where('categorias', 'like', '%771%')->where('Disponible', '=', 'S')->count();
                    }
                    break;
                }
            case "Percusiones-menores": {
                    //PERCUSIONES MENORES 764
                    $instrumentos = Gonher::where('categorias', 'like', '%764%')->where('Disponible', '=', 'S')->skip(($index - 1) * 15)->take(15)->get();

                    if ($index === '1') {
                        $count = Gonher::where('categorias', 'like', '%764%')->where('Disponible', '=', 'S')->count();
                    }
                    break;
                }
            case "Teclados": {
                    //TECLADOS 791
                    $instrumentos = Gonher::where('categorias', 'like', '%791%')->where('Disponible', '=', 'S')->skip(($index - 1) * 15)->take(15)->get();

                    if ($index === '1') {
                        $count = Gonher::where('categorias', 'like', '%791%')->where('Disponible', '=', 'S')->count();
                    }
                    break;
                }
            case "Pianos": {
                    //PIANOS 836
                    $instrumentos = Gonher::where('categorias', 'like', '%836%')->where('Disponible', '=', 'S')->skip(($index - 1) * 15)->take(15)->get();

                    if ($index === '1') {
                        $count = Gonher::where('categorias', 'like', '%836%')->where('Disponible', '=', 'S')->count();
                    }
                    break;
                }
            case "Teclado-Sintetizadores": {
                    //790
                    $instrumentos = Gonher::where('categorias', 'like', '%865%')->where('titulo', 'like', 'SINTETIZADOR%')->where('Disponible', '=', 'S')->skip(($index - 1) * 15)->take(15)->get();

                    if ($index === '1') {
                        $count = Gonher::where('categorias', 'like', '%865%')->where('titulo', 'like', 'CONTROLADOR%')->where('Disponible', '=', 'S')->count();
                    }
                    break;
                }
            case "Teclado-Controladores": {
                    //790
                    $instrumentos = Gonher::where('categorias', 'like', '%865%')->where('Disponible', '=', 'S')->skip(($index - 1) * 15)->take(15)->get();

                    if ($index === '1') {
                        $count = Gonher::where('categorias', 'like', '%865%')->where('Disponible', '=', 'S')->count();
                    }
                    break;
                }
            case "Teclado-Amplificadores": {
                    //790
                    break;
                }
            case "Teclado-Accesorios": {
                    //790
                    break;
                }
            case "Clarinete": {
                    //794 CLARINETE
                    $instrumentos = Gonher::where('categorias', 'like', '%794%')->where('titulo', 'like', 'CLARINETE%')->where('Disponible', '=', 'S')->skip(($index - 1) * 15)->take(15)->get();

                    if ($index === '1') {
                        $count = Gonher::where('categorias', 'like', '%794%')->where('titulo', 'like', 'CLARINETE%')->where('Disponible', '=', 'S')->count();
                    }
                    break;
                }
            case "Saxofón": {
                    //794 SAXOFON
                    $instrumentos = Gonher::where('categorias', 'like', '%794%')->where('titulo', 'like', 'SAXOFON%')->where('Disponible', '=', 'S')->skip(($index - 1) * 15)->take(15)->get();

                    if ($index === '1') {
                        $count = Gonher::where('categorias', 'like', '%794%')->where('titulo', 'like', 'SAXOFON%')->where('Disponible', '=', 'S')->count();
                    }
                    break;
                }
            case "Saxor": {
                    //
                    break;
                }
            case "Trombón": {
                    //TROMBON 803
                    $instrumentos = Gonher::where('categorias', 'like', '%803%')->where('titulo', 'like', 'TROMBON%')->where('Disponible', '=', 'S')->skip(($index - 1) * 15)->take(15)->get();

                    if ($index === '1') {
                        $count = Gonher::where('categorias', 'like', '%803%')->where('titulo', 'like', 'TROMBON%')->where('Disponible', '=', 'S')->count();
                    }
                    break;
                }
            case "Trompeta": {
                    //803 TROMPETA
                    $instrumentos = Gonher::where('categorias', 'like', '%803%')->where('titulo', 'like', 'TROMPETA%')->where('Disponible', '=', 'S')->skip(($index - 1) * 15)->take(15)->get();

                    if ($index === '1') {
                        $count = Gonher::where('categorias', 'like', '%803%')->where('titulo', 'like', 'TROMPETA%')->where('Disponible', '=', 'S')->count();
                    }
                    break;
                }
            case "Flautas": {
                    //FLAUTAs
                    $instrumentos = Gonher::where('titulo', 'like', 'FLAUTA%')->where('Disponible', '=', 'S')->skip(($index - 1) * 15)->take(15)->get();

                    if ($index === '1') {
                        $count = Gonher::where('titulo', 'like', 'FLAUTA%')->where('Disponible', '=', 'S')->count();
                    }
                    break;
                }
            case "Armónicas": {
                    //812
                    $instrumentos = Gonher::where('categorias', 'like', '%812%')->where('Disponible', '=', 'S')->skip(($index - 1) * 15)->take(15)->get();

                    if ($index === '1') {
                        $count = Gonher::where('categorias', 'like', '%812%')->where('Disponible', '=', 'S')->count();
                    }
                    break;
                }
            case "Tubas": {
                    //TUBA 803
                    $instrumentos = Gonher::where('categorias', 'like', '%803%')->where('titulo', 'like', 'TUBA%')->where('Disponible', '=', 'S')->skip(($index - 1) * 15)->take(15)->get();

                    if ($index === '1') {
                        $count = Gonher::where('categorias', 'like', '%803%')->where('titulo', 'like', 'TUBA%')->where('Disponible', '=', 'S')->count();
                    }
                    break;
                }
            case "Bafles": {
                    //749
                    $instrumentos = Gonher::where('categorias', 'like', '%749%')->where('Disponible', '=', 'S')->skip(($index - 1) * 15)->take(15)->get();

                    if ($index === '1') {
                        $count = Gonher::where('categorias', 'like', '%749%')->where('Disponible', '=', 'S')->count();
                    }
                    break;
                }
            case "Mezcladoras": {
                    //780
                    $instrumentos = Gonher::where('categorias', 'like', '%780%')->where('Disponible', '=', 'S')->skip(($index - 1) * 15)->take(15)->get();

                    if ($index === '1') {
                        $count = Gonher::where('categorias', 'like', '%780%')->where('Disponible', '=', 'S')->count();
                    }
                    break;
                }
            case "Amplificadores": {
                    //799
                    $instrumentos = Gonher::where('categorias', 'like', '%799%')->where('Disponible', '=', 'S')->skip(($index - 1) * 15)->take(15)->get();

                    if ($index === '1') {
                        $count = Gonher::where('categorias', 'like', '%799%')->where('Disponible', '=', 'S')->count();
                    }
                    break;
                }
            case "Microfonía": {
                    //824
                    $instrumentos = Gonher::where('categorias', 'like', '%824%')->where('Disponible', '=', 'S')->skip(($index - 1) * 15)->take(15)->get();

                    if ($index === '1') {
                        $count = Gonher::where('categorias', 'like', '%824%')->where('Disponible', '=', 'S')->count();
                    }
                    break;
                }
            case "Interfaces": {
                    //909
                    $instrumentos = Gonher::where('categorias', 'like', '%909%')->where('Disponible', '=', 'S')->skip(($index - 1) * 15)->take(15)->get();

                    if ($index === '1') {
                        $count = Gonher::where('categorias', 'like', '%909%')->where('Disponible', '=', 'S')->count();
                    }
                    break;
                }
            case "Procesadores": {
                    //843
                    $instrumentos = Gonher::where('categorias', 'like', '%843%')->where('Disponible', '=', 'S')->skip(($index - 1) * 15)->take(15)->get();

                    if ($index === '1') {
                        $count = Gonher::where('categorias', 'like', '%843%')->where('Disponible', '=', 'S')->count();
                    }
                    break;
                }
            case "Violines": {
                    //1024
                    $instrumentos = Gonher::where('categorias', 'like', '%1024%')->where('Disponible', '=', 'S')->skip(($index - 1) * 15)->take(15)->get();

                    if ($index === '1') {
                        $count = Gonher::where('categorias', 'like', '%1024%')->where('Disponible', '=', 'S')->count();
                    }
                    break;
                }
            case "Ukuleles": {
                    //UKELELE
                    $instrumentos = Gonher::where('titulo', 'like', 'UKELELE%')->where('Disponible', '=', 'S')->skip(($index - 1) * 15)->take(15)->get();

                    if ($index === '1') {
                        $count = Gonher::where('titulo', 'like', 'UKELELE%')->where('Disponible', '=', 'S')->count();
                    }
                    break;
                }
            case "Violas": {
                    //1181
                    $instrumentos = Gonher::where('categorias', 'like', '%1181%')->where('Disponible', '=', 'S')->skip(($index - 1) * 15)->take(15)->get();

                    if ($index === '1') {
                        $count = Gonher::where('categorias', 'like', '%1181%')->where('Disponible', '=', 'S')->count();
                    }
                    break;
                }
            case "Bajo-sexto": {
                    //BAJO SEXTO
                    $instrumentos = Gonher::where('titulo', 'like', 'BAJO SEXTO%')->where('Disponible', '=', 'S')->skip(($index - 1) * 15)->take(15)->get();

                    if ($index === '1') {
                        $count = Gonher::where('titulo', 'like', 'BAJO SEXTO%')->where('Disponible', '=', 'S')->count();
                    }
                    break;
                }
            case "Violoncellos": {
                    //
                    break;
                }
            case "Cuerdas-Otros": {
                    //1022  not UKELELE
                    $instrumentos = Gonher::where('categorias', 'like', '%1022%')->where('titulo', 'not like', 'UKELELE%')->where('Disponible', '=', 'S')->skip(($index - 1) * 15)->take(15)->get();

                    if ($index === '1') {
                        $count = Gonher::where('categorias', 'like', '%865%')->where('titulo', 'not like', 'UKELELE%')->where('Disponible', '=', 'S')->count();
                    }
                    break;
                }
            default: {
                    $instrumentos = Gonher::where('titulo', 'like', '%' . $category . '%')
                        // ->orWhere('descripcion_corta', 'like', '%' . $category . '%')
                        // ->orWhere('descripcion_completa', 'like', '%' . $category . '%')
                        ->orWhere('categorias', 'like', '%' . $category . '%')
                        ->skip(($index - 1) * 15)->take(15)->get();
                    if ($index === '1') {
                        $count = Gonher::where('titulo', 'like', '%' . $category . '%')
                            // ->orWhere('descripcion_corta', 'like', '%' . $category . '%')
                            // ->orWhere('descripcion_completa', 'like', '%' . $category . '%')
                            ->orWhere('categorias', 'like', '%' . $category . '%')
                            ->count();
                    }
                    break;
                }
        }
        $categoria = CategoryModel::where('slug', 'like', $category)->get();

        $products = ProductModel::select('product.*', 'provedor.provedor', 'provedor.porcentaje')
            ->join('provedor', 'product.id_provedor', '=', 'provedor.id')
            ->where('status', '=', 1)
            ->where('category', '=', $categoria[0]->id)->skip(($index - 1) * 15)->take(15)->get();

        $counts = ProductModel::select('product.*', 'provedor.provedor', 'provedor.porcentaje')
            ->join('provedor', 'product.id_provedor', '=', 'provedor.id')
            ->where('status', '=', 1)
            ->where('category', '=', $categoria[0]->id)->count();

        //dd($products->toArray());
        //
        // if ($instrumentos === null) {
        //     return response()->json(["datos" => [], "total" => $count], 200);
        // }

        foreach ($instrumentos as $instrumento) {
            $totalvendido = ProductOrderModel::where('sku', 'like', $instrumento->sku)->count();

            array_push($productos, [
                "id" => $instrumento->sku,
                "Nombre" => $instrumento->titulo,
                "Marca" => $instrumento->marca,
                "Descripcion" => $instrumento->descripcion_corta,
                "Descripcion_completa" => $instrumento->descripcion_completa,
                "Imagen" => $instrumento->imagen,
                "Propietario" => $instrumento->propietario,
                "Imgs" => json_decode($instrumento->imgs, true),
                "Categorias" => json_decode($instrumento->categorias, true),
                "Precio" => $this->getCosto($instrumento->propietario, $instrumento->precio), //$instrumento->precio,
                "Precio_distribuidor" => $instrumento->precio_dis,
                "Precio_con_descuentos" => $instrumento->precio_desc,
                "Disponible" => $instrumento->disponible,
                "ubicacion" => 'api',
                "vendido" => $totalvendido,
                "url" => '',
            ]);
        }

        foreach ($products as $instrumento) {

            $prImage = ProductGalleryModel::where('id_product', '=', $instrumento->id)->get();
            $imgpr = "";
            $imgspr = array();
            if ($prImage->count() > 0) {
                $imgpr = '/assets/storage/product/' . $producto->sku . '/' . $prImage[0]->image;
                $x = -1;
                foreach ($prImage as $img) {
                    $x++;
                    if ($x === 0) {
                        continue;
                    }

                    array_push($imgspr, '/assets/storage/product/' . $producto->sku . '/' . $img->image);
                    //$imgspr .= '"/assets/storage/product/' . $producto->sku . '/' . $img->image . '",';
                }
                //unset($imgspr[0]);
                //$imgspr .= "]";
            } else {
                $imgpr = "/assets/noproduct.jpg";
            }

            //print_r($imgspr);
            //print_r(json_decode($imgspr, true));exit;
            $totalvendido = ProductOrderModel::where('sku', 'like', $instrumento->sku)->count();

            array_push($productos, [
                "id" => $instrumento->sku,
                "Nombre" => $instrumento->title . ' MOD. ' . $instrumento->sku,
                "Marca" => $instrumento->trademark,
                "Descripcion" => $instrumento->description,
                "Descripcion_completa" => $instrumento->description,
                "Imagen" => $imgpr,
                "Propietario" => $instrumento->provedor,
                "Imgs" => $imgspr,
                "Categorias" => [
                    "ID" => $categoria[0]->name, "ID" => $categoria[0]->id,
                    "PADREID" => $categoria[0]->mainid
                ],
                "Precio" => $this->getCosto($instrumento->provedor, $instrumento->previus_price), //$precio,
                "Precio_distribuidor" => (float) $instrumento->previus_price,
                "Precio_con_descuentos" => (float) $instrumento->previus_price,
                "Disponible" => 'S',
                "url" => $instrumento->url,
                "vendido" => $totalvendido,
                "ubicacion" => 'local',
            ]);
        }

        return response()->json(["datos" => $productos, "total" => $count + $counts], 200);
    }
    public function getarticulo($busqueda)
    {
        $product = Gonher::where('sku', "=", $busqueda)->get();
        if ($product->count() > 0) {

            $product = $product[0];
            $totalvendido = ProductOrderModel::where('sku', 'like', $product->sku)->count();

            $productos = [
                "id" => $product->sku,
                "Nombre" => $product->titulo,
                "Marca" => $product->marca,
                "Descripcion" => $product->descripcion_corta,
                "Descripcion_completa" => $product->descripcion_completa,
                "Imagen" => $product->imagen,
                "Propietario" => $product->propietario,
                "Imgs" => json_decode($product->imgs, true),
                "Categorias" => json_decode($product->categorias, true),
                "Precio" => $this->getCosto($product->propietario, $product->precio_dis), //$product->precio,
                "Precio_distribuidor" => $product->precio_dis,
                "Precio_con_descuentos" => $product->precio_desc,
                "Disponible" => $product->disponible,
                "url" => '',
                "vendido" => $totalvendido,
                "ubicacion" => 'api',
            ];
            return response()->json($productos, 200);
        } else {

            $producto = ProductModel::select('product.*', 'provedor.provedor', 'provedor.porcentaje')
                ->join('provedor', 'product.id_provedor', '=', 'provedor.id')
                ->where('status', '=', 1)
                ->where('sku', '=', $busqueda)->get();
            //dd($busqueda);
            if ($producto->count() > 0) {
                $producto = $producto[0];
                $categoria = CategoryModel::where("ID", '=', $producto->category)->get();
                $prImage = ProductGalleryModel::where('id_product', '=', $producto->id)->get();
                $imgpr = "";
                $imgspr = array();
                if ($prImage->count() > 0) {
                    $imgpr = '/assets/storage/product/' . $producto->sku . '/' . $prImage[0]->image;
                    $x = -1;
                    foreach ($prImage as $img) {
                        $x++;
                        if ($x === 0) {
                            continue;
                        }

                        array_push($imgspr, '/assets/storage/product/' . $producto->sku . '/' . $img->image);
                        //$imgspr .= '"/assets/storage/product/' . $producto->sku . '/' . $img->image . '",';
                    }
                    //unset($imgspr[0]);
                    //$imgspr .= "]";
                } else {
                    $imgpr = '/assets/noproduct.jpg';
                }

                //print_r($imgspr);
                //print_r(json_decode($imgspr, true));exit;
                $totalvendido = ProductOrderModel::where('sku', 'like', $producto->sku)->count();

                $productos = [
                    "id" => $producto->sku,
                    "Nombre" => $producto->title . ' MOD. ' . $producto->sku,
                    "Marca" => $producto->trademark,
                    "Descripcion" => $producto->description,
                    "Descripcion_completa" => $producto->description,
                    "Imagen" => $imgpr,
                    "Propietario" => $producto->provedor,
                    "Imgs" => $imgspr,
                    "Categorias" => $categoria->count() === 0 ? [] : [
                        "ID" => $categoria[0]->name, "ID" => $categoria[0]->id,
                        "PADREID" => $categoria[0]->mainid
                    ],
                    "Precio" => $this->getCosto($producto->provedor, $producto->previus_price), //$precio,
                    "Precio_distribuidor" => (float) $producto->previus_price,
                    "Precio_con_descuentos" => (float) $producto->previus_price,
                    "Disponible" => 'S',
                    "ubicacion" => 'local',
                    "vendido" => $totalvendido,
                    "url" => $producto->url,
                ];
                return response()->json($productos, 200);
            }
        }
        return response()->json([], 200);
    }
    private function getCosto($provedorNombre, $precio)
    {
        $costo = $precio;
        $provedor = ProvedorModel::where('provedor', '=', $provedorNombre)->get();
        if ($provedor->count() > 0) {
            $provedor = $provedor[0];
            if ($provedor->descuento > 0) {
                $descuento = ((float) $precio) * ((float) $provedor->descuento / 100);
                $costo = $precio - $descuento;
            }
            $costo = $costo * 1.16;
            if ($provedor->porcentaje > 0) {
                $costo = ($costo) * ((float) 1 + ((float) $provedor->porcentaje / 100));
            }
        }
        return $costo;
    }
}
