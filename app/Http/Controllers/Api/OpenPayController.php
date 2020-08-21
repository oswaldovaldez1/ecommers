<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\ReportesController;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\ProductOrderModel;
use Illuminate\Http\Request;

class OpenPayController extends Controller
{

    public function __construct()
    {
    }

    //metodos de pago
    public function store(Request $request)
    {
        $folio = "";
        switch ($request->metodo) {
            case 0: {
                    $folio = $request->folio;
                    $this->guardar($request, 'paypal', $folio, 'pagado');
                    break;
                }
            case 1: {
                    $folio = $this->generateRandomString(30);
                    $this->guardar($request, 'transferencia', $folio, 'pendiente');
                    break;
                }
        }
        return response()->json(['id' => $folio]);
    }

    private function guardar($request, $metodo, $id, $status)
    {
        $orden = new Order();
        $orden->method = $metodo;
        $orden->pay_amount = str_replace(",", "", $request->mount);
        $orden->order_number = $id;
        $orden->customer_email = $request->email;
        $orden->customer_name = $request->firstname . ' ' . $request->secondname;
        $orden->customer_phone = $request->phone;
        $orden->customer_addr = $request->address;
        $orden->customer_city = $request->city;
        $orden->customer_col = $request->col;
        $orden->customer_edo = $request->state;
        $orden->customer_zip = $request->zip;
        $orden->referencias = $request->referencias;
        $orden->status = $status;
        $orden->nseg = "";
        $orden->save();

        $productos = json_decode($request->products, true);

        foreach ($productos as $producto) {

            $detalleOrden = new ProductOrderModel();
            $detalleOrden->orden_id = $orden->id;
            $detalleOrden->sku = $producto['id'];
            $detalleOrden->titulo = $producto['Nombre'];
            $detalleOrden->precio = $producto['Precio'];
            $detalleOrden->qty = $producto['cantidad'];
            $detalleOrden->propietario = $producto['Propietario'];
            $detalleOrden->ubicacion = $producto['ubicacion'];
            $detalleOrden->save();
        }

        $reporte = new ReportesController();
        $reporte->getReporteunitario($orden->id);
    }
    private function generateRandomString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
    private static function toArray($object)
    {
        $reflectionClass = new \ReflectionClass($object);

        $properties = $reflectionClass->getProperties();

        $array = [];
        foreach ($properties as $property) {
            $property->setAccessible(true);
            $value = $property->getValue($object);
            if (is_object($value)) {
                $array[$property->getName()] = self::toArray($value);
            } else {
                $array[$property->getName()] = $value;
            }
        }
        return $array;
    }
}
