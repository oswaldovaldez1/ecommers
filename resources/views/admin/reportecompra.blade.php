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
  <table width="100%">
      <tr>
          <td width="50%">
          <p style="font-weight: 600; font-size: 15px; border-left: 10px solid #5a83c2; padding-top: 15px; padding-bottom: 15px;">Fecha límite de pago:</p>
          <p style="margin-left: 15px; font-weight: 800; font-size: 12px;">
              {{date("d-m-Y", strtotime("+ 5 day"))}}
          </p>
      </td>
      <td width="50%" align="right" style="background-color: #ef7968;">
          <div style="height: 100px;  padding-top: 30px; padding-right: 20px">
<p style="color: white; font-size: 15px;">Total a pagar:</p>
<p style=" font-weight: 600; color: white; font-size: 30px; padding-top: 30px;">$ {{number_format($orden->pay_amount,2)}}</p>
</div>
      </td>
      </tr>
  </table>
<br>
<br>
<p style="font-weight: 800; font-size: 20px; border-left: 10px solid #5a83c2; padding-top: 15px; padding-bottom: 15px;">Detalle de la Compra</p>
<br>
<table width="100%">
    <tr style="background-color:  #e9ecef;">
        <td >
            <p style="margin-left: 8px; font-weight: 600;">Descripción</p>
        </td>
        <td>
            <p style="margin-left: 8px;">
                {{$config->concepto}}
            </p>
        </td>
    </tr>
    <tr style="background-color: #ced4da;">
        <td>
            <p style="margin-left: 8px; font-weight: 600;">Fecha</p>
        </td>
        <td>
            <p style="margin-left: 8px;">
                {{$orden->created_at}}
            </p>
        </td>
    </tr>
</table>
<br>
<br>
<p style="font-weight: 800; font-size: 20px; border-left: 10px solid #5a83c2; padding-top: 15px; padding-bottom: 15px;">Pasos a realizar el pago</p>
<br>
<table width="100%">
    <tr style="background-color:  #e9ecef;">
        <td style="padding-top: 50px; padding-left: 60px; padding-bottom: 50px;">
            <span>Proporcionar los siguientes datos de transferencia:</span>
<br>
            <p>
                <span style="font-weight: 700;">Beneficiario : </span>{{$config->beneficiario}}
            </p>

            <p>
                <span style="font-weight: 700;">Banco : </span>{{$config->banco}}
            </p>

            <p>
                <span style="font-weight: 700;">Clabe : </span>{{$config->clabe}}
            </p>

            <p>
                <span style="font-weight: 700;">Concepto : </span>{{$config->concepto}}
            </p>

            <p>
                <span style="font-weight: 700;">Referencia : </span>{{$config->referencia}}
            </p>

            <p>
                <span style="font-weight: 700;">Importe : </span>$ {{number_format($orden->pay_amount,2)}}
            </p>
        </td>
        <td>

        </td>
    </tr>
</table>


    <br/>
      <br/>
      <p>Una vez realizado el pago, re-envíar el correo con el comprobante.</p>



</body>

</html>
