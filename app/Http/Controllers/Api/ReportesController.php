<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\correos;
use App\Models\Order;
use App\Models\ProductOrderModel;
use App\Models\ConfiguracionModel;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use PDF;

class ReportesController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function pdf($status, $inicio, $fin)
    {
        /**
         - - -
         - f f
         s - -
         s f f

         */
        $orden = null;
        //$orden = Order::where('status', 'pagado')->get();
        $titulo = 'Listado de Pedidos';
        $total = 0;
        $subtitulo = '';
        if ($status === '-' && $inicio === '-' && $fin === '-') {
            $subtitulo = 'Listado de pedidos completo';
            $orden = Order::all();
        } else if ($status === '-' && $inicio != '-' && $fin != '-') {
            $subtitulo .= 'Listado de pedidos completo, de ' . $inicio . ' al ' . $fin;
            $orden = Order::whereBetween('created_at', [$inicio, $fin])->get();
        } else if ($status != '-' && $inicio === '-' && $fin === '-') {
            $subtitulo = 'Listado de pedidos con estatus ' . $status;
            $orden = Order::where('status', $status)->get();
        } else if ($status != '-' && $inicio != '-' && $fin != '-') {
            $subtitulo = 'Listado de pedidos con estatus ' . $status .
                ', de ' . $inicio . ' al ' . $fin;
            $orden = Order::where('status', $status)->whereBetween('created_at', [$inicio, $fin])->get();
        }

        $pdf2 = \PDF::loadView('admin.reportecompleto', compact('orden', 'titulo', 'subtitulo', 'total'));
        $pdf2->setPaper('A4', 'landscape');

        return $pdf2->stream();
    }
    public function setSeguimiento($nseg, $id)
    {
        $orden = Order::find($id);
        \Config::set('mail.username', 'ventas@lanesa1957.com');
        $datos = new \stdClass();
        $datos->from = \config('mail')['username'];
        $datos->view = 'mails.mensajeria';
        $datos->text = 'mails.mensajeria';
        $datos->fromname = 'La Nesa1957 ventas';
        $datos->archivo = null;
        $datos->filename = "";
        $datos->numeroSeguimiento = $nseg;
        $datos->ubicacion = $orden->customer_addr . ', Col.' . $orden->customer_col . ', ' . $orden->customer_city . ', ' . $orden->customer_edo . ', CP: ' . $orden->customer_zip;
        $datos->referencia = $orden->referencias;
        $datos->subject = 'Número de Seguimiento';
        Mail::to($orden->customer_email)->send(new correos($datos));
    }
    public function getReporteunitario($id)
    {

        $orden = Order::find($id);
        $config = ConfiguracionModel::find(1);
        $ordendetail = ProductOrderModel::where('orden_id', $id)->get();

        $pdf = \PDF::loadView('admin.reporteindividual', compact('orden', 'ordendetail'));


        //return $pdf->stream();
        \Config::set('mail.username', 'pedidos@lanesa1957.com');
        $datos = new \stdClass();
        $datos->from = \config('mail')['username'];
        $datos->view = 'mails.mail';
        $datos->text = 'mails.mail';
        $datos->fromname = 'La Nesa1957 ventas';
        $datos->archivo = $pdf->output();
        $datos->filename = "orden.pdf";
        $datos->subject = 'Orden de Compra';
        Mail::to('ventas@lanesa1957.com')->send(new correos($datos));

        //Mail::to('oswaldovaldez92@gmail.com')->send(new correos($datos));

        if ($orden->status === 'pendiente') {

            $pdf2 = \PDF::loadView('admin.reportecompra', compact('config', 'orden'));

            \Config::set('mail.username', 'ventas@lanesa1957.com');
            $datos->from = \config('mail')['username'];
            $datos = new \stdClass();
            $datos->from = \config('mail')['username'];
            $datos->view = 'mails.mail';
            $datos->text = 'mails.mail';
            $datos->fromname = 'La Nesa1957 ventas';
            $datos->archivo = $pdf2->output();
            $datos->filename = "Compra.pdf";
            $datos->subject = 'Orden de Compra';
            Mail::to($orden->customer_email)->send(new correos($datos));
        }

        //Mail::to('lanesa1957@live.com.mx')->cc('federicofederal6@hotmail.com')->send(new correos($datos));
    }
}