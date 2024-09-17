<!DOCTYPE html>
<html>

<head>
    <title>ORDEN DESPACHO | INDAVES</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            font-size: 14px;
            margin: 0;
            padding: 0;
            color: #333; /* Color de texto principal */
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #007bff; /* Color del borde inferior de la cabecera */
            padding-bottom: 10px;
            /* background-color: #f8f9fa;  */
            /* Color de fondo de la cabecera */
        }

        .header img {
            width: 100px;
        }

        .header h1 {
            margin: 5px 0;
            font-size: 18px;
            color: #007bff; /* Color del título de la cabecera */
        }

        .header p {
            margin: 2px 0;
            color: #555; /* Color del texto en la cabecera */
        }

        h2 {
            border-bottom: 1px solid #007bff; /* Color del borde inferior de los encabezados h2 */
            padding-bottom: 5px;
            margin-bottom: 10px;
            font-size: 14px;
            color: #007bff; /* Color del texto de los encabezados h2 */
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 100px;
        }

        table, th, td {
            border: 1px solid #dee2e6; /* Color del borde de la tabla */
        }

        th, td {
            padding: 8px;
            text-align: left;
        }

        th {
            /* background-color: #e9ecef;  */
            /* Color de fondo de los encabezados de la tabla */
            color: #333;
            /* Color del texto en los encabezados de la tabla */
        }

        tr:nth-child(even) {
            /* background-color: #f2f2f2;  */
            /* Color de fondo alternativo para las filas de la tabla */
        }

        .footer {
            text-align: center;
            margin-top: 20px;
            border-top: 2px solid #007bff; /* Color del borde superior del pie de página */
            padding-top: 10px;
            /* background-color: #f8f9fa;  */
            /* Color de fondo del pie de página */
        }

        .footer h3 {
            margin: 0;
            font-size: 14px;
            color: #e74c3c; /* Color del título en el pie de página */
        }

        .footer p {
            margin: 5px 0;
            /* color: #555;  */
            /* Color del texto en el pie de página */
        }

        .text-small{
            font-size: 12px;
        }
    </style>
</head>

<body>
<!-- Cabecera -->
<div class="header">
    <img src="{{ public_path('logo.png') }}" alt="Logo">
    <h1>{{ $empresa->name }}</h1>
    <p>{{ $empresa->address }}</p>
    <p>Pedidos: {{ $empresa->phone }}</p>
    <p>{{ $empresa->email }}</p>
</div>


<!-- Información de la Venta -->
<div>
    <h2>DATOS DE DESPACHO</h2>
    <table>
        <tr>
            <td class="text-small">SERIE DE DESPACHO:</td>
            <td class="text-small">{{ $venta->serie_orden }}</td>
        </tr>
        <tr>
            <td class="text-small">CLIENTE:</td>
            <td class="text-small">{{  $venta->cliente->razon_social }}</td>
        </tr>
        <tr>
            <td class="text-small">FECHA:</td>
            <td class="text-small">{{  $venta->created_at->format('d/m/Y')}}</td>
        </tr>
    </table>
</div>


<!-- Detalles de la Orden -->
<div>
    <h2>DETALLE DE DESPACHO</h2>
    <table>
        <tbody>
        @foreach ($venta->detalles as $detalle)
            <tr>
                <td  class="text-small">Presentación</td>
                <td  class="text-small">{{ $detalle->presentacion_pollo_descripcion }}</td>
            </tr>
            <tr>
                <td  class="text-small">Tipo Pollo</td>
                <td  class="text-small">{{ $detalle->tipo_pollo_descripcion }}</td>
            </tr>
            <tr>
                <td  class="text-small">Cantidad de Pollo</td>
                <td  class="text-small">{{ $detalle->cantidad_pollos }}</td>
            </tr>
            <tr>
                <td  class="text-small">Peso Bruto</td>
                <td  class="text-small">{{ $detalle->peso_bruto }}</td>
            </tr>
            <tr>
                <td  class="text-small">Cantidad de Jabas</td>
                <td  class="text-small">{{ $detalle->cantidad_jabas }}</td>
            </tr>
            <tr>
                <td  class="text-small">Tara</td>
                <td  class="text-small">{{ $detalle->tara }}</td>
            </tr>
            <tr>
                <td  class="text-small">Peso Neto</td>
                <td  class="text-small">{{ $detalle->peso_neto }}</td>
            </tr>
            <tr>
                <td colspan="2"></td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

<!-- Footer -->
<div class="footer">
    <h3>La empresa no acepta devoluciones</h3>
    <p>Gracias por su preferencia.</p>
</div>
</body>

</html>
