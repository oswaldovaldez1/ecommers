<!doctype html>
<html lang="es">
<head>
<meta charset="UTF-8">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">

<title>Reporte</title>

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
 <style>
    body{
      font-family: sans-serif;
    }
    @page {
      margin: 160px 50px;
    }
    header { position: fixed;
      left: 0px;
      top: -140px;
      right: 0px;
      height: 100px;
      text-align: center;
    }
    header h1{
      margin: 10px 0;
    }
    header h2{
      margin: 0 0 10px 0;
    }
    footer {
      position: fixed;
      left: 0px;
      bottom: -100px;
      right: 0px;
      height: 40px;
    }
    footer .page:after {
      content: counter(page);
    }
    footer table {
      width: 100%;
    }
    footer p {
      text-align: right;
    }
    footer .izq {
      text-align: left;
    }
  </style>
<body>
  <header>
    <table width="100%">
    <tr>
        <td valign="top">
            <img class="img-fluid" src="https://lanesa1957.com/img/lanesalogo.png"  style="max-width: 100px; position: absolute; top:0px; left:0px;">
        </td>
        <td align="right">
<br><br>
             <p>
                La Nesa1957
            </p>
            <p>
                TEL.  9515142216
            </p>

        </td>
    </tr>
  </table>
<h3 style="text-align: center">{{$titulo}}</h3>
<p style="text-align: center">{{$subtitulo}}</p>
  </header>
  <footer>
    <table>
      <tr>
        <td>
            <p class="izq">
20 de noviembre 722 Col. centro. Oaxaca, Oax. CP 68000
            </p>
        </td>
        <td>
          <p class="page">
            Página
          </p>
        </td>
      </tr>
    </table>
  </footer>
  <div id="content" style="padding-top: 30px;">
    <table class="table table-striped">
  <thead class="thead-dark">
    <tr>
      <th scope="col" style="text-align: center;">
          #
        </th>
      <th scope="col" style="text-align: center;">Metodo</th>
      <th scope="col" style="text-align: center;">Comprador</th>
      <th scope="col" style="text-align: center;">Costo</th>
      <th scope="col" style="text-align: center;">Estatus</th>
      <th scope="col" style="text-align: center;">Fecha</th>
    </tr>
  </thead>
  <tbody>
      @foreach($orden as $or)
      <tr>
      <th scope="row" style="text-align: center;">{{$loop->iteration}}</th>
      <td>{{$or->method}}</td>
      <td>{{$or->customer_name}}</td>
      <td  style="text-align: right; padding-right: 20px;">{{number_format($or->pay_amount,2)}}</td>
      <td style="text-align: center;">{{$or->status}}</td>
      <td style="text-align: center;">{{$or->created_at}}</td>
    </tr>
     @php($total += $or->pay_amount)
    @endforeach
  </tbody>
</table>
<div class="d-flex flex-row-reverse" style="margin-top: 50px;">
    <br>
<p style="text-align: right">Total: {{number_format($total,2)}}</p>
</div>
  </div>
</body>
</html>
