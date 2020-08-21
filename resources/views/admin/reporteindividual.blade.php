<!doctype html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Nota de Pago</title>

<style type="text/css">
    * {
        font-family: Verdana, Arial, sans-serif;
    }
    table{
        font-size: x-small;
    }
    tfoot tr td{
        font-weight: bold;
        font-size: x-small;
    }
    .gray {
        background-color: lightgray
    }
    p{
        font-size: 12px;
        line-height: 5px;
    }
</style>

</head>
<body>

  <table width="100%">
    <tr>
        <td valign="top">
            <img class="img-fluid" src="https://lanesa1957.com/img/lanesalogo.png"  style="max-width: 100px; position: absolute; top:0px; left:0px;">
        </td>
        <td align="right">
            <h3>La Nesa1957</h3>
            <pre>
                Diego Gonsalez Ayala
                Persona física con actividad empresarial
                20 de noviembre 722
                Col. centro. Oaxaca, Oax.
                CP 68000
                TEL.  9515142216
                lanesa1957@live.com.mx
                Orden N°: {{$orden->order_number}}
            </pre>
        </td>
    </tr>

  </table>


  <br/>
  {{-- <p style="
  position: absolute;
  font-size: 90px;
  opacity: 0.3;
  transform: rotate(-35deg);
  left:150px;
  top:200px;
  ">PAGADO</p> --}}
  {{-- <p style="
  position: absolute;
  font-size: 90px;
  opacity: 0.3;
  transform: rotate(-35deg);
  left:70px;
  top:200px;
  ">CANCELADO</p> --}}

  <table width="100%">
    <thead style="background-color: lightgray;">
      <tr>
        <th>#</th>
        <th>Description</th>
        <th>Quantity</th>
        <th>Unit Price $</th>
        <th>Total $</th>
        <th>Propetario</th>
      </tr>
    </thead>
    <tbody>
        <?php
                $listado=1;
        ?>

        @foreach ($ordendetail as $detalle)
        <tr>
            <th scope="row">{{$listado}}</th>
            <td>{{$detalle->titulo}}</td>
            <td align="center">{{$detalle->qty}}</td>
            <td align="right">{{number_format($detalle->precio,2)}}</td>
            <td align="right">{{number_format($detalle->precio*$detalle->qty,2)}}</td>
            <td align="right">{{$detalle->propietario}}</td>
        </tr>

        <?php
                $listado++;
        ?>
        @endforeach
    </tbody>

    <tfoot>
        <tr>
            <td colspan="3"></td>
            <td align="right">Total $</td>
            <td align="right" class="gray">$ {{number_format($orden->pay_amount,2)}}</td>
        </tr>
    </tfoot>
  </table>
  <p>
    Datos del comprador
</p>
<p>
    Nombre:{{$orden->customer_name}}
</p>
<p>
    Telefono:{{$orden->customer_phone}}
</p>
<p>
    Correo:{{$orden->customer_email}}
</p>
<p>
    Dirección:{{$orden->customer_addr.' Col. '.$orden->customer_col.', '.$orden->customer_city.', '.$orden->customer_edo}}

</p>
<p>
    CP: {{$orden->customer_zip}}
</p>

<p>
    Referencia:
</p>
</body>

</html>
