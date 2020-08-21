<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\ProductOrderModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\ReportesController;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Order::paginate(10);
    }
    public function detalle($id)
    {
        $detalle = ProductOrderModel::where('orden_id', $id)->get();
        return response()->json(['detalle' => $detalle]);
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
        //
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
        $orden = Order::find($id);
        if ($request->nseg != '') {
            $orden->nseg = $request->nseg;
        }
        $orden->status = $request->status;
        if ($orden->update()) {
            if ($request->nseg != '' && $request->status === 'enviado') {
                $reporte = new ReportesController();
                $reporte->setSeguimiento($request->nseg, $id);
            }
            return response()->json(['message' => 'Orden Actualizada'], 201);
        } else {
            return response()->json(['message' => 'No se pudo actualizar la orden,inténtelo más tarde'], 405);
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



    public function busqueda($search)
    {
        $respuesta = Order::where('order_number', 'like', '%' . $search . '%')
            ->orWhere('customer_email', 'like', '%' . $search . '%')
            ->orWhere('customer_phone', 'like', '%' . $search . '%')
            ->orWhere('customer_city', 'like', '%' . $search . '%')
            ->orWhere('customer_edo', 'like', '%' . $search . '%')
            ->orWhere('customer_name', 'like', '%' . $search . '%')
            ->get()->toArray();

        return response()->json($respuesta);
    }
}
